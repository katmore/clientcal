<?php 
   require("do.mysqlsession.inc.php");
   $mLoggedUsername = ""; $mSessionid = ""; $mSeskey = "";
   //session_id();
   ////session_cache_expire(30);
   session_set_cookie_params(0, "/");
   session_start();
   //setcookie(session_name(),session_id(), time()+$expiry, "/");
   //$mSessionid = session_id();