<?php
/**
 * Clientcal "tables" configuration.
 *
 * It is expected that this include file will return an associative array value.
 * <b>Returns: array</b> associative array of config values.
 */

$config = [];

$config['sentry_table'] = "sentry";
$config['sentrytype_table'] = "sentrytype";
$config['supervisor_table'] = "supervisor";
$config['customer_table'] = "customer";
$config['customertype_table'] = "customertype";
$config['customerphone_table'] = "customer_phone";
$config['site_table'] = "site";
$config['supervisor_table'] = "supervisor";

return $config;
