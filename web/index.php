<?php
   require_once("include/wtfpanel.inc.php");
   require_once("include/html.inc.php");
   require_once("include/html.menu.inc.php");
   $mNotice = "";
   
   if (isset($_POST["username"])) { //if logging in
      include("include/getsession.inc.php");
      wtfpanel_getloginvals();
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
            $mNotice .= "problem ($sRet) using authentication:<br />$mPanelError<br />";
         }
      }
   } else
   if (isset($_GET["logout"])) {
      include("include/getsession.inc.php");
      killsession();
      $mAuthorized = "false";
      $mMode = "login";
   } else {
      $mMode = "login"; //default mode if getauth fails
      include("include/getsession.inc.php");
      include("include/getauth.inc.php"); 
   }
   
   //$mAuthorized = "true";
   if ($mAuthorized == "true") {
      $mMode = "show_menu";
   }
   if ($mMode == "show_menu") {
      echo wtfpanel_header("Panel Menu");
   }
   if (($mMode == "login") || ($mMode == "failed_login")) {
      echo wtfpanel_header("Please Sign In");
   }
   echo wtfpanel_top();
   if ($mMode == "login") {
      
      echo wtfpanel_logintable($default_tableclass,"./");
   } else
   if ($mMode == "failed_login") {
      if ($mNotice != "") {
         echo $mNotice;
      }
      
      echo wtfpanel_failedlogintable($default_tableclass,"./");
   } else
   if ($mMode == "show_menu") {
      echo wtfpanel_panelmenu($default_tableclass);
   }
   echo wtfpanel_bottom();
