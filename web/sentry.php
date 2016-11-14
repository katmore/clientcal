<?php
return new class() {
   const APP_DIR = __DIR__. '/../app';
    
    
    
    
   public function __construct() {
      require self::APP_DIR . '/web-common.php';

      require self::APP_DIR . '/Resources/controller/view.php';
      require self::APP_DIR . '/Resources/controller/menu.php';
      require self::APP_DIR . '/Resources/controller/customer.php';

      require self::APP_DIR . '/Resources/view/login.php';
   }
};
