<?php

namespace clientcal;

   function enumcustomerconfig() {
      $config = [];
      
      $config=array_merge($config,(new config('tables'))->getAssoc());
      
      $config=array_merge($config,(new config('customer'))->getAssoc());
      
      return $config;
   }
   function customerjobs() {
      global $mCust_key,$mMySched;

      //GetCustomer($My,$TableCust,$CustKey,$pName,$pStreetaddr,$pCity,$pState,$pZip,$pCustomertype,$pLastUpdated)
      if (0 != ($sRet =
      GetCustomer
      (
         $mMySched,
         "customer",
         $mCust_key,
         $sCustName,
         $sStreetaddr,
         $sCity,
         $sState,
         $sZip,
         $sCustomertype,
         $sLastUpdated
      )
      ))
      { /*--begin if error on custinfo--*/
         global $mNotice;
         $mNotice .= "
         <br>problem ($sRet) in _customerjobs while Customer Info:<br>
         $mError<br>";
         return "error (admin notice)";
      } /*--end if error on custinfo--*/

      if (0 != ($sRet =
      GetCustomerPrimaryPhone
      (
         $mMySched,
         $mCust_key,
         $sPhonenumber,
         $sPhonetype
      )
      ))
      { /*--begin if error on priphone--*/
         global $mNotice;
         $mNotice .= "
         <br>problem ($sRet) in _customerjobs while Enumerating:<br>
         $mError<br>";
         return "error (admin notice)";
      } /*--end if error on priphone--*/


      if (0 != ($sRet =
      EnumerateCustomerJobs
      (
         $mMySched,
         $mCust_key,
         $sCount,
         $sSentryKey,
         $sHeading,
         $sStartdate,
         $sSentrytype
      )
      ))
      { /*--begin if error on enumjobs--*/
         global $mNotice;
         $mNotice .= "
         <br>problem ($sRet) in _customerjobs while Enumerating:<br>
         $mError<br>";
         return "error (admin notice)";
      } /*--begin if error on enumjobs--*/
      //add style sheet
      $ret = "
<style>
   .sjdheading {
      background:#c0c0c0;
      font-weight:bold;
      color:black;
   }
   .sjdinfo {
      background:#cccccc;
      color:black;
      font-weight:normal;
      margin-top:2px;
   }
   .sjdres {
      background:#cccccc;
      color:black;
      font-weight:normal;
      text-decoration:none;
      padding-top:2px;
      padding-left:2px;
   }
   a.sjdres {
      color:#0000ff;
   }
   a.sjdres:hover {
      text-decoration:underline;
   }
</style>";
/*
//$sSentryKey,$sHeading,$sStartdate,$sSentrytype
 */
      //$sCustName,$sPhonenumber,$sPhonetype
      $sInfo = "";
      if ($sCount > 0) {
         if ($sCount > 1) { /*--begin if 2 or more--*/
            $sInfo .= "$sCount jobs";
         } /*--end if 2 or more--*/
         else { /*--begin if 1--*/
            $sInfo .= "1 job";
         } /*--end if 1--*/
      }
      else { /*--begin if 0--*/
         $sInfo .= "no jobs";
      } /*--end if 0--*/
      $ret .= "
<div style=\"width:300px;\"><!--begin display box for search display-->
         <div class=\"sjdheading\"><!--begin heading for search display-->
$sCustName:<span style=\"font-weight:normal;font-style:italic;\">$sInfo"
. "</span><br>"
. "<span style=\"font-weight:normal;font-size:8pt;\">$sPhonetype phone:" .
FormatCustomerPhone($sPhonenumber) . "</span><br>
         </div><!--end heading for search display-->";
      $sLastYear = 1;
      for ($i = 0;$i < $sCount;$i++) { /*--begin result loop--*/
         $sDate = explode("-",$sStartdate[$i]);
         //hr,min,sec,mon,day,yea
         $sStamp = mktime(0,0,0,$sDate[1],$sDate[2],$sDate[0]);

         $ret .= "
<div class=\"sjdres\"><!--begin search result entry-->
";
         if ($sLastYear != date("Y",$sStamp)) {
            $sLastYear = date("Y",$sStamp);
            $ret .= "<b>" . date("Y",$sStamp) . "&nbsp;</b>";
         }
         //<a class=\"sjdres\" href=\"./sentry.php?show=$sSentryKey[$i]\">
         $ret .= "
<style>
   a.sjdent {
      text-decoration:none;
      color:black;
   }
</style>
<div class=\"weekly\">"
. "<a class=\"sjdent\" href=\"./sentry.php?show=$sSentryKey[$i]\">"
. "<div style=\"padding:0px;margin:0px;\"><div style=\"float:left;width:5em;\" class=\"weekly_entrytype_" . str_replace(" ","_",$sSentrytype[$i]) . "\">&nbsp;$sSentrytype[$i]</div></div>"
. "&nbsp;" . date("M j",$sStamp)
. ":<span style=\"font-size:7pt;\">"

. "</span></div>"
. " $sHeading[$i]";
         $ret .= "
   </a>
</div><!--end search result entry-->";
      } /*--end result loop--*/

      $ret .= "
</div><!--end display box for search display-->";
      return $ret;

   } /*--end function customerjobs--*/

   function getcustomerjobsvars() {
      global $mCust_key;
      if (isset($_GET["jobs"])) {
         $mCust_key = $_GET["jobs"];
      }
   }
   function customersearch() {
      global $mCust_q;
      $ret = "";
      $ret .= "
<style>
   .custq {
      border:solid black 1px;
      font-size:8pt;
   }
</style>
<form method=\"GET\" action=\"./customer.php?search\"
style=\"
margin:0;
display:inline;
padding:0;
font-size:8pt;
\"

>


<input type=\"text\" name=\"q\"
   size=\"30\"
   value=\"$mCust_q\"
   class=\"custq\"
>
<input type=\"submit\" name=\"search\" value=\"search\"
class=\"custq\"
>
</form>
";
      return $ret;
   }
   function addcustomervarerrmsg($pErrArea,$Area,$Msg) {
      if (isset($pErrArea[$Area])) {
         $pErrArea[$Area] .= ", ";
      } else {
         $pErrArea[$Area] = "";
      }
      $pErrArea[$Area] .= $Msg;
   }
   function customeralphaforlink($letter,$next) {
      global $mMySched;
      global $mSentry_key;
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      $sRet = EnumerateCustomerWPriPhoneByAlpha($mMySched,"customer","customer_phone",$letter,$sCount,$sCustKey,$sName,$sStreetaddr,$sCity,$sState,$sZip,$sCusttype,$sPriphonetype,$sPriphone,$sLastUpdated);
      if ($sRet != 0) {
         global $mError;
         $ret = "problem ($sRet) getting customer list:<br />$mError";
         return $ret;
      }
      $ret = "
      <div style=\"width:300px;white-space:normal;\">
         <div style=\"background:#CCCCCC;font-weight:bold;white-space:normal;\">";
         for ($i = 65;$i < 91;$i++) {
            if ($letter == chr($i)) {
               $ret .= "[" . chr($i) . "] ";
            } else {
               $ret .= "<a href=\"./sentry.php?linkexistingcustomer=$mSentry_key&alpha=" . chr($i) . "\">[" . chr($i) . "]</a> ";
            }
         }
      $ret .= "
         </div>";
      if ($sCount == 0) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">no customers found under $letter</div>";
      } else
      if ($sCount == 1) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">1 customer found under $letter</div>";
      } else
      if ($sCount > 1) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">$sCount customers found under $letter</div>";
      }

      if ($sCount > 0) {
         for ($i = 0;$i < $sCount;$i++) {
            $ret .= "
            <div style=\"background:#CCCCCC;margin-top:2px;\">
            <span style=\"font-weight:bold;\">{$sName[$i]}:</span> {$sPriphone[$i]} {$sPriphonetype[$i]}<br />
            {$sCity[$i]}, {$sState[$i]}&nbsp;<a href=\"./sentry.php?next=$next&sentry=$mSentry_key&linkcustomer=" . $sCustKey[$i] . "\">[link]</a>
            </div>
            ";
         }
      }
      $ret .= "
      </div>";
      return $ret;
   }

   function customerinfo_area($custkey,$name,$phone,$phonetype,$city,$state) {
      $ret = "";
         $ret .= "
<a href=\"./customer.php?edit=" . $custkey . "\" class=\"sqdres\"><span style=\"color:blue;font-weight:bold;\">"
. FormatCustomerName($name) . "</span></a>: "
. "<span style=\"font-weight:normal;\">"
. FormatCustomerPhone($phone)
. " $phonetype</span><br>
<span style=\"font-weight:normal;\">
&nbsp;" . ucwords(strtolower($city)) . "
" . ucwords($state) . "
<a href=\"./customer.php?jobs=$custkey\">[jobs]</a>
</span>
";
      return $ret;
   }

   function customersearch_exact($term,$existing_ckeys) {

      global $mMySched;
      $ret = "";
      //see if query is exact match
      $sRet = SearchQCustomerExact(
         $mMySched,"customer",$term,
         $sCount,
         $sCustKey,
         $sName,
         $sCusttype,
         $sPhone,
         $sPhonetype,
         $sAddr,
         $sCity,
         $sState,
         $sZip
      );
      if ($sRet != 0) {
         global $mNotice, $mError;
         $mNotice .= $mError;
         return false;
      }

      //global $mNotice;
      //$mNotice .= "asdf";

      $ret .= "
<div style=\"width:300px;\"><!--begin display box for search display-->
         <div class=\"sqdheading\"><!--begin heading for search display-->
search results<br>";
//<span style=\"font-weight:normal;\">query: '" . $term . "'</span>
      $ret .= customersearch();
      $ret .= "
         </div><!--end heading for search display-->";
      $sCountinfo = "$sCount exact matches:";
      if ($sCount < 1) {
         $sCountinfo = "no exact matches:";
      }
      if ($sCount == 1) {
         $sCountinfo = "one exact match:";
      }
      /*
      $ret .= "
         <div class=\"sqdinfo\"><!--begin info for search display-->
      <span style=\"font-weight:bold;\">$sCountinfo</span><br>
         </div><!--end info for search display-->
      ";*/
//         $sCustKey,$sName,$sCusttype,$sPhone,
//         $sPhonetype,$sAddr,$sCity,$sState,$sZip
      $sCKey = array();
      for ($i = 0; $i < $sCount; $i++) { /*--begin for Exact Match--*/
         $existing_ckeys[] = $sCustKey[$i];
         $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->

";
         $ret .= customerinfo_area(
         $sCustKey[$i],$sName[$i],$sPhone[$i],$sPhonetype[$i],$sCity[$i],$sState[$i]
         );
         $ret .= "

</div><!--end search result entry-->
";
      } /*--end Exact Match loop--*/
      return $ret;
   }

   function customersearch_startwords($term,$existing_ckeys) {
      $ret = "";
      global $mMySched;
      //SearchQCustomerCountainWords
      $sRet = SearchQCustomerStartWords(
         $mMySched,"customer",$term,
         $sCount,
         $sCustKey,
         $sName,
         $sCusttype,
         $sPhone,
         $sPhonetype,
         $sAddr,
         $sCity,
         $sState,
         $sZip
      );
      $sCountinfo = "$sCount names start with:";
      if ($sCount < 1) {
         $sCountinfo = "no names start with:";
      }
      if ($sCount == 1) {
         $sCountinfo = "one name starts with:";
      }

      /*
      $sCountinfo = "starting with";

      $ret .= "
         <div class=\"sqdinfo\"><!--begin info for search display-->
      <span style=\"font-size:8pt;font-weight:normal;\">$sCountinfo</span><br>
         </div><!--end info for search display-->
      ";
      */
      $iDupe = 0;
      for ($i = 0; $i < $sCount; $i++) { /*--begin for Word Exact--*/

         if ( ! in_array  ( $sCustKey[$i] , $existing_ckeys)) { /*--begin if not dupe--*/

         $existing_ckeys[] = $sCustKey[$i];
         $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->

";
         //<a href=\"./customer.php?edit=" . $sCustKey[$i] . "\" class=\"sqdres\">
         $ret .= customerinfo_area(
         $sCustKey[$i],$sName[$i],$sPhone[$i],$sPhonetype[$i],$sCity[$i],$sState[$i]
         );
         $ret .= "

</div><!--end search result entry-->
";
         } /*--end if not dupe--*/ else {
            $iDupe++;
         } /*-- end if dupe--*/

      } /*--end for Word Exact--*/


      if  ($iDupe > 0) { /*--being Word Exact dupe info--*/
         $sInfo = "";
         if ($sCount > 0) {
            $sInfo .= "...and ";
         }
         $sInfo .= "$iDupe others already listed above";
         if ($iDupe == 1) {
            $sInfo = "...already listed above";
         }/*
         $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->
<span style=\"font-weight:normal;font-style:italic;\">$sInfo</span>
</div><!--end search result entry-->
";*/
      } /*--end Word Exact dupe info--*/
      return $ret;
   }

   function customersearch_contains($term,$existing_ckeys) {

      global $mMySched;
      $ret = "";
      //SearchQCustomerContains
      $sRet = SearchQCustomerContains(
         $mMySched,"customer",$term,
         $sCount,
         $sCustKey,
         $sName,
         $sCusttype,
         $sPhone,
         $sPhonetype,
         $sAddr,
         $sCity,
         $sState,
         $sZip
      );
      /*
      $sCountinfo = "$sCount names contain:";
      if ($sCount < 1) {
         $sCountinfo = "no names contain:";
      }
      if ($sCount == 1) {
         $sCountinfo = "one name contains:";
      }
      $ret .= "
         <div class=\"sqdinfo\"><!--begin info for search display-->
      <span style=\"font-weight:bold;\">$sCountinfo</span><br>
         </div><!--end info for search display-->
      ";*/
      $iDupe = 0;
      for ($i = 0; $i < $sCount; $i++) { /*--begin for Word Exact--*/

         if ( ! in_array  ( $sCustKey[$i] , $existing_ckeys)) { /*--begin if not dupe--*/

         $existing_ckeys[] = $sCustKey[$i];
         $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->

";
         $ret .= customerinfo_area(
         $sCustKey[$i],$sName[$i],$sPhone[$i],$sPhonetype[$i],$sCity[$i],$sState[$i]
         );
         $ret .= "

</div><!--end search result entry-->
";
         } /*--end if not dupe--*/ else {
            $iDupe++;
         } /*-- end if dupe--*/

      } /*--end for Word Exact--*/


      if  ($iDupe > 0) { /*--being Word Exact dupe info--*/
         $sInfo = "";
         if ($sCount > 0) {
            $sInfo .= "...and ";
         }
         $sInfo .= "$iDupe others already listed above";
         if ($iDupe == 1) {
            $sInfo = "...already listed above";
         }/*
         $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->
<span style=\"font-weight:normal;font-style:italic;\">$sInfo</span>
</div><!--end search result entry-->
";*/
      } /*--end Word Exact dupe info--*/

      return $ret;
   }

   function customersearchresult() {
      global $mMySched;
      global $mCust_q;



      //add style sheet
      $ret = "
<style>
   .sqdheading {
      background:#c0c0c0;
      font-weight:bold;
      color:black;
   }
   .sqdinfo {
      background:#cccccc;
      color:black;
      font-weight:normal;
      margin-top:2px;
   }
   .sqdres {
      background:#cccccc;
      color:black;
      font-weight:normal;
      text-decoration:none;
      margin-top:2px;
      padding-left:2px;
   }
   a.sqdres:hover {
      text-decoration:underline;
   }
</style>";


      $sQuery = $mCust_q;

      //see if query is empty
      if ($mCust_q == "") {
         $sQuery = "alpha:a";
      }


      $sCkeys = array();

      $ret .= customersearch_exact($sQuery,$sCkeys);

      $ret .= customersearch_startwords($sQuery,$sCkeys);

      $ret .= customersearch_contains($sQuery,$sCkeys);







      $ret .= "
         </div><!--end display box for search display-->
      ";
      return $ret;
   }
   function getcustomersearchpostvars() {
      global $mCust_q,$mCust_valid;
      if (isset($_POST["q"])) {
         $mCust_valid = true;
         $mCust_q = $_POST["q"];
      } else
      if (isset($_GET["q"])) {
         $mCust_valid = true;
         $mCust_q = $_GET["q"];
      }
      return true;
   }

   function customeralpha($letter,$showsearch = false) {
      global $mMySched;
      $customer_table = "customer";$customerphone_table = "customer_phone";
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      $sRet = EnumerateCustomerWPriPhoneByAlpha($mMySched,$customer_table,$customerphone_table,$letter,$sCount,$sCustKey,$sName,$sStreetaddr,$sCity,$sState,$sZip,$sCusttype,$sPriphonetype,$sPriphone,$sLastUpdated);
      if ($sRet != 0) {
         global $mError;
         $ret = "problem ($sRet) getting customer list:<br />$mError";
         return $ret;
      }
//add style sheet
      $ret = "
<style>
   .sqdheading {
      background:#c0c0c0;
      font-weight:bold;
      color:black;
   }
   .sqdinfo {
      background:#cccccc;
      color:black;
      font-weight:normal;
      margin-top:2px;
   }
   .sqdres {
      background:#cccccc;
      color:black;
      font-weight:normal;
      text-decoration:none;
      margin-top:2px;
      padding-left:2px;
   }
   a.sqdres:hover {
      text-decoration:underline;
   }
</style>";
      $ret .= "
      <div style=\"width:300px;\">
         <div style=\"background:#CCCCCC;font-weight:bold;\">";


         for ($i = 65;$i < 91;$i++) {
            if ($letter == chr($i)) {
               $ret .= "[" . chr($i) . "] ";
            } else {
               $ret .= "<a href=\"./customer.php?alpha=" . chr($i) . "\">[" . chr($i) . "] </a>";
            }
         }
      if ($showsearch) {
         $ret .= customersearch() . "<br>";
      }
      $ret .= "
         </div>";
      if ($sCount == 0) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">no customers found under $letter</div>";
      } else
      if ($sCount == 1) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">1 customer found under $letter</div>";
      } else
      if ($sCount > 1) {
         $ret .= "
         <div style=\"background:#CCCCCC;font-weight:bold;\">$sCount customers found under $letter</div>";
      }
      /*
               $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->

";
         $ret .= customerinfo_area(
         $sCustKey[$i],$sName[$i],$sPhone[$i],$sPhonetype[$i],$sCity[$i],$sState[$i]
         );
         $ret .= "

</div><!--end search result entry-->
";*/
      if ($sCount > 0) {
         for ($i = 0;$i < $sCount;$i++) {
            $ret .= "
<div class=\"sqdres\"><!--begin search result entry-->";
            $ret .= customerinfo_area($sCustKey[$i],$sName[$i],$sPriphone[$i],$sPriphonetype[$i],$sCity[$i],$sState[$i]);
            $ret .= "
</div><!--end search result entry-->";
            /*
            $ret .= "
            <div style=\"background:#CCCCCC;margin-top:2px;\">
            <span style=\"font-weight:bold;\">{$sName[$i]}:</span> {$sPriphone[$i]} {$sPriphonetype[$i]}<br />
            {$sCity[$i]}, {$sState[$i]}&nbsp;<a href=\"./customer.php?edit={$sCustKey[$i]}\">[view]</a>
            </div>
            ";
            */
         }
      }
      $ret .= "
      </div>";
      return $ret;
   }
//    function formatcustomername() {
//       global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name;
//       $sName = "";
//       if ($mCust_nametype == "individual") {
//          $sName = "$mCust_lastname, $mCust_firstname";
//       }
//       if ($mCust_nametype == "company") {
//          $sName = $mCust_name;
//       }
//       return $sName;
//    }
   function updatecustomerprocess() {
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      global $mNotice;
      global $mCust_key;
      global $mMySched;
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      //see if any of the phone numbers differ from the database
      $sRet = EnumerateCustomerPhonenumbers($mMySched,$customerphone_table,$mCust_key,$sCount,$sPhonenumber,$sPhonetype);
      if ($sRet != 0) {
         global $mNotice,$mError;
         $mNotice .= "problem ($mRet) while getting customer info:<br />$mError<br />";
         return -1;
      }
      $sStor_hmphone = "";$sStor_wkphone = "";$sStor_mbphone = "";$sStor_fxphone = "";
      $sStorExist_hmphone = FALSE;$sStorExist_wkphone = FALSE;$sStorExist_mbphone = FALSE;$sStorExist_fxphone = FALSE;
      for($i = 0;$i < $sCount;$i++) {
         if ($sPhonetype[$i] == "hm") {
            $sStor_hmphone = $sPhonenumber[$i];
            $sStorExist_hmphone = TRUE;
         }
         if ($sPhonetype[$i] == "wk") {
            $sStor_wkphone = $sPhonenumber[$i];
            $sStorExist_wkphone = TRUE;
         }
         if ($sPhonetype[$i] == "mb") {
            $sStor_mbphone = $sPhonenumber[$i];
            $sStorExist_mbphone = TRUE;
         }
         if ($sPhonetype[$i] == "fx") {
            $sStor_fxphone = $sPhonenumber[$i];
            $sStorExist_fxphone = TRUE;
         }
      }
      //update hmphone
      if ($sStorExist_hmphone === TRUE) {
         if ($sStor_hmphone != $mCust_hmphone) {
            if ($mCust_hmphone == "") {
               //remove record
               if (0 != ($sRet = DeleteCustomerPhone($mMySched,$customerphone_table,$mCust_key,"hm"))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) removing customer hm phone:<br />$mError<br />";
                  return -1;
               }
            } else {
               if (0 != ($sRet = UpdateCustomerPhone($mMySched,$customerphone_table,$mCust_key,"hm",$mCust_hmphone))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) updating customer hm phone:<br />$mError<br />";
                  return -1;
               }
            }
         }
      } else {
         if ($mCust_hmphone != "") {
            if (0 != ($sRet = AddCustomerPhone($mMySched,$customerphone_table,$mCust_key,"hm",$mCust_hmphone))) {
               global $mError,$mNotice;
               $mNotice .= "problem ($sRet) adding customer hm phone:<br />$mError<br />";
               return -1;
            }
         }
      }
      //update wkphone
      if ($sStorExist_wkphone === TRUE) {
         if ($sStor_wkphone != $mCust_wkphone) {
            if ($mCust_wkphone == "") {
               //remove record
               if (0 != ($sRet = DeleteCustomerPhone($mMySched,$customerphone_table,$mCust_key,"wk"))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) removing customer wk phone:<br />$mError<br />";
                  return -1;
               }
            } else {
               if (0 != ($sRet = UpdateCustomerPhone($mMySched,$customerphone_table,$mCust_key,"wk",$mCust_wkphone))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) updating customer wk phone:<br />$mError<br />";
                  return -1;
               }
            }
         }
      } else {
         if ($mCust_wkphone != "") {
            if (0 != ($sRet = AddCustomerPhone($mMySched,$customerphone_table,$mCust_key,"wk",$mCust_wkphone))) {
               global $mError,$mNotice;
               $mNotice .= "problem ($sRet) adding customer wk phone:<br />$mError<br />";
               return -1;
            }
         }
      }
      //update mbphone
      if ($sStorExist_mbphone === TRUE) {
         if ($sStor_mbphone != $mCust_mbphone) {
            if ($mCust_mbphone == "") {
               //remove record
               if (0 != ($sRet = DeleteCustomerPhone($mMySched,$customerphone_table,$mCust_key,"mb"))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) removing customer mb phone:<br />$mError<br />";
                  return -1;
               }
            } else {
               if (0 != ($sRet = UpdateCustomerPhone($mMySched,$customerphone_table,$mCust_key,"mb",$mCust_mbphone))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) updating customer mb phone:<br />$mError<br />";
                  return -1;
               }
            }
         }
      } else {
         if ($mCust_mbphone != "") {
            if (0 != ($sRet = AddCustomerPhone($mMySched,$customerphone_table,$mCust_key,"mb",$mCust_mbphone))) {
               global $mError,$mNotice;
               $mNotice .= "problem ($sRet) adding customer mb phone:<br />$mError<br />";
               return -1;
            }
         }
      }
      //update fxphone
      if ($sStorExist_fxphone === TRUE) {
         if ($sStor_fxphone != $mCust_fxphone) {
            if ($mCust_fxphone == "") {
               //remove record
               if (0 != ($sRet = DeleteCustomerPhone($mMySched,$customerphone_table,$mCust_key,"fx"))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) removing customer fx phone:<br />$mError<br />";
                  return -1;
               }
            } else {
               if (0 != ($sRet = UpdateCustomerPhone($mMySched,$customerphone_table,$mCust_key,"fx",$mCust_fxphone))) {
                  global $mError,$mNotice;
                  $mNotice .= "problem ($sRet) updating customer fx phone:<br />$mError<br />";
                  return -1;
               }
            }
         }
      } else {
         if ($mCust_fxphone != "") {
            if (0 != ($sRet = AddCustomerPhone($mMySched,$customerphone_table,$mCust_key,"fx",$mCust_fxphone))) {
               global $mError,$mNotice;
               $mNotice .= "problem ($sRet) adding customer fx phone:<br />$mError<br />";
               return -1;
            }
         }
      }
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      //glean submitted name info
      $sSub_name = "";
      if ($mCust_nametype == "individual") {
         $sSub_name = "$mCust_lastname, $mCust_firstname";
      }
      if ($mCust_nametype == "company") {
         $sSub_name = $mCust_name;
      }
      //update name if changed
      if (0 != ($sRet = GetCustomerName($mMySched,$customer_table,$mCust_key,$sStor_name))) {
         global $mError,$mNotice;
         $mNotice .= "problem ($sRet) getting stored customer name:<br />$mError<br />";
         return -1;
      }
      if ($sStor_name != $sSub_name) {
         if (0 != ($sRet = UpdateCustomerName($mMySched,$customer_table,$mCust_key,$sSub_name))) {
            global $mError,$mNotice;
            $mNotice .= "problem ($sRet) updating customer name:<br />$mError<br />";
            return -1;
         }
      }
      //update name type if changed
      if (0 != ($sRet = GetCustomerType($mMySched,$customer_table,$mCust_key,$sStor_type))) {
         global $mError,$mNotice;
         $mNotice .= "problem ($sRet) getting stored customer type:<br />$mError<br />";
         return -1;
      }
      if ($sStor_type != $mCust_customertype) {
         if (0 != ($sRet = UpdateCustomerType($mMySched,$customer_table,$mCust_key,$mCust_customertype))) {
            global $mError,$mNotice;
            $mNotice .= "problem ($sRet) updating customer type:<br />$mError<br />";
            return -1;
         }
      }
      //update addr if changed
      if (0 != ($sRet = GetCustomerAddrByKey($mMySched,$customer_table,$mCust_key,$sStor_streetaddr,$sStor_city,$sStor_state,$sStor_zip))) {
         global $mError,$mNotice;
         $mNotice .= "problem ($sRet) getting stored customer address:<br />$mError<br />";
         return -1;
      }
      //$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      if (($sStor_streetaddr != $mCust_streetaddr) ||
         ($sStor_city != $mCust_city) ||
         ($sStor_state != $mCust_state) ||
         ($sStor_zip != $mCust_zip)) {
         if (0 != ($sRet = UpdateAddr($mMySched,$customer_table,$mCust_key,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip))) {
            global $mError,$mNotice;
            $mNotice .= "problem ($sRet) updating stored customer address:<br />$mError<br />";
            return -1;
         }
      }
      return 0;
   }
   function getcustomerprocess() {
      global $mCust_key;
      global $mMySched;
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      $mCust_lastname = "";$mCust_firstname = "";$mCust_name = "";
      $mRet = GetCustomer($mMySched,"customer",$mCust_key,$sName,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype,$sLastUpdated);
      if ($mRet != 0) {
         global $mNotice,$mError;
         $mNotice .= "problem ($mRet) while getting customer info:<br />$mError<br />";
         return -1;
      }
      //if there's a comma and a space.. then nametype is individual
      if (($pos = strpos($sName,", ")) !== FALSE) {
         $mCust_nametype = "individual";
         $mCust_lastname = substr($sName,0,$pos);
         $mCust_firstname = substr($sName,$pos + 2,strlen($sName) - ($pos + 2));
      } else {
         $mCust_nametype = "company";
         $mCust_name = $sName;
      }
      $mRet = EnumerateCustomerPhonenumbers($mMySched,$customerphone_table,$mCust_key,$sCount,$sPhonenumber,$sPhonetype);
      if ($mRet != 0) {
         global $mMode;
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while getting customer phone info:<br />$mError<br />";
         return -1;
      }
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      for($i = 0;$i < $sCount;$i++) {
         if ($sPhonetype[$i] == "hm") {
            $mCust_hmphone = $sPhonenumber[$i];
         }
         if ($sPhonetype[$i] == "wk") {
            $mCust_wkphone = $sPhonenumber[$i];
         }
         if ($sPhonetype[$i] == "mb") {
            $mCust_mbphone = $sPhonenumber[$i];
         }
         if ($sPhonetype[$i] == "fx") {
            $mCust_fxphone = $sPhonenumber[$i];
         }
      }
      return 0;
   }
   function testcustsomervars($pErrArea) {
      $pErrArea = array();
      $sNoErrors = TRUE;
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      if (($mCust_nametype != "company") && ($mCust_nametype != "individual")) {
         addcustomervarerrmsg($pErrArea,"name","invalid name type given");
         $sNoErrors = FALSE;
      }
      if ($mCust_nametype == "company") {
         if ($mCust_name == "") {
            addcustomervarerrmsg($pErrArea,"name","company name cannot be blank");
            $sNoErrors = FALSE;
         }
         if (strpos($mCust_name,",",0) !== FALSE) {
            addcustomervarerrmsg($pErrArea,"name","company name cannot contain a comma");
            $sNoErrors = FALSE;
         }
      }
      if ($mCust_nametype == "individual") {
         if ($mCust_lastname == "") {
            addcustomervarerrmsg($pErrArea,"name","last name cannot be blank");
            $sNoErrors = FALSE;
         }
         if (strpos($mCust_lastname,",",0) !== FALSE) {
            addcustomervarerrmsg($pErrArea,"name","last name cannot contain a comma");
            $sNoErrors = FALSE;
         }
      }
      if ($mCust_city == "") {
         addcustomervarerrmsg($pErrArea,"citystatezip","city cannot be blank");
         $sNoErrors = FALSE;
      }
      if ($mCust_state == "") {
         addcustomervarerrmsg($pErrArea,"citystatezip","state cannot be blank");
         $sNoErrors = FALSE;
      }
      if ($mCust_customertype == "") {
         addcustomervarerrmsg($pErrArea,"customertype","invalid customer type given");
         $sNoErrors = FALSE;
      }
      return $sNoErrors;
   }
   function globalizecustomerphonepostvars() {
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      if (isset($_POST["hmphone"])) {
         $mCust_hmphone = $_POST["hmphone"];
      }
      if (isset($_POST["wkphone"])) {
         $mCust_wkphone = $_POST["wkphone"];
      }
      if (isset($_POST["mbphone"])) {
         $mCust_mbphone= $_POST["mbphone"];
      }
      if (isset($_POST["fxphone"])) {
         $mCust_fxphone = $_POST["fxphone"];
      }
   }
   function processcustomerphonevars($pCount,$pType,$pNumber) {
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      $pCount = 0;
      $pType = array();$pNumber = array();
      if ($mCust_hmphone != "") {
         $pType[$pCount] = "hm";
         $pNumber[$pCount] = $mCust_hmphone;
         $pCount++;
      }
      if ($mCust_wkphone != "") {
         $pType[$pCount] = "wk";
         $pNumber[$pCount] = $mCust_wkphone;
         $pCount++;
      }
      if ($mCust_mbphone != "") {
         $pType[$pCount] = "mb";
         $pNumber[$pCount] = $mCust_mbphone;
         $pCount++;
      }
      if ($mCust_fxphone != "") {
         $pType[$pCount] = "fx";
         $pNumber[$pCount] = $mCust_fxphone;
         $pCount++;
      }
   }
   function globalizecustomerpostvars() {
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      if (isset($_POST["firstname"])) {
         $mCust_firstname = $_POST["firstname"];
      }
      if (isset($_POST["lastname"])) {
         $mCust_lastname = $_POST["lastname"];
      }
      if (isset($_POST["nametype"])) {
         $mCust_nametype = $_POST["nametype"];
      }
      if (isset($_POST["name"])) {
         $mCust_name = $_POST["name"];
      }
      if (isset($_POST["streetaddr"])) {
         $mCust_streetaddr = $_POST["streetaddr"];
      }
      if (isset($_POST["city"])) {
         $mCust_city = $_POST["city"];
      }
      if (isset($_POST["state"])) {
         $mCust_state = $_POST["state"];
      }
      if (isset($_POST["zip"])) {
         $mCust_zip = $_POST["zip"];
      }
      if (isset($_POST["customertype"])) {
         $mCust_customertype = $_POST["customertype"];
      }
   }
   function customernoticetable($notice) {
      $ret = "
      <table width=\"400\">
         <tr>
            <td class=\"project\">$notice</td>
         </tr>
      </table>";
      return $ret;
   }
   function customerlistforlink($caption,$next) {
      global $mMySched;
      global $mSentry_key;
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      $ret = "
      <table width=\"400\">
         <tr>
            <td class=\"heading\">$caption</td>
         </tr>
         ";
      $sRet = EnumerateCustomersByName($mMySched,$customer_table,"","",$sCount,$sKey,$sName,$sStreetaddr,$sCity,$sState,$sZip,$sCustomertype,$sLastUpdated);
      if ($sRet == 0) {
         for ($i = 0;$i < $sCount;$i++) {
            $ret .= "
         <tr>
            <td class=\"project\"><b><a href=\"./sentry.php?next=$next&sentry=$mSentry_key&linkcustomer=" . $sKey[$i] . "\">[link]</a></b> " . $sName[$i] . ": " . $sCity[$i] . ", " . $sState[$i] . " (" . $sCustomertype[$i] . ")</td>
         </tr>";
         }
      } else {
         $ret .= "
         <tr>
            <td class=\"project\">
            problem ($sRet) while getting customers:<br />$mError
            </td>
         </tr>";
      }
      $ret .= "
      </table>";
      return $ret;
   }
   function clearcustomerphonevars() {
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      $mCust_hmphone = "";$mCust_wkphone = "";$mCust_mbphone = "";$mCust_fxphone = "";
   }
   function clearcustomervars() {
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      $mCust_nametype = "";$mCust_lastname = "";$mCust_firstname = "";$mCust_name = "";$mCust_streetaddr = "";$mCust_city = "";$mCust_state = "";$mCust_zip = "";$mCust_customertype = "";
   }
   function customerfilevars() {
      global $mCustfile_type;
      if (isset($_POST["custfile_type"])) {
         $mCustfile_type = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST["custfile_type"]);
      }
   }
   function embedvars() {
      global $mCustfile_embed;
      $mCustfile_embed = "-1";
      if (isset($_GET["embed"])) {
         $mCustfile_embed = preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET["embed"]);
      }
   }
   function custfile_doctypevars() {
      global $mCustfile_doctype;
      if (isset($_POST["custfile_type"])) {
         $mCustfile_doctype = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST["custfile_type"]);
      }
   }
   
   function custfile_deleteyesnovars() {
      global $mCustfile_delete_token;
         if (isset($_POST["custfile_delete_token"])) {
         $mCustfile_delete_token = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST["custfile_delete_token"]);
      }
   }
   
   function custfile_delete_confirm() {
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      global $mCustfile_embed,$mCust_key,$mCustfile_name,$mCustfile_handle,$mCustfile_md5;
      $_SESSION["custfile_delete_token_sess"] = mt_rand ( 1000 , 9999 );
      
      $mCustfile_handle = hash($hashalgo_custfile,$mCust_key . "." . $mCustfile_embed);
      $mCustfile_md5 = md5_file($dir_custfiles . $mCustfile_handle);
      
      
      $ret = "
      <style><!--
      #confirm_delete_custfile input {
      	border:1px solid black;
      	margin-top:1px;
      	margin-bottom:1px;
      }
      --></style>
      <div id=\"confirm_delete_custfile\">Really delete this file:
      <div style=\"margin-left:10px;\">
      '$mCustfile_name'?
      </div>
      <form style=\"margin:0;\" action=\"./customer.php?edit=$mCust_key&amp;embed=$mCustfile_embed\" method=\"POST\">
      <input type=\"hidden\" value=\"" . $_SESSION["custfile_delete_token_sess"] . "\" name=\"custfile_delete_token\" >
      <input type=\"submit\" name=\"custfile_delete_yes\" value=\"yes\">
      <input type=\"submit\" name=\"custfile_delete_no\" value=\"no\">
      <span style=\"font-size:0.70em;\">&nbsp;md5:$mCustfile_md5</span>
      </form>
      </div>
      ";
      return $ret;
   }
   function custfiletypes($checked="invoice",$idsuffix="{RAND}") {
      if ($idsuffix == "{RAND}") {
         $idsuffix = "_" . mt_rand(10000,99999);
      }
      $ret = "
                     <input ";
      if ($checked == "invoice") {
         $ret .= "checked ";
      }
      $ret .= "type=\"radio\" name=\"custfile_type\" value=\"invoice\" id=\"invoice$idsuffix\" ><label for=\"invoice$idsuffix\">invoice</label>
                     <input ";
      if ($checked == "quote") {
         $ret .= "checked ";
      }
      $ret .= "type=\"radio\" name=\"custfile_type\" value=\"quote\" id=\"quote$idsuffix\"><label for=\"quote$idsuffix\">quote</label>
                     <input ";
      if ($checked == "photo") {
         $ret .= "checked ";
      }
      $ret .= "type=\"radio\" name=\"custfile_type\" value=\"photo\" id=\"photo$idsuffix\"><label for=\"photo$idsuffix\">photo</label>";
      return $ret;
   }
   
   function customerfilestable() {
      global $mMySched,$mCust_key;
      global $mCustfile_embed;
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      $ret = "";
      $sRet = EnumerateCustfiles(
      $mMySched,
      $mCust_key,
      "",//LimitStart
      "",//LimitMax
      $sCount,
      $sHandle,
      $sDoctype,
      $sTimestampAdded,
      $sMimetype,
      $sName
      );
      
      $ret .= "
               <tr>
                  <td class=\"project\">
                  ";
      if ($sRet != 0) {
         global $mError;
         $ret .= "
                  problem ($sRet) getting CustomerTypes:<br />$mError";
         $ret .= "
                     </td>
                  </tr>
         ";
         return $ret;
      }
      if ($sCount == 0) {
         $ret .= "no files previously saved for this customer";
         return "";
      } else {
         if ($sCount == 1 ) {
            $ret .= "1 file saved for this customer";
         } else {
            $ret .= "$sCount files saved for this customer";
         }
         //
         $ret .= "
         <style><!--
         a.custfile_embed_link:hover {
         	text-decoration:underline;
         }
         --></style>
         ";
         $ret .= "<ul style=\"margin:0;\">";
         for ($i = 0;$i < $sCount;$i++) {
            $sMaxNamelen = 13;
            if (strlen($sName[$i]) > $sMaxNamelen) {
               $sShowName = htmlentities(substr($sName[$i],0,$sMaxNamelen - 3)) . "...";
            } else {
               $sShowName = htmlentities($sName[$i]);
            }
            $sShowType = $sMimetype[$i];
            $sShowType = str_replace("application/","",$sShowType);
            $sShowType = str_replace("image/","",$sShowType);
            //image/

            //<a target=\"_blank\" href=\"./customer.php?id=$mCust_key&amp;file=" . $sHandle[$i] . "\"></a>
            $ret .= "<li>";
            if ($mCustfile_embed == $sHandle[$i]) {
               $ret .= "<a style=\"font-family:courier new,courier,monospace;\" href=\"./customer.php?edit=$mCust_key\">[-]</a>";
            } else {
               $ret .= "<a style=\"font-family:courier new,courier,monospace;\" href=\"./customer.php?edit=$mCust_key&amp;embed=" . $sHandle[$i] . "\">[+]</a>";
            }
            
            if ($mCustfile_embed != $sHandle[$i]) {
               $ret .= "<a style=\"color:black;\" href=\"./customer.php?edit=$mCust_key&amp;embed=" . $sHandle[$i] . "\">";
            }
            
            $ret .= "<img height=\"$tinysquarethumb_defaultx\" width=\"$tinysquarethumb_defaultx\" src=\"./customer.php?tinysquarejpgthumb&amp;id=$mCust_key&amp;file=" . $sHandle[$i] . "\">";
            
            if ($mCustfile_embed != $sHandle[$i]) {
               $ret .= "</a>";
            }
            
            $ret .= "&nbsp;";
            
            //<a class=\"custfile_embed_link\" style=\"color:black;\" href=\"./customer.php?edit=$mCust_key&amp;embed=" . $sHandle[$i] . "\">
            if ($mCustfile_embed != $sHandle[$i]) {
               $ret .= "<a class=\"custfile_embed_link\" style=\"color:black;\" href=\"./customer.php?edit=$mCust_key&amp;embed=" . $sHandle[$i] . "\">";
            }
            
            $ret .= "" . htmlentities($sDoctype[$i] . " [" . date("M j, Y",$sTimestampAdded[$i])) . "]
            ($sShowType) '" . $sShowName. "'";
            
            if ($mCustfile_embed != $sHandle[$i]) {
               $ret .= "</a>";
            }
            
            if ($mCustfile_embed == $sHandle[$i]) {

               $ret .= "
               <style><!--
               #custfile input {
               border: 1px solid black;
               margin-bottom:1px;
               margin-top:1px;
               }
               --></style>
               <div id=\"custfile\">
               <div><form  style=\"margin:0;\" method=\"POST\"
               	action =\"./customer.php?edit=" . $mCust_key . "&amp;embed=" . $sHandle[$i] . "\">

               	<a target=\"_blank\" href=\"./customer.php?id=$mCust_key&amp;file=" . $sHandle[$i] . "\"><img src=\"./customer.php?jpgthumb&amp;id=$mCust_key&amp;file=" . $sHandle[$i] . "\"></a>
               	
               </div>
               <div>";
               $mCustfile_handle = hash($hashalgo_custfile,$mCust_key . "." . $sHandle[$i]);
               if (is_file($dir_custfiles . $mCustfile_handle)) {
                  $mCustfile_md5 = md5_file($dir_custfiles . $mCustfile_handle);
               } else {
                  $mCustfile_md5 = null;
               }
               $ret .= "
            <span style=\"font-size:0.70em;\"><a target=\"_blank\" href=\"./customer.php?id=$mCust_key&amp;file=" . $sHandle[$i] . "\">[download original...]</a> md5:$mCustfile_md5</span>
            	";
               $ret .= "
               </div>
               <div>
               
               	<input type=\"text\" size=\"40\" name=\"custfile_name\" value=\"" . htmlentities($sName[$i]) . "\" >
               	<input type=\"submit\" name=\"custfile_name_update\" value=\"rename\">
               </div>";
               global $mCustfile_showeditdoctype;
               if ($mCustfile_showeditdoctype !== true) {
                  $ret .= "
               <div>
               	<input type=\"text\" size=\"10\" value=\"type: " . htmlentities($sDoctype[$i]) . "\" readonly>
               	<input type=\"submit\" name=\"custfile_doctype_update\" value=\"change\">
               </div>";
               }
               
               if ($mCustfile_showeditdoctype === true) {
                  $ret .= "
               <div>";
                  $ret .= custfiletypes($sDoctype[$i]);
                  $ret .= "
               </div>
               <div>
               	<input type=\"submit\" name=\"custfile_doctype_submit\" value=\"submit type change\">
               </div>
               ";
               }
               $ret .= "
               <div>
               	<input type=\"submit\" name=\"custfile_delete\" value=\"delete\">
               	</form>
               </div>
               </div><!--#custfile-->
               ";
            }/*end if file displayed*/
         }/*end file loop*/
         $ret .= "</ul>";
      }
      $ret .= "
                  </td>
               </tr>
      ";
      return $ret;
   }
   function custfileupdate_namevars() {
      global $mCustfile_name;
      if (isset($_POST["custfile_name"])) {
         $mCustfile_name = preg_replace("/[^a-zA-Z0-9\s.]/", "", $_POST["custfile_name"]);
      }
   }

   function addfiletable() {
      global $mCust_key;
      global $mMySched;
      global $mAuthorized_username;
      global $mCustfile_showadd,$mCustfile_showemailup,$mCustfile_showcheckemail;
      
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      
      $ret = "";
      
      if ($mCustfile_showadd === true) {
         $ret .= "
               <tr>
                  <td class=\"project\">
                  <form style=\"margin:0;\" enctype=\"multipart/form-data\" action=\"./customer.php?id=$mCust_key\" method=\"POST\">
                  <div>
                     
                     <div style=\"font-weight:normal;\" id=\"file_label\"><a style=\"font-family:courier new, courier, monospace;color:blue;\" href=\"./customer.php?edit=$mCust_key\">[-]</a><a style=\"color:black;\" href=\"./customer.php?edit=$mCust_key\">add file</a>
                     </div>
                     <input type=\"file\" name=\"userfile\">
                     
							<div id=\"custfiletypes\">
							<div style='font-weight:bold;'>document type:</div>
							";
         $ret .= custfiletypes();
         $ret .= "	</div><!--#custfiletypes-->
                  </div>
                  <div>
                     <input type=\"submit\" name=\"custfile_upload\" value=\"upload\">
                  </div>";
         $ret .= "</form>";
         if ($mCustfile_showemailup === true) {
            $ret .= "<div>";
            if (0 == ($mRet = AddCustfileMailqueue($mMySched,$mCust_key,$mAuthorized_username,$sToken))) {
               $ret .= "
               &nbsp;&bull;&nbsp;<a href=\"mailto:$custfile_mailup_email?subject=$sToken&amp;body=" .
                htmlentities("Attach the file to you want to upload to this email.") . "%0A%0A" .
                htmlentities("If it's an image, you can paste it anywhere in the body (like right here).") . "%0A%0A" .
                htmlentities("Multiple attachments or images should be fine.") . "%0A%0A" .
                htmlentities("Do not send a duplicate of this email, as ") . "" .
                htmlentities("the token referenced in this email can only be used once.") . "%0A%0A" .
                htmlentities("Do not modify the subject line.") . "%0A%0A" .
                htmlentities("Token Ref:") . "%0A" .
                "$sToken" . "$sToken\">click here to email file/upload token:<br>
               
               <span style=\"font-size:0.70em;\">&nbsp;&nbsp;[" . $sToken . "]</span></a><br>
               <span style=\"font-size:0.70em;\">&nbsp;&nbsp;&nbsp;$sToken</span>
               ";
            } else {
               global $mError;
               $ret .= "Error (while getting mailup link):<br>$mError";
            }
            $ret .= "</div>";
         } else {
            $ret .= "
            	<div><a style=\"
            		font-family:courier new, courier, monospace;
            		color:blue;\" 
            		href=\"./customer.php?edit=$mCust_key&amp;mailup&amp;addcustfile\">[+]</a><a 
            		style=\"color:black;\" 
            		href=\"./customer.php?edit=$mCust_key&amp;mailup&amp;addcustfile\">email file</a>
            	</div>
            ";
         } /*end if/else show-email-upload*/
         $ret .= "
         	<div><a style=\"
         		font-family:courier new, courier, monospace;
         		color:blue;\" 
         		href=\"./customer.php?edit=$mCust_key&amp;addcustfile&amp;checkup\">[o]</a><a 
         		style=\"color:black;\" 
         		href=\"./customer.php?edit=$mCust_key&amp;addcustfile&amp;checkup\">check for email'ed files</a>
         	</div>
         ";
         if ($mCustfile_showcheckemail === true) {
            $ret .= "<div>";
            if (0 == ($mRet = ProcessCustfileMailqueue($mMySched,$sReport))) {
               $ret .= "&nbsp;&nbsp;<span style=\"font-size:0.70em;\">success: $sReport</span>";
            } else {
               global $mError;
               $ret .= "&nbsp;&nbsp;Error while processing custfile mail<br>:$mError";
            }
            $ret .= "</div>";
         } 

         
         $ret .= "
                  
                  </td>
               </tr>";
      } else {
         $ret .= "
               <tr>
                  <td class=\"project\">
         			<div>
                     
                     <div style=\"font-weight:normal;\" id=\"file_label\"><a style=\"font-family:courier new, courier, monospace;color:blue;\" href=\"./customer.php?addcustfile&amp;edit=$mCust_key\">[+]</a><a style=\"color:black;\" href=\"./customer.php?addcustfile&amp;edit=$mCust_key\">add file</a></div>
                  </div>
                  </td>
               </tr>";
      }
      return $ret;
   }
   
   function customeraddformtable($action,$submitcaption) {
      global $mMySched;
      global $mAddcustomerErr;
      global $mCust_hmphone,$mCust_wkphone,$mCust_mbphone,$mCust_fxphone;
      global $mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype;
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      $sState = $default_customerstate;
      if ($mCust_state != "") {
         $sState = $mCust_state;
      }
      $sNametypeSelected = "individual";
      if ($mCust_nametype == "company") {
         $sNametypeSelected = "company";
      }
      $statustext = $submitcaption;
      if ($submitcaption == "next") {
         $statustext = "new";
      }
      $statustext .= " customer";
      if ($submitcaption != "next") {
         global $mCust_key;
         $statustext .= "
<div style=\"
   float:right;
\">
            <a href=\"./customer.php?jobs=$mCust_key\">[jobs]</a>
</div>
";
      }
      $ret = "
      <table width=\"300\">
         <tr>
            <td>
            
            <table width=\"100%\">
               <tr>
                  <td class=\"heading\"><b>$statustext</b></td>
               </tr>";
      if ($submitcaption !== "next") {
         $ret .= addfiletable();
      }

      

      if ($submitcaption !== "next")
      $ret .= customerfilestable();
      $ret .= "
               <tr><form action=\"$action\" method=\"POST\">
                  <td class=\"project\">";
      if (isset($mAddcustomerErr["name"])) {
         $ret .= " <b>" . $mAddcustomerErr["name"] . "</b>";
      }
      $ret .= "
               
               <table border=\"0\">
               <tr>
                  <td valign=\"top\" align=\"left\" width=\"40\" class=\"project\">
                  <label for=\"individual_nametype\">individual</label>
                  <input id=\"individual_nametype\" type=\"radio\" name=\"nametype\" value=\"individual\"";
      if ($sNametypeSelected == "individual") {
         $ret .= " checked";
      }
      $ret .= ">
                  </td>
                  <td valign=\"top\" class=\"project\">
                  <table border=\"0\">
                     <tr>
                        <td valign=\"top\">
                  last name:<br />
                  <input type=\"text\" size=\"20\" value=\"$mCust_lastname\" name=\"lastname\">
                        </td>
                        <td align=\"top\">
                  first name:<br />
                  <input type=\"text\" size=\"20\" value=\"$mCust_firstname\" name=\"firstname\">
                        </td>
                     </tr>
                  </table>
                  </td>
               </tr>
               </tr>
                  <td valign=\"top\" align=\"left\" class=\"project\">
                  <label for=\"company_nametype\">company</label>
                  <input id=\"company_nametype\" type=\"radio\" name=\"nametype\" value=\"company\"";
      if ($sNametypeSelected == "company") {
         $ret .= " checked";
      }
      $ret .= ">

                  </td>
                  <td valign=\"top\" class=\"project\">
                     <table>
                        <tr>
                           <td>
                  name:";
      $ret .= "<br />
                  <input type=\"text\" size=\"40\" value=\"$mCust_name\" name=\"name\">
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
                  </td>
               </table>
               <tr>
                  <td class=\"project\">
                  street:";
      if (isset($mAddcustomerErr["streetaddr"])) {
         $ret .= " <b>" . $mAddcustomerErr["streetaddr"] . "</b>";
      }
      $ret .= "<br />
                  <textarea cols=\"40\" rows=\"2\" name=\"streetaddr\">$mCust_streetaddr</textarea>
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">";
      $ret .= "
                  <table border=\"0\">
                     <tr>
                        <td>
                        city:<br />
                        <input name=\"city\" type=\"text\" size=\"30\" value=\"$mCust_city\">
                        </td>
                        <td>
                        state:<br />
                        <input name=\"state\" type=\"text\" size=\"2\" value=\"$sState\">
                        </td>
                        <td>
                        zip:<br />
                        <input name=\"zip\" type=\"text\" size=\"10\" value=\"$mCust_zip\">
                        </td>
                     </tr>
                  </table>";
      if ((isset($mAddcustomerErr["citystatezip"]))) {
         $ret .= "<b>";
         if (isset($mAddcustomerErr["citystatezip"])) {
            $ret .= $mAddcustomerErr["citystatezip"];
         }
         $ret .= "</b>";
      }
      //FormatCustomerPhone($sPhonenumber)
      //$mCust_hmphone
      //$mCust_wkphone
      //$mCust_mbphone
      //$mCust_fxphone
      $ret .= "
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">
                  <table border=\"0\">
                     <tr>
                        <td valign=\"top\">
                        <b>phone:</b>
                        </td>
                        <td>
                        home<br />
                        <input type=\"text\" size=\"12\" name=\"hmphone\" value=\"" 
                        . FormatCustomerPhone($mCust_hmphone) . "\">
                        </td>
                        <td>
                        work<br />
                        <input type=\"text\" size=\"12\" name=\"wkphone\" value=\"" 
                        . FormatCustomerPhone($mCust_wkphone) . "\">
                        </td>
                        <td>
                        mobile<br />
                        <input type=\"text\" size=\"12\" name=\"mbphone\" value=\"" 
                        . FormatCustomerPhone($mCust_mbphone) . "\">
                        </td>
                        <td>
                        fax<br />
                        <input type=\"text\" size=\"12\" name=\"fxphone\" value=\"" 
                        . FormatCustomerPhone($mCust_fxphone) . "\">
                        </td>
                     </tr>
                  </table>
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">";
      $sRet = EnumerateCustomertypes($mMySched,$customertype_table,$sCount,$sName,$sBrief,$sDescription);
      if ($sRet == 0) {
         $sSelectedIdx = 1;
         for ($i = 0;$i < $sCount;$i++) {
            if ($sName[$i] == $mCust_customertype) {
               $sSelectedIdx = $i;
            }
         }
         $ret .= "customer type:<br />
                  <select size=\"1\" name=\"customertype\">";
         for($i = 0;$i < $sCount;$i++) {
            $ret .= "
                     <option value=\"" . $sName[$i] . "\"";
            if ($sSelectedIdx == $i) {
               $ret .= " selected";
            }
            $ret .= ">" . $sBrief[$i] . "</option>";
         }
         $ret .= "
                  </select>";
      } else {
         global $mError;
         $ret .= "
                  problem ($sRet) getting CustomerTypes:<br />$mError";
      }
      $submitcaptionval = $submitcaption;
      if ($submitcaption == "update") {
         $submitcaptionval = "save";
      }
      $ret .= "
                  </td>
               </tr>
               <tr>
                  <td class=\"project\">
                  <input type=\"submit\" value=\"$submitcaptionval\">
                  </td>
               </tr>
            </table>
            </form>
            </td>
         </tr>
      </table>";
      return $ret;
   }
