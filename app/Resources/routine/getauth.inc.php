<?php
   $mAuthorized = "false";
   if (isset($_SESSION['authorized'])) {
       if ($_SESSION['authorized'] == "true") {
          $mAuthorized = "true";
          $mAuthorized_username = $_SESSION['username'];
       }
       if (isset($_SESSION['username'])) {
            if (($mRet = userexists($_SESSION['username'])) != 1) {
               if ($mRet < 0) {
                  $mNotice .= "problem with user lookup during session authentication:<br />$mPanelError<br />";
               }
                $mAuthorized = "false";
            } else {
                $mLoggedUsername = $_SESSION['username'];
            }
       }
   }
   if ($mAuthorized == "false") {
      killsession();
   }
