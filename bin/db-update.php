#!/usr/bin/env php
<?php
use clientcal\config;
use Ifsnop\Mysqldump\Mysqldump;
return(function() {
   if (0!==($exitStatus=($installer = new class() {

   const ME_LABEL = 'ClientCal Database Installer';
   
   const HELP_LABEL = "ClientCal Scheduler Project: https://github.com/katmore/clientcal";
   
   const USAGE = '[--help [ [--non-interactive] [--quiet | --verbose] [--my-cnf | [--username=<db username> [--password=<db password>]] [ [--host=<mysql host> [--dbname=<mysql dbname>]] | [--dsn=<PDO Data Source Name>]]]]]';
   
   const COPYRIGHT = '(c) 2006-2017 Paul D. Bird II. All Rights Reserved.';
   
   const ME = 'db-install.php';
   
   const FALLBACK_DBNAME = "clientcal";
   
   const FALLBACK_HOST = "localhost";
   
   const FALLBACK_USERNAME = "root";
   
   const FALLBACK_SCHEMA_JSON = __DIR__ . "/../app/data/schema-sql/db-schema.json";
   
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
      $fallbackDbname = self::FALLBACK_DBNAME;
      $fallbackHost = self::FALLBACK_HOST;
      $fallbackUsername = self::FALLBACK_USERNAME;
      $help=<<<"EOT"
Output Control:
--help
   Enable "help mode": outputs this message then exits.

--quiet
   Enable "quiet mode": only output will be errors to STDERR.

--verbose (ignored if --quiet switch is present)
   Enable "verbose mode": outputs extra information (such as full system paths, etc.)

--non-interactive
   Enable "non interactive mode": No input prompts will be issued (such as for username, password, etc.)

Database Options:
--my-cnf=[<path to MySQL config file>]
   Indicate that the database parameters for host, username, and password should be retrieved from a MySQL config file.
   The path is optional and when omitted defaults to: {home dir}/.my.cnf
   NOTE: The MySQL config file's value for the database name is ignored. 

--dbname=<mysql dbname> (optional, cannot be in conjunction used with --dsn option)
   MySQL Dbname
   default value: "$fallbackDbname"

--username=<db username> (optional, cannot be in conjunction used with --my-cnf option)
   DB Username
   default value: "$fallbackUsername"

--password=<db password> (optional, cannot be in conjunction used with --my-cnf option)
   DB Password
   default value: {none}

--host=<mysql host> (optional, cannot be used in conjunction with the --dsn option)
   MySQL Host
   default value: "$fallbackHost"

--dsn=<PDO Data Source Name> (optional, cannot be used in conjunction with the --my-cnf option)
   PDO Data Source Name string
   See: http://php.net/manual/en/pdo.construct.php
EOT;
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
    * @return \PDO | int provides PDO object or exit status code
    */
   private function _getPdo() {
      
      $supplied_option = false;
      
      $option=getopt("",[
         "my-cnf::",
         "username::",
         "password::",
         "dsn::",
      ]);
   
   
   
      $pdoconfig = [
         'username'=>self::FALLBACK_USERNAME,
         'password'=>null,
         'dsn'=>null,
      ];
   
      $myCnf=null;
      if (isset($option['my-cnf'])) {
         $supplied_option = true;
         if (!empty($option['my-cnf'])) {
            if (!is_file($option['my-cnf']) || !is_readable($option['my-cnf'])) {
               self::_showErrLine([self::ME.": (ERROR) MySQL config path did not resolve to a readable file: {$option['my-cnf']}"]);
               return $this->_exitStatus = 1;
            }
            $myCnfFile= $option['my-cnf'];
         } else {
            if (!empty($_SERVER['HOME']) && realpath($_SERVER['HOME']) && is_dir($_SERVER['HOME'])) {
               $myCnfFile = realpath($_SERVER['HOME'])."/.my.cnf";
               if (!is_file($myCnfFile) || !is_readable($myCnfFile)) {
                  self::_showErrLine([self::ME.": (ERROR) A readable MySQL config file did not exist in the user home directory: $myCnfFile"]);
                  return $this->_exitStatus = 1;
               }
            } else {
               self::_showErrLine([self::ME.": (ERROR) The PHP Environment did not provide a 'HOME' directory for .my.cnf config file."]);
               return $this->_exitStatus = 1;
            }
         }
   
         if ($this->_verbose) {
            self::_showLine(["using MySQL config file: $myCnfFile"]);
         }
         $myCnf = parse_ini_file($myCnfFile,true);
         if (!isset($myCnf['client']) || !is_array($myCnf['client'])) {
            self::_showErrLine([self::ME.": (ERROR) bad MySQL config file; missing [client]' section"]);
            return $this->_exitStatus = 1;
         }
         $usedSetting=[];
         $myCnfSetting=['host','user','password'];
         $myCnfValue=[];
         foreach($myCnfSetting as $k) {
            if (!empty($myCnf['client'][$k])) {
               $myCnfValue[$k]=$myCnf['client'][$k];
               $usedSetting[]=$k;
            }
         }
         unset($k);
         unset($v);
          
         if (!count($usedSetting)) {
            self::_showErrLine([self::ME.": (ERROR) bad MySQL config file; at least one of the following settings must exist in the [client] section: ".implode(", ",$myCnfSetting)]);
            return $this->_exitStatus = 1;
         }
          
         $this->_quiet || self::_showLine(['using MySQL config file settings for: '.implode(', ',$usedSetting)]);
          
         if (!empty(getopt("",['dbname'])['dbname'])) {
            $dbname=getopt("",['dbname'])['dbname'];
         } else {
            $dbname=self::FALLBACK_DBNAME;
         }
          
         if (!empty($myCnfSetting['host'])) {
            $host = $myCnfSetting['host'];
         } else {
            if (!empty(getopt("",['host'])['host'])) {
               $host=getopt("",['host'])['host'];
            } else {
               $host=self::FALLBACK_HOST;
            }
         }
          
         if (!empty($myCnfValue['user'])) $pdoconfig['username']=$myCnfValue['user'];
         if (!empty($myCnfValue['password'])) $pdoconfig['password']=$myCnfValue['password'];
          
         unset($myCnfValue);
         unset($myCnfSetting);
         unset($usedSetting);
      } else {
         foreach($pdoconfig as $k=>&$v) {
            if (!empty($option[$k])) {
               $v=$option[$k];
               $supplied_option = true;
            }
         }
         unset($k);
         unset($v);
         /*
          'host'=>'localhost',
          'dbname'=>'shrturl',
          */
         if (empty($pdoconfig['dsn'])) {
            if (!empty(getopt("",['host'])['host'])) {
               $host=getopt("",['host'])['host'];
               $supplied_option = true;
            } else {
               $host=self::FALLBACK_HOST;
            }
         }
      }
      if (!empty(getopt("",['dbname'])['dbname'])) {
         $dbname=getopt("",['dbname'])['dbname'];
         $supplied_option = true;
      } else {
         $dbname=self::FALLBACK_DBNAME;
      }
      
      if (!$supplied_option) {
         $this->_quiet || self::_showLine(["Starting PDO connection (using existing clientcal configuration)..."]);
         $mysqlConfig = config::LoadAssoc("mysql");
         try {
            $pdo = new \PDO($mysqlConfig['dsn'],$mysqlConfig['username'],$mysqlConfig['password'],$mysqlConfig['options']);
         } catch (PDOException $e) {
            self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage() . " using existing clientcal configuration (app/config/mysql.php)"]);
            return $this->_exitStatus = 1;
         }
         $this->_quiet || self::_showLine(["(PDO connection success)"]);
         return $pdo;
      }
      
      $pdoconfig['dsn'] = "mysql:host=$host;dbname=$dbname";
   
      try {
         $this->_quiet || self::_showLine(["Starting PDO connection..."]);
         $pdo = new class (
               $pdoconfig['dsn'],
               $pdoconfig['username'],
               $pdoconfig['password'],
               [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
               ) extends \PDO {
                  public $dsn;
                  public $username;
                  public $password;
                  public $options;
                  public function __construct($dsn,$username,$password,$options) {
                     $this->dsn = $dsn;
                     $this->username = $username;
                     $this->password = $password;
                     $this->options = $options;
                     parent::__construct($dsn,$username,$password,$options);
                  }
         };
      } catch (PDOException $e) {
         if ($this->_nonInteractive || $myCnf) {
            $err=self::ME.": (ERROR) Connection failed: ".$e->getMessage();
   
            if($myCnf) $err.=" using MySQL config file: $myCnfFile";
   
            self::_showErrLine([$err]);
            return $this->_exitStatus = 1;
         } else {
            self::_showErrLine([self::ME.": (NOTICE) Connection failed: ".$e->getMessage()]);
            echo PHP_EOL."Interactive mode: provide new connection details or Ctrl+C to exit...".PHP_EOL;
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
               $pdo = new class (
                     $pdoconfig['dsn'],
                     $pdoconfig['username'],
                     $pdoconfig['password'],
                     [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]
                     ) extends \PDO {
                        public $dsn;
                        public $username;
                        public $password;
                        public $options;
                        public function __construct($dsn,$username,$password,$options) {
                           $this->dsn = $dsn;
                           $this->username = $username;
                           $this->password = $password;
                           $this->options = $options;
                           parent::__construct($dsn,$username,$password,$options);
                        }
               };
            } catch (PDOException $e) {
               self::_showErrLine([self::ME.": (ERROR) Connection failed: ".$e->getMessage()]);
               return $this->_exitStatus = 1;
            }
         }
      }
      $this->_quiet || self::_showLine(["(PDO connection success)"]);
      return $pdo;
   }
   
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
   
   const CREATE_SCHEMA_VERSION_SQL = "CREATE TABLE `schema_version` (
         `ns` varchar(100) COLLATE utf8_bin NOT NULL,
         `version` varchar(20) COLLATE utf8_bin NOT NULL,
         `installed_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY (`ns`,`version`),
         KEY (`ns`,`installed_time`)
         ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
   
   //CLIENTCAL_ROOT
   public function __construct() {
      
      if (isset(getopt("",["help",])['help'])) {
         self::_showIntro();
         self::showHelp();
         return;
      }
      
      $this->_verbose = false;
      if (!($this->_quiet=isset(getopt("",["quiet",])['quiet']))) {
         $this->_verbose=isset(getopt("",["verbose",])['verbose']);
      }
      
      $this->_nonInteractive = isset(getopt("",["non-interactive",])['non-interactive']);
      
      $this->_quiet || self::_showIntro();
      
      require self::_getAppDir() . "/bin-common.php";
      
      if (!($pdo = self::_getPdo()) instanceof PDO) return $pdo;
      
      $schemaJson = self::FALLBACK_SCHEMA_JSON;
      if (!is_file(realpath($schemaJson))) {
         self::_showErrLine([self::ME.": (ERROR) schema-json path did not resolve to readable file: '$schemaJson'"]);
         return $this->_exitStatus = 1;
      }
      $schemaJson = realpath($schemaJson);
      
      $this->_quiet || self::_showLine(["schema-json: $schemaJson"]);
      
      $schemaDir = pathinfo($schemaJson,\PATHINFO_DIRNAME);
      if (!is_dir($schemaDir)) {
         self::_showErrLine([self::ME.": (ERROR) could not stat schema-json directory: '$schemaDir'"]);
         return $this->_exitStatus = 1;
      }
      $schemaDir = realpath($schemaDir);
      
      $this->_verbose && self::_showLine(["schema-dir: $schemaDir"]);
      
      $schemaCfg = new class(json_decode(file_get_contents($schemaJson),true),$schemaDir) {
         public $latestVersion;
         public $name;
         public $versionHistory;
         public $type;
         public $table = 'schema_version';
         public $sql_dir;
         public function __construct(array $cfg,$schemaDir) {
             foreach($this as $prop=>&$v) {
                if (!empty($cfg[str_replace("_","-",$prop)])) $v=$cfg[str_replace("_","-",$prop)];
             }
             unset($prop);
             unset($v);
             $this->sql_dir = $schemaDir;
             if (is_array($this->versionHistory)) {
                ksort ( $this->versionHistory );
             }
         }
      };
      
      $badCfg=[];
      foreach($schemaCfg as $prop=>$v) {
         if (empty($v)) $badCfg[]="   missing value for '".str_replace("_","-",$prop)."'";
      }
      unset($prop);
      unset($v);
      
      if ($badCfg) {
         self::_showErrLine([self::ME.": (ERROR) bad db-schema.json:"]);
         self::_showErrLine($badCfg);
         return $this->_exitStatus = 1;
      }
      
      $this->_quiet || self::_showLine(["latest schema version: v{$schemaCfg->latestVersion}"]);
      
      $this->_verbose && self::_showLine(["schema-table: {$pdo->query('select database()')->fetchColumn()}.{$schemaCfg->table}"]);
      
      $version = null;
      $stmt = $pdo->query("SHOW TABLES LIKE '{$schemaCfg->table}'");
      if (!$stmt->rowCount()) {
         $pdo->query(str_replace("schema_version",$schemaCfg->table,self::CREATE_SCHEMA_VERSION_SQL));
         $stmt = $pdo->query("SHOW TABLES LIKE 'customer'");
         if ($stmt->rowCount()) $initialVersion = $version = "1.98";
      } else {
         $stmt = $pdo->query("SHOW COLUMNS IN `{$schemaCfg->table}`");
         $hasNS = false;
         while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if ($row['Field']=='ns') {
               $hasNS = true;
               break 1;
            }
         }
         if (!$hasNS) {
            $renameTable = $schemaCfg->table."_backup_".date("YmdHis");
            $pdo->query("RENAME TABLE `{$schemaCfg->table}` TO `$renameTable`");
            $pdo->query(str_replace("schema_version",$schemaCfg->table,self::CREATE_SCHEMA_VERSION_SQL));
            $pdo->query("
            INSERT INTO
               `{$schemaCfg->table}`
            (`ns`,`version`,`installed_time`)
            SELECT
               ('{$schemaCfg->name}',`version`,`installed_time`)
            FROM
               `$renameTable`
            ");
         }
         $stmt = $pdo->prepare("
         SELECT
            `version`
         FROM
            `{$schemaCfg->table}`
         WHERE
            `ns`=:ns
         ORDER BY
            `installed_time`
         ASC
         LIMIT 1
         ");
         $stmt->execute([':ns'=>$schemaCfg->name]);
         if ($stmt->rowCount()) {
            $version = $stmt->fetch(PDO::FETCH_ASSOC)['version'];
         } else {
            $stmt = $pdo->query("SHOW TABLES LIKE 'customer'");
            if ($stmt->rowCount()) $initialVersion = $version = "1.98";
         }
      }
      
      $stmt = $pdo->query("SHOW TABLES");
      if ($stmt->rowCount()>1) {
         $databaseName = $pdo->query('select database()')->fetchColumn();
         $backupFile = "$databaseName-".date("Ymd")."T".date("HiO").".sql";
         self::_showErrLine([self::ME.": (WARNING) found existing tables in '$databaseName' database"]);
         if ($this->_nonInteractive) {
            $createBackup = true;
         } else {
            $createBackup = null;
            for($i=0;$i<5;$i++) {
               $confirm = readline("Create backup [y/n]: ");
               if (substr($confirm,0,1)=='y') {
                  $createBackup = true;
                  break 1;
               } elseif (substr($confirm,0,1)=='n') {
                  $createBackup = false;
                  break 1;
               }
            }
            if ($createBackup===null) {
               self::_showErrLine([self::ME.": (ERROR) Invalid backup confirmation $i times."]);
               return $this->_exitStatus = 1;
            }
         }
         if ($createBackup) {
            self::_showErrLine([self::ME.": (NOTICE) existing database will be exported to: $backupFile"]);
            $this->_quiet || self::_showLine(["started export at ".date("c")."..."]);
            $dump = new Mysqldump($pdo->dsn, $pdo->username, $pdo->password,[],$pdo->options);
            $dump->start($backupFile);
            $this->_quiet || self::_showLine(["(export complete)"]);
         }
      }
      
      if (!$version) {
         $initialVersion = '0';
         $dumpSql = "{$schemaCfg->sql_dir}/{$schemaCfg->latestVersion}/schema-dump.sql";
         $this->_verbose && self::_showLine(["restoring database using schema v{$schemaCfg->latestVersion} using: $dumpSql"]);
         if (!is_readable($dumpSql) || !is_file($dumpSql)) {
            self::_showErrLine([self::ME.": (ERROR) schema dump did not resolve to readable file: $dumpSql"]);
            return $this->_exitStatus = 1;
         }
         $pdo->exec(file_get_contents($dumpSql));
         $version=$schemaCfg->latestVersion;
         $pdo->prepare("
         INSERT INTO
            `{$schemaCfg->table}`
         SET
            ns=:ns,
            version=:version
         ")->execute([':version'=>$version,':ns'=>$schemaCfg->name]);
         
         unset($dumpSql);
      } else {
         $initialVersion = $currentVersion = $version;
         $this->_quiet || self::_showLine(["current schema: v$version"]);
         
         
         foreach($schemaCfg->versionHistory as $schema_v=>$schema_subdir) {
            if ($schema_v<=$currentVersion) continue;
            $this->_quiet || self::_showLine(["migrating to schema v$schema_v"]);
            $dbVersionJson = "{$schemaCfg->sql_dir}/$schema_subdir/db-version.json";
            if (false === ($dbVersion = file_get_contents($dbVersionJson))) {
               self::_showErrLine([self::ME.": (ERROR) failed to read file '$dbVersionJson'"]);
               return $this->_exitStatus = 1;
            }
            if (null === ($dbVersion = json_decode($dbVersion))) {
               self::_showErrLine([self::ME.": (ERROR) file contains invalid JSON '$dbVersionJson'"]);
               return $this->_exitStatus = 1;
            }
            
            if (!isset($dbVersion->{"sql-command"})) {
               self::_showErrLine([self::ME.": (ERROR) db-version.json file'$dbVersionJson' is missing expected 'sql-command' field"]);
               return $this->_exitStatus = 1;
            }
            if (!is_array($dbVersion->{"sql-command"})) {
               self::_showErrLine([self::ME.": (ERROR) db-version.json file'$dbVersionJson' 'sql-command' field is not an array as expected"]);
               return $this->_exitStatus = 1;
            }
            foreach($dbVersion->{"sql-command"} as $schema_v_sql) {
               
               $sql_path = "{$schemaCfg->sql_dir}/$schema_subdir/$schema_v_sql";
               
               if (false === ($sql = file_get_contents($sql_path))) {
                  self::_showErrLine([self::ME.": (ERROR) failed to read file '$sql_path'"]);
                  return $this->_exitStatus = 1;
               }
               $this->_quiet || self::_showLine(["starting '$schema_v_sql' (of schema v$schema_v)"]);
               try {
                  $pdo->exec($sql);
               } catch (PDOException $e) {
                  self::_showErrLine([self::ME.": (ERROR) '$schema_v_sql' (of schema v$schema_v) failed: ".$e->getMessage()]);
                  return $this->_exitStatus = 1;
               }
               $version=$schemaCfg->latestVersion;
               $pdo->prepare("
               INSERT INTO
                  `{$schemaCfg->table}`
               SET
                  ns=:ns,
                  version=:version
               ")->execute([':version'=>$schema_v,':ns'=>$schemaCfg->name]);
               
               $version = $currentVersion = $schema_v;
               
               $this->_quiet || self::_showLine(["finished '$schema_v_sql' (of schema v$schema_v)"]);
            }
            unset($schema_v_sql);
            
            $this->_quiet || self::_showLine(["completed migration to schema v$schema_v"]);
         }
         unset($schema_v);
         unset($schema_subdir);
         
         
//          for($newVersion = floatval($version)+0.01;$newVersion<($schemaCfg->latestVersion+0.01);$newVersion+=0.01) {
//             $updateSql = "{$schemaCfg->sql_dir}/$newVersion/schema-updates.sql";
//             $this->_verbose && self::_showLine(["updating from v$version to v$newVersion using: $updateSql"]);
//             if (!is_readable($updateSql) || !is_file($updateSql)) {
//                self::_showErrLine([self::ME.": (ERROR) update sql did not resolve to readable file: $updateSql"]);
//                return $this->_exitStatus = 1;
//             }
//             $pdo->exec(file_get_contents($updateSql));
//             $version=$newVersion;
//             $pdo->prepare("
//             INSERT INTO
//                `{$schemaCfg->table}`
//             SET
//                ns=:ns,
//                version=:version
//             ")->execute([':version'=>$version,':ns'=>$schemaCfg->name]);
//             sleep(1);
//          }
//          unset($newVersion);
//          unset($updateSql);
      }
      
      if ($version!=$schemaCfg->latestVersion) {
         self::_showErrLine([self::ME.": (ERROR) unable to update to latest schema v{$schemaCfg->latestVersion}"]);
         return $this->_exitStatus = 1;
      }
      
      $this->_quiet || self::_showLine(["database '{$pdo->query('select database()')->fetchColumn()}' updated to schema v$version"]);
   }
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

})->getExitStatus())) {
   if (PHP_SAPI=='cli') {
      $installer->showUsage();
      exit($exitStatus);
   }
   return $exitStatus;
}
})();








