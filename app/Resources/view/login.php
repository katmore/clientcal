<?php

namespace clientcal;

   foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) {
      global $$k;
      $$k=$v;
   }
   
   foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v) {
      global $$k;
      $$k=$v;
   }
   
   foreach(require(CLIENTCAL_CONFIG_DIR."/customer.php") as $k=>$v) {
      global $$k;
      $$k=$v;
   }
   
   foreach(require(CLIENTCAL_CONFIG_DIR."/mysql.php") as $k=>$v) {
      global $$k;
      $$k=$v;
   }
   
   foreach(require(CLIENTCAL_CONFIG_DIR."/tables.php") as $k=>$v) {
      global $$k;
      $$k=$v;
   }
   
   global $mUsername,$mPassword,$mLoggedUsername,$mNotice;
   
   if (isset($_POST["username"])) { //if logging in
      
      require self::APP_DIR."/Resources/routine/getsession.php";
      getloginvals();

      $sRet = verifyuserinfo($mUsername,$mPassword,$mLoggedUsername);
      if ($sRet === TRUE) {
         $_SESSION["authorized"] = "true";
         $_SESSION["username"] = $mLoggedUsername;
         $mAuthorized = "true";
      } else {
         $mAuthorized = "false";
         $mMode = "failed_login";
         killsession();
         //newsession();
         if ($sRet !== FALSE) {
            $mNotice .= "problem ($sRet) using authentication:<br />$mError<br />";
         }
      }
   } else
      if (isset($_GET["logout"])) {
         require self::APP_DIR."/Resources/routine/getsession.php";
         killsession();
         $mAuthorized = "false";
         $mMode = "login";
      } else {
         $mMode = "login"; //default mode if getauth fails
         
         require self::APP_DIR."/Resources/routine/getsession.php";
         
         require self::APP_DIR."/Resources/routine/getauth.php";
         
      }
      
      //$mAuthorized = "true";
      if ($mAuthorized == "true") {
         $mMode = "show_menu";
      }
      if ($mMode == "show_menu") {
         
         echo header(" Menu");
      }
      if (($mMode == "login") || ($mMode == "failed_login")) {
         
         echo header("Please Sign In");
      }
      echo top();
      if ($mMode == "login") {
          
         echo logintable($default_tableclass,"./");
      } else
         if ($mMode == "failed_login") {
            if ($mNotice != "") {
               echo $mNotice;
            }
             
            echo failedlogintable($default_tableclass,"./");
         } else
            if ($mMode == "show_menu") {
               echo panelmenu($default_tableclass);
            }
         echo bottom();
