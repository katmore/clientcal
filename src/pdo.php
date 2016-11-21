<?php
namespace clientcal;

class pdo extends \PDO {
   
   public function __construct($dsn=null, $username = null, $passwd = null, $options = null) {
      $config = new config("mysql");
      $options = $config['options'];
      //PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
      $options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
      parent::__construct($config['dsn'],$config['username'],$config['password'],$options);
   }
   
}