#!/usr/bin/env php
<?php
return(function() {
   if (0!==($exitStatus=($installer = new class() {

   const ME_LABEL = 'ClientCal Database Installer';
   
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
   
   const USAGE = '[--help][--non-interactive][--quiet]';
   
   const COPYRIGHT = '(c) 2006-2018 Paul D. Bird II. All Rights Reserved.';
   
   const ME = 'config-install.php';
   
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
      echo self::HELP_LABEL."\n";
      $fallbackDbname = self::FALLBACK_DBNAME;
      $fallbackHost = self::FALLBACK_HOST;
      $fallbackUsername = self::FALLBACK_USERNAME;
      echo <<<"EOT"
Output Control:
--help
   Enable "help mode": outputs this message then exits.

--quiet
   Enable "quiet mode": only output will be errors to STDERR.

--non-interactive
   Enable "non interactive mode": No input prompts will be issued (such as for username, password, etc.)

Database Options:

EOT;
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
   
   
   public function __construct() {
      
      if (isset(getopt("",["help",])['help'])) {
         self::_showIntro();
         self::showHelp();
         return;
      }
      
      $this->_verbose = false;
      $this->_quiet=isset(getopt("",["quiet",])['quiet']);
      
      $this->_nonInteractive = isset(getopt("",["non-interactive",])['non-interactive']);
      
      $this->_quiet || self::_showIntro();
      
      


      
   }
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

})->getExitStatus())) {
   if (PHP_SAPI=='cli') {
      $installer->showUsage();
      exit($exitStatus);
   }
   return $exitStatus;
}
})();








