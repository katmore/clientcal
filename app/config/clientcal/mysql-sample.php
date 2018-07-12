<?php
/**
 * Clientcal "mysql" configuration.
 *
 * Use "bin/config-update.php" to modify the config values in this file.
 *
 * See: https://github.com/katmore/clientcal#configuration-update-utility
 * for more information.
 *
 * NOTE: This file must be renamed to "mysql.php" to take effect.
 * It is ignored when the name ends with the "-sample.php" suffix.
 *
 * It is expected that this include file will return an associative array value.
 * <b>Returns: array</b> associative array of config values.
 */

$config = [];

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
