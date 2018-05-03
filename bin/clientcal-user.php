#!/usr/bin/env php
<?php
use clientcal\config;

return(function() {
   if (0!==($exitStatus=($installer = new class() {

      const ME_LABEL = 'ClientCal User Manager';
       
      const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
       
      const USAGE = '[--help][--usage] | [--app-dir=<PATH>] <ACTION:add|set-password|set-email|reset-password|remove|list> [<USERNAME> [<PASSWORD>|<EMAIL>]]';
       
      const COPYRIGHT = '(c) 2006-2018 Paul D. Bird II. All Rights Reserved.';
       
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
         echo self::HELP_LABEL.PHP_EOL;
         $fallbackAppDir = self::DEFAULT_APP_DIR;
         $me = self::ME;
         $help=<<<"HELP"
Options:
--help
   Output a help message and exit.

--usage
   Output a brief usage message and exit.

--quiet
   Provide only essential output to STDOUT.

--app-dir=<PATH>
   The directory where this script will look for the "bin-common.php" file.
   Local system default: $fallbackAppDir
   
Arguments:
<ACTION>
   Specify the user management action to perform.
   Possible values: add, password, remove.
      add: Creates a new user; prompts for password unless the <PASSWORD> argument is specified.
      remove: Removes an existing user.
      reset-password: Sends a user password-reset email.
      set-password: Updates an existing user's password; prompts for password unless the <PASSWORD> argument is specified.
      set-email: Updates an existing user's email address.
      list: Provide list of existing users.

<PASSWORD>
   Specify the user's password for an applicable <ACTION>; avoid being prompted for password.

<EMAIL>
   Specify a user's email address for an applicable <ACTION>; avoid being prompted for email.
HELP;
         echo str_replace("\n",\PHP_EOL,$help).\PHP_EOL;
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
      private static function showErrLine(array $strLines,bool $showPrefix=true) {
         $prefix = "";
         if ($showPrefix) $prefix = self::ME . ": ";
         if (PHP_SAPI=='cli') {
            $stderr = fopen('php://stderr', 'w');
            foreach ($strLines as $line) fwrite($stderr, $prefix.$line.\PHP_EOL);
            fclose($stderr);
            return;
         }
         foreach ($strLines as $line) echo $prefix.$line.\PHP_EOL;
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
            $appPathErrorStatus = 1;
         }
         if (!is_file($binCommonPath) || !is_readable($binCommonPath)) {
            $this->exitStatus = $appPathErrorStatus;
            static::showErrLine(["$appDirOriginLabel did not contain a readable 'bin-common.php' file at '$binCommonPath'"]);
            return;
         }
         require $binCommonPath;
         
         $arg = [];
         
         if (isset($_SERVER) && isset($_SERVER['argv']) && is_array($_SERVER['argv'])) $arg = $_SERVER['argv'];
         
         $action_arg = null;
         
         if (isset($arg[1])) $action_arg = $arg[1];
         
         $modeSwitch = getopt("hu?",["help","usage",]);
         if (isset($modeSwitch['help']) || isset($modeSwitch['h']) || isset($modeSwitch['?']) || ($action_arg==='help')) {
            static::showIntro();
            echo \PHP_EOL;
            static::showHelp();
            echo \PHP_EOL;
            static::showUsage();
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
         
         /*
          * map for backwards compatibility
          */
         if ($action_arg == 'change') {
            $action_arg = 'set-password';
         }
         
         if (!in_array($action_arg,['add','set-password','remove','reset-password','set-email','list'])) {
            $this->exitStatus = 2;
            static::showErrLine(["unknown <ACTION> '$action_arg'"]);
            return;
         }
         
         /*
          * username sanity check
          */
         if (in_array($action_arg,['add','set-password','remove','reset-password','set-email'])) {
            if (empty($arg[2])) {
               $this->exitStatus = 2;
               static::showErrLine(["missing <USERNAME>"]);
               return;
            }
            $username = $arg[2];
            if (!ctype_alnum(str_replace(['-','_','.'],"",$username))) {
               $this->exitStatus = 2;
               static::showErrLine(["<USERNAME> contains invalid characters"]);
               return;
            }
         }
         
         /*
          * password prompt and sanity check
          */
         if (in_array($action_arg,['add','set-password'])) {
            if (!empty($arg[3])) {
               $password = $arg[3];
            } else {
               //$password = readline("Please provide password for '$username': ");
               echo "Password for '$username': ";
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
         }
         
         if ($action_arg == 'set-email') {
            if (!empty($arg[3])) {
               $email = $arg[3];
            } else {
               $email = readline("Email address for '$username': ");
               if (empty($email)) {
                  $this->exitStatus = 4;
                  static::showErrLine(["email cannot be empty"]);
                  return;
               }
               if (false===filter_var($email,\FILTER_VALIDATE_EMAIL)) {
                  $this->exitStatus = 4;
                  static::showErrLine(["invalid email address"]);
                  return;
               }
            }
         }
         
         /*
          * get mysql configuration
          */
         $mysqlConfig = config::LoadAssoc("mysql");
         
         $pdo = new \PDO($mysqlConfig['dsn'],$mysqlConfig['username'],$mysqlConfig['password'],$mysqlConfig['options']);
         
         if ($action_arg=='list') {
            $pdo->prepare("SELECT username FROM user");
            $stmt->execute();
            if (!$stmt->rowCount()) {
               static::showErrLine(["no clientcal login users found"]);
               return;
            }
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
               static::showLine([$row['username']]);
            }
            return;
         }
         
         if ($action_arg=='add') {
            $password_hash = password_hash($password, \PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO user SET username=:username, password=:password");
            $stmt->execute([
               ':username'=>$username,
               ':password'=>$password_hash,
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
         
         if ($action_arg=='set-password') {
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
         
         if ($action_arg=='remove') {
            $stmt = $pdo->prepare("DELETE FROM user WHERE username=:username");
            $stmt->execute([
               ':username'=>$username,
            ]);
            if (!$stmt->rowCount()) {
               $this->exitStatus = 5;
               static::showErrLine(["failed to remove user"]);
               return;
            }
            static::showLine(["removed user '$username'"]);
            return;
         }
         
         if ($action_arg == 'set-email') {
            $stmt = $pdo->prepare("UPDATE user SET email=:email WHERE username=:username");
            $stmt->execute([
               ':username'=>$username,
               ':email'=>$email,
            ]);
            if (!$stmt->rowCount()) {
               $this->exitStatus = 5;
               static::showErrLine(["failed to update user email"]);
               return;
            }
            static::showLine(["updated email for user '$username'"]);
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








