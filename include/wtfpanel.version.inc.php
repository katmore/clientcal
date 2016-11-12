<?php
/*
 *
 * do.version.inc.php of schedule
 *
 */


function PanelUniqueModuleList() {
   $sList = array(
      "customer",
      "site",
      "sentry",
      "supervisor"
   );
   return $sList;
}

function PanelVersionNo() {
   $sMajor = "1";
   $sMinor = "53";
   return "$sMajor.$sMinor";
}

function PanelProductName() {
   return "ClientCal";
}

function PanelVersionNotes() {
   return "";
}

function PanelCopyrightNotice() {
   return "&copy;2006-2011 P.D.Bird II";
}