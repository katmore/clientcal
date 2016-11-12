<?php
   function PanelError($errno,$message) {
      global $mPanelError;
      $mPanelError = $message;
      return $errno;
   }
   function PanelPHPErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
      global $mPanelPHPError;
      global $mPanelPHPErrorX;
      global $mPanelFormatPHPError;
      $errortype = array (
               E_ERROR          => "Error",
               E_WARNING        => "Warning",
               E_PARSE          => "Parsing Error",
               E_NOTICE          => "Notice",
               E_CORE_ERROR      => "Core Error",
               E_CORE_WARNING    => "Core Warning",
               E_COMPILE_ERROR  => "Compile Error",
               E_COMPILE_WARNING => "Compile Warning",
               E_USER_ERROR      => "User Error",
               E_USER_WARNING    => "User Warning",
               E_USER_NOTICE    => "User Notice",
               //E_STRICT          => "Runtime Notice"
      );
      $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
      if ($mPanelFormatPHPError != "")
         $sLineF = $mPanelFormatPHPError;
      else
         $sLineF = "<li>%errmsg%; (%errortype%) on line <b>%linenum%</b><br />&nbsp;&nbsp; %filename%<br />";
      $sLineF = str_replace("%errno%",$errno,$sLineF);
      if (isset($errortype[$errno])) {
         $errtypedsc = $errortype[$errno];
      } else {
         $errtypedsc = "unknown type";
      }
      $sLineF = str_replace("%errortype%",$errtypedsc,$sLineF);
      $sLineF = str_replace("%errmsg%",$errmsg,$sLineF);
      $sLineF = str_replace("%filename%",$filename,$sLineF);
      $sLineF = str_replace("%linenum%",$linenum,$sLineF);
      $mPanelPHPError .= $sLineF;
      array_push($mPanelPHPErrorX,$sLineF);
   }
   
   if (!isset($mPanelPHPError)) {
      if (!isset($mPanelFormatPHPError)) //this allows context defined format of errors
         $mPanelFormatPHPError = "<li>%errmsg%; (%errortype%) on line <b>%linenum%</b><br />&nbsp;&nbsp; %filename%<br />";
      $mPanelPHPError = "";
      $mPanelPHPErrorX = array();
      //error_reporting(2047);
      $mOldErrorHandler = set_error_handler("PanelPHPErrorHandler");
   }

