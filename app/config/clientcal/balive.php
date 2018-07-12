<?php
/**
 * Clientcal "balive" configuration.
 * 
 * It is expected that this include file will return an associative array value.
 * <b>Returns: array</b> associative array of config values.
 */

$config = [];

$config['balive_ping_url'] = "./balive.xml.php";
$config['balive_interval'] = 10000; //interval in ms, 0 means balive inactive
$config['balive_login_url'] = "./?logout";
$config['error_line_format'] = "
      <phperror type=\"%errortype%\" line=\"%linenum%\">
         <msg>%errmsg%</msg>
         <filename>%filename%</filename>
      </phperror>";

return $config;
