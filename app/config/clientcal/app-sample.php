<?php
/**
 * Clientcal "app" configuration.
 * 
 * Use "bin/config-update.php" to modify the config values in this file.
 * 
 * See: https://github.com/katmore/clientcal#configuration-update-utility 
 * for more information.
 *
 * NOTE: This file must be renamed to "app.php" to take effect.
 * It is ignored when the name ends with the "-sample.php" suffix.
 * 
 * It is expected that this include file will return an associative array value.
 * <b>Returns: array</b> associative array of config values.
 */

$config = [];
$config['sitename'] = "ClientCal Scheduler"; // name of the panel
$config['sitesubtitle'] = "Schedule your Dreams";
$config['copyrightnotice'] = "&copy;2011 Doug Bird"; // copyright notice
$config['usenotice'] = "use of this site subject to terms and conditions"; // terms of use notice
$config['loginnotice'] = "Sign In";
$config['components_url_prefix'] = "/components";

return $config;
