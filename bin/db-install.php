<?php
return(function() {
   if (0!==($exitStatus=(new class() {

   const ME_LABEL = 'ClientCal Database Installer';
   
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
   
   const USAGE = '[--help [ [--quiet] [--username=<db username> [--password=<db password>]] [ [--host=<mysql host>[--dbname=<mysql dbname>]] | [--dsn=<PDO Data Source Name>]]]]';
   
   const COPYRIGHT = '(c) 2006-2017 Paul D. Bird II. All Rights Reserved.';
   
   const ME = 'db-install.php';
   
   const FALLBACK_DBNAME = "clientcal";
   
   const FALLBACK_HOST = "localhost";
   
   const FALLBACK_USERNAME = "root";
   
   const FALLBACK_SCHEMA_JSON = __DIR__ . "/../app/data/mysql/schema.json";
   
   private static function _showHelp() {
      echo HELP_LABEL."\n";
      $fallbackDbname = self::FALLBACK_DBNAME;
      $fallbackHost = self::FALLBACK_HOST;
      $fallbackUsername = self::FALLBACK_USERNAME;
      echo <<<"EOT"
SWITCHES:
--help (optional)
   Output this message and exit.

--quiet (optional)
   Only output errors.

OPTIONS:
--username=<db username> (optional)
   DB Username
   default value: "root"

--password=<db password> (optional)
   DB Password

--host=<mysql host> (optional, ignored if --dsn option is present)
   MySQL Host
   default value: "$fallbackHost"

--dbname=<mysql dbname> (optional, ignored if --dsn option is present)
   MySQL Dbname
   default value: "$fallbackDbname"

--dsn=<PDO Data Source Name> (optional)
   PDO Data Source Name string
   See: http://php.net/manual/en/pdo.construct.php

EOT;
   }
   
   /**
    * @return void
    * @static
    */
   private static function _showUsage() {
      echo SELF::ME." ".self::USAGE.\PHP_EOL;
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
   
   //CLIENTCAL_ROOT
   public function __construct() {
      
      if (!empty(getopt("",["help",])['help'])) {
         self::_showUsage();
         return;
      }
      
      $quiet=!empty(getopt("",["quiet",])['quiet']);
      
      $quiet || self::_showIntro();
      
      $option=getopt("",[
         "username::",
         "password::",
         "dsn::",
      ]);
      
      $quiet || self::_showLine(["mysql PDO connect..."]);
      
      $pdoconfig = [
         'username'=>self::FALLBACK_USERNAME,
         'password'=>null,
         'dsn'=>null,
      ];
      foreach($pdoconfig as $k=>&$v) {
         if (!empty($option[$k])) $v=$option[$k];
      }
      unset($k);
      unset($v);
      /*
         'host'=>'localhost',
         'dbname'=>'clientcal',
       */
      if (empty($pdoconfig['dsn'])) {
         if (!empty(getopt("",['host'])['host'])) {
            $host=getopt("",['host'])['host'];
         } else {
            $host=self::FALLBACK_HOST;
         }
         if (!empty(getopt("",['dbname'])['dbname'])) {
            $dbname=getopt("",['dbname'])['dbname'];
         } else {
            $dbname=self::FALLBACK_DBNAME;
         }
         $pdoconfig['dsn'] = "mysql:host=$host;dbname=$dbname"; 
      }
      
      try {
         $pdo = new PDO(
             $pdoconfig['dsn'], 
             $pdoconfig['username'], 
             $pdoconfig['password'],
             [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
         );
      } catch (PDOException $e) {
         if ($quiet) {
            self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage()]);
            return $this->_exitStatus = 1;
         } else {
            self::_showErrLine([self::ME.": (NOTICE) Connection failed: ".$e->getMessage()]);
            echo PHP_EOL."Correct connection parameters below or use Ctrl^C to cancel.".PHP_EOL.PHP_EOL;
            $newHost = readline("Enter the host ($host): ");
            if (!empty($newHost)) {
               $host = $newHost;
            }
            $newDbname = readline("Enter the dbname ($dbname): ");
            if (!empty($newDbname)) {
               $dbname = $newDbname;
            }
            $pdoconfig['dsn'] = "mysql:host=$host;dbname=$dbname";
            $newUsername = readline("Enter the username ({$pdoconfig['username']}): ");
            if (!empty($newUsername)) {
               $pdoconfig['username'] = $newUsername;
            }
            exec("which stty",$output,$sttyStatus);
            if (empty($sttyStatus)) {
               echo "Enter the password ({hidden}): ";
               $newPassword = preg_replace('/\r?\n$/', '', `stty -echo; head -n1 ; stty echo`);
               echo PHP_EOL;
               $pdoconfig['password'] = $newPassword;
            }
            try {
               $pdo = new PDO(
                     $pdoconfig['dsn'],
                     $pdoconfig['username'],
                     $pdoconfig['password'],
                     [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
                     );
            } catch (PDOException $e) {
               self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage()]);
               return $this->_exitStatus = 1;
            }
         }
      }
      
      $quiet || self::_showLine(["(PDO connection success)"]);
      
      $schemaJson = self::FALLBACK_SCHEMA_JSON;
      if (!is_file(realpath($schemaJson))) {
         self::_showErrLine([self::ME.": (ERROR) schema-json path did not resolve to readable file: '$schemaJson'"]);
         return $this->_exitStatus = 1;
      }
      $schemaJson = realpath($schemaJson);
      
      $quiet || self::_showLine(["schema-json: $schemaJson"]);
      
      $schemaDir = pathinfo($schemaJson,\PATHINFO_DIRNAME);
      if (!is_dir($schemaDir)) {
         self::_showErrLine([self::ME.": (ERROR) could not stat schema-json directory: '$schemaDir'"]);
         return $this->_exitStatus = 1;
      }
      
      $quiet || self::_showLine(["schema-dir: $schemaDir"]);
      
      $schemaCfg = new class(json_decode(file_get_contents($schemaJson),true)) {
         public $version;
         public $table;
         public $sql_dir;
         public function __construct(array $cfg) {
             foreach($this as $prop=>&$v) {
                if (!empty($cfg[str_replace("_","-",$prop)])) $v=$cfg[str_replace("_","-",$prop)];
             }
             unset($prop);
             unset($v);
         }
      };
      
      $badCfg=[];
      foreach($schemaCfg as $prop=>$v) {
         if (empty($v)) $badCfg[]="   missing value for '".str_replace("_","-",$prop)."'";
      }
      unset($prop);
      unset($v);
      
      if ($badCfg) {
         self::_showErrLine([self::ME.": (ERROR) bad schema.json format:"]);
         self::_showErrLine($badCfg);
         return $this->_exitStatus = 1;
      }
      
      $quiet || self::_showLine(["latest schema version: v{$schemaCfg->version}"]);
      
      $version = null;
      $stmt = $pdo->query("SHOW TABLES LIKE '{$schemaCfg->table}'");
      if (!$stmt->rowCount()) {
         $pdo->query("CREATE TABLE `{$schemaCfg->table}` (
           `version` varchar(20) COLLATE utf8_bin NOT NULL,
           `installed_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
           PRIMARY KEY (`version`),
           KEY `installed_time` (`installed_time`)
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
         $stmt = $pdo->query("SHOW TABLES LIKE 'customer'");
         if ($stmt->rowCount()) $version = "1.98";
      } else {
         $stmt = $pdo->query("
         SELECT
            version
         FROM
            {$schemaCfg->table}
         ORDER BY
            installed_time ASC
         LIMIT 1
         ");
         if ($stmt->rowCount()) $version = $stmt->fetch(PDO::FETCH_ASSOC)['version'];
      }
      if (!$version) {
         $dumpSql = "{$schemaCfg->sql_dir}/{$schemaCfg->version}/schema-dump.sql";
         $quiet || self::_showLine(["populating database using schema v{$schemaCfg->version} using: $dumpSql"]);
         if (!is_readable($dumpSql) || !is_file($dumpSql)) {
            self::_showErrLine([self::ME.": (ERROR) schema dump did not resolve to readable file: $dumpSql"]);
            return $this->_exitStatus = 1;
         }
         $pdo->exec(file_get_contents($dumpSql));
         $version=$schemaCfg->version;
         unset($dumpSql);
      } else {
         for($newVersion = floatval($version)+0.01;$newVersion<($schemaCfg->version+0.01);$newVersion+=0.01) {
            $updateSql = "{$schemaCfg->sql_dir}/$newVersion/schema-updates.sql";
            $quiet || self::_showLine(["updating from v$version to v$newVersion using: $updateSql"]);
            if (!is_readable($updateSql) || !is_file($updateSql)) {
               self::_showErrLine([self::ME.": (ERROR) update sql did not resolve to readable file: $updateSql"]);
               return $this->_exitStatus = 1;
            }
            $pdo->exec(file_get_contents($updateSql));
            $version=$newVersion;
         }
         unset($newVersion);
         unset($updateSql);
      }
      $quiet || self::_showLine(["database '{$pdo->query('select database()')->fetchColumn()}' updated to schema v$version"]);
      if ($version!=$schemaCfg->version) {
         self::_showErrLine([self::ME.": (ERROR) unable to update to latest schema v{$schemaCfg->version}"]);
         return $this->_exitStatus = 1;
      }
   }
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

})->getExitStatus())) {
   if (PHP_SAPI=='cli') exit($exitStatus);
   return $exitStatus;
}
})();








