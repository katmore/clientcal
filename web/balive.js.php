<?php
   require_once ("include/settings.balive.php");
   
   header("Content-type: application/x-javascript");
   $mBaliveInterval = 10000;
   if (is_numeric($balive_interval)) //the setting must be above 10 seconds, or its dumb
      if ($balive_interval > 10000)
         $mBaliveInterval = $balive_interval;
?>
var mReq;
var mLastReqStatus;
function balive_statechange() {
   if (mReq) {
      if (mReq.readyState == 4) {
         if (mReq.status == 200) {
            var sRes = mReq.responseXML.documentElement;
            if (sRes) {
               var sStatus = sRes.getElementsByTagName('status')[0].firstChild.data;
               var sMsgArea = document.getElementById("balivemsg");
               if (sStatus == "true") {
                  if (mLastReqStatus == false) {
                     sMsgArea.style.background = "#00FF00";
                     sMsgArea.innerHTML = "service communication restored";
                     setTimeout('sMsgArea.style.visibility = "hidden"',<?php echo $mBaliveInterval;?>);
                  }
                  setTimeout('balive_ping()',<?php echo $mBaliveInterval;?>);
               } else {
                  var sMsg = "your session is no longer active <a href='<?php echo $balive_login_url?>'>log back in</a>";
                  if (sMsgArea) {
                  sMsgArea.style.background = "#FF0000";
                  sMsgArea.style.visibility = "visible";
                  sMsgArea.innerHTML = sMsg;
                  } else {
                     //alert("your session is no longer active");
                  }
               }
               mLastReqStatus = true;
            } else {
               
               var sMsgArea = document.getElementById("balivemsg");
               var sMsg = "message communication problem <span style='cursor:pointer;color:blue;' onclick='balive_ping()'>try again</span>";
               if (sMsgArea) {
                  sMsgArea.style.background = "#FF0000";
                  sMsgArea.style.visibility = "visible";
                  sMsgArea.innerHTML = sMsg;
               } else {
                  //alert("message communication problem");
               }
               mLastReqStatus = false;
               setTimeout('balive_ping()',<?php echo $mBaliveInterval;?>);
            }
         } else {
            if (sMsgArea) {
               sMsgArea = document.getElementById("balivemsg");
               sMsgArea.style.background = "#FF0000";
               sMsgArea.style.visibility = "visible";
               sMsgArea.innerHTML = "service communication problem <span style='cursor:pointer;color:blue;' onclick='balive_ping()'>try again</span>";
            } else {
               //alert("service communications problem, will try again");
            }
            mLastReqStatus = false;
            setTimeout('balive_ping()',<?php echo $mBaliveInterval;?>);
         }
      }
   } else {
      //balive_init();
   }
}
function balive_ping() {
   if (mReq) {
      mReq.onreadystatechange = balive_statechange;
      mReq.open("GET","<?php echo $balive_ping_url;?>",true);
      if (window.XMLHttpRequest) {
         mReq.send(null);
      } else
      if (window.ActiveXObject) {
         mReq.send();
      }
   }
}
function balive_init() {
   if (window.XMLHttpRequest) {
      mReq = new XMLHttpRequest();
   } else
   if (window.ActiveXObject) {
      mReq = new ActiveXObject("Microsoft.XMLHTTP");
   }
   setTimeout('balive_ping()',<?php echo $mBaliveInterval;?>);
}