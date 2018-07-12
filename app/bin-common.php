<?php
/**
 * Peforms tasks necessary for PHP scripts located in the "clientcal/bin" dir.
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
             * display "friendly" message if in CLI mode
             */
            if (PHP_SAPI=='cli') {
               fwrite(STDERR, "The vendor/autoload.php file is missing or inaccessible, have you run Composer?\n\n");
            }
            
            /*
             * always trigger fatal error
             */
            trigger_error("Composer autoload file is missing or inaccessible in the expected path '".static::AUTOLOADER_PATH."'",E_USER_ERROR);
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

