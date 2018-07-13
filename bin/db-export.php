#!/usr/bin/env php
<?php
use clientcal\config;
use Ifsnop\Mysqldump\Mysqldump;


if (0!==($exitStatus=($installer = new class() {

   const ME_LABEL = 'ClientCal database export utility';
    
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
    
   const USAGE = '[-hu] | [--non-interactive] [--quiet | --verbose]] [[--export-file=<path to write SQL dump> | [--export-name=<export name>]] [--app-dir=<path to project app directory>]]';
    
   const COPYRIGHT = '(c) 2006-2018 Paul D. Bird II. All Rights Reserved.';
    
   const ME = 'db-export.php';
   
   const FALLBACK_APP_DIR=__DIR__.'/../app';
   
   private static function _getAppDir() :string {
      if (!empty(getopt("",["app-dir::",])['app-dir'])) {
         return getopt("",["app-dir::",])['app-dir'];
      }
      return self::FALLBACK_APP_DIR;
   }
   
   /**
    * @return void
    * @static
    */
   public static function showUsage() {
      echo "Usage: ".PHP_EOL;
      echo "   ".SELF::ME." ".self::USAGE.\PHP_EOL;
   }
    
   public static function showHelp() {
      echo self::HELP_LABEL.PHP_EOL;
      $help=<<<"HELP"
Output Control:
--help
   Enable "help mode": outputs this message then exits.

--quiet
   Enable "quiet mode": only output will be errors to STDERR.

--verbose (ignored if --quiet switch is present)
   Enable "verbose mode": outputs extra information (such as full system paths, etc.)

--non-interactive
   Enable "non interactive mode": No input prompts will be issued (such as for username, password, etc.)

Export Configuration:

--stdout
   The export will be output to STDOUT instead of saving to the file system.
   Cannot be used in conjunction with the --export-name or --export-file options.

--export-name=<identifying name> (optional)
   Resolves export file based on the "export name" relative to the backup directory.
   for example; if a value of "daily" is specified, the export file path will be resolved as:
     app/data/mysql/backup/daily.sql
   If the resolved file exists, it will be overwritten.
   Cannot be used in conjunction with the --stdout switch.

--export-file=<path> (optional, ignored when the --export-name option is present)
   Specify full system path to write the sql dump into.
   If the file exists, it will be overwritten.
   Cannot be used in conjunction with the --stdout switch.

App Configuration:
--app-dir=<path to project app directory> (optional)
   The directory where this script will look for the "bin-common.php" file and "data/mysql" sub-directory.
HELP;
      echo str_replace("\n",\PHP_EOL,$help).\PHP_EOL;
   }
    
   /**
    * @return void
    * @static
    */
   private static function _showIntro() {
      echo self::ME_LABEL."\n".self::COPYRIGHT.\PHP_EOL;
   }
    
   /**
    * @return void
    * @param string[]
    * @static
    */
   private static function _showErrLine(array $strLines) {
      $stderr = fopen('php://stderr', 'w');
      foreach ($strLines as $line) fwrite($stderr, "$line".\PHP_EOL);
      fclose($stderr);
   }
   /**
    * @return void
    * @param string[]
    * @static
    */
   private static function _showLine(array $strLines) {
      foreach ($strLines as $line) echo "$line".\PHP_EOL;
   }
    
   /**
    * @var int
    */
   private $_exitStatus=0;
    
   /**
    * @return int Exit status
    */
   public function getExitStatus() :int { return $this->_exitStatus; }

   /**
    * @var bool
    */
   private $_quiet;
    
   /**
    * @var bool
    */
   private $_verbose;
    
   /**
    * @var bool
    */
   private $_nonInteractive;
   
   /**
    * @var bool
    */
   private $_stdoutMode;
   
   public function __construct() {

      if (isset(getopt("",["help",])['help'])) {
         self::_showIntro();
         self::showHelp();
         return;
      }
      
      if (isset(getopt("",["usage",])['usage'])) {
         self::_showIntro();
         self::showUsage();
         return;
      }

      $this->_verbose = false;
      if (!($this->_quiet=isset(getopt("",["quiet",])['quiet']))) {
         $this->_verbose=isset(getopt("",["verbose",])['verbose']);
      }

      $this->_nonInteractive = isset(getopt("",["non-interactive",])['non-interactive']);
      
      $this->_stdoutMode = isset(getopt("",["stdout",])['stdout']);
      
      if ($this->_stdoutMode) {
         $this->_verbose = false;
         $this->_quiet = true;
      }

      $this->_quiet || self::_showIntro();

      require self::_getAppDir() . "/bin-common.php";
      
      $config = config::LoadAssoc("mysql");
      
      if ($this->_stdoutMode) {
         if (false===($backupFile = tempnam(sys_get_temp_dir(), static::ME."-"))) {
            trigger_error("tempnam() failed",\E_USER_ERROR);
         }
         register_shutdown_function(function() use(&$backupFile)
         {
            if (file_exists($backupFile)) unlink($backupFile);
         });
      } else
      if (!empty(getopt("",["export-name::",])['export-name'])) {
         $backupName = getopt("",["export-name::",])['export-name'];
         
         if (pathinfo($backupName,\PATHINFO_EXTENSION)!=='sql') $backupName.=".sql";
         
         $this->_quiet || self::_showLine(["export name: $backupName"]);
         $backupRootDir = self::_getAppDir()."/data/mysql/backup";
         $backupDir = pathinfo("$backupRootDir/$backupName",\PATHINFO_DIRNAME);
         if (!is_dir($backupDir)) {
            if (!mkdir($backupDir,0770,true)) {
               self::_showErrLine([self::ME . " (ERROR) could not create backup directory: $backupDir"]);
               return $this->_exitStatus = 1;
            }
            $this->_verbose && self::_showLine(["created export volume dir: $backupDir"]);
         }
         $backupFile = "$backupRootDir/$backupName";
      } else
      if (!empty(getopt("",["export-file::",])['export-file'])) {
         $backupFile = getopt("",["export-file::",])['export-file'];
      } else {
         $backupRootDir = self::_getAppDir()."/data/mysql/backup";
         
         $backupRelpath = date("Y/m/d")."/{$config['dbname']}-".date("Ymd")."T".date("HiO").".sql";
         $backupDir = pathinfo("$backupRootDir/$backupRelpath",\PATHINFO_DIRNAME);
         if (!is_dir($backupDir)) {
            if (!mkdir($backupDir,0770,true)) {
               self::_showErrLine([self::ME . " (ERROR) could not create backup directory: $backupDir"]);
               return $this->_exitStatus = 1;
            }
            $this->_verbose && self::_showLine(["created export volume dir: $backupDir"]);
         }
         $this->_quiet || self::_showLine(["export volume: $backupRelpath"]);
         $backupFile = "$backupRootDir/$backupRelpath";
      }
      $this->_verbose && self::_showLine(["export-file: $backupFile"]);
      $this->_quiet || self::_showLine(["started export at ".date("c")."..."]);
      $dump = new Mysqldump($config['dsn'], $config['username'], $config['password'],[],$config['options']);
      $dump->start($backupFile);
      if ($this->_stdoutMode) {
         if (false===($h = fopen($backupFile,"rb"))) {
            trigger_error("fopen() failed",\E_USER_ERROR);
         }
         while(!feof($h)) {
            if (false===($buff = fread($h,1024*1024))) {
               trigger_error("fread() failed",\E_USER_ERROR);
            }
            echo $buff;
         }
         fclose($h);
      }
      $this->_quiet || self::_showLine(["(export complete)"]);
   }
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

})->getExitStatus())) {
   if (PHP_SAPI=='cli') {
      $installer->showUsage();
      exit($exitStatus);
   }
   return $exitStatus;
}








