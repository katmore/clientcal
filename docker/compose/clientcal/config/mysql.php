<?php
return (function() {
   $config['dbhost'] = "mysql";
   $config['dbname'] = "clientcal";
    
   $config['username'] = $config['dbuser'] = "clientcal";
   $config['password'] = $config['dbpasswd'] = "clientcal";
   $password_file =  __DIR__."/.mysql-password";
   
   if (is_file($password_file) && is_readable($password_file)) {
      if (false!==($password = file_get_contents($password_file))) {
         $config['password'] = $config['dbpasswd'] = trim($password);
      }
   }
   
   $config['dsn'] = 'mysql:host='.$config['dbhost'].';dbname='.$config['dbname'];
   $config['options'] = [
      \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
   ];
   
   return $config;
})();