#!/usr/bin/env php
<?php
return(function() {
  
   if (0!==($exitStatus=($installer = new class() {

   const ME_LABEL = 'ClientCal Configuration Installer';
   
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
   
   const USAGE = '[--help] | [--non-interactive][--quiet][--config-source-dir=<PATH>][--config-dir=<PATH>]';
   
   const COPYRIGHT = '(c) 2006-2018 Paul D. Bird II. All Rights Reserved.';
   
   const ME = 'config-install.php';
   
   const DEFAULT_CONFIG_DIR=__DIR__.'/../app/config';
   const DEFAULT_CONFIG_SOURCE_DIR=__DIR__.'/../app/config';
   
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
    * Display a help message.
    * 
    * @return void
    * @static 
    */
   public static function showHelp() : void {
      echo self::HELP_LABEL.\PHP_EOL;
      $configDir = self::DEFAULT_CONFIG_DIR;
      $configSourceDir = self::DEFAULT_CONFIG_SOURCE_DIR;
      $help=<<<"HELP"
Mode Switches:
--help
   Output a help message and exit.

--usage
   Output a brief usage message and exit.

Output Control Switches:
--quiet
   The only output will be error messages to STDERR.

--non-interactive
   No input prompts will be issued; such as for username, password, etc.

Path Options:
--config-source-dir=<PATH>
   Path to the source directory to search for config files.
   Default: $configSourceDir

--config-root-dir=<PATH>
   Path to directory where config files will be written.
   Default: $configDir
HELP;
      echo str_replace("\n",\PHP_EOL,$help).\PHP_EOL;
   }
   
   /**
    * Display an intro message.
    * 
    * @return void
    */
   public static function showIntro() : void {
      echo self::ME_LABEL.\PHP_EOL.self::COPYRIGHT.\PHP_EOL;
   }
   
   /**
    * Display one or more lines of an error message; output is written to STDERR if the SAPI is 'cli'.
    * 
    * @return void
    * @param string[] $strLines one or more error message lines
    * @see php_sapi_name()
    */
   private function showErrLine(array $strLines,bool $showPrefix=true) : void {
      if (PHP_SAPI=='cli') {
         $prefix = $showPrefix?static::ME.": ":"";
         $stderr = fopen('php://stderr', 'w');
         foreach ($strLines as $line) fwrite($stderr, $prefix.$line.\PHP_EOL);
         fclose($stderr);
         return;
      }
      foreach ($strLines as $line) echo "$line".\PHP_EOL;
   }
   
   /**
    * Display one or more lines; by default output is supressed if '--quiet' mode is active.
    * 
    * @return void
    * @param string[] one or more message lines
    * @param bool 
    * @see static::$quiet
    */
   private function showLine(array $strLines, bool $showOnQuiet=false) {
      if ($this->quiet && !$showOnQuiet) return;
      foreach ($strLines as $line) echo "$line".\PHP_EOL;
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
   
   /**
    * Check directory path sanity 
    * 
    * @return string|null error message string on sanity failure, or null on success
    */
   private static function dirSanityMessage(string $path) {
      if (empty($path)) {
         return "cannot be empty";
      }
      if (!file_exists($path)) {
         return "not found";
      }
      if (!is_dir($path)) {
         return "is not a directory";
      }
      if (!is_writable($path)) {
         return "missing write permissions";
      }
   }
   
   public function __construct() {
      /*
       * apply 'help' or 'usage' modes
       */
      $modeSwitch = getopt("",["help","usage",]);
      if (isset($modeSwitch['help'])) {
         static::showIntro();
         static::showHelp();
         return;
      }
      if (isset($modeSwitch['usage'])) {
         static::showUsage();
         return;
      }
      
      /*
       * apply 'quiet' and 'non-interactive' switches
       */
      $outputControlSwitch = getopt("",["quiet","non-interactive",]);
      $this->quiet = isset($outputControlSwitch['quiet']);
      $this->nonInteractive = isset($outputControlSwitch['non-interactive']);
      
      /*
       * display intro if quiet switch is inactive
       */
      if (!$this->quiet) { static::showIntro(); echo \PHP_EOL; }
         
      /*
       * sanity enforcement for config-dir, config-source-dir options
       */
      $pathOption = getopt("",["config-dir::","config-source-dir::"]);
      foreach($pathOption as $opt=>$path) {
         if (!empty($sanityMessage = static::dirSanityMessage($path))) {
            $this->showErrLine(["--$opt path '$path' $sanityMessage"]);
            $this->exitStatus = 2;
         }
      }
      unset($opt);
      unset($path);
      unset($sanityMessage);
      
      /*
       * exit if path sanity error
       */
      if ($this->exitStatus !== 0) return;
      
      /*
       * apply config-dir, config-source-dir options or use default values
       */
      $defaultCheck = [];
      if (isset($pathOption['config-dir'])) {
         $configDir = $pathOption['config-dir'];
      } else {
         $defaultCheck['DEFAULT_CONFIG_DIR'] = $configDir = static::DEFAULT_CONFIG_DIR;
      }
      if (isset($pathOption['config-source-dir'])) {
         $configDir = $pathOption['config-source-dir'];
      } else {
         $defaultCheck['DEFAULT_CONFIG_SOURCE_DIR'] = $configDir = static::DEFAULT_CONFIG_SOURCE_DIR;
      }
      
      /*
       * sanity enforcement for default config-dir, config-source-dir values
       */
      foreach($defaultCheck as $label=>$path) {
         if (!empty($sanityMessage = static::dirSanityMessage($path))) {
            $this->showErrLine(["$label path '$path' $sanityMessage"]);
            $this->exitStatus = 2;
         }
      }
      unset($label);
      unset($path);
      unset($sanityMessage);
      
      /*
       * exit if path sanity error
       */
      if ($this->exitStatus !== 0) return;
      
      
      
   }
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

})->getExitStatus())) {
   if (PHP_SAPI=='cli') exit($exitStatus);
   return $exitStatus;
}
})();








