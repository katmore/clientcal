<?php
return (function() {

   $config['dbhost'] = "localhost";
   $config['dbname'] = "clientcal";
    
   $config['username'] = $config['dbuser'] = "clientcal";
   $config['password'] = $config['dbpasswd'] = "";
   $config['dsn'] = 'mysql:host='.$config['dbhost'].';dbname='.$config['dbname'];
   $config['options'] = [
      \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
   ];
   
   return $config;
})();