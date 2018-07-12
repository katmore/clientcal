#!/usr/bin/env php
<?php
if (0!==($exitStatus=(new class() {

   const ME_LABEL = 'ClientCal configuration update utility';
   
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
   
   const USAGE = '[-hu] | [<...OPTIONS>] [<CONFIG-NAME>]';
   
   const COPYRIGHT = '(c) 2006-2018 Paul D. Bird II. All Rights Reserved.';
   
   const ME = 'config-update.php';
   
   const DEFAULT_CONFIG_DIR=__DIR__.'/../app/config/clientcal';
   const DEFAULT_CONFIG_SOURCE_DIR=__DIR__.'/../app/config/clientcal';
   
   /**
    * Display a help message.
    * 
    * @return void
    * @static 
    */
   public static function showHelp() : void {
      
      $configDir = self::DEFAULT_CONFIG_DIR;
      $configSourceDir = self::DEFAULT_CONFIG_SOURCE_DIR;
      $configNamePossibleValues="";
      $errorReporting = error_reporting(error_reporting() & ~E_WARNING & ~E_NOTICE);
      $configFilesSource = glob("$configSourceDir/*.php");
      !is_array($configFilesSource) && $configFilesSource=[];
      $configFiles = glob("$configDir/*.php");
      !is_array($configFiles) && $configFiles=[];
      error_reporting($errorReporting);
      $configFiles = array_merge($configFiles,$configFilesSource);

      $configNamePossibleValues = [];
      foreach(array_diff($configFiles, ['..', '.']) as $f) {
         if (is_dir($f) || (substr($f,0,1)==".")) continue;
         $f_filename = pathinfo($f,\PATHINFO_FILENAME);
         if (substr($f_filename,strlen('-BACKUP')*-1)==='-BACKUP') {
            continue;
         }
         if (substr($f_filename,strlen("-sample")*-1)==="-sample") {
            $configNamePossibleValues[] = substr($f_filename,0,strlen($f_filename)-strlen("-sample"));
         } else {
            $configNamePossibleValues[] = $f_filename;
         }
      }
      unset($f_filename);
      
      $configNamePossibleValues = "\n   Possible values: ".implode(", ",$configNamePossibleValues);
   
      
      $configSourceLocalDefault = "";
      if (is_dir($configSourceDir)) {
         $configSourceLocalDefault = "\n   Local system default: ".realpath($configSourceDir);
      }
      
      $configRootLocalDefault = "";
      if (is_dir($configDir)) {
         $configRootLocalDefault = "\n   Local system default: ".realpath($configDir);
      }
      
      $help=<<<"HELP"
Arguments:
<CONFIG-NAME>
   Optionally specify a single config file to update.$configNamePossibleValues

Mode Switches:
--help, -h
   Output a help message and exit.

--usage, -u
   Output a brief usage message and exit.

Output Control Options:
--quiet, -q
   Provide only essential output to STDOUT.

--non-interactive
   No input prompts will be issued for config values; the default values will be used automatically.

Path Options:
--config-source-dir=<PATH>
   Path to the source directory to search for config files.$configSourceLocalDefault

--config-root-dir=<PATH>
   Path to directory where config files will be written.$configRootLocalDefault
HELP;
      echo str_replace("\n",\PHP_EOL,$help).\PHP_EOL;
   }
   
   /**
    * Display a brief usage message.
    *
    * @return void
    * @static
    */
   public static function showUsage() : void {
      echo "Usage: ".\PHP_EOL;
      echo "   ".SELF::ME." ".self::USAGE.\PHP_EOL;
   }
   
   /**
    * Display an intro message.
    * 
    * @return void
    */
   public static function showIntro() : void {
      echo self::ME_LABEL.\PHP_EOL.self::COPYRIGHT.\PHP_EOL;
   }
   
   const SHOW_ERROR_LINE_DISPLAY_PREFIX = 2;
   /**
    * Display one or more lines of an error message; output is written to STDERR if the SAPI is 'cli'.
    * 
    * @return void
    * @param string[] $strLines one or more error message lines
    * @param int $flags static::SHOW_ERROR_LINE_DISPLAY_PREFIX
    * @see php_sapi_name()
    */
   private function showErrLine(array $strLines,int $flags=self::SHOW_ERROR_LINE_DISPLAY_PREFIX) : void {
      $prefix = ($flags & static::SHOW_ERROR_LINE_DISPLAY_PREFIX)?static::ME.": ":"";
      if (PHP_SAPI=='cli') {
         $stderr = fopen('php://stderr', 'w');
         foreach ($strLines as $line) fwrite($stderr, $prefix.$line.\PHP_EOL);
         fclose($stderr);
         return;
      }
      foreach ($strLines as $line) echo $prefix.$line.\PHP_EOL;
   }
   
   const SHOW_LINE_DISPLAY_ON_QUIET = 1;
   const SHOW_LINE_DISPLAY_PREFIX = 2;
   
   /**
    * Display one or more lines; by default output is supressed if '--quiet' mode is active.
    * 
    * @return void
    * @param string[] one or more message lines
    * @param int $flags static::SHOW_LINE_DISPLAY_ON_QUIET, static::SHOW_LINE_DISPLAY_PREFIX
    * @see static::$quiet
    */
   private function showLine(array $strLines, int $flags=0) {
      if ($this->quiet && !($flags & static::SHOW_LINE_DISPLAY_ON_QUIET)) return;
      $prefix = ($flags & static::SHOW_LINE_DISPLAY_PREFIX)?static::ME.": ":"";
      foreach ($strLines as $line) echo $prefix.$line.\PHP_EOL;
   }
   
   /**
    * @var int exit status
    */
   private $exitStatus=0;
   
   /**
    * Provides command exit status.
    * 
    * @return int exit status, 0 is success, other numbers indicate an error
    */
   public function getExitStatus() :int { return $this->exitStatus; }
   
   /**
    * @var bool whether or not quiet switch is active 
    */
   private $quiet = false;
   
   /**
    * @var bool whether or not non-interactive switch is active
    */
   private $nonInteractive = false;
   
   const DIR_SANITY_CHECK_WRITABLE = 1;
   
   /**
    * Check directory path sanity 
    * 
    * @return string|null error message string on sanity failure, or null on success
    */
   private static function dirSanityMessage(string $path,int $flags=0) {
      if (empty($path)) {
         return "cannot be empty";
      }
      if (!file_exists($path)) {
         return "not found";
      }
      if (!is_dir($path)) {
         return "is not a directory";
      }
      if (!is_readable($path)) {
         return "missing read permissions";
      }
      if ($flags & self::DIR_SANITY_CHECK_WRITABLE) {
         if (!is_writable($path)) {
            return "missing write permissions";
         }
      }
   }
   
   const CONFIG_TARGET_VALUE_DISPLAY_MAX_LEN = 32;
   const CONFIG_EXISTING_VALUE_DISPLAY_MAX_LEN = 32;
   
   public function __construct() {
      
      $fullArg = $arg = [];
      $optind = 0;
      getopt("",["",],$optind);
      if (isset($_SERVER) && isset($_SERVER['argv']) && is_array($_SERVER['argv'])) {
         $fullArg = $arg = $_SERVER['argv'];
      }
      $arg0 = $arg[0];
      for($i=0;$i<$optind;$i++) { array_shift($arg); }
      array_unshift($arg,$arg0);
      
      $arg1 = null;
      isset($arg[1]) && $arg1 = $arg[1];
      
      /*
       * apply 'help' or 'usage' modes
       */
      $modeSwitch = getopt("hu?",["help","usage",]);
      if (isset($modeSwitch['help']) || isset($modeSwitch['h']) || isset($modeSwitch['?']) || ($arg1==='help')) {
         static::showIntro();
         echo self::HELP_LABEL.\PHP_EOL;
         echo PHP_EOL;
         static::showUsage();
         echo PHP_EOL;
         static::showHelp();
         return;
      }
      if (isset($modeSwitch['usage']) || isset($modeSwitch['u']) || ($arg1==='usage')) {
         static::showUsage();
         return;
      }
      
      $singleConfig = null;
      
      if ($arg1!==null) {
         $singleConfig = $arg1;
      }
      
      /*
       * apply 'quiet' and 'non-interactive' switches
       */
      $outputControlSwitch = getopt("q",["quiet","non-interactive",]);
      $this->quiet = isset($outputControlSwitch['quiet']);
      if (!$this->quiet) $this->quiet = isset($outputControlSwitch['q']);
      $this->nonInteractive = isset($outputControlSwitch['non-interactive']);
      
      /*
       * display intro if quiet switch is inactive
       */
      if (!$this->quiet) { static::showIntro(); echo \PHP_EOL; }
      
      if (isset($arg[2])) {
         $this->showErrLine(["unrecognized 2nd positional argument: ".$arg[2]]);
         $this->exitStatus = 2;
         return;
      }
      
      $validOptions = ['--config-dir','--config-source-dir','--quiet','--non-interactive'];
      foreach($fullArg as $a) {
         if (substr($a,0,1)=='-') {
            if (!in_array($a,$validOptions)) {
               $this->showErrLine(["unrecognized option: $a"]);
               $this->exitStatus = 2;
            }
         }
      }
      unset($a);
      
      if ($this->exitStatus !== 0) {
         return;
      }
         
      /*
       * sanity enforcement for config-dir, config-source-dir options
       */
      $pathOption = getopt("",["config-dir::","config-source-dir::"]);
      if (isset($pathOption['config-dir'])) {
         if (!empty($sanityMessage = static::dirSanityMessage($pathOption['config-dir'],self::DIR_SANITY_CHECK_WRITABLE))) {
            $this->showErrLine(["--config-dir path '{$pathOption['config-dir']}' $sanityMessage"]);
            $this->exitStatus = 2;
         }
      }
      if (isset($pathOption['config-source-dir'])) {
         if (!empty($sanityMessage = static::dirSanityMessage($pathOption['config-source-dir']))) {
            $this->showErrLine(["--config-source-dir path '{$pathOption['config-source-dir']}' $sanityMessage"]);
            $this->exitStatus = 2;
         }
      }
      
      /*
       * exit if path sanity error
       */
      if ($this->exitStatus !== 0) {
         return;
      }
      
      /*
       * apply config-dir option or use default value
       */
      if (isset($pathOption['config-dir'])) {
         $configDir = $pathOption['config-dir'];
      } else {
         $configDir = static::DEFAULT_CONFIG_DIR;
         if (!empty($sanityMessage = static::dirSanityMessage($configDir))) {
            $this->showErrLine(["DEFAULT_CONFIG_DIR path '$configDir' $sanityMessage"]);
            $this->exitStatus = 2;
         }
      }
      
      /*
       * apply config-source-dir option or use default value
       */
      if (isset($pathOption['config-source-dir'])) {
         $configSourceDir = $pathOption['config-source-dir'];
      } else {
         $configSourceDir = static::DEFAULT_CONFIG_SOURCE_DIR;
         if (!empty($sanityMessage = static::dirSanityMessage($configSourceDir))) {
            $this->showErrLine(["DEFAULT_CONFIG_SOURCE_DIR path '$configSourceDir' $sanityMessage"]);
            $this->exitStatus = 2;
         }
         
      }
      
      /*
       * exit if path sanity error
       */
      if ($this->exitStatus !== 0) {
         $this->showErrLine(["one or more sanity checks failed for default config paths"]);
         return;
      }
      
      /*
       * prepare config linked lists
       */
      $configVal_Source = [];
      $configMd5_Source = [];
      $configPath_Source = [];
      
      $configVal_Existing = [];
      $configMd5_Existing = [];
      $configPath_Existing = [];
      
      $configPath_Target = [];
      $configVal_Target = [];
      
      $foundSingleConfig = false;
      
      /*
       * intitial config-source-dir scan and config file sanity enforcement
       */
      foreach (glob("$configSourceDir/*.php") as $f) {
         
         if (is_dir($f) || (substr($f,0,1)==".")) continue;
         
         $f_filename = pathinfo($f,PATHINFO_FILENAME );
         
         if (substr($f_filename,strlen('-BACKUP')*-1)==='-BACKUP') {
            continue;
         } else
         if (substr($f_filename,strlen('-sample')*-1)==='-sample') {
            $f_filename = substr($f_filename,0,strlen($f_filename)-strlen("-sample"));
         }
         
         if ($singleConfig!==null) {
            if ($f_filename===$singleConfig) {
               $foundSingleConfig = true;
            } else {
               continue;
            }
         }
         
         $f_sample_check = "$configSourceDir/$f_filename-sample.php";
         if (is_file($f_sample_check)) {
            $f_path = $f_sample_check;
         } else {
            $f_path = $f;
         }
         
         if (!is_readable($f_path)) {
            $this->showErrLine(["config source file '".pathinfo($f_path,\PATHINFO_BASENAME)."' is not readable"]);
            $this->exitStatus = 3;
            continue;
         }
         
         $config = require $f_path;
         if (!is_array($config)) {
            $this->showErrLine(["config source file '".pathinfo($f_path,\PATHINFO_BASENAME)."' did not return an array"]);
            $this->exitStatus = 3;
            continue;
         }
         
         $configVal_Source[$f_filename] = $config;
         $configMd5_Source[$f_filename] = md5_file($f_path);
         $configPath_Source[$f_filename] = $f_path;
         $configPath_Target[$f_filename] = "$configDir/$f_filename.php";
      }
      unset($f);
      unset($config);
      
      /*
       * exit if config-source-dir scan sanity error
       */
      if ($this->exitStatus !== 0) {
         $this->showErrLine(["one or more sanity checks failed when checking contents of '-sample.php' files in config-source-dir: '$configSourceDir'"]);
         return;
      }
      
      /*
       * intitial config-dir scan and config file sanity enforcement
       */
      foreach (glob("$configDir/*.php") as $f) {
         
         if (is_dir($f) || (substr($f,0,1)==".")) continue;
         
         $f_filename = pathinfo($f,PATHINFO_FILENAME );
         
         if (substr($f_filename,strlen('-BACKUP')*-1)==='-BACKUP') {
            continue;
         } else
         if (substr($f_filename,strlen('-sample')*-1)==='-sample') {
            $f_filename = substr($f_filename,0,strlen($f_filename)-strlen("-sample"));
         } 
         
         if ($singleConfig!==null) {
            if ($f_filename===$singleConfig) {
               $foundSingleConfig = true;
            } else {
               continue;
            }
         }
         
         if (!is_readable($f)) {
            $this->showErrLine(["config file '".pathinfo($f,\PATHINFO_BASENAME)."' is not readable"]);
            $this->exitStatus = 3;
            continue;
         }
         
         $config = require $f;
         if (!is_array($config)) {
            $this->showErrLine(["config file '".pathinfo($f,\PATHINFO_BASENAME)."' did not return an array"]);
            $this->exitStatus = 3;
            continue;
         }
         
         $configMd5_Existing[$f_filename] = md5_file($f);
         $configPath_Existing[$f_filename] = $f;
         $configVal_Existing[$f_filename] = $config;
         $configPath_Target[$f_filename] = "$configDir/$f_filename.php";
      }
      unset($f);
      unset($config);
      unset($f_filename);
      
      /*
       * exit if config-source-dir scan sanity error
       */
      if ($this->exitStatus !== 0) {
         $this->showErrLine(["one or more sanity checks failed when checking contents of config files in config-dir: '$configDir'"]);
         return;
      }
      
      if (($singleConfig!==null) && !$foundSingleConfig) {
         $this->showErrLine(["unknown <CONFIG-NAME> '$singleConfig'"]);
         $this->exitStatus = 2;
         return;
      }
      
      /*
       * merge source to target config 
       */
      foreach($configPath_Target as $f_filename=>$f) {
         
         if ($singleConfig!==null) {
            if ($f_filename!==$singleConfig) {
               continue;
            }
         }
         
         $existing_md5 = $source_md5 = null;
         
         $configVal_Target[$f_filename] = [];
         
         $existingVal = [];
         
         if (isset($configVal_Source[$f_filename])) {
            $source_md5 = md5(json_encode($configVal_Source[$f_filename]));
            foreach($configVal_Source[$f_filename] as $k=>$v) {
               $configVal_Target[$f_filename][$k] = $v;
            }
            unset($k);
            unset($v);
         }
         
         if (isset($configVal_Existing[$f_filename])) {
            $existing_md5 = md5(json_encode($configVal_Existing[$f_filename]));
            foreach($configVal_Existing[$f_filename] as $k=>$v) {
               if (!isset($configVal_Target[$f_filename][$k])) {
                  $configVal_Target[$f_filename][$k] = $v;
               }
               $existingVal[$k] = $v;
            }
            unset($k);
            unset($v);
         }
         
         if (!$this->nonInteractive) {
            foreach($configVal_Target[$f_filename] as $k=>&$v) {
               $existingSuffix = "";
               if (isset($existingVal[$k]) && ($existingVal[$k]!==$v)) {
                  if (is_scalar($existingVal[$k])) {
                     $existingValDisp = $existingVal[$k];
                     if (strlen($existingValDisp)>self::CONFIG_EXISTING_VALUE_DISPLAY_MAX_LEN) {
                        $existingValDisp = substr($existingValDisp,0,self::CONFIG_EXISTING_VALUE_DISPLAY_MAX_LEN-3)."...(truncated)";
                     }
                  } else {
                     $existingValDisp = json_encode($existingVal[$k]);
                     if (strlen($existingValDisp)>self::CONFIG_EXISTING_VALUE_DISPLAY_MAX_LEN) {
                        $existingValDisp = substr($existingValDisp,0,self::CONFIG_EXISTING_VALUE_DISPLAY_MAX_LEN-3)."...(truncated)";
                     }
                     $existingValDisp = "{".gettype($existingVal[$k]).": ".$existingValDisp."}";
                  }
                  $existingSuffix = " (current value: $existingValDisp)";
               }
               if (is_scalar($v)) {
                  $valDisp = $v;
                  if (strlen($valDisp)>self::CONFIG_TARGET_VALUE_DISPLAY_MAX_LEN) {
                     $valDisp = substr($valDisp,0,self::CONFIG_TARGET_VALUE_DISPLAY_MAX_LEN-3)."...(truncated)";
                  }
               } else {
                  $valDisp = json_encode($v);
                  if (strlen($valDisp)>self::CONFIG_TARGET_VALUE_DISPLAY_MAX_LEN) {
                     $valDisp = substr($valDisp,0,self::CONFIG_TARGET_VALUE_DISPLAY_MAX_LEN-3)."...(truncated)";
                  }
                  $valDisp = "{".gettype($v).": ".$valDisp."}";
               }
               $input = readline("$f_filename '$k' [$valDisp]$existingSuffix: ");
               if (!empty($input)) {
                  if (ctype_digit($input)) {
                     $v = (int) $input;
                  } else if (ctype_alnum($input)) {
                     if ($input === 'null') {
                        $v = null;
                     } else {
                        $v = $input;
                     }
                  } else {
                     if (null !==($json_decoded=json_decode($input))) {
                        $v = $json_decoded;
                     } else {
                        $v = $input;
                     }
                  }
               }
            }
            unset($k);
            unset($v);
         }
         
         $target_md5 = md5(json_encode($configVal_Target[$f_filename]));
         
         
         if (($target_md5 === $existing_md5) && file_exists($configPath_Target[$f_filename])) {
            $this->showLine(["skipping config file '".realpath($configPath_Target[$f_filename])."' (no changes made)"],self::SHOW_LINE_DISPLAY_ON_QUIET);
         } else {
            if (isset($configPath_Existing[$f_filename])) {
               $backup_path = "$configDir/.$f_filename-{$configMd5_Existing[$f_filename]}-BACKUP.php";
               if (!file_exists($backup_path)) {
                  if (!copy($configPath_Existing[$f_filename],$backup_path)) {
                     $this->showErrLine(["failed to create backup of config file '{$configPath_Existing[$f_filename]}' to '$backup_path'"]);
                     $this->exitStatus = 4;
                     return;
                  }
                  $this->showLine(["created backup of '{$configPath_Existing[$f_filename]}' config file"]);
               }
            }
            if ($target_md5===$source_md5) {
               if (!copy($configPath_Source[$f_filename],$configPath_Target[$f_filename])) {
                  $this->showErrLine(["failed to copy source config '{$configPath_Source[$f_filename]}' to '$configPath_Target[$f_filename]'"]);
                  $this->exitStatus = 4;
                  return;
               }
               $this->showLine(["created config file '{$configPath_Existing[$f_filename]}' (from source config)"],self::SHOW_LINE_DISPLAY_ON_QUIET);
            } else {
               $generated_by = self::ME;
               $generated_time = date('c');
               $heading = <<< "HEADING"
<?php
// 
// '$f_filename' - Clientcal Config 
// Generated by $generated_by at $generated_time
//
HEADING;
               if (false === ($target_temp_path = tempnam(sys_get_temp_dir(), self::ME))) {
                  $this->showErrLine(["failed to create temp file for '$f_filename' config file"]);
                  $this->exitStatus = 4;
                  return;
               }
               if (false===file_put_contents($target_temp_path, $heading."\n")) {
                  $this->showErrLine(["failed while writing to temp file '$target_temp_path' for '$f_filename' config file"]);
                  $this->exitStatus = 4;
                  return;
               }
               if (false===file_put_contents($target_temp_path, 'return (function() {'."\n\n".'   $config = [];'."\n\n",\FILE_APPEND)) {
                  $this->showErrLine(["failed while writing to temp file '$target_temp_path' for '$f_filename' config file"]);
                  $this->exitStatus = 4;
                  return;
               }
               foreach($configVal_Target[$f_filename] as $k=>$v) {
                  ob_start();
                  var_export($v);
                  $v_export = ob_get_clean();
                  if (false===file_put_contents($target_temp_path, '   $config["'.$k.'"] = '.$v_export.";\n\n",\FILE_APPEND)) {
                     $this->showErrLine(["failed while writing to temp file '$target_temp_path' for '$f_filename' config file on '$k' config key"]);
                     $this->exitStatus = 4;
                     return;
                  }
               }
               unset($k);
               unset($v);
               if (false===file_put_contents($target_temp_path, '   return $config;'."\n",\FILE_APPEND)) {
                  $this->showErrLine(["failed while writing to temp file '$target_temp_path' for '$f_filename' config file"]);
                  $this->exitStatus = 4;
                  return;
               }
               if (false===file_put_contents($target_temp_path, '})();'."\n",\FILE_APPEND)) {
                  $this->showErrLine(["failed while writing to temp file '$target_temp_path' for '$f_filename' config file"]);
                  $this->exitStatus = 4;
                  return;
               }
               if (!copy($target_temp_path,$configPath_Target[$f_filename])) {
                  $this->showErrLine(["failed to copy config file '$configPath_Target[$f_filename]' from generated temp file"]);
                  $this->exitStatus = 4;
                  return;
               }
               $this->showLine(["created config file '{$configPath_Existing[$f_filename]}' (generated from input)"],self::SHOW_LINE_DISPLAY_ON_QUIET);
               unlink($target_temp_path);
            }
         }
         $this->showLine([""]);
         
      }
      unset($f);
      unset($f_filename);
      
      $this->showLine(["successfully updated config".($singleConfig!==null?" for '$singleConfig'":"")]);
      
   }

})->getExitStatus())) {
   if (PHP_SAPI=='cli') exit($exitStatus);
   return $exitStatus;
}








