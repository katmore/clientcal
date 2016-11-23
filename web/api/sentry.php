<?php
use clientcal\apiController;
use clientcal\api;

define("CLIENTCAL_APP_DIR",__DIR__."/../../app");

require CLIENTCAL_APP_DIR . '/web-common.php';

new apiController\httpInterface(new api\sentryApi);