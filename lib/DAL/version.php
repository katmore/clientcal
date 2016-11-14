<?php

namespace clientcal;



function UniqueModuleList() {
   $sList = array(
      "customer",
      "site",
      "sentry",
      "supervisor"
   );
   return $sList;
}

function VersionNo() {
   $sMajor = "1";
   $sMinor = "53";
   return "$sMajor.$sMinor";
}

function ProductName() {
   return "ClientCal";
}

function VersionNotes() {
   return "";
}

function CopyrightNotice() {
   return "&copy;2006-2011 P.D.Bird II";
}