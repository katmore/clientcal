#!/usr/bin/env php
<?php
use clientcal\config;

return(function() {
   if (0!==($exitStatus=($installer = new class() {

      const ME_LABEL = 'ClientCal Database Installer';
       
      const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
       
      const USAGE = '[--help][--usage] | [--app-dir=<PATH>] <ACTION:add|change> <USERNAME> [<PASSWORD>]';
       
      const COPYRIGHT = '(c) 2006-2017 Paul D. Bird II. All Rights Reserved.';
       
      const ME = 'clientcal-user.php';
      
      const DEFAULT_APP_DIR=__DIR__.'/../app';
      
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
         $fallbackAppDir = self::DEFAULT_APP_DIR;
         $me = self::ME;
         echo <<<"EOT"
Mode Switches:
--help
   Output a help message and exit.

--usage
   Output a brief usage message and exit.

Output Control Switches:
--quiet
   Provide only essential output to STDOUT.

--non-interactive
   No input prompts will be issued for config values; the default values will be used automatically.

Path Options:
--app-dir=<PATH>
   The directory where this script will look for the "bin-common.php" file.
   Local system default: $fallbackAppDir
EOT;
      }
       
      /**
       * @return void
       * @static
       */
      private static function showIntro() {
         echo self::ME_LABEL."\n".self::COPYRIGHT.\PHP_EOL;
      }
       
      /**
       * @return void
       * @param string[]
       * @static
       */
      private static function showErrLine(array $strLines) {
         $prefix = self::ME . ": ";
         $stderr = fopen('php://stderr', 'w');
         foreach ($strLines as $line) fwrite($stderr, $prefix.$line.\PHP_EOL);
         fclose($stderr);
      }
      /**
       * @return void
       * @param string[]
       * @static
       */
      private static function showLine(array $strLines) {
         foreach ($strLines as $line) echo "$line".\PHP_EOL;
      }
       
      /**
       * @var int
       */
      private $exitStatus=0;
      
      private static function hide_term() {
         if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty -echo');
      }
      
      private static function restore_term() {
         if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
            system('stty echo');
      }
      
       
      /**
       * @return int Exit status
       */
      public function getExitStatus() :int { return $this->exitStatus; }

      /**
       * @var bool
       */
      private $quiet;
       
      /**
       * @var bool
       */
      private $_verbose;
       
      /**
       * @var bool
       */
      private $_nonInteractive;
      
      const PASSWORD_MIN_LEN = 8;
      
      public function __construct() {
         
         $arg = [];
         
         if (isset($_SERVER) && isset($_SERVER['argv']) && is_array($_SERVER['argv'])) $arg = $_SERVER['argv'];
         
         $action_arg = null;
         
         if (isset($arg[1])) $action_arg = $arg[1];
         
         $modeSwitch = getopt("hu?",["help","usage",]);
         if (isset($modeSwitch['help']) || isset($modeSwitch['h']) || isset($modeSwitch['?']) || ($action_arg==='help')) {
            static::showIntro();
            static::showHelp();
            return;
         }
         if (isset($modeSwitch['usage']) || isset($modeSwitch['u']) || ($action_arg==='usage')) {
            static::showUsage();
            return;
         }

         if (!$this->quiet) {
            self::showIntro();
            static::showLine([""]);
         }
         
         if (empty($action_arg)) {
            $this->exitStatus = 2;
            static::showErrLine(["missing <ACTION>"]);
            return;
         }
         
         if (!in_array($action_arg,['add','change'])) {
            $this->exitStatus = 2;
            static::showErrLine(["unknown <ACTION> '$action_arg'"]);
            return;
         }
         
         /*
          * username sanity check
          */
         if (empty($arg[2])) {
            $this->exitStatus = 2;
            static::showErrLine(["missing <USERNAME>"]);
            return;
         }
         $username = $arg[2];
         
         /*
          * bin-common.php sanity check and inclusion
          */
         if (!empty(getopt("",["app-dir::",])['app-dir'])) {
            $binCommonPath = getopt("",["app-dir::",])['app-dir'] . "/bin-common.php";
            $appDirOriginLabel = '--app-dir';
            $appPathErrorStatus = 2;
         } else {
            $binCommonPath = self::DEFAULT_APP_DIR . "/bin-common.php";
            $appDirOriginLabel = 'DEFAULT_APP_DIR';
            $appPathErrorStatus = 3;
         }
         if (!is_file($binCommonPath) || !is_readable($binCommonPath)) {
            $this->exitStatus = $appPathErrorStatus;
            static::showErrLine(["$appDirOriginLabel did not contain a readable 'bin-common.php' file at '$binCommonPath'"]);
            return;
         }
         require $binCommonPath;
         
         /*
          * password prompt and sanity check
          */
         if (!empty($arg[3])) {
            $password = $arg[3];
         } else {
            //$password = readline("Please provide password for '$username': ");
            echo "Please provide password for '$username': ";
            static::hide_term();
            $password = rtrim(fgets(STDIN), PHP_EOL);
            static::restore_term();
            echo PHP_EOL;
            if (empty($password)) {
               $this->exitStatus = 4;
               static::showErrLine(["password cannot be empty"]);
               return;
            }
            echo "Confirm password: ";
            static::hide_term();
            $password2 = rtrim(fgets(STDIN), PHP_EOL);
            static::restore_term();
            echo PHP_EOL;
            
            if ($password!==$password2) {
               $this->exitStatus = 4;
               static::showErrLine(["passwords did not match"]);
               return;
            }
            
         }
         
         if (strlen($password) < self::PASSWORD_MIN_LEN) {
            $this->exitStatus = 4;
            static::showErrLine(["password must be at least ".self::PASSWORD_MIN_LEN. " characters long"]);
            return;
         }
         
         /*
          * get mysql configuration
          */
         $mysqlConfig = config::LoadAssoc("mysql");
         
         $pdo = new \PDO($mysqlConfig['dsn'],$mysqlConfig['username'],$mysqlConfig['password'],$mysqlConfig['options']);
         
         if ($action_arg=='add') {
            $stmt = $pdo->prepare("INSERT INTO user SET username=:username, password=PASSWORD(:password)");
            $stmt->execute([
               ':username'=>$username,
               ':password'=>$password,
            ]);
            if (!$stmt->rowCount()) {
               $this->exitStatus = 5;
               static::showErrLine(["failed to create user"]);
               return;
            }
            $user_id = $pdo->lastInsertId();
            static::showLine(["created user #$user_id '$username'"]);
            return;
         }
         
         if ($action_arg=='change') {
            $stmt = $pdo->prepare("UPDATE user SET password=PASSWORD(:password) WHERE username=:username");
            $stmt->execute([
               ':username'=>$username,
               ':password'=>$password,
            ]);
            if (!$stmt->rowCount()) {
               $this->exitStatus = 5;
               static::showErrLine(["failed to update user password"]);
               return;
            }
            static::showLine(["updated password for user '$username'"]);
            return;
         }
         //
         //
         //
      }
       

       
       
       
       
       
       
       
       
       
       
       
       
       
       
       

   })->getExitStatus())) {
      if (PHP_SAPI=='cli') {
         exit($exitStatus);
      }
      return $exitStatus;
   }
})();








