<?php
use clientcal\apiController;
use clientcal\apiHandler;

define("CLIENTCAL_APP_DIR",__DIR__."/../../app");

require CLIENTCAL_APP_DIR . '/web-common.php';

new clientcal\apiController\httpInterface(new apiHandler\calendar);