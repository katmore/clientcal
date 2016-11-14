<?php

namespace clientcal;

   function simplemessagetable($message) {
      $ret = "
      <table>
         <tr>
            <td>$message
            </td>
         </tr>
      </table>
      ";
      return $ret;
   }
   function balivescript() {
      //e=document.getElementById("s");
      //balivemsg
      //style.width = x + 'px';
      //.firstChild.nodeValue
      //createElement("cancel")
      //setAttribute('href', 'mypage.htm');
      //appendChild(newnode) <- put link in sMsgArea
      $ret = "
            <script type=\"text/javascript\">
               var mReq;
               function balive_statechange() {
                  if (mReq.readyState == 4) {
                     if (mReq.status == 200) {

                        sRes = mReq.responseXML.documentElement;
                        sStatus = sRes.getElementsByTagName('status')[0].firstChild.data;
                        sMsgArea = document.getElementById(\"balivemsg\");
                        if (sStatus == \"true\") {
                           //alert('xml said TRUE');
                        } else {
                           //alert('xml said FALSE');
                           sMsgArea.style.background = \"#FF0000\";
                           sMsgArea.style.visibility = \"visible\";
                           sMsgArea.firstChild.nodeValue = \"your session is no longer active\";
                           sMsgArea.innerHTML = \"your session is no longer active <a href='./'>log back in</a>\";
                           //var sLink = document.createElement('a');
                           //sLink.setAttribute('href','./');
                           //sMsgArea.appendChild(sLink);
                           //sLink.nodeValue = \"log back in\";
                        }

                        //alert('successful ping');
                        setTimeout('balive_ping()',10000);
                     }
                  }
               }
               function balive_ping() {
                  if (window.XMLHttpRequest) {
                     mReq = new XMLHttpRequest();
                     mReq.onreadystatechange = balive_statechange;
                     mReq.open(\"GET\",\"http://www.clientcal.com/schedule/balive.xml.php\",true);
                     mReq.send(null);
                  } else
                  if (window.ActiveXObject) {
                     mReq = new ActiveXObject(\"Microsoft.XMLHTTP\");
                     if (mReq) {
                        mReq.onreadystatechange = balive_statechange;
                        mReq.open(\"GET\", \"http://www.clientcal.com/schedule/balive.xml.php\", true);
                        mReq.send();
                     }
                  }
               }
               function balive_init() {
                  setTimeout('balive_ping()',10000);
               }
            </script>
            ";
            //visibility:hidden
            return $ret;
   }
   function header($subtitle) {
      global $mHeadExtra;
      
      
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) $$k=$v;
      
      
      
      foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v)  $$k=$v;
      
      
      $ret = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
   <head>
      $mHeadExtra";
      /*
      $ret .= "
      <title>$sitename";
      if ($subtitle != "") {
         $ret .= " | " . $subtitle;
      }
      */
      $ret .= "
      <title>";
      if ($subtitle != "") {
         $ret .= "$subtitle | ";
      }
      $ret .= $sitename;
      $ret .= "</title>
      <link rel=\"STYLESHEET\" href=\"style.css\" type=\"text/css\">
<link rel=\"shortcut icon\"
 href=\"images/clientcal_favicon.ico\" />
      ";
      if (isset($balive_interval)) {
         if ($balive_interval > 0) {
            //$ret .= balivescript();
            $ret .= "
      <script type=\"text/javascript\" src=\"balive.js.php\"></script>";
         }
      }
      $ret .= "
   </head>
      ";
      return $ret;
   }
   function topminimal() {
//       include("settings.php");
//       include ("settings.balive.php");
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) $$k=$v;
      foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v) $$k=$v;
      
      global $mAuthorized;
      global $mBodyExtra;
      $sBodyExtra = $mBodyExtra;
      if ($mAuthorized == "true")
      if (isset($balive_interval)) {
         if ($balive_interval > 0) {
            $sBodyExtra .= "onload=\"javascript:balive_init();\"";
         }
      }
      if ($sBodyExtra != "") {
         $ret = "
   <body $sBodyExtra>";
      } else
      $ret = "
   <body>";
      $ret .= "
      ";
      return $ret;
   }
   function top() {
//       include("settings.php");
//       include ("settings.balive.php");
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) $$k=$v;
      foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v) $$k=$v;
      
      global $mAuthorized;
      global $mBodyExtra;
      $sBodyExtra = $mBodyExtra;
      if ($mAuthorized == "true")
      if (isset($balive_interval)) {
         if ($balive_interval > 0) {
            $sBodyExtra .= "onload=\"javascript:balive_init();\"";
         }
      }
      if ($sBodyExtra != "") {
         $ret = "
   <body $sBodyExtra>";
      } else
      $ret = "
   <body>";
      $ret .= "
   <table>
      <tr>
         <td valign=\"top\"><a href=\"./\"><img border=\"0\" alt=\"\" src=\"images/clientcal_logo.half.jpg\"/></a></td><td valign=\"top\"> <a href=\"./\"><b>$sitesubtitle</b></a></td>
      </tr>
      <tr>
         <td></td>
      </tr>
   </table>
      ";
      return $ret;
   }
   function bottomminimal() {
      global $mAuthorized;
      global $mLoggedUsername;
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) $$k=$v;
      foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v) $$k=$v;
//       include ("settings.php");
//       include ("settings.balive.php");
      $ret = "";
      if ($mAuthorized == "true") {
         //$ret .= " <a href=\"./?logout\">[Log Out]</a>";
         $sStamp = time();
         $ret .= "<br />requested by $mLoggedUsername at " . date("g:ia",$sStamp) . " on " . date("F j, Y",$sStamp) . ".&nbsp;";
      }
      //$ret .= " is $copyrightnotice";
      //$ret .= " " . VersionNo();
      $ret .= "<span style='font-size:0.75em;'>";
      $ret .=  ProductName() . " v" . VersionNo() . " " . CopyrightNotice() ;
      $ret .= "</span>";
      if ($mAuthorized == "true")
      if (isset($balive_interval)) {
         if ($balive_interval > 0) {
            $ret .= "<br />
      <span style=\"visibility:hidden;background:#FF0000;border:1px solid #000000;position:absolute;margin-left:5px;\" id=\"balivemsg\"></span>";
         }
      }
//       if ($show_php_errors === TRUE) {
//          global $mPHPError;
//          if ($mPHPError != "") {
//             $ret .= "
//             <table width=\"800\" border=\"1\">
//                <tr>
//                   <td>
//                   $mPHPError
//                   </td>
//                </tr>
//             </table>";
//          }
//       }
      $ret .= "
   </body>
</html>
      ";
      return $ret;
   }
   function bottom() {
      global $mAuthorized;
      global $mLoggedUsername;
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) $$k=$v;
      foreach(require(CLIENTCAL_CONFIG_DIR."/balive.php") as $k=>$v) $$k=$v;
      $ret = "
<span style='font-size:0.75em;'>
      ";
      if ($mAuthorized == "true") {
         $ret .= " <a href=\"./?logout\">[Log Out]</a>";
         $ret .= " ($mLoggedUsername) ";
      }
      $ret .=  ProductName() . " v" . VersionNo() . " " . CopyrightNotice() ;

      $ret .= "</span>";
      $ret .= "
   <div style=\"margin-top:5px;background-color:white; width:128px; height:32px;\">
   <img src=\"https://www.startssl.com/img/startcom_secured_keys_80x15.png\" border=\"0\" alt=\"Free SSL Secured By StartCom\" title=\"Free SSL Secured By StartCom\">

      ";
      if ($mAuthorized == "true")
      if (isset($balive_interval)) {
         if ($balive_interval > 0) {
            $ret .= "<br />
      <span style=\"visibility:hidden;background:#FF0000;border:1px solid #000000;position:absolute;margin-left:5px;\" id=\"balivemsg\"></span>";
         }
      }
//       if ($show_php_errors === TRUE) {
//          global $mPHPError;
//          if ($mPHPError != "") {
//             $ret .= "
//             <table width=\"800\">
//                <tr>
//                   <td class=\"project\">
//                   $mPHPError
//                   </td>
//                </tr>
//             </table>";
//          }
//       }
      $ret .= "
   </body>
</html>
      ";
      return $ret;
   }
   function getloginvals() {
      global $mUsername,$mPassword;
      if (isset($_POST["username"]))
         $mUsername = $_POST["username"];
      if (isset($_POST["password"]))
         $mPassword = $_POST["password"];
   }
   function failedlogintable_old($tableclass,$action) {
      global $mUsername;
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) {
         $$k=$v;
      }
      $ret = "
      <form method=\"POST\" action=\"$action\">
      <table class=\"$tableclass\" border=\"0\" width=\"100%\">
         <tr>
            <td valign=\"top\">
            <b>not signed in</b><br />
            to use the features of the control panel, you must be signed in<br />
            <i>sign in failure: username and password do not match our records</i>
            </td>
            <td width=\"250\">
               <table width=\"100%\">
         <tr>
            <td valign=\"top\" align=\"center\" class=\"heading\"><b>$loginnotice</b></td>
         </tr>
         <tr>
            <td class=\"project\" align=\"center\">
               username:<br />
               <input type=\"text\" value=\"$mUsername\" name=\"username\"><br />
               password:<br />
               <input type=\"password\" name=\"password\"><br /><br />
               <input type=\"submit\" value=\"Sign in\"><br /><br />
            </td>
         </tr>
               </table>
            </td>
            <td width=\"30\">
            &nbsp;
            </td>
         </tr>

      </table>
      </form>
      ";
      return $ret;
   }
   function failedlogintable($tableclass,$action) {
      global $mUsername;
//       include ("settings.php");
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) {
         global $$k;
         $$k=$v;
      }
      $ret = "
      <form method=\"POST\" action=\"$action\">
      <table class=\"$tableclass\" border=\"0\" width=\"300px\">
         <tr>
            <td valign=\"top\">
            <b>not signed in</b><br />
            <i>sign in failure: username and password do not match our records</i>
            </td>

      </table>
      <table class=\"$tableclass\" border=\"0\" width=\"250px\">
         <tr>
            <td valign=\"top\" align=\"center\" class=\"heading\"><b>$loginnotice</b></td>
         </tr>
         <tr>
            <td class=\"project\" align=\"center\">
               username:<br />
               <input type=\"text\" value=\"$mUsername\" name=\"username\"><br />
               password:<br />
               <input type=\"password\" name=\"password\"><br /><br />
               <input type=\"submit\" value=\"Sign in\"><br /><br />
            </td>
         </tr>


            <td >
            &nbsp;
            </td>
         </tr>

      </table>
      </form>
      ";
      return $ret;
   }
   function echophperrortable() {
//       return -1;
//       require("settings.php");
//       if ($show_php_errors === FALSE) return -1;
//       global $mPHPError;
//       if ($mPHPError != "") {
//          $ret = "
//       <table width=\"800\">
//          <tr>
//             <td class=\"project\">
//             $mPHPError
//             </td>
//          </tr>
//       </table>";
//          echo $ret;
//       }
   }
   function logintable($tableclass,$action) {
      global $mUsername;
      //include ("settings.php");
      foreach(require(CLIENTCAL_CONFIG_DIR."/app.php") as $k=>$v) {
         $$k=$v;
      }
      $ret = "
      <form method=\"POST\" action=\"$action\">
      <table class=\"$tableclass\" border=\"0\" width=\"300px\">
         <tr>
            <td valign=\"top\">
            <b>not signed in</b><br />
            to use the features of the control panel, you must be signed in
            </td>

      </table>
      <table class=\"$tableclass\" border=\"0\" width=\"250px\">
         <tr>
            <td valign=\"top\" align=\"center\" class=\"heading\"><b>$loginnotice</b></td>
         </tr>
         <tr>
            <td class=\"project\" align=\"center\">
               username:<br />
               <input type=\"text\" value=\"$mUsername\" name=\"username\"><br />
               password:<br />
               <input type=\"password\" name=\"password\"><br /><br />
               <input type=\"submit\" value=\"Sign in\"><br /><br />
            </td>
         </tr>


            <td >
            &nbsp;
            </td>
         </tr>

      </table>
      </form>
      ";
      return $ret;
   }





















