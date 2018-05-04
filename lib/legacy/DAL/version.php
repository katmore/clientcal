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

function ProductName() {
   return "ClientCal";
}

function CopyrightNotice() {
   return "&copy;2006-".date('Y')." P.D.Bird II. All Rights Reserved.";
}