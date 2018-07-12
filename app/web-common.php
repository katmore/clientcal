<?php
/**
 * Peforms tasks necessary for PHP scripts located in the "clientcal/web" dir.
 */
use clientcal\config;

new class() {
   
   const CLIENTCAL_CONFIG_BASE_DIR = __DIR__.'/config/clientcal';
   
   const AUTOLOADER_PATH = __DIR__.'/../vendor/autoload.php';

   const DEFAULT_TIMEZONE_CONFIG_FILE = __DIR__.'/config/default-timezone.php';
   
   const TEST_CLASS = 'clientcal\config';
   
   public function __construct() {
      
      if (!class_exists(static::TEST_CLASS)) {
         
         /*
          * check if missing vendor/autoload.php, attempt to display "friendly" error message
          */
         if (!is_file(static::AUTOLOADER_PATH) || !is_readable(static::AUTOLOADER_PATH)) {
            
            /*
             * set "500 Internal Server Error" status code to determine if headers already sent
             */
            $errorReporting = error_reporting(0); //supress WARNING/NOTICE output
            $responseCodeSuccess = false !== http_response_code(500);
            $responseCodeSuccess && header("Content-type: text/plain",true);
            error_reporting($errorReporting); //restore error reporting
            
            /*
             * display "friendly" error message if headers were not already sent 
             */
            if ($responseCodeSuccess) {
               if (ini_get("display_errors")) {
                  echo "The vendor/autoload.php file is missing or inaccessible, have you run Composer?\n\n\n\n";
               } else {
                  echo "We are experiencing difficulties. Contact supoprt if this problem persists.\n\n\n\n";
               }
            }
            
            /*
             * always trigger fatal error
             */
            trigger_error("Composer autoload file is missing or inaccessible in the expected path 'static::AUTOLOADER_PATH'",E_USER_ERROR);
         }
         
         /*
          * include vendor/autoload.php
          */
         $this->autoloader = require static::AUTOLOADER_PATH;
      }
      
      /*
       * set default timezone if "default-timezone" config file exists
       */
      if (is_file(static::DEFAULT_TIMEZONE_CONFIG_FILE)) {
         date_default_timezone_set(require static::DEFAULT_TIMEZONE_CONFIG_FILE);
      }
      
      /*
       * set base configuration directory for clientcal
       */
      config::SetBaseDir(static::CLIENTCAL_CONFIG_BASE_DIR);
      
   }
};

