<?php

namespace clientcal;

function enumsentryconfig() {
   $config['actionm_headingcells_height'] = 95;
   $config['actionm_headingcells_width'] = 100;
   $config['actionm_headingcells_format'] = "";
   $config['actionm_cellheading_class'] = "cellheading";
   //$config['actionm_cellheadingsel_class'] = "cellheadingsel";
   $config['actionm_cellcontent_class'] = "cellcontent";
   //$config['actionm_cellcontentsel_class'] = "cellcontentsel";
   //<div class=\"supervisor_%entry.supervisorkey%\">
   //./sentry.php?submitdupe=%entry.key%&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%
   $config['actionm_entrycaption_format'] = "
         <div title=\"%entry.custname%: %entry.heading% - %site.streetaddr% (%entry.type%)\" class=\"entrycaption_%entry.supervisorkey%\">
            %entry.custname:limitchar:10%%dashifcustandheading%%entry.heading:limitchar:8%
         </div>
         ";
   $config['actionm_blankcell_format'] = "
   <div style=\"height:100%;border: 1px solid #000000;background:#D6D2CE;\">&nbsp;</div>";
   $config['actionm_daycell_format'] = "
   <div class=\"%cellcontentclass%\">
      <div title=\"select %date:l%, %date:F% %date:j%, %date:Y%\" class=\"%cellheadingclass%\" onclick=\"location.href='./sentry.php?%actionmselect%=%actionmentry.key%&amp;month=%date:n%&day=%date:j%&year=%date:Y%'\">
         <div style=\"float: left;width:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:j%</div>
         <div style=\"float:right;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%entrycountabove0%</div>
         <div style=\"border-left: 1px solid #000000;margin-left:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:D%</div>
      </div>
      %entries%
   </div>";
   $config['actionm_start_dayofweekw'] = 1;
   //Sunday, June 4, 1972sssss
   //$config['actionm_headingcaption
   $config['actionm_headingcaption'] = "%actionmcaption%";
   $config['actionmcaption_style'] = "font-family:Tahoma;font-size:11pt;font-weight:bold;color:#000000;vertical-align:top;float:left;";
   $config['actionm_headingtools'] = "
   <a href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%date:n%&amp;year=%date:Y%&amp;%toggleshowsentries.toggle%\">[%toggleshowsentries.caption%]</a>
   %date:F% %date:Y%
   
   <a title=\"show selection for previous month %prevmonth.date:F% %prevmonth.date:Y%\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%prevmonth.date:n%&amp;year=%prevmonth.date:Y%\">&lt;</a>
   <a title=\"refresh this month\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%date:n%&amp;year=%date:Y%\">||</a>
   <a title=\"show selection for next month: %nextmonth.date:F% %nextmonth.date:Y%\" href=\"./sentry.php?%actionmbrowse%=%actionmentry.key%&amp;month=%nextmonth.date:n%&amp;year=%nextmonth.date:Y%\">&gt;</a>
   ";
   
   $config['monthv_headingcells_width'] = 100;
   $config['monthv_headingcells_height'] = 95;
   $config['monthv_headingcells_format'] = "";
   $config['monthv_cellheading_class'] = "cellheading";
   $config['monthv_cellheadingsel_class'] = "cellheadingsel";
   $config['monthv_cellcontent_class'] = "cellcontent";
   $config['monthv_cellcontentsel_class'] = "cellcontentsel";
   //<div class=\"supervisor_%entry.supervisorkey%\">
   $config['monthv_entrytype_legend_format'] = "
   <span class=\"entrycaption_entrytype_%entry.type%\">%entry.typebrief%</span>
   ";
   $config['monthv_entrycaption_format'] = "
         <div title=\"%entry.custname%: %entry.heading% - %site.streetaddr% (%entry.type%)\" onclick=\"location.href='./sentry.php?show=%entry.key%'\" class=\"entrycaption_entrytype_%entry.type%\">
            %entry.custname:limitchar:10%%dashifcustandheading%%entry.heading:limitchar:8%
         </div>
         ";
   $config['monthv_blankcell_format'] = "
   <div style=\"height:100%;border: 1px solid #000000;background:#D6D2CE;\">&nbsp;</div>";
   $config['monthv_daycell_format'] = "
   <div class=\"%cellcontentclass%\">
      <div title=\"select %date:l%, %date:F% %date:j%, %date:Y%\" class=\"%cellheadingclass%\" onclick=\"location.href='./sentry.php?showsentries=%date:n%&day=%date:j%&year=%date:Y%'\">
         <div style=\"float: left;width:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:j%</div>
         <div style=\"float:right;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%entrycountabove0%</div>
         <div style=\"border-left: 1px solid #000000;margin-left:15px;font-family:Tahoma;font-size:8pt;vertical-align:top;\">%date:D%</div>
      </div>
      %entries%
   </div>";
   $config['monthv_start_dayofweekw'] = 1;
   //Sunday, June 4, 1972sssss
   $config['monthv_headingtools'] = "
   <a title=\"show entries for %date:l%, %date:F% %date:j%, %date:Y%\" href=\"sentry.php?showday&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%\">[day]</a>
   <a href=\"./sentry.php?addwprocess&amp;cancel=showsentries&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%\" title=\"create a new entry for %date:l%, %date:F% %date:j%, %date:Y%\">[new]</a>";
   $config['monthv_headingcaption'] = "
   <a title=\"show entries for previous month:%prevmonth.date:l%, %prevmonth.date:F% %prevmonth.date:j%, %prevmonth.date:Y%\" href=\"./sentry.php?showsentries=%prevmonth.date:n%&amp;year=%prevmonth.date:Y%&amp;day=%prevmonth.date:j%\">&lt;</a>
   <a title=\"refresh this month\" href=\"./sentry.php?showsentries=%date:n%&amp;year=%date:Y%&amp;day=%date:j%\">||</a>
   <a title=\"show entries for next month: %nextmonth.date:l%, %nextmonth.date:F% %nextmonth.date:j%, %nextmonth.date:Y%\" href=\"./sentry.php?showsentries=%nextmonth.date:n%&amp;year=%nextmonth.date:Y%&amp;day=%nextmonth.date:j%\">&gt;</a>
   %jumpmonthform%
   %date:l%, %date:F% %date:j%, %date:Y%";
   
   foreach((new config("tables"))->getAssoc() as $k=>$v) $config[$k]=$v;

   return $config;
}


   function nextsentrymonth($monthno,$year,&$pNextmonth,&$pNextyear) {
      if (($monthno + 1) > 12) {
         $pNextmonth = 1;
         $pNextyear = $year + 1;
         return 0;
      }
      $pNextmonth = $monthno + 1;
      $pNextyear = $year;
      return 0;
   }
   function previoussentrymonth($monthno,$year,&$pPrevmonth,&$pPrevyear) {
      if (($monthno - 1) < 1) {
         $sPrevmonth = 12;
         $pPrevyear = $year;
         return 0;
      }
      $pPrevmonth = $monthno - 1;
      $pPrevyear = $year;
      return 0;
   }
   function sentrydatebits($Expr,$Timestamp) {
      $ret = $Expr;
      $ret = str_replace("%date:j%",date("j",$Timestamp),$ret);
      $ret = str_replace("%date:D%",date("D",$Timestamp),$ret);
      $ret = str_replace("%date:F%",date("F",$Timestamp),$ret);
      $ret = str_replace("%date:Y%",date("Y",$Timestamp),$ret);
      $ret = str_replace("%date:l%",date("l",$Timestamp),$ret);
      $ret = str_replace("%date:n%",date("n",$Timestamp),$ret);
      if (strpos($Expr,"%prevmonth") !== FALSE) {
         previoussentrymonth(date("n",$Timestamp),date("Y",$Timestamp),$sPrevmonth,$sPrevyear);
         $sDaysInMonth = days_in_month(CAL_GREGORIAN,$sPrevmonth,$sPrevyear);
         $sPrevday = date("j",$Timestamp);
         if ($sPrevday > $sDaysInMonth) {
            $sPrevday = $sDaysInMonth;
         }
         $sPrevStamp = mktime(0,0,0,$sPrevmonth,$sPrevday,$sPrevyear);
         $ret = str_replace("%prevmonth.date:j%",date("j",$sPrevStamp),$ret);
         $ret = str_replace("%prevmonth.date:D%",date("D",$sPrevStamp),$ret);
         $ret = str_replace("%prevmonth.date:F%",date("F",$sPrevStamp),$ret);
         $ret = str_replace("%prevmonth.date:Y%",date("Y",$sPrevStamp),$ret);
         $ret = str_replace("%prevmonth.date:l%",date("l",$sPrevStamp),$ret);
         $ret = str_replace("%prevmonth.date:n%",date("n",$sPrevStamp),$ret);
      }
      if (strpos($Expr,"%nextmonth") !== FALSE) {
         nextsentrymonth(date("n",$Timestamp),date("Y",$Timestamp),$sNextmonth,$sNextyear);
         $sDaysInMonth = days_in_month(CAL_GREGORIAN,$sNextmonth,$sNextyear);
         $sNextday = date("j",$Timestamp);
         if ($sNextday > $sDaysInMonth) {
            $sNextday = $sDaysInMonth;
         }
         $sNextStamp = mktime(0,0,0,$sNextmonth,$sNextday,$sNextyear);
         $ret = str_replace("%nextmonth.date:j%",date("j",$sNextStamp),$ret);
         $ret = str_replace("%nextmonth.date:D%",date("D",$sNextStamp),$ret);
         $ret = str_replace("%nextmonth.date:F%",date("F",$sNextStamp),$ret);
         $ret = str_replace("%nextmonth.date:Y%",date("Y",$sNextStamp),$ret);
         $ret = str_replace("%nextmonth.date:l%",date("l",$sNextStamp),$ret);
         $ret = str_replace("%nextmonth.date:n%",date("n",$sNextStamp),$ret);

      }
      //jumpmonth($Timestamp) //%jumpmonthform%
      $ret = str_replace("%jumpmonthform%",jumpmonth($Timestamp),$ret);
      return $ret;
   }
   function actionmcaption($Timestamp,$actionmcaption,$actionmbrowse,$showsentries) {
      $actionm_headingcaption = "";
      
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      
      global $mSentry_key;
      $ret = sentrydatebits($actionm_headingcaption,$Timestamp);
      $ret = str_replace("%actionmcaption%",$actionmcaption,$ret);
      $ret = str_replace("%actionmbrowse%",$actionmbrowse,$ret);
      $ret = str_replace("%actionmentry.key%",$mSentry_key,$ret);
      //%toggleshowsentries.toggle%\">[%toggleshowsentries.caption%]
      if ($showsentries == true) {
         $ret = str_replace("%toggleshowsentries.toggle%","hidesentries",$ret);
         $ret = str_replace("%toggleshowsentries.caption%","hide entries",$ret);
      } else {
         $ret = str_replace("%toggleshowsentries.toggle%","",$ret);
         $ret = str_replace("%toggleshowsentries.caption%","show entries",$ret);
      }
      return $ret;
   }
   function jumpmonth($Timestamp) {
      $ret = "
<style>
   .jumpmonth {
      border:solid black 1px;
      font-size:8pt;
   }
</style>
<form method=\"POST\" action=\"./sentry.php?godate\"
style=\"
margin:0;
display:inline;
padding:0;
font-size:8pt;
\"

>
   <input type=\"text\" name=\"go_m\"
   size=\"1\"
   value=\"" . date("n",$Timestamp) . "\"
   class=\"jumpmonth\"
>&nbsp;-&nbsp;<input type=\"text\" name=\"go_d\"
   size=\"1\"
   value=\"" . date("j",$Timestamp) . "\"
   class=\"jumpmonth\"
>&nbsp;-&nbsp;<input type=\"text\" name=\"go_yyyy\"
   size=\"2\"
   value=\"" . date("Y",$Timestamp) . "\"
   class=\"jumpmonth\"
>
   <input type=\"submit\" name=\"go\" value=\"go\"
class=\"jumpmonth\"
>
</form>


      ";
      return $ret;
   }
   function monthvcaption($Timestamp) {
      //require("../../../include/settings.sentry.php");
      
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = "";
      //$ret = "<div>";



      $ret .= sentrydatebits($monthv_headingcaption,$Timestamp);
      //$ret .= jumpmonth($Timestamp);

      //$ret .= "</div>";
      return $ret;
   }
   function sentrydelistpostvars() {
      global $mSentrydelist_confirm;
      $mSentrydelist_confirm = false;
      if (isset($_POST["yes"])) {
         $mSentrydelist_confirm = true;
      }
   }
   function sentrygodatepostvars() {
      global $mGodate_valid,$mNotice;
      global $mGodate_m,$mGodate_d,$mGodate_yyyy;
      $mGodate_valid = false;
      if (isset($_POST["go_m"])) {
         $mGodate_m = $_POST["go_m"];
      } else {
         $mGodate_valid = false;
         $mNotice .= "no month<br>";
         return false;
      }
      if (isset($_POST["go_d"])) {
         $mGodate_d = $_POST["go_d"];
      } else {
         $mGodate_valid = false;
         $mNotice .= "no day<br>";
         return false;
      }
      if (isset($_POST["go_yyyy"])) {
         $mGodate_yyyy = $_POST["go_yyyy"];
      } else {
         $mGodate_valid = false;
         $mNotice .= "no year<br>";
         return false;
      }


      if (!is_numeric( $mGodate_m )) {
         $mGodate_valid = false;
         $mNotice .= "non numeric input for go m ($mGodate_m)<br>";
         return false;
      }

      if (!is_numeric( $mGodate_d )) {
         $mGodate_valid = false;
         $mNotice .= "non numeric input for go d ($mGodate_d)<br>";
         return false;
      }

      if (!is_numeric( $mGodate_yyyy )) {
         $mGodate_valid = false;
         $mNotice .= "non numeric input for go yyyy ($mGodate_yyyy)<br>";
         return false;
      }

      if ( (int)$mGodate_m != $mGodate_m ) {
         $mGodate_valid = false;
         $mNotice .= "non int input for go m ($mGodate_m)<br>";
         return false;
      }

      if ((int)$mGodate_d != $mGodate_d ) {
         $mGodate_valid = false;
         $mNotice .= "non int input for go d ($mGodate_d)<br>";
         return false;
      }

      if ((int)$mGodate_yyyy != $mGodate_yyyy ) {
         $mGodate_valid = false;
         $mNotice .= "non int input for go yyyy ($mGodate_yyyy)<br>";
         return false;
      }


      if ( ($mGodate_m < 1) || ($mGodate_m > 12) ) {
         $mGodate_valid = false;
         $mNotice .= "month out of range<br>";
         return false;
      }

      if ( ($mGodate_d < 1) || ($mGodate_d > 31) ) {
         $mGodate_valid = false;
         $mNotice .= "day out of range<br>";
         return false;
      }

      if ( ($mGodate_yyyy < 1970) || ($mGodate_yyyy > 2080) ) {
         $mGodate_valid = false;
         $mNotice .= "year out of range<br>";
         return false;
      }

      if (! checkdate  ( $mGodate_m , $mGodate_d  , $mGodate_yyyy  )) {
         $mGodate_valid = false;
         $mNotice .= "date tested bad using<br>";
         return false;
      }
      $mGodate_valid = true;
      return true;

   }
   function rawgodatevars() {
      global $mGodate_m,$mGodate_d,$mGodate_yyyy;
      $ret ="
m:" . htmlentities($mGodate_m) . "<br>
d:" . htmlentities($mGodate_d) . "<br>
yyyy:" . htmlentities($mGodate_yyyy) . "<br>
         <br>";

      return $ret;
   }
   function confirmsentrydelist($action) {
      global $mSentry_key,$mSentry_heading,$mSentry_startdate,$mSentry_starttime,$mSentry_sentrytype,$mCust_key,$mCust_name;
      $ret = "
      <div style=\"width:400;font-family:Tahoma;font-size;11pt;\">
         <form method=\"POST\" action=\"$action\">
         <div style=\"background:#999999;height:20;vertical-align:top;\">
            <div style=\"vertical-align:top;background:#999999;float:left;\">
               <span style=\"font-weight:bold;color:#FFFFFF;\">delist this entry from the schedule?</span>
            </div>
         </div>
         <div style=\"width:100%;background:#CCCCCC;\">
            <b>$mSentry_heading</b><br />
            date:$mSentry_startdate<br />
            customer:$mCust_name
         </div>
         <div style=\"width:100%;background:#CCCCCC;\">
         <input title=\"confirm the delisting of this entry from the schedule\" type=\"submit\" name=\"yes\" value=\"Yes\">
         <input title=\"keep this entry in the schedule\" type=\"submit\" name=\"no\" value=\"No\">
         </div>
         </form>
      </div>
      ";
      return $ret;
   }
   function sentriesbyday($caption,$monthno,$year,$day) {
      global $mMySched;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = EnumerateSentriesWSomeSiteAndCustInfoForDay($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$monthno,$year,$day,"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <div style=\"width:400;font-family:Tahoma;font-size;11pt;\">
         <div style=\"background:#999999;width:100%\">
            $caption
         </div>
         <div style=\"background:#CCCCCC;width:100%\">
            problem ($sRet) while getting info for schedule entries:<br />$mError
         </div>
      </div>";
         return $ret;
      }
      $ret = "
      <div style=\"width:400;font-family:Tahoma;font-size;11pt;\">
         <div style=\"background:#999999;height:20;vertical-align:top;\">
            <div style=\"vertical-align:top;background:#999999;float:left;\">
               $caption
            </div>
            <div style=\"vertical-align:top;float:right;\">
            <b><a href=\"sentry.php?showsentries=$monthno&amp;month=$monthno&amp;day=$day&amp;year=$year\">[month]</a></b>
            <a href=\"sentry.php?printday&amp;month=$monthno&amp;day=$day&amp;year=$year\" target=\"_blank\">[print]</a>
            <a href=\"sentry.php?addwprocess&amp;cancel=showday&amp;month=$monthno&amp;day=$day&amp;year=$year\">[new]</a>
            </div>
         </div>
         ";
      for($i = 0;$i < $sCount;$i++) {
         $sTimePart = explode(":",$sStarttime[$i]);
         //mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
         //HH:MM:SS
         $sHH = $sTimePart[0];$sMM = $sTimePart[1]; $sSS = $sTimePart[2];
         $sStamp = mktime($sHH,$sMM,$sSS,1,1,1979);

         $ret .= "
         <div style=\"border-bottom: 1px solid #FFFFFF;background:#CCCCCC;width:100%\">
            <b>" . $sSentrytype[$i] . ": " . $sHeading[$i] . "</b><br />
            " . $sCust_name[$i] . "; " . $sSite_streetaddr[$i] . ", " . $sSite_city[$i] . ", " . $sSite_state[$i] . " " . $sSite_zip[$i] . "<br />";
         $ret .= date("g:ia",$sStamp) . "<br />";
         $ret .= "
            <b>crew:</b> " . $sSupervisor_name[$i] . "<br />
            <b>" . $sPriphone_type[$i] . "</b> " . $sPriphone_num[$i] . "<br />
            <a href=\"./sentry.php?show=" . $sSentryKey[$i] . "\">[view]</a>
         </div>";
      }
      $ret .= "
      </div>";
      return $ret;
   }
   function sentriesbydayforprint($caption,$monthno,$year,$day) {
      global $mMySched;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = EnumerateSentriesWSomeSiteAndCustInfoForDayC($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$monthno,$year,$day,"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sSite_sdirections,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <div style=\"width:400;font-family:Tahoma;font-size;11pt;\">
         <div style=\"background:#FFFFFF;width:100%;border: 1px solid #000000;\">
            $caption
         </div>
         <div style=\"background:#FFFFFF;width:100%;border: 1px solid #000000; \">
            problem ($sRet) while getting info for schedule entries:<br />$mError
         </div>
      </div>";
         return $ret;
      }
      $ret = "
      <div style=\"width:400;font-family:Tahoma;font-size;11pt;margin-left:5px;margin-top:5px;\">
         <div style=\"background:#FFFFFF;height:20;vertical-align:top;border: 1px solid #000000;width:100%;\">
            <div style=\"vertical-align:top;background:#FFFFFF;float:left;\">
               $caption
            </div>
         </div>
         ";
      for($i = 0;$i < $sCount;$i++) {
         $sTimePart = explode(":",$sStarttime[$i]);
         //mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
         //HH:MM:SS
         $sHH = $sTimePart[0];$sMM = $sTimePart[1]; $sSS = $sTimePart[2];
         $sStamp = mktime($sHH,$sMM,$sSS,1,1,1979);
         $ret .= "
         <div style=\"border-bottom: 1px solid #000000;border-right: 1px solid #000000;border-left: 1px solid #000000;width:100%\">
            <b>" . $sSentrytype[$i] . ": " . $sHeading[$i] . "</b><br />
            " . $sCust_name[$i] . "; " . $sSite_streetaddr[$i] . ", " . $sSite_city[$i] . ", " . $sSite_state[$i] . " " . $sSite_zip[$i] . "<br />";
         $ret .= date("g:ia",$sStamp) . "<br />";
         if ($sSite_sdirections[$i] != "") {
            $ret .= "
                     <b>direction notes:</b><br />
                     " . $sSite_sdirections[$i] . "<br />";
         }
         $ret .= "
            <b>crew:</b> " . $sSupervisor_name[$i] . "<br />";
         if ($sPriphone_type[$i] == "") {
            $ret .= "<b>{no phone info}</b>";
         } else {
            $ret .= "
            <b>" . $sPriphone_type[$i] . "</b> " . $sPriphone_num[$i] . "<br />";
         }
         $ret .= "
         </div>";
      }
      $ret .= "
      </div>";
      return $ret;
   }
   function monthvtools($Timestamp) {
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      return sentrydatebits($monthv_headingtools,$Timestamp);
   }
   function actionmtools($Timestamp,$actionmcaption,$actionmbrowse,$showsentries) {
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      global $mSentry_key;
      $ret = sentrydatebits($actionm_headingtools,$Timestamp);
      $ret = str_replace("%actionmcaption%",$actionmcaption,$ret);
      $ret = str_replace("%actionmbrowse%",$actionmbrowse,$ret);
      $ret = str_replace("%actionmentry.key%",$mSentry_key,$ret);
      if ($showsentries == true) {
         $ret = str_replace("%toggleshowsentries.toggle%","hidesentries",$ret);
         $ret = str_replace("%toggleshowsentries.caption%","hide entries",$ret);
      } else {
         $ret = str_replace("%toggleshowsentries.toggle%","",$ret);
         $ret = str_replace("%toggleshowsentries.caption%","show entries",$ret);
      }
      return $ret;
   }
   function sentrygoogleapiscript() {
      $ret = "<script src=\"http://maps.google.com/maps?file=api&v=1&key=ABQIAAAA8r5nZFALlAb09o8z4vq8qhTZlEbBv2ofKC2JdKBTwGn3OFWaPRSup-7GlohwpWa0vz7OQ4hj3V2xqA\" type=\"text/javascript\"></script>";
      return $ret;
   }
   function blankcell($Area) {
      $monthv_blankcell_format = "";
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = $monthv_blankcell_format;
      return $ret;
   }
   function actionmblankcell($Area) {
      $actionm_blankcell_format = "";
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = $actionm_blankcell_format;
      return $ret;
   }
   function actionmentrycaption_siteinfo($Format,$Streetaddr,$City,$State,$Zip,$Sdirections) {
      $ret = $Format;
      if ($Streetaddr != "") {
         $sStreetaddr = htmlentities($Streetaddr);
         $sStreetaddr = preg_replace('/\s\s+/', ' ', $sStreetaddr); //strip whitespace
         $ret = str_replace("%site.streetaddr%",$sStreetaddr,$ret);
      } else {
         $ret = str_replace("%site.streetaddr%",htmlentities("{no addr}"),$ret);
      }
      return $ret;
   }
   function entrycaption_siteinfo($Format,$Streetaddr,$City,$State,$Zip,$Sdirections) {
      $ret = $Format;
      if ($Streetaddr != "") {
         $sStreetaddr = htmlentities($Streetaddr);
         $sStreetaddr = preg_replace('/\s\s+/', ' ', $sStreetaddr); //strip whitespace
         $ret = str_replace("%site.streetaddr%",$sStreetaddr,$ret);
      } else {
         $ret = str_replace("%site.streetaddr%",htmlentities("{no addr}"),$ret);
      }
      return $ret;
   }
   function entrycaption($Key,$Heading,$Starttime,$Sentrytype,$Supervisor,$Supervisorkey,$CustName) {
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = $monthv_entrycaption_format;
      $ret = str_replace("%entry.custname%",htmlentities($CustName),$ret);
      $ret = str_replace("%entry.heading%",htmlentities($Heading),$ret);
      $ret = str_replace("%entry.key%",htmlentities($Key),$ret);
      //%site.streetaddr% (%entry.type%)
      if (strpos($monthv_entrycaption_format,"%entry.type") !== FALSE) {
         //$Sentrytype
         $ret = str_replace("%entry.type%",$Sentrytype,$ret);
      }
      if (strpos($monthv_entrycaption_format,"%entry.heading:limitchar:8%") !== FALSE) {
         if (strlen($Heading) > 8) {
            $sTrunc = substr($Heading,0,8);
            $ret = str_replace("%entry.heading:limitchar:8%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.heading:limitchar:8%",htmlentities($Heading),$ret);
         }
      }
      //%entry.custname:limitchar:4%
      if (strpos($monthv_entrycaption_format,"%entry.custname:limitchar") !== FALSE) {
         if (strlen($CustName) > 4) {
            $sTrunc = substr($CustName,0,4);
            $ret = str_replace("%entry.custname:limitchar:4%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:4%",htmlentities($CustName),$ret);
         }
         if (strlen($CustName) > 5) {
            $sTrunc = substr($CustName,0,5);
            $ret = str_replace("%entry.custname:limitchar:5%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:5%",htmlentities($CustName),$ret);
         }
         if (strlen($CustName) > 10) {
            $sTrunc = substr($CustName,0,10);
            $ret = str_replace("%entry.custname:limitchar:10%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:10%",htmlentities($CustName),$ret);
         }
      }
      //%dashifcustandheading%
      if (strpos($monthv_entrycaption_format,"%dashifcustandheading%") !== FALSE) {
         if (($Heading != "") && ($CustName != "")) {
            $ret = str_replace("%dashifcustandheading%","-",$ret);
         } else {
            $ret = str_replace("%dashifcustandheading%","",$ret);
         }
      }
      $ret = str_replace("%entry.supervisorkey%",htmlentities($Supervisorkey),$ret);
      return $ret;
   }
   function actionmentrycaption($Key,$Heading,$Starttime,$Sentrytype,$Supervisor,$Supervisorkey,$CustName) {
      $actionm_entrycaption_format = "";
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = $actionm_entrycaption_format;
      $ret = str_replace("%entry.custname%",htmlentities($CustName),$ret);
      $ret = str_replace("%entry.heading%",htmlentities($Heading),$ret);
      $ret = str_replace("%entry.key%",htmlentities($Key),$ret);
      //%site.streetaddr% (%entry.type%)
      if (strpos($actionm_entrycaption_format,"%entry.type") !== FALSE) {
         //$Sentrytype
         $ret = str_replace("%entry.type%",$Sentrytype,$ret);
      }
      if (strpos($actionm_entrycaption_format,"%entry.heading:limitchar:8%") !== FALSE) {
         if (strlen($Heading) > 8) {
            $sTrunc = substr($Heading,0,8);
            $ret = str_replace("%entry.heading:limitchar:8%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.heading:limitchar:8%",htmlentities($Heading),$ret);
         }
      }
      //%entry.custname:limitchar:4%
      if (strpos($actionm_entrycaption_format,"%entry.custname:limitchar") !== FALSE) {
         if (strlen($CustName) > 4) {
            $sTrunc = substr($CustName,0,4);
            $ret = str_replace("%entry.custname:limitchar:4%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:4%",htmlentities($CustName),$ret);
         }
         if (strlen($CustName) > 5) {
            $sTrunc = substr($CustName,0,5);
            $ret = str_replace("%entry.custname:limitchar:5%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:5%",htmlentities($CustName),$ret);
         }
         if (strlen($CustName) > 10) {
            $sTrunc = substr($CustName,0,10);
            $ret = str_replace("%entry.custname:limitchar:10%",htmlentities($sTrunc),$ret);
         } else {
            $ret = str_replace("%entry.custname:limitchar:10%",htmlentities($CustName),$ret);
         }
      }
      //%dashifcustandheading%
      if (strpos($actionm_entrycaption_format,"%dashifcustandheading%") !== FALSE) {
         if (($Heading != "") && ($CustName != "")) {
            $ret = str_replace("%dashifcustandheading%","-",$ret);
         } else {
            $ret = str_replace("%dashifcustandheading%","",$ret);
         }
      }
      $ret = str_replace("%entry.supervisorkey%",htmlentities($Supervisorkey),$ret);
      return $ret;
   }
   function actionmdayofmonthcellval($Timestamp,$SentryCount,$actionmselect,$showsentries) {
      $actionm_daycell_format = "";
      $actionm_cellheading_class = "";
      $actionm_cellcontent_class = "";
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      global $mSentry_key;
      $ret = $actionm_daycell_format;
      $ret = str_replace("%date:j%",date("j",$Timestamp),$ret);
      $ret = str_replace("%date:D%",date("D",$Timestamp),$ret);
      $ret = str_replace("%date:F%",date("F",$Timestamp),$ret);
      $ret = str_replace("%date:Y%",date("Y",$Timestamp),$ret);
      $ret = str_replace("%date:l%",date("l",$Timestamp),$ret);
      $ret = str_replace("%date:n%",date("n",$Timestamp),$ret);
      $ret = str_replace("%cellheadingclass%",$actionm_cellheading_class,$ret);
      $ret = str_replace("%cellcontentclass%",$actionm_cellcontent_class,$ret);
      $ret = str_replace("%actionmentry.key%",$mSentry_key,$ret);
      $ret = str_replace("%entrycount%",$SentryCount,$ret);
      $ret = str_replace("%actionmselect%",$actionmselect,$ret);
      if ($SentryCount > 0) {
         $ret = str_replace("%entrycountabove0%",$SentryCount,$ret);
         global $mMySched;
         //require("settings.php");
         foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
         $sRet = EnumerateSentriesWSomeSiteAndCustInfoForDayB($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,date("n",$Timestamp),date("Y",$Timestamp),date("j",$Timestamp),"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor,$sSupervisorKey,$sCustName,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
         if ($sRet == 0) {
            if ($showsentries === TRUE) {
               $sEntries = "";
               for($i = 0;$i < $sCount;$i++) {
                  //actionmentrycaption
                  $sCur = actionmentrycaption($sSentryKey[$i],$sHeading[$i],$sStarttime[$i],$sSentrytype[$i],$sSupervisor[$i],$sSupervisorKey[$i],$sCustName[$i]);
                  $sCur = actionmentrycaption_siteinfo($sCur,$sSite_streetaddr[$i],$sSite_city[$i],$sSite_state[$i],$sSite_zip[$i],"");
                  $sEntries .= $sCur;
                  //$ret = str_replace("%entries%",entrycaption($Heading,$Starttime,$Sentrytype,$Supervisor,$CustName),$ret);
               }
               $ret = str_replace("%entries%",$sEntries,$ret);
            } else {
               $ret = str_replace("%entries%","",$ret);
            }
         } else {
            $ret = str_replace("%entries%","",$ret);
            global $mNotice,$mError;
            $mNotice .= "problem ($sRet) while getting sentries for day " . date("j",$Timestamp) . "<br />$mError<br />";
         }
      } else {
         $ret = str_replace("%entrycountabove0%","",$ret);
         $ret = str_replace("%entries%","",$ret);
      }
      return $ret;
   }
   function dayofmonthcellval($Timestamp,$Sel,$SentryCount) {
      $monthv_daycell_format = "";
      $monthv_cellheadingsel_class = "";
      $monthv_cellcontentsel_class = "";
      $monthv_cellheading_class = "";
      $monthv_cellcontent_class = "";
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      $ret = $monthv_daycell_format;
      $ret = str_replace("%date:j%",date("j",$Timestamp),$ret);
      $ret = str_replace("%date:D%",date("D",$Timestamp),$ret);
      $ret = str_replace("%date:F%",date("F",$Timestamp),$ret);
      $ret = str_replace("%date:Y%",date("Y",$Timestamp),$ret);
      $ret = str_replace("%date:l%",date("l",$Timestamp),$ret);
      $ret = str_replace("%date:n%",date("n",$Timestamp),$ret);
      if ($Sel === TRUE) {
         $ret = str_replace("%cellheadingclass%",$monthv_cellheadingsel_class,$ret);
         $ret = str_replace("%cellcontentclass%",$monthv_cellcontentsel_class,$ret);
      } else {
         $ret = str_replace("%cellheadingclass%",$monthv_cellheading_class,$ret);
         $ret = str_replace("%cellcontentclass%",$monthv_cellcontent_class,$ret);
      }
      $ret = str_replace("%entrycount%",$SentryCount,$ret);
      if ($SentryCount > 0) {
         $ret = str_replace("%entrycountabove0%",$SentryCount,$ret);
         global $mMySched;
         $sentry_table = "sentry";
         $supervisor_table = "supervisor";
         foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
         $sRet = EnumerateSentriesWSomeSiteAndCustInfoForDayB($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,date("n",$Timestamp),date("Y",$Timestamp),date("j",$Timestamp),"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor,$sSupervisorKey,$sCustName,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
         if ($sRet == 0) {
            $sEntries = "";
            for($i = 0;$i < $sCount;$i++) {
               $sCur = entrycaption($sSentryKey[$i],$sHeading[$i],$sStarttime[$i],$sSentrytype[$i],$sSupervisor[$i],$sSupervisorKey[$i],$sCustName[$i]);
               $sCur = entrycaption_siteinfo($sCur,$sSite_streetaddr[$i],$sSite_city[$i],$sSite_state[$i],$sSite_zip[$i],"");
               $sEntries .= $sCur;
               //$ret = str_replace("%entries%",entrycaption($Heading,$Starttime,$Sentrytype,$Supervisor,$CustName),$ret);
            }
            $ret = str_replace("%entries%",$sEntries,$ret);
         } else {
            $ret = str_replace("%entries%","",$ret);
            global $mNotice,$mError;
            $mNotice .= "problem ($sRet) while getting sentries for day " . date("j",$Timestamp) . "<br />$mError<br />";
         }
      } else {
         $ret = str_replace("%entrycountabove0%","",$ret);
         $ret = str_replace("%entries%","",$ret);
      }
      return $ret;
   }
   function actionmonthv($action,$month,$year,$actionmselect,$actionmcaption,$actionmbrowse,$showentries,$cancelnav) {
      global $mMySched;
      global $mNotice;
      //require("../../../include/settings.sentry.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      //require("settings.php");
      $sDummy = mktime(0,0,0,3,$actionm_start_dayofweekw,2004);
      if (($sDummy === FALSE) || ($sDummy == -1)) {
         $mNotice .= "invalid Start day of week monthv_start_dayofweekw:$actionm_start_dayofweekw, defaulting to Monday (1)";
         $sFirstweekdayw = 1;
      } else {
         $sFirstweekdayw = $actionm_start_dayofweekw;
      }
      for($i = 0;$i < 7;$i++) {
         $sWDStamp[$i] = $sDummy;
         $sDummy = mktime(0,0,0,3,date("d",$sDummy)+1, 2004);
      }
      $sCurDaysInMonth = days_in_month(CAL_GREGORIAN,$month,$year);
      $sSelStamp = mktime(0,0,0,$month,1,$year);
      //%toggleshowsentries.toggle%\">[%toggleshowsentries.caption%]
      $ret = "

      <table class=\"actionmmain\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td class=\"actionmmain\" colspan=\"7\">
            <div style=\"height:20;border: 1px solid #000000;vertical-align:top;\">
               <div style=\"$actionmcaption_style\">"
            . actionmcaption($sSelStamp,$actionmcaption,$actionmbrowse,$showentries)
            . "
               </div>
               <div style=\"font-family:Tahoma;font-size:11pt;vertical-align:top;float:right;\">"
            . actionmtools($sSelStamp,$actionmcaption,$actionmbrowse,$showentries)
            . "
               </div>
            </div>";
      $ret .= "
            </td>
         </tr>";
      //the heading row as formatted in settings
      $sHCells = $actionm_headingcells_format;
      for($i = 0;$i < 7;$i++) {
         $sHCells = str_replace("%weekday$i:l%",date("l",$sWDStamp[$i]),$sHCells);
         $sHCells = str_replace("%weekday$i:D%",date("D",$sWDStamp[$i]),$sHCells);
      }
      $ret .= $sHCells;

      $ret .= "
               <tr>";
      $sCurDay=1;
      $sCurStamp = mktime(0,0,0,$month,$sCurDay,$year);
      $sCol=0;
      $sFirstDayw = date("w", $sCurStamp);
      for($i = 0;$i < 7;$i++) {
         if (($sFirstDayw == ($sFirstweekdayw + $i)) || (($i + $sFirstweekdayw) > 6)) {
            break;
         }
         $ret .= "
                  <td class=\"monthvcallcell\" height=\"$actionm_headingcells_height\" width=\"$actionm_headingcells_width\" >
                     " . actionmblankcell("Top") . "
                  </td>
                     "; //the blank cells prefixing the first day of the week row
         $sCol++;
      }
      $sRet = EnumerateSentryCountByDay($mMySched,$sentry_table,$month,$year,$sSentryCount);
      if ($sRet != 0) {
         global $mError;
         $mNotice .= "problem ($sRet) while getting sentry counts:<br />$mError<br />";
         $sSentryCount = array();
         $sSentryCount = array_fill(1,$sCurDaysInMonth,0);
      }

      while ($sCurDay<$sCurDaysInMonth + 1) {
            if ($sSelStamp == $sCurStamp) {
               $sCurSel = TRUE;
            } else {
               $sCurSel = FALSE;
            }
            //actionmdayofmonthcellval
            $ret .= "
                  <td class=\"monthvcallcell\" height=\"$actionm_headingcells_height\" width=\"$actionm_headingcells_width\" >
                  " . actionmdayofmonthcellval($sCurStamp,$sSentryCount[$sCurDay],$actionmselect,$showentries) . "
                  </td>
                     ";
         $sCurDay++;
         $sCurStamp = mktime(0,0,0,$month,$sCurDay,$year);
         $sCol++;
         if ($sCol>6) { //if on the 7th cell make the next weeks row
            $ret .= "
               </tr>
               <tr>";
            $sCol=0;
         }
      }
      if ($sCol != 0) {
         for($i=$sCol;$i<7;$i++) { //the blank cells suffixing the final day of the week row
            $ret .= "
                  <td class=\"monthvcallcell\" height=\"$actionm_headingcells_height\" width=\"$actionm_headingcells_width\" >" . actionmblankcell("Bottom") . "</td>
                  ";
         }
      }
      $ret .= "
               </tr>
               <tr>
                  <td colspan=\"7\"><b><a href=\"$cancelnav\">[cancel]</a></b></td>
               </tr>";
      $ret .= "
      </table>

      ";
      return $ret;
   }
   function entrytypelegend($Format,$Typename,$Typebrief,$Typedesc) {
      $ret = $Format;
      $ret = str_replace("%entry.type%",$Typename,$ret);
      $ret = str_replace("%entry.typebrief%",$Typebrief,$ret);
      $ret = str_replace("%entry.typedesc%",$Typedesc,$ret);
      return $ret;
   }
   function sentrymonthv($month,$year,$day) {
      global $mMySched;
      global $mNotice;
//       require("../../../include/settings.sentry.php");
//       require("settings.php");
      foreach(enumsentryconfig() as $k=>$v) $$k=$v;
      unset($k);
      unset($v);
      $sDummy = mktime(0,0,0,3,$monthv_start_dayofweekw,2004);
      if (($sDummy === FALSE) || ($sDummy == -1)) {
         $mNotice .= "invalid Start day of week monthv_start_dayofweekw:$monthv_start_dayofweekw, defaulting to Monday (1)";
         $sFirstweekdayw = 1;
      } else {
         $sFirstweekdayw = $monthv_start_dayofweekw;
      }
      for($i = 0;$i < 7;$i++) {
         $sWDStamp[$i] = $sDummy;
         $sDummy = mktime(0,0,0,3,date("d",$sDummy)+1, 2004);
      }
      $sCurDaysInMonth = days_in_month(CAL_GREGORIAN,$month,$year);
      $sSelStamp = mktime(0,0,0,$month,$day,$year);
      $ret = "

      <table class=\"monthvmain\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
         <tr>
            <td class=\"monthvmain\" colspan=\"7\">
            <div style=\"height:20;border: 1px solid #000000;vertical-align:top;\">
               <div style=\"font-family:Tahoma;font-size:11pt;vertical-align:top;float:left;\">"
            . monthvcaption($sSelStamp)
            . "
               </div>
               <div style=\"font-family:Tahoma;font-size:11pt;vertical-align:top;float:right;\">"
            . monthvtools($sSelStamp)
            . "
               </div>
            </div>";
      $ret .= "
            </td>
         </tr>";
      //the heading row as formatted in settings
      $sHCells = $monthv_headingcells_format;
      for($i = 0;$i < 7;$i++) {
         $sHCells = str_replace("%weekday$i:l%",date("l",$sWDStamp[$i]),$sHCells);
         $sHCells = str_replace("%weekday$i:D%",date("D",$sWDStamp[$i]),$sHCells);
      }
      $ret .= $sHCells;

      $ret .= "
               <tr>";
      $sCurDay=1;
      $sCurStamp = mktime(0,0,0,$month,$sCurDay,$year);
      $sCol=0;
      $sFirstDayw = date("w", $sCurStamp);
      for($i = 0;$i < 7;$i++) {
         if (($sFirstDayw == ($sFirstweekdayw + $i)) || (($i + $sFirstweekdayw) > 6)) {
            break;
         }
         $ret .= "
                  <td class=\"monthvcallcell\" height=\"$monthv_headingcells_height\" width=\"$monthv_headingcells_width\" >
                     " . blankcell("Top") . "
                  </td>
                     "; //the blank cells prefixing the first day of the week row
         $sCol++;
      }
      $sRet = EnumerateSentryCountByDay($mMySched,$sentry_table,$month,$year,$sSentryCount);
      if ($sRet != 0) {
         global $mError;
         $mNotice .= "problem ($sRet) while getting sentry counts:<br />$mError<br />";
         $sSentryCount = array();
         $sSentryCount = array_fill(1,$sCurDaysInMonth,0);
      }

      while ($sCurDay<$sCurDaysInMonth + 1) {
            if ($sSelStamp == $sCurStamp) {
               $sCurSel = TRUE;
            } else {
               $sCurSel = FALSE;
            }
            $ret .= "
                  <td class=\"monthvcallcell\" height=\"$monthv_headingcells_height\" width=\"$monthv_headingcells_width\" >
                  " . dayofmonthcellval($sCurStamp,$sCurSel,$sSentryCount[$sCurDay]) . "
                  </td>
                     ";
         $sCurDay++;
         $sCurStamp = mktime(0,0,0,$month,$sCurDay,$year);
         $sCol++;
         if ($sCol>6) { //if on the 7th cell make the next weeks row
            $ret .= "
               </tr>
               <tr>";
            $sCol=0;
         }
      }
      if ($sCol != 0) {
         for($i=$sCol;$i<7;$i++) { //the blank cells suffixing the final day of the week row
            $ret .= "
                  <td class=\"monthvcallcell\" height=\"$monthv_headingcells_height\" width=\"$monthv_headingcells_width\" >" . blankcell("Bottom") . "</td>
                  ";
         }
      }
      $ret .= "
               </tr>";
      $sRet = EnumerateSentrytypes($mMySched,$sentrytype_table,$sCount,$sName,$sBrief,$sDescription);
      if ($sRet == 0) {
         $ret .= "
               <tr>
                  <td colspan=\"7\">";
         for ($i = 0;$i < $sCount;$i++) {
            //$sLine = $monthv_entrytype_legend_format;
            $ret .= entrytypelegend($monthv_entrytype_legend_format,$sName[$i],$sBrief[$i],$sDescription[$i]);
         }
         $ret .= "
                  </td>
               </tr>";
      } else {
         global $mError;
         $ret .= "
               <tr>
                  <td colspan=\"7\">problem ($sRet) while getting sentry types for legend:<br />$mError</td>
               </tr>";
      }
      $ret .= "
      </table>

      ";
      return $ret;
   }
   function sentrywithinfotable($caption,$showmap) {
      global $mMySched;
      global $mSentry_key;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = GetSentryWSomeSiteAndCustInfo($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$mSentry_key,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_key,$sCust_name,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"project\">
            problem ($sRet) while getting info for schedule entry<br />$mError
            </td>
         </tr>
      </table>";
         return $ret;
      }
      $sDatePart = explode("-",$sStartdate);
      //mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
      $sYear = $sDatePart[0];$sMonth = $sDatePart[1]; $sCurDay = $sDatePart[2];
      $sDateTimestamp = mktime(0,0,0,$sMonth,$sCurDay,$sYear);
      $sDatePretty = date("l, F j, Y",$sDateTimestamp);
      //<b><a href=\"sentry.php?showsentries&amp;month=$monthno&amp;day=$day&amp;year=$year\">[month]</a></b>
      //<a title=\"duplicate this entry on another date\" href=\"./sentry.php?duplicate=$mSentry_key\">[dupe]</a>
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">
            <div style=\"float:left;\">
            <b>entry <a title=\"view all entries in for " . date("F",$sDateTimestamp) . " in calendar view\" href=\"./sentry.php?showsentries=$sMonth&amp;month=$sMonth&amp;day=$sCurDay&amp;year=$sYear\">[month]</a></b>
            </div>
            <div style=\"float:right;\"><a title=\"duplicate this entry on another date\" href=\"./sentry.php?duplicate=$mSentry_key&amp;month=$sMonth&amp;year=$sYear\">[dupe]</a></span>
            </td>
         </tr>
         <tr>
            <td class=\"project\">
            <b>$sSentrytype $sHeading:</b> $sDatePretty<br />
            <b>crew: </b>$sSupervisor_name<br />
            <a href=\"./sentry.php?edit=$mSentry_key\">[entry info]</a>
            </td>
         </tr>
         <tr>
            <td class=\"project\">";
      $ret .= "
            ";
      $ret .= "
            $sSite_streetaddr<br />
            $sSite_city, $sSite_state $sSite_zip<br />
            <a href=\"./sentry.php?editsite=$mSentry_key\">[site info]</a>
            </td>
         </tr>";
      //$sGmapAnchor = "<a href=\"./sentry.php?show=$mSentry_key&amp;showmap=true&amp;maptype=google\">[google map]</a>";
      $sGmapAnchor = "";
      $sBmapAnchor = "<a href=\"./sentry.php?show=$mSentry_key&amp;showmap=true&amp;maptype=bing\">[map]</a>";
      $sSmapAnchor = "<a href=\"./sentry.php?show=$mSentry_key&amp;showmap=true&amp;maptype=street\">[street view]</a>";

      if ($showmap) {
         $sRet = GetSiteLatLon($mMySched,"site",$mSentry_key,$sLat,$sLon);
         $sZoom = 15;
         if ( ($sLat == 0) && ($sLon == 0) ) {
            //default to 200 Hughes, willard mo (37.281253, -93.429131)
            $sLat = 37.281253;
            $sLon = -93.429131;
            $sZoom = 12;
         }

         $sMapDebug = "";
         if ($sRet != 0) {
            $sMapDebug = "Loc:$sLat,$sLon";
            global $mError;
            $sMapDebug .= "Error $sRet:<br>$mError<br>";

         }
         $ret .= "
         <tr>
            <td class=\"project\"><a href=\"./sentry.php?show=$mSentry_key\">[-]</a>$sMapDebug
         ";
         $sMapType = "bing";
         if (isset($_GET["maptype"])) {
            if ($_GET["maptype"] == "google") {
               $sMapType = "google";
            } else
            if ($_GET["maptype"] == "street") {
               $sMapType = "street";
            }
         }
         if ($sMapType == "bing") {
            $ret .= "$sGmapAnchor $sSmapAnchor";
            require("routine/do.sentrybingmap.php");
         } else
         if ($sMapType == "google") {
            $ret .= "$sBmapAnchor $sSmapAnchor";
            require("routine/do.sentrygooglemap.php");
         } else
         if ($sMapType == "street") {
            $ret .= "$sGmapAnchor $sBmapAnchor";
            require("routine/do.gstreetmap.php");
         }
         $ret .= "
            </td>
         </tr>
         ";
      } /*-endif show map-*/ else { /*-if not showing map-*/
         $ret .= "
         <tr>
            <td class=\"project\">
            <a href=\"./sentry.php?show=$mSentry_key&amp;showmap=true&amp;maptype=bing\">[+]</a>
            $sBmapAnchor
            $sSmapAnchor
            </td>
         </tr>";
      }
      $ret .= "
         <tr>
            <td class=\"project\">";
      if ($sCust_key > 0) {
         $ret .= "
            $sCust_name<br />";
         if (($sPriphone_type != "") && ($sPriphone_num != "")) {
            $ret .= "
               <b>$sPriphone_type</b> " . FormatCustomerPhone($sPriphone_num) . "<br />";
         }
         $sRet = EnumerateCustomerPhonenumbers($mMySched,$customerphone_table,$sCust_key,$sCount,$sPhonenumber,$sPhonetype);
         if ($sRet != 0) {
            global $mError;
            $ret .= "
               problem ($sRet) while getting phone numbers for customer:$sCust_key<br />$mError<br />";
         } else {
            if ($sCount < 1) {
               $ret .= "
                  (no phone numbers on record)<br />";
            } else {
               for ($i = 0;$i < $sCount;$i++) {
                  if ($sPhonetype[$i] != $sPriphone_type) {
                     $ret .= "
                  <b>" . $sPhonetype[$i] . "</b> " . $sPhonenumber[$i];
                  }
               }
               $ret .= "
                  <br />";
            }
         }
         $ret .= "
            <a href=\"./customer.php?edit=$sCust_key\">[customer info]</a>";
      } else {
         $ret .= "
            no customer linked to this entry";
      }
      $ret .= "
            <a href=\"./sentry.php?editcustlink=$mSentry_key\">[change customer link]</a>
            </td>
         </tr>";
      $ret .= "
      </table>";
      return $ret;
   }
   function sentrygetstartdateparts($startdate,&$pYYYY,&$pMM,&$pDD) {
      $pYYYY = 0;$pMM = 0;$pDD = 0;
      $sDateparts = explode("-",$startdate);
      if (count($sDateparts) == 3) {
         $pYYYY = $sDateparts[0];
         $pMM = $sDateparts[1];
         $pDD = $sDateparts[2];
         return 0;
      }
      return -1;
   }
   function sentriesbyweektable_poop() {
      global $mMySched;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = EnumerateSentriesWSomeSiteAndCustInfo($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$monthno,$year,"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">$caption</td>
         </tr>
         <tr>
            <td class=\"project\">problem ($sRet) while getting info for schedule entries:<br />$mError</td>
         </tr>
      </table>";
         return $ret;
      }
   }
   function sentriesbyweektable($caption,$weekno,$year) {
      global $mMySched;
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = EnumerateSentriesWSomeSiteAndCustInfoForWeek($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$weekno,$year,"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">$caption</td>
         </tr>
         <tr>
            <td class=\"project\">problem ($sRet) while getting info for schedule entries:<br />$mError</td>
         </tr>
      </table>";
         return $ret;
      }
      //previoussentrymonth($monthno,$year,$sPrevmonth,$sPrevyear);
      //nextsentrymonth($monthno,$year,$sNextmonth,$sNextyear);
      $sPrevweek = $weekno - 1;
      $sNextweek = $weekno + 1;
      $sNextyear = $year;
      $sPrevyear = $year;
      if ($sPrevweek < 1) {
         $sPrevweek = 53;
         $sPrevyear = $year - 1;
      }
      if ($sNextweek > 53) {
         $sNextweek = 1;
         $sNextyear = $year + 1;
      }
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">
            <table width=\"100%\">
               <tr>
                  <td align=\"left\" class=\"heading\">

                  <a title=\"\" href=\"./sentry.php?sentrieslist=$sPrevweek&year=$sPrevyear\">&lt;</a>
                  <a href=\"./sentry.php?sentrieslist=$weekno&year=$year\">||</a>
                  <a href=\"./sentry.php?sentrieslist=$sNextweek&year=$sNextyear\">&gt;</a>
                  $caption
                  </td>
                  <td align=\"right\" class=\"heading\">
                 &nbsp;
                  </td>
               </tr>
            </table>
            </td>
         </tr>";
      $sLoopTimestamp = strtotime($year . "W" . sprintf("%02d",$weekno));
      for ($j = 0; $j < 7;$j++) {
         $ret .= "
         <tr>
            <td class=\"project\">";
         //add in the day of week

         $ret .= "<b><a href=\"./sentry.php?showday&month=" . date("n",$sLoopTimestamp) . "&day=" . date("j",$sLoopTimestamp) . "&year=" . date("Y",$sLoopTimestamp) . "\">" . date("D n/d",$sLoopTimestamp) . "</a></b> ";
         $sRet = EnumerateSentriesWSomeSiteAndCustInfoForDay(
            $mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,
            date("n",$sLoopTimestamp),date("Y",$sLoopTimestamp),date("j",$sLoopTimestamp),"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,
            $sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type
         );
         if ($sRet != 0) {
            global $mError;
            $ret .= " error $sRet: $mError";
            //return $ret;
         }

         if ($sCount == 0) {
            $ret .= "<br />&nbsp;";
         }
         /*else
         if ($sCount == 1) {
            $ret .= "1 job";
         } else
         $ret .= " $sCount jobs";
         */
         $ret .= "<br />
         ";
         for ($i = 0; $i < $sCount; $i++) {
            $ret .= "
            <div class=\"weekly\"><a style=\"color:black;\" href=\"./sentry.php?show=" . $sSentryKey[$i] . "\">
            <span class=\"weekly_entrytype_" . str_replace(" ","_",$sSentrytype[$i]) . "\">" . $sSentrytype[$i] . "</span>
            " . $sCust_name[$i] . " ";
            if ($sPriphone_num[$i] != "") {
               $ret .= "(" . $sPriphone_num[$i] . ")";
            }
            $ret .= "</a></div>
            ";
         }
         $ret .= "<a href=\"./sentry.php?addwprocess&cancel=showsentries&month=" . date("n",$sLoopTimestamp) . "&day=" . date("j",$sLoopTimestamp) . "&year=" . date("Y",$sLoopTimestamp) . "\">[new]</a>
            </td>
         </tr>
         ";
         $sLoopTimestamp = $sLoopTimestamp + 86400;
      }
      $ret .= "
         <tr>
            <td class=\"heading\">
            <table width=\"100%\">
               <tr>
                  <td align=\"left\" class=\"heading\">

                  <a title=\"\" href=\"./sentry.php?sentrieslist=$sPrevweek&year=$sPrevyear\">&lt;</a>
                  <a href=\"./sentry.php?sentrieslist=$weekno&year=$year\">||</a>
                  <a href=\"./sentry.php?sentrieslist=$sNextweek&year=$sNextyear\">&gt;</a>
                  $caption
                  </td>
               </tr>
            </table>
            </td>
         </tr>
      </table>";
      return $ret;
   }
   function sentriesbymonthtable_old($caption,$monthno,$year) {
      global $mMySched;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $sRet = EnumerateSentriesWSomeSiteAndCustInfo($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$customerphone_table,$monthno,$year,"","",$sCount,$sSentryKey,$sHeading,$sStartdate,$sStarttime,$sSentrytype,$sSupervisor_name,$sCust_name,$sCust_phone,$sSite_streetaddr,$sSite_city,$sSite_state,$sSite_zip,$sPriphone_num,$sPriphone_type);
      if ($sRet != 0) {
         global $mError;
         $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">$caption</td>
         </tr>
         <tr>
            <td class=\"project\">problem ($sRet) while getting info for schedule entries:<br />$mError</td>
         </tr>
      </table>";
         return $ret;
      }
      previoussentrymonth($monthno,$year,$sPrevmonth,$sPrevyear);
      nextsentrymonth($monthno,$year,$sNextmonth,$sNextyear);
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">
            <table width=\"100%\">
               <tr>
                  <td align=\"left\" class=\"heading\">
                  $caption
                  </td>
                  <td align=\"right\" class=\"heading\">
                  <b>
                  <a title=\"\" href=\"./sentry.php?sentrieslist=$sPrevmonth&year=$sPrevyear\">&lt;</a>
                  <a href=\"./sentry.php?sentrieslist=$monthno&year=$year\">||</a>
                  <a href=\"./sentry.php?sentrieslist=$sNextmonth&year=$sNextyear\">&gt;</a>
                  </b>
                  </td>
               </tr>
            </table>
            </td>
         </tr>";
      for($i = 0;$i < $sCount;$i++) {
         //array explode ( string separator, string string [, int limit] )
         $sStartdatep = explode("-",$sStartdate[$i]);
         $ret .= "
         <tr>
            <td class=\"project\">
            <a href=\"./sentry.php?showday&month=" . $sStartdatep[1] . "&day=" . $sStartdatep[2] . "&year=" . $sStartdatep[0] . "\">" . $sStartdate[$i] . "</a><br />
            <b>" . $sSentrytype[$i] . ": " . $sHeading[$i] . "</b><br />
            " . $sCust_name[$i] . "; " . $sSite_streetaddr[$i] . ", " . $sSite_city[$i] . ", " . $sSite_state[$i] . " " . $sSite_zip[$i] . "<br />
            <b>crew:</b> " . $sSupervisor_name[$i] . "<br />
            <b>" . $sPriphone_type[$i] . "</b> " . $sPriphone_num[$i] . "<br />
            <a href=\"./sentry.php?show=" . $sSentryKey[$i] . "\">[view]</a>
            </td>
         </tr>";
      }
      $ret .= "
      </table>";
      return $ret;
   }
   function addsentryvarerrmsg(&$pErrArea,$Area,$Msg,$Delimiter) {
      if (isset($pErrArea[$Area])) {
         $pErrArea[$Area] .= $Delimiter;
      } else {
         $pErrArea[$Area] = "";
      }
      $pErrArea[$Area] .= $Msg;
   }
   function testsentryvars(&$pErrArea) {
      $pErrArea = array();
      $sNoErrors = TRUE;
      global $mSentry_sentrytype,$mSentry_heading,$mSentry_supervisorkey,$mSentry_notes,$mSentry_startdate,$mSentry_starttime;
      /*
      if ($mSentry_heading == "") {
         addsentryvarerrmsg($pErrArea,"heading","caption cannot be blank",", ");
         $sNoErrors = FALSE;
      }
      */
      if ($mSentry_startdate == "") {
         addsentryvarerrmsg($pErrArea,"startdate","start date cannot be blank",", ");
         $sNoErrors = FALSE;
      } else {
         $sYYYY = 0; $sMM = 0; $sDD = 0;
         
         $sDateparts = explode ( "-" , $mSentry_startdate);
         
         //list($sYYYY,$sMM,$sDD) = split("-", $mSentry_startdate);
         if (count($sDateparts) == 3)
            list($sYYYY,$sMM,$sDD) = $sDateparts;
         $sCheckDate = TRUE;
         if (!is_numeric($sYYYY)) {
            addsentryvarerrmsg($pErrArea,"startdate","start year should be numeric",", ");
            $sNoErrors = FALSE;
            $sCheckDate = FALSE;
         }
         if (!is_numeric($sMM)) {
            addsentryvarerrmsg($pErrArea,"startdate","start month should be numeric",", ");
            $sNoErrors = FALSE;
            $sCheckDate = FALSE;
         }
         if (!is_numeric($sDD)) {
            addsentryvarerrmsg($pErrArea,"startdate","start day of month should be numeric",", ");
            $sNoErrors = FALSE;
            $sCheckDate = FALSE;
         }
         if ($sCheckDate) {
            if (!checkdate($sMM,$sDD,$sYYYY)) {
               addsentryvarerrmsg($pErrArea,"startdate",
                  "start date is not a real date",", ");
               $sNoErrors = FALSE;
            }
         }
         if (empty($_POST["startampm"])) {
            addsentryvarerrmsg($pErrArea,"startdate",
                  "must select AM or PM",", ");
            $mSentry_starttime = "";
            $sNoErrors = FALSE;
         }
      }
      return $sNoErrors;
   }
   function globalizesentryformpostvars() {
      global $mSentry_sentrytype,$mSentry_heading,$mSentry_supervisorkey,$mSentry_notes,$mSentry_startdate,$mSentry_weekdayrepeat,$mSentry_starttime;
      if (isset($_POST["sentrytype"])) {
         $mSentry_sentrytype = $_POST["sentrytype"];
      }
      if (isset($_POST["heading"])) {
         $mSentry_heading = $_POST["heading"];
      }
      if (isset($_POST["supervisorkey"])) {
         $mSentry_supervisorkey = $_POST["supervisorkey"];
      }
      if (isset($_POST["notes"])) {
         $mSentry_notes = $_POST["notes"];
      }
      if (isset($_POST["startdate"])) {
         $mSentry_startdate = $_POST["startdate"];
      }
      if (isset($_POST["starttime"])) {
         $mSentry_starttime = $_POST["starttime"];
      }
      if ((isset($_POST["starthour"])) && (isset($_POST["startmin"])) && (isset($_POST["startampm"]))) {
         //concat them to (HH:MM:SS)mil
         //date ( string format [, int timestamp] )
         //mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
         $sSentryTime = strtotime ($_POST["starthour"] . ":" . $_POST["startmin"] . " " . $_POST["startampm"]);
         $mSentry_starttime = date("H:i:00",$sSentryTime);
      }
      if (isset($_POST["weekdayrepeat"])) {
         $mSentry_weekdayrepeat = $_POST["weekdayrepeat"];
      }
   }

   function sentrynoticetable($notice) {
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"project\">$notice</td>
         </tr>
      </table>";
      return $ret;
   }
   function existingcustomerformtable($action,$width,$submitcaption) {
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\"><b>use existing customer</b></td>
         </tr>
         <tr>
            <td class=\"project\">
            <form method=\"POST\" action=\"$action\">
            <table width=\"100%\">
               <tr>
                  <td>
                  <input type=\"submit\" name=\"existingcustomer\" value=\"$submitcaption\">
                  </td>
               </tr>
            </table>
            </form>
            </td>
         </tr>
      </table>";
      return $ret;
   }
   function sentrytable($width,$caption) {
      global $mSentry_sentrytype,$mSentry_heading,$mSentry_supervisorkey,$mSentry_notes,$mSentry_startdate,$mSentry_starttime;
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\">$caption</td>
         </tr>
         <tr>
            <td class=\"project\">$mSentry_heading:$mSentry_sentrytype</td>
         </tr>
         <tr>
            <td class=\"project\">$mSentry_startdate,$mSentry_starttime</td>
         </tr>
      </table>";
      return $ret;
   }
   function editsentryformtable($action,$width) {
      global $mMySched;
      global $mSentry_sentrytype,$mSentry_heading,$mSentry_supervisorkey,$mSentry_notes,$mSentry_startdate,$mSentry_starttime;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      $ret = "";
      return $ret;
   }
   function enumeratetimes($StartHour,&$pHour,&$pMin) {
      $pHour = array(); $pMin = array();
      if ($StartHour < 10) {
         $pHour[0] = "0$StartHour";
      } else {
         $pHour[0] = "$StartHour";
      }
      //$pHour[0] = $StartHour;
      $StartHour++;
      for ($i = 1;$i < 12;$i++) {
         if ($StartHour > 12) {
            $StartHour = 1;
         }
         if ($StartHour < 10) {
            $pHour[$i] = "0$StartHour";
         } else {
            $pHour[$i] = "$StartHour";
         }
         $StartHour++;
      }
      for ($i = 0;$i < 60;$i++) {
         if ($i < 10) {
            $pMin[$i] = "0$i";
         } else {
            $pMin[$i] = "$i";
         }
      }
      return 0;
   }
   function addsentryformtable($action,$width,$submitcaption,$caption,$cancelnav) {
      global $mMySched; //the resource handler for this function
      global $mAddSentryerr;
      global $mSentry_sentrytype,$mSentry_heading,$mSentry_supervisorkey,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_weekdayrepeat,$mSentry_key;
      //require("settings.php");
      foreach((new config("tables"))->getAssoc() as $k=>$v) $$k=$v;
      global $mShowYear,$mShowMonth,$mShowDay;
      if (($mShowYear != "") && ($mShowMonth != "") && ($mShowDay != "")) {
         $sShowStamp = mktime(0,0,0,$mShowMonth,$mShowDay,$mShowYear);
         if (($sShowStamp === FALSE) || ($sShowStamp == -1)) {
            global $mNotice;
            $mNotice .= "supplied date is invalid";
         } else {
            if ($mSentry_startdate == "") {
               //YYYY-MM-DD
               $mSentry_startdate = date("Y-m-d",$sShowStamp);
            }
         }
      }
      $ret = "
      <table width=\"300\">
         <tr>
            <td >
            <form method=\"POST\" action=\"$action\">
            <table width=\"100%\">
            <tr>
               <td class=\"heading\">
               <div style=\"font-family:Tahoma;font-size:11pt;vertical-align:top;float:left;\">
               $caption</div>
               <div style=\"font-family:Tahoma;font-size:11pt;vertical-align:top;float:right;\">";
      if (($mSentry_key != "") && ($mSentry_key > 0)) {
         $ret .= "
               <a title=\"delist this entry from the schedule\" href=\"./sentry.php?delist=$mSentry_key\">[delist]</a>
               ";
      }
      $ret .= "
               </div>
               </td>
            </tr>
            <tr>
               <td class=\"project\">";
      $sRet = EnumerateSentrytypes($mMySched,$sentrytype_table,$sCount,$sName,$sBrief,$sDescription);
      if ($sRet == 0) {
         if ($sCount > 0) {
            //name=sentrytype
            $sSelectedIdx = 0;
            for ($i = 0;$i < $sCount;$i++) {
               if ($sName[$i] == $mSentry_sentrytype) {
                  $sSelectedIdx = $i;
               }
            }
            for ($i = 0;$i < $sCount;$i++) {
               $ret .= "
               <span class=\"labeling\" style=\"white-space: nowrap;\" title=\"" . $sDescription[$i] . "\"><label for=\"sentrytype_" . $sName[$i] . "\"><input type=\"radio\" name=\"sentrytype\" id=\"sentrytype_" . $sName[$i] . "\" value=\"" . $sName[$i] . "\"";
               if ($i == $sSelectedIdx) {
                  $ret .= " checked";
               }
               $ret .= ">" . str_replace(' ','&nbsp;',$sBrief[$i]) . "</label></span>";
            }
         } else {
            $ret .= "no schedule entry types found";
         }
      } else {
         global $mError;
         $ret .= "problem ($sRet) while getting sentry types:<br />$mError";
      }
      $ret .= "
               </td>
            </tr>
            <tr>
               <td class=\"project\">
               <div class=\"labeling\" title=\"typically a brief one word description\">description</span>";
      if (isset($mAddSentryerr["heading"])) {
         $ret .= " <b>" . $mAddSentryerr["heading"] . "</b>";
      }
      $ret .= "</div>
               <span class=\"labeling\" title=\"typically a brief one word description\"><input size=\"25\" type=\"text\" name=\"heading\" value=\"$mSentry_heading\"></span>
               </td>
            </tr>
            <tr>
               <td class=\"project\">
               <table>
                  <tr>
                     <td valign=\"top\">
               <span title=\"the date of this entry in YYYY-MM-DD format\">date</span>";
      $ret .= "<br />
               <input title=\"the date of this entry in YYYY-MM-DD format\" type=\"text\" size=\"30\" name=\"startdate\" value=\"$mSentry_startdate\">";
      $ret .= "
                     </td>
                     <td valign=\"top\">
                     </td>
                     </tr>
                     <tr>
                     <td class=\"project\">
               <label title=\"the time this entry is supposed to start\">start time<br /></label>";
      //<input type=\"text\" size=\"30\" name=\"starttime\" value=\"$mSentry_starttime\">
      if ($mSentry_starttime != "") {
      //mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
         //split ( string pattern, string string [, int limit] )
         $sSentryTParts = explode(":",$mSentry_starttime);

         $sExistStamp = mktime($sSentryTParts[0],$sSentryTParts[1]);
         $sExistHour = date("h",$sExistStamp);
         $sExistMin = $sSentryTParts[1];
         $sExistAMPM = date("a",$sExistStamp);
         global $mNotice;
         $mNotice .= "hour=$sExistHour,min=$sExistMin,ampm=$sExistAMPM";
      }
      $ret .= "   <select name=\"starthour\">";
      $sRet = enumeratetimes(7,$sHour,$sMin);
      foreach($sHour as $val) {
         $ret .= "
                     <option value=\"$val\"";
         if (isset($sExistHour))
         if ($sExistHour ==  $val) {
            $ret .= " selected";
         }
         $ret .= ">$val</option>";
      }
      $ret .= "
                  </select>&nbsp;:&nbsp;<select name=\"startmin\">";
      foreach($sMin as $val) {
         $ret .= "
                     <option value=\"$val\"";
         if (isset($sExistMin))
         if ($sExistMin == $val) {
            $ret .= " selected";
         }
         $ret .= ">$val</option>";
      }
      $ret .= "
                  </select>
                  <select name=\"startampm\">";
      $ret .= "
                        <option value=\"\"";
      if (!isset($sExistAMPM)) {
         $ret .= " selected";
      }
      $ret .= "></option>
      ";
      $ret .= "
                     <option value=\"am\"";
      if (isset($sExistAMPM))
      if ($sExistAMPM == "am") {
         $ret .= " selected";
      }
      $ret .= ">am</option>
                     <option value=\"pm\"";
      if (isset($sExistAMPM))
      if ($sExistAMPM == "pm") {
         $ret .= " selected";
      }
      $ret .= ">pm</option>
                  </select>
                     </td>";
      /*
      $ret .= "
                     <td valign=\"top\">
               <label title=\"the number of weekdays this entry will repeat\">days<br />
               <input title=\"the number of weekdays this entry will repeat\" type=\"text\" size=\"2\" name=\"weekdayrepeat\" value=\"$mSentry_weekdayrepeat\"></label>
                     </td>";
      */
      $ret .= "
                   </tr>
               </table>";
      if (isset($mAddSentryerr["startdate"])) {
         $ret .= "<b>" . $mAddSentryerr["startdate"] . "</b>";
      }
      $ret .= "
               </td>
            </tr>
            <tr>
               <td class=\"project\">
               <table>
                  <tr>
                     <td valign=\"top\">
               <b>crew</b><br />";
      $sRet = EnumerateSupervisors($mMySched,$supervisor_table,$sCount,$sKey,$sName,$sFirst,$sLast);
      if ($sRet == 0) {
         if ($sCount > 0) {
            $ret .= "
               <select name=\"supervisorkey\" size=\"5\">";
            $sSelected = FALSE;
            if ($mSentry_supervisorkey != "") {
               $sSelected = $mSentry_supervisorkey;
            }
            for ($i = 0;$i < $sCount;$i++) {
               $ret .= "
                  <option value=\"" . $sKey[$i] . "\"";
               if ($sSelected !== FALSE) {
                  if ($sKey[$i] == $sSelected) {
                     $ret .= " selected";
                  }
               } else {
                  if ($i == 0) {
                     $ret .= " selected";
                  }
               }
               $ret .= ">" . $sFirst[$i] . " " . $sLast[$i] . "</option>";
            }
            $ret .= "
               </select>";
         } else {
            $ret .= "no supervisors found";
         }
      } else {
         global $mError;
         $ret .= "problem ($sRet) while getting supervisors:<br />$mError";
      }
      $ret .= "
                     </td>
                     <!-- do not display the 'notes' section anymore b/c Linda messes it up-->
                     <td valign=\"top\" style=\"display:none;\">
               <b>notes:</b><br />
               <textarea  cols=\"10\" rows=\"6\" name=\"notes\">$mSentry_notes</textarea>
                     </td>
                  </tr>
               </table>
               </td>
            </tr>
            <tr>
               <td class=\"project\">
               <input type=\"submit\" name=\"add_sentry\" value=\"$submitcaption\">
               <b><a href=\"$cancelnav\">[cancel]</a></b>
               </td>
            </tr>
            </table>
            </form>
            </td>
         </tr>
      </table>";
      return $ret;
   }

