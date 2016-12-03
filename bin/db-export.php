<?php
use Ifsnop\Mysqldump\Mysqldump;

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
      if ($exit===true) exit(0);
      if (is_int($exit)) exit($exit);
   }
    
   /**
    * @return void
    */
   private static function _showIntro() {
      echo <<<"EOT"
ClientCal Data Export Script
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
    
   public function __construct() {
      $dump = new Mysqldump('mysql:host=localhost;dbname=testdb', 'username', 'password');
      $dump->start('storage/work/dump.sql');

   }
};