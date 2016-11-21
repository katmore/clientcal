<?php
return new class() {
   
   const CLIENTCAL_ROOT=__DIR__.'/../';

   /**
    * @return void
    * @param int | bool $exit
    */
   private static function _showUsage($exit=false) {
      global $argv;
      $script=$argv[0];
      $name = 'MyName';
      echo <<<"EOT"
php $script [[--username=<mysql username>][--password=<mysql password>][[--skip-composer] | [--composer=</path/to/composer>]]]
EOT;
   }
   
   public function __construct() {
      global $argv;
      //$user = $argv[1];

      self::_showIntro();
      
      $option=getopt("",[
         "composer::",        // Optional value
         "skip-composer",           // Flag
         "username::",
         "password::",
      ]);
      
      if (!empty($option['composer'])) {
         $composer = $option['composer'];
      } else {
         $cmd = "which composer";
         $output=[];
         $composer = exec($cmd,$output,$ret);
         if ($ret!=0) {
            echo "could not find composer\n";
            self::_showUsage($ret);
         }
      }
      
      echo "running composer...\n";
      $cmd="cd ".CLIENTCAL_ROOT." ; $composer install ; $composer update";
      $output=[];
      $composer = exec($cmd,$output,$ret);
      if ($ret!=0) {
         $output[]="clientcal install error: composer failed with the following output:\n";
         self::_showErrLine($output);
         self::_showUsage($ret);
      }
      echo "composer done!\n";
      
      if (!class_exists('\\PDO')) {
         
      }
      
      $pdoconfig = [
         'username'=>null,
         'password'=>null,
         'host'=>'localhost',
         'dbname'=>'clientcal',
      ];
      
      try {
         $dbh = new PDO(
            'mysql:host='.$pdoconfig['host'].';dbname='.$pdoconfig['host'], 
             $pdoconfig['username'], 
             $pdoconfig['password']
         );
         foreach($dbh->query('SELECT * from FOO') as $row) {
            print_r($row);
         }
         $dbh = null;
      } catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
      }
      
   }
   /**
    * @return void
    */
   private static function _showIntro() {
      echo <<<"EOT"
ClientCal Installer Script
(c) 2006-2017 Paul D. Bird II. All Rights Reserved.\n
EOT;
   }

   /**
    * @return void
    * @param string[]
    */
   private function _showErrLine(array $strLines) {
      $stderr = fopen('php://stderr', 'w');
      foreach ($strLines as $line) fwrite($stderr, "$line\n");
      fclose($stderr);;
   }
};