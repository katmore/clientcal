<?php

namespace clientcal;

   function balive_header($action) {
      $ret = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<balive>
   <action time=\"" . time() . "\">$action</action>";
      return $ret;
   }
   function balive_bottom() {
      global $mPHPError;
      $ret = "";
      if ($mPHPError != "") {
         $ret .= $mPHPError;
      }
      $ret .= "
</balive>";
      return $ret;
   }
   function balive_message($type,$val) {
      $ret = "
   <message type=\"$type\">$val</message>";
      return $ret;
   }
   function balive_status($type,$val) {
      $ret = "
   <status type=\"$type\">$val</status>";
      return $ret;
   }
