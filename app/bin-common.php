<?php
use clientcal\config;

/**
 * @return \Composer\Autoload\ClassLoader
 */
return (function() {
   /*
    * set default timezone
    */
   date_default_timezone_set(require __DIR__.'/config/default-timezone.php');
   
   /*
    * register autoloader
    */
   $autoloader = require require __DIR__.'/config/autoloader-path.php';
   
   /*
    * set base configuration for clientcal
    */
   config::SetBaseDir(require __DIR__.'/config/clientcal-config-path.php'); 
   
   return $autoloader;
})();

