<?php
   //Set no caching
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   header("Cache-Control: no-store, no-cache, must-revalidate");
   header("Cache-Control: post-check=0, pre-check=0", false);
   header("Pragma: no-cache");
   require_once("include/settings.balive.inc.php");
   $mPanelFormatPHPError = $error_line_format;
   require_once("include/wtfpanel.err.inc.php");
   require_once("include/wtfpanel.inc.php");
   require_once("include/xml.balive.inc.php");
   
   include("include/getsession.inc.php");
   $mMode = "login";
   include("include/getauth.inc.php"); 
   header("Content-type: text/xml; charset=UTF-8");
   echo balive_header("response");
   if ($mAuthorized == "true") {
      echo balive_status("authorization","true");
   } else {
      echo balive_status("authorization","false");
      echo balive_message("failure","session expired");
   }
   echo balive_bottom();
