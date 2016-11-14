<?php

namespace clientcal;

foreach(config::EnumConfigNames() as $configName) {
   foreach((new config($configName))->getAssoc() as $k=>$v){
      $$k=$v;
   }
}
unset($configName);

   include("routine/getsession.php");
   $mMode = "login";
   include("routine/getauth.php");
   

   
   if ($mAuthorized == "true") {
      $mMode = "nothing";
      if (isset($_GET["add"])) {
         $mMode = "add_sentry";
      } else
         if (isset($_GET["submitnew"])) {
            $mMode = "submitnew";
         } else
            if (isset($_GET["show"])) {
               $mMode = "show_sentry";
            } else
               if (isset($_GET["addwprocess"])) {
                  $mMode = "showadd_wprocess";
               } else
                  if (isset($_GET["submitsentry_linkcust"])) {
                     $mMode = "submitsentry_showlinkcust";
                  } else
                     if (isset($_GET["linkexistingcustomer"])) {
                        $mMode = "linkexistingcustomer_find";
                     } else
                        if (isset($_GET["linknewcustomer"])) {
                           $mMode = "linknewcustomer";
                        } else
                           if (isset($_GET["linkcustomer"])) {
                              $mMode = "linkcustomer";
                           } else
                              if (isset($_GET["assocsite"])) {
                                 $mMode = "assocsite";
                              } else
                                 if (isset($_GET["showsentries"])) {
                                    $mMode = "showsentries";
                                 } else
                                    if (isset($_GET["editsite"])) {
                                       $mMode = "showedit_site";
                                    } else
                                       if (isset($_GET["updatesite"])) {
                                          $mMode = "updatesite";
                                       } else
                                          if (isset($_GET["edit"])) {
                                             $mMode = "showedit_sentry";
                                          } else
                                             if (isset($_GET["updatesentry"])) {
                                                $mMode = "updatesentry";
                                             } else
                                                if (isset($_GET["editcustlink"])) {
                                                   $mMode = "editcustlink";
                                                } else
                                                   if (isset($_GET["sentrieslist"])) {
                                                      //sentriesbymonthtable($caption,$monthno,$year)
                                                      $mMode = "sentrieslist";
                                                   } else
                                                      if (isset($_GET["showday"])) {
                                                         $mMode = "showday";
                                                      } else
                                                         if (isset($_GET["delist"])) {
                                                            $mMode = "delistconfirm";
                                                         } else
                                                            if (isset($_GET["printday"])) {
                                                               $mMode = "printday";
                                                            } else
                                                               if (isset($_GET["duplicate"])) {
                                                                  $mMode = "duplicate";
                                                               } else
                                                                  if (isset($_GET["dupeselect"])) {
                                                                     $mMode = "dupeselect";
                                                                  } else
                                                                     if (isset($_GET["godate"])) {
                                                                        $mMode = "godate";
                                                                     }
   }
   $mNotice = "";
   $mSubtitle = "";
   $mRet = ConnectToDB($dbhost,$dbname,$dbuser,$dbpasswd,$mMySched);
   if ($mRet != 0) {
      $mMode = "gen_error";
      $mNotice .= "problem ($mRet) while connecting to the schedule database:<br />$mError<br />";
   }
   
   //diff places sentry key shows up
   if ($mMode == "editcustlink") {
      $mSentry_key = 0;
      if (isset($_GET["editcustlink"])) {
         if (is_numeric($_GET["editcustlink"])) {
            $mSentry_key = $_GET["editcustlink"];
         }
      }
   }
   if ($mMode == "show_sentry") {
      $mSentry_key = 0;
      if (isset($_GET["show"])) {
         if (is_numeric($_GET["show"])) {
            $mSentry_key = $_GET["show"];
         }
      }
   }
   if ($mMode == "updatesite") {
      $mSentry_key = 0;
      if (isset($_GET["updatesite"])) {
         if (is_numeric($_GET["updatesite"]))
            $mSentry_key = $_GET["updatesite"];
      }
   }
   if ($mMode == "updatesentry") {
      $mSentry_key = 0;
      if (isset($_GET["updatesentry"])) {
         if (is_numeric($_GET["updatesentry"]))
            $mSentry_key = $_GET["updatesentry"];
      }
   }
   if ($mMode == "showedit_site") {
      $mSentry_key = 0;
      if (isset($_GET["editsite"])) {
         if (is_numeric($_GET["editsite"]))
            $mSentry_key = $_GET["editsite"];
      }
   }
   if ($mMode == "showedit_sentry") {
      $mSentry_key = 0;
      if (isset($_GET["edit"])) {
         if (is_numeric($_GET["edit"]))
            $mSentry_key = $_GET["edit"];
      }
   }
   if ($mMode == "delistconfirm") {
      $mSentry_key = 0;
      if (isset($_GET["delist"])) {
         if (is_numeric($_GET["delist"]))
            $mSentry_key = $_GET["delist"];
      }
   }
   if ($mMode == "delistconfirm") {
      if (isset($_GET["confirm"])) {
         $mMode = "delistconfirm_examine";
      }
   }
   if ($mMode == "duplicate") {
      $mSentry_key = 0;
      if (isset($_GET["duplicate"])) {
         if (is_numeric($_GET["duplicate"])) {
            $mSentry_key = $_GET["duplicate"];
         }
      }
   }
   if ($mMode == "dupeselect") {
      $mSentry_key = 0;
      if (isset($_GET["dupeselect"])) {
         if (is_numeric($_GET["dupeselect"])) {
            $mSentry_key = $_GET["dupeselect"];
         }
      }
   }
   
   //before output occurs
   if ($mMode == "godate") {
      //get postvars
      sentrygodatepostvars();
      if ($mGodate_valid === false) {
         $mNotice .= rawgodatevars();
         //default to current date
   
         $mShowMonth = date("n");
   
   
         $mShowDay = date("j");
   
   
         $mShowYear = date("Y");
         $mMode = "showsentries";
   
   
      }
   }
   
   if ($mMode == "duplicate") {
      $mShow_sentries = true;
      //make sure sentry exists
      if (0 != (
            $mRet = GetSentryWCustName($mMySched,$sentry_table,$customer_table,$mSentry_key,$mSentry_heading,$mSentry_startdate,$mSentry_starttime,$mSentry_sentrytype,$mSentry_custkey,$mSentry_custname)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
            }
   }
   if ($mMode == "duplicate") {
      if (isset($_GET["hidesentries"])) {
         $mShow_sentries = false;
      }
   }
   if ($mMode == "delistconfirm_examine") {
      sentrydelistpostvars();
      if ($mSentrydelist_confirm === true) {
         if (0 != (
               $mRet = GetSentryStartdate($mMySched,$sentry_table,$mSentry_key,$mSentry_startdate,$mSentryLastUpdate)
               )) {
                  $mMode = "gen_error";
                  $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
               }
               //$mNotice .= "delisted entry $mSentry_key per user";
               $mMode = "delistentry_true";
      } else {
         $mNotice .= "<li>did not delist entry $mSentry_key per user";
         $mMode = "showedit_sentry";
      }
   }
   if ($mMode == "delistentry_true") {
      if (0 != (
            $mRet = DelistSentry($mMySched,$sentry_table,$mSentry_key)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
            } else {
               $mNotice .= "delisted entry $mSentry_key";
            }
   }
   if ($mMode == "delistconfirm") {
      if (0 != ($sRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentryLastUpdate))) {
         $mMode = "gen_error";
         $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
      }
   }
   if ($mMode == "delistconfirm") {
      if (0 != (
            //$mRet = GetSentry($mMySched,$sentry_table,$mSentry_key,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype,$mSentry_LastUpdate)
            $mRet = GetSentryWCustName($mMySched,$sentry_table,$customer_table,$mSentry_key,$mSentry_heading,$mSentry_startdate,$mSentry_starttime,$mSentry_sentrytype,$mCust_key,$mCust_name)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while getting entry:<br />$mError";
            }
   }
   
   if ($mMode == "editcustlink") {
      //make sure sentry exists
      if (0 != ($sRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentryLastUpdate))) {
         $mMode = "gen_error";
         $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
      }
   }
   if ($mMode == "editcustlink") {
      $mRet = GetSentry($mMySched,$sentry_table,$mSentry_key,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype,$mSentry_LastUpdate);
      if ($mRet != 0) {
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while getting Sentry $mSentry_key:<br />$mError<br />";
      }
   }
   //showday
   if (($mMode == "showsentries") || ($mMode == "sentrieslist") || ($mMode == "showday") || ($mMode == "printday") || ($mMode == "duplicate") || ($mMode == "dupeselect")) {
      $mShowMonth = 0;
      $mShowYear = 0;
      $mShowDay = 0;
      $mShowWeek = 0;
   }
   if ($mMode == "showsentries") {
      if (isset($_GET["showsentries"])) {
         $mShowMonth = $_GET["showsentries"];
      }
   }
   if ($mMode == "sentrieslist") {
      if (isset($_GET["sentrieslist"])) {
         $mShowWeek  = $_GET["sentrieslist"];
      }
      if ($mShowWeek == 0) {
         $mShowWeek = date("W");
      }
   }
   if (($mMode == "showday") || ($mMode == "printday") || ($mMode == "duplicate") || ($mMode == "dupeselect")) {
      if (isset($_GET["month"])) {
         $mShowMonth  = $_GET["month"];
      }
   }
   if (($mMode == "showsentries") || ($mMode == "sentrieslist") || ($mMode == "showday") || ($mMode == "printday") || ($mMode == "duplicate") || ($mMode == "dupeselect")) {
      if (isset($_GET["day"])) {
         $mShowDay = $_GET["day"];
      }
      if (isset($_GET["year"])) {
         $mShowYear = $_GET["year"];
      }
      if ($mShowMonth == "CurMonth") {
         $mShowMonth = date("n");
      }
   
      //default to current date
      if ($mShowMonth == "") {
         $mShowMonth = date("n");
      }
      if ($mShowDay == "") {
         $mShowDay = date("j");
      }
      if ($mShowYear == "") {
         $mShowYear = date("Y");
      }
   
   }
   
   
   if ($mMode == "dupeselect") {
      //get the all the sentry & site and then create new one
      if (0 != (
            $mRet = GetSentryWSomeSiteAndCustInfoB($mMySched,$sentry_table,$supervisor_table,$customer_table,$site_table,$mSentry_key,$mNew_Heading,$mNew_Notes,$mOld_Startdate,$mNew_Starttime,$mNew_Sentrytype,$mNew_Supervisor_key,$mNew_Supervisor_name,$mNew_Cust_key,$mNew_Cust_name,$mNew_Site_streetaddr,$mNew_Site_city,$mNew_Site_state,$mNew_Site_zip,$mNew_Site_sdirections)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while looking up entry:<br />$mError";
            }
   }
   if ($mMode == "dupeselect") {
      $mNew_stamp = mktime(0,0,0,$mShowMonth,$mShowDay,$mShowYear);
      $mNew_Startdate = date("Y-m-d",$mNew_stamp);
      if (0 != (
            $mRet = AddSentry($mMySched,$sentry_table,$mNew_Heading,$mNew_Notes,$mNew_Startdate,$mNew_Starttime,$mNew_Supervisor_key,$mNew_Sentrytype,$mNew_Sentry_key)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while adding entry:<br />$mError";
            }
   }
   if ($mMode == "dupeselect") {
      //assoc cust
      if (0 != (
            $mRet = UpdateSentryCustomer($mMySched,$sentry_table,$mNew_Sentry_key,$mNew_Cust_key)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while associating customer:<br />$mError";
            }
   }
   if ($mMode == "dupeselect") {
      //create site info
      if (0 != (
            $mRet = AddSite($mMySched,$site_table,$mNew_Sentry_key,$mNew_Site_streetaddr,$mNew_Site_city,$mNew_Site_state,$mNew_Site_zip,$mNew_Site_sdirections)
            )) {
               $mMode = "gen_error";
               $mNotice .= "problem ($sRet) while creating site info:<br />$mError";
            }
   }
   if ($mMode == "dupeselect") {
      //go into view mode for the new entry
      $mSentry_key = $mNew_Sentry_key;
      $mMode = "show_sentry";
   }
   if ($mMode == "updatesite") {
      globalizesitepostvars();
      //check if a site record exists
      $mRet = GetSiteLastUpdate($mMySched,$site_table,$mSentry_key,$mSentry_LastUpdated);
      if ($mRet == 0) {
         $mRet = UpdateSite($mMySched,$site_table,$mSentry_key,$mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections);
         if ($mRet == 0) {
            $mNotice .= "successfully updated site info<br />";
            $mMode = "show_sentry";
         } else {
            $mMode = "gen_error";
            $mNotice .= "problem ($mRet) while updating site info:<br />$mError<br />";
         }
      } else
         if ($mRet == -1) {
            $mRet = AddSite($mMySched,$site_table,$mSentry_key,$mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections);
            if ($mRet == 0) {
               $mNotice .= "successfully added site info<br />";
               $mMode = "show_sentry";
            } else {
               $mMode = "gen_error";
               $mNotice .= "problem ($mRet) while updating site info:<br />$mError<br />";
            }
         } else {
            $mMode = "gen_error";
            $mNotice .= "problem ($mRet) while updating site info:<br />$mError<br />";
         }
   }
   if ($mMode == "showedit_sentry") {
      $mRet = GetSentry($mMySched,$sentry_table,$mSentry_key,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype,$mSentry_LastUpdate);
      if ($mRet != 0) {
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while getting Sentry $mSentry_key:<br />$mError<br />";
      }
   }
   if ($mMode == "updatesentry") {
      globalizesentryformpostvars();
      if (!testsentryvars($mAddSentryerr)) {
         $mNotice .= "<b>problems with the entry</b><br />";
         $mMode = "showedit_sentry";
      }
   }
   if ($mMode == "updatesentry") {
      $mRet = UpdateSentry($mMySched,$sentry_table,$mSentry_key,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype);
      if ($mRet == 0) {
         //$mMode = "showsentries";
         $mMode = "show_sentry";
         $mShowMonth = substr($mSentry_startdate,5,2);
         $mShowYear = substr($mSentry_startdate,0,4);
         $mNotice .= "<li>successfully updated $mSentry_key<br />";
         //$mNotice .= "<li>showing $mShowMonth of $mShowYear<br />";
      } else {
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while updating Sentry $mSentry_key:<br />$mError<br />";
      }
   }
   if ($mMode == "showedit_site") {
      $mRet = GetSomeSentryAndSiteInfo($mMySched,$sentry_table,$site_table,$mSentry_key,$mSentry_Heading,$mSentry_Startdate,$mSentry_Starttime,$mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections,$mSentry_LastUpdated,$mSite_LastUpdated);
   }
   if ($mMode == "delistentry_true") {
      //$mSentry_startdate
      $mMode = "showsentries";
      if (isset($_GET["next"])) {
         //something fancier to do than just assuming month v mode
      }
   
      if ($mMode == "showsentries") {
         $mPart = explode("-",$mSentry_startdate);
         $mShowMonth = $mPart[1];
         $mShowDay = $mPart[2];
         $mShowYear = $mPart[0];
      }
   }
   if ($mMode == "godate") {
      $mShowMonth = $mGodate_m;
      $mShowDay = $mGodate_d;
      $mShowYear = $mGodate_yyyy;
      $mMode = "showsentries";
   }
   //if ($mMode == "showsentries") {
   if (($mMode == "showsentries") || ($mMode == "sentrieslist") || ($mMode == "showday") || ($mMode == "printday")) {
      //$mNotice .= "here the day is $mShowDay";
   
      $mBadYear = FALSE;
      if (is_numeric($mShowYear)) {
         if (!checkdate(1,1,$mShowYear)) {
            $mBadYear = TRUE;
            $mNotice .= "<li>invalid year given for shown year, using server's current year instead<br />";
         }
      } else {
         $mBadYear = TRUE;
         $mNotice .= "<li>bad format year given for shown year, using server's current year instead<br />";
      }
      $mBadMonth = FALSE;
      if (is_numeric($mShowMonth)) {
         if (!checkdate($mShowMonth,1,1979)) {
            $mBadMonth = TRUE;
            $mNotice .= "<li>invalid month given for ShowMonth, using server's current month instead<br />";
         }
      } else {
         $mBadMonth = TRUE;
         $mNotice .= "<li>bad format for month given for ShowMonth, using server's current month instead<br />";
      }
      $mBadDay = FALSE;
      if ($mBadMonth === FALSE) {
         if (is_numeric($mShowDay)) {
            //checkdate ( int month, int day, int year )
            if (!checkdate($mShowMonth,$mShowDay,$mShowYear)) {
               $mBadYear = TRUE;
               $mNotice .= "<li>invalid day used (year:$mShowYear)<br />";
            }
         } else {
            $mBadYear = TRUE;
            $mNotice .= "<li>bad format year given for shown day<br />";
         }
      }
      if ($mBadDay) {
         $mShowDay = 1;
      }
      if ($mBadMonth) {
         $mShowMonth = date("n");
         $mShowDay = 1;
      }
      if ($mBadYear) {
         $mShowYear = date("Y");
      }
      $mShowStamp = mktime(0,0,0,$mShowMonth,$mShowDay,$mShowYear);
   }
   if ($mMode == "assocsite") {
      $mSentry_key = 0;
      if (isset($_GET["assocsite"])) {
         $mSentry_key = $_GET["assocsite"];
      }
   }
   if ($mMode == "assocsite") {
      //$mRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentryLastUpdate);
      $mRet = GetSentryStartdate($mMySched,$sentry_table,$mSentry_key,$mSentryStartdate,$mSentryLastUpdate);
      if ($mRet != 0) {
         if ($mRet == -1) {
            $mNotice .= "<li>invalid sentry key given to associate with site<br />";
            $mMode = "gen_error";
         } else {
            $mNotice .= "<li>problem validating sentry key:<br />$mError<br />";
         }
      }
   }
   if ($mMode == "assocsite") {
      globalizesitepostvars();
      $mRet = AssociateSite($mMySched,$site_table,$mSentry_key,$mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections);
      if ($mRet == 0) {
         $mNotice .= "<li>successfully updated site info<br />";
         $mMode = "showsentries";
         /*
          $mShowMonth = date("n");
          $mShowYear = date("Y");
          */
         //( string separator, string string [, int limit] )
         $mPart = explode("-",$mSentryStartdate);
         //YYYY-MM-DD
         $mShowMonth = $mPart[1];
         $mShowDay = $mPart[2];
         $mShowYear = $mPart[0];
      } else {
         $mNotice .= "<li>problem ($mRet) while updating site info:<br />$mError<br />";
         $mMode = "gen_error";
      }
   }
   
   if ($mMode == "linkcustomer") {
      $mSentry_key = 0;
      if (isset($_GET["sentry"])) {
         $mSentry_key = $_GET["sentry"];
      }
      $mRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentry_LastUpdate);
      if ($mRet != 0) {
         $mNotice .= "<li>invalid sentry key '$mSentry_key' given to link a customer to<br />$mError<br />";
         $mMode = "gen_error";
      }
   }
   if ($mMode == "linkcustomer") {
      $mCustomer_key = 0;
      if (isset($_GET["linkcustomer"])) {
         $mCustomer_key = $_GET["linkcustomer"];
      }
      $mRet = GetCustomerLastUpdate($mMySched,$customer_table,$mCustomer_key,$mTmpLastUpdate);
      if ($mRet != 0) {
         $mNotice .= "<li>invalid customer key '$mCustomer_key' given to link sentry to<br />$mError<br />";
         $mMode = "gen_error";
      }
   }
   if ($mMode == "linkcustomer") {
      $mRet = UpdateSentryCustomer($mMySched,$sentry_table,$mSentry_key,$mCustomer_key);
      if ($mRet == 0) {
         $mNotice .= "<li>successfully linked customer to entry";
      } else {
         $mNotice .= "<li>problem ($mRet) linking customer to entry:<br />$mError<br />";
         $mMode = "gen_error";
      }
   }
   if ($mMode == "linkcustomer") {
      $mSentry_key = 0;
      if (isset($_GET["sentry"])) {
         $mSentry_key = $_GET["sentry"];
      }
      $mMode = "suggestsite";
      if (isset($_GET["next"])) {
         $mMode = $_GET["next"];
      }
   }
   
   //put linknew here
   if ($mMode == "linknewcustomer") {
      $mSentry_key = 0;
      if (isset($_GET["linknewcustomer"])) {
         $mSentry_key = $_GET["linknewcustomer"];
      }
      $mRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentryLastUpdate);
      if ($mRet != 0) {
         if ($mRet == -1) {
            $mNotice .= "<li>invalid sentry derived to link new customer with:$mSentry_key<br />";
            $mMode = "gen_error";
         } else {
            $mNotice .= "<li>problem ($mRet) while validating sentry key:<br />$mError<br />";
            $mMode = "gen_error";
         }
      }
   }
   if ($mMode == "linknewcustomer") {
      globalizecustomerpostvars();
      if (!testcustsomervars($mAddcustomerErr)) {
         $mNotice .= "<li>problems with this customer entry<br />";
         $mMode = "showlinkcust";
      }
   }
   if ($mMode == "linknewcustomer") {
      $mRet = AddCustomer($mMySched,$customer_table,formatcustomername(),$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype,$mCustomer_key);
      if ($mRet == 0) {
         $mNotice .= "<li>successfully added customer:$mCustomer_key<br />";
      } else {
         $mNotice .= "<li>problem ($mRet) while adding customer:<br />$mError<br />";
         $mMode = "gen_error";
      }
      globalizecustomerphonepostvars();
      processcustomerphonevars($mPHCount,$mPHType,$mPHNumber);
      for ($i = 0;$i < $mPHCount;$i++) {
         $mRet = AssociateCustomerPhone($mMySched,$customerphone_table,$mCustomer_key,$mPHType[$i],$mPHNumber[$i]);
         if ($mRet != 0) {
            $mNotice .= "<li>problem ($mRet) while associating phone nubmer:$i," . $mPHType[$i] . "," . $mPHNumber[$i] . "<br />$mError<br />";
         }
         if ($i == 0) { //make this primary until we have a better interface
            $mRet = UpdateCustomerPrimaryPhoneType($mMySched,$customer_table,$mCustomer_key,$mPHType[$i]);
            if ($mRet != 0) {
               $mNotice .= "<li>problem ($mRet) while updating primary phonetype for customer:$mCustomer_key on:$i," . $mPHType[$i] . "," . $mPHNumber[$i] . "<br />$mError<br />";
            }
         }
      }
      $mRet = UpdateSentryCustomer($mMySched,$sentry_table,$mSentry_key,$mCustomer_key);
      if ($mRet != 0) {
         $mMode = "gen_error";
         $mNotice .= "<li>problem ($mRet) while updating sentry customer:<br />$mError<br />";
      }
   }
   if ($mMode == "linknewcustomer") {
      $mMode = "suggestsite";
      if (isset($_GET["next"])) {
         $mMode = $_GET["next"];
      }
   }
   
   
   
   if ($mMode == "suggestsite") {
      //$mSite_streetaddr,$mSite_city,$mSite_state,$mSite_zip,$mSite_sdirections;
      //$mCust_nametype,$mCust_lastname,$mCust_firstname,$mCust_name,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype
      $mRet = GetCustomerAddrByKey($mMySched,$customer_table,$mCustomer_key,$mSuggest_streetaddr,$mSuggest_city,$mSuggest_state,$mSuggest_zip);
      if ($mRet == 0) {
         $mSite_streetaddr = $mSuggest_streetaddr;
         $mSite_city = $mSuggest_city;
         $mSite_state = $mSuggest_state;
         $mSite_zip = $mSuggest_zip;
      } else {
         $mNotice .= "<li>problem ($mRet) getting customer addr to suggest site with:<br />$mError<br />";
         $mMode = "gen_error";
      }
   }
   if ($mMode == "linkexistingcustomer_find") {
      //make sure there's a valid entry key to link to
      $mSentry_key = 0;
      if (isset($_GET["linkexistingcustomer"])) {
         $mSentry_key = $_GET["linkexistingcustomer"];
      }
      $mRet = GetSentryLastUpdate($mMySched,$sentry_table,$mSentry_key,$mSentry_LastUpdate);
      if ($mRet != 0) {
         $mNotice .= "<li>invalid sentry key '$mSentry_key' given to link a customer to<br />$mError";
         $mMode = "gen_error";
      }
   }
   
   if ($mMode == "show_sentry") {
      $mRet = GetSentryHeading($mMySched,$sentry_table,$mSentry_key,$mSentry_heading);
      if ($mRet != 0) {
         $mMode = "gen_error";
         if ($mRet == -1) {
            $mNotice .= "<li>sentry key:$mSentry_key not found<br />";
         } else {
            $mNotice .= "<li>problem ($mRet) while getting Sentry info:$mSentry_key<br />$mError<br />";
         }
      }
   }
   
   if ($mMode == "submitnew") {
      $mModeAfterSuccessSubmit = "add_sentry";
      $mModeAfterFailSubmit = "add_sentry";
   }
   if ($mMode == "submitsentry_showlinkcust") {
      $mModeAfterSuccessSubmit = "showlinkcust";
      $mModeAfterFailSubmit = "showadd_wprocess";
      $mMode = "submitnew";
   }
   
   if ($mMode == "submitnew") {
      globalizesentryformpostvars();
   }
   if ($mMode == "submitnew") {
      if (!testsentryvars($mAddSentryerr)) {
         $mMode = $mModeAfterFailSubmit;
         $mNotice .= "<li>problems with information submitted for entry<br />";
      }
   }
   if ($mMode == "submitnew") {
      $mRet = AddSentry($mMySched,$sentry_table,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype,$mSentry_key);
      if ($mRet == 0) {
         $mNotice .= "successfully added schedule entry<br />";
         $mMode = $mModeAfterSuccessSubmit;
      } else {
         $mNotice .= "problem ($mRet) while adding schedule entry:<br />$mError<br />";
         $mMode = $mModeAfterFailSubmit;
      }
   }
   
   if (($mMode == "showsentries") || ($mMode == "sentrieslist")) {
      if (!is_numeric($mShowMonth)) {
         $mMode = "gen_error";
         $mNotice .= "<li>bad format for month given to show entries:$mShowMonth</br>";
      }
   }
   if (($mMode == "showsentries") || ($mMode == "sentrieslist")) {
      //if (($mShowMonth < 1) || ($mShowMonth > 12)) {
      if (!checkdate($mShowMonth,1,1979)) {
         $mMode = "gen_error";
         $mNotice .= "<li>bad month given to show entries:$mShowMonth</br>";
      }
   }
   if (($mMode == "showsentries") || ($mMode == "sentrieslist")) {
      //int mktime ( [int hour [, int minute [, int second [, int month [, int day [, int year [, int is_dst]]]]]]] )
      $mShowMonthTimestamp = mktime(12,1,1,$mShowMonth);
      $mShowMonthText = date("F",$mShowMonthTimestamp);
   }
   
   if ($mMode == "show_sentry") {
      $mShowMap = FALSE;
      if (isset($_GET["showmap"])) {
         if (strtoupper($_GET["showmap"]) == "TRUE") {
            $mShowMap = TRUE;
         }
      }
   }
   if ($mMode == "showadd_wprocess") {
      if (isset($_GET["month"])) {
         $mShowMonth = $_GET["month"];
      }
      if (isset($_GET["day"])) {
         $mShowDay = $_GET["day"];
      }
      if (isset($_GET["year"])) {
         $mShowYear = $_GET["year"];
      }
      if (isset($_GET["cancel"])) { //list of modes valid to "cancel" to
         $mCancelMode = "showsentries";
         if ($_GET["cancel"] == "showsentries") {
            $mCancelMode = "showsentries";
         } else
            if ($_GET["cancel"] == "showday") {
               $mCancelMode = "showday";
            }
      }
   }
   if (isset($mCancelMode))
      if (($mCancelMode == "showsentries") || ($mCancelMode == "showday")) {
         $mCancelNav = "./sentry.php?$mCancelMode";
         if ((isset($mShowMonth)) && (isset($mShowDay)) && (isset($mShowYear))) {
            $mShowStamp = mktime(0,0,0,$mShowMonth,$mShowDay,$mShowYear);
            if (($mShowStamp === FALSE) || ($mShowStamp == -1)) {
               global $mNotice;
               $mNotice .= "supplied date is invalid";
            } else {
               $mCancelNav .= "=$mShowMonth&day=$mShowDay&year=$mShowYear";
            }
         }
      }
   $mSubtitle = "";
   $mHeadExtra = "";
   $mHeadExtra = "
      <link rel=\"STYLESHEET\" href=\"style.sentry.css.php\" type=\"text/css\">";
   if (($mMode == "login") || ($mMode == "failed_login")) {
      $mSubtitle = "Please Sign In";
   } else
      if ($mMode == "nothing") {
         $mSubtitle = "Nothing";
      } else
         if ($mMode == "gen_error") {
            $mSubtitle = "critical error";
         } else
            if ($mMode == "add_sentry") {
               $mSubtitle = "add schedule entry";
            } else
               if ($mMode == "showadd_wprocess") {
                  $mSubtitle = "add schedule entry process";
               } else
                  if ($mMode == "showlinkcust") {
                     $mSubtitle = "link customer to schedule entry";
                  } else
                     if ($mMode == "linkexistingcustomer_find") {
                        $mSubtitle = "link existing customer to entry";
                     } else
                        if ($mMode == "suggestsite") {
                           $mSubtitle = "Site for Entry";
                        } else
                           if ($mMode == "showsentries") {
                              $mSubtitle = "$mShowMonthText $mShowYear";
                           } else
                              if ($mMode == "show_sentry") {
                                 $mSubtitle = "$mSentry_heading | Entry";
                                 if ($mShowMap) {
                                    //$mHeadExtra .= sentrygoogleapiscript();
                                 }
                              } else
                                 if ($mMode == "showedit_site") {
                                    $mSubtitle = "Edit Site Info | $mSentry_Heading | $mSentry_Startdate";
                                 } else
                                    if ($mMode == "showedit_sentry") {
                                       $mSubtitle = "Edit Sentry Info | $mSentry_heading | $mSentry_startdate";
                                    } else
                                       if ($mMode == "editcustlink") {
                                          $mSubtitle = "Edit Sentry Info | Change Customer Link | $mSentry_heading";
                                       } else
                                          if ($mMode == "sentrieslist") {
                                             $mSubtitle = "wk$mShowWeek, $mShowYear";
                                          } else
                                             if ($mMode == "showday") {
                                                $mSubtitle = date("F j,Y",$mShowStamp) . " | Entries";
                                             } else
                                                if ($mMode == "delistconfirm") {
                                                   //$mSentry_key,$mSentry_heading,$mSentry_notes,$mSentry_startdate,$mSentry_starttime,$mSentry_supervisorkey,$mSentry_sentrytype,$mSentry_LastUpdate
                                                   $mSubtitle = "confirm delisting of $mSentry_heading for $mSentry_startdate | $mSentry_key";
                                                } else
                                                   if ($mMode == "printday") {
                                                      $mSubtitle = date("F j,Y",$mShowStamp) . " Entries";;
                                                   }
   
                                                if ($mMode != "printday") {
                                                   echo header($mSubtitle);
                                                   echo top();
                                                }
                                                if ($mMode == "printday") {
                                                   echo header($mSubtitle);
                                                   echo topminimal();
                                                }
   
                                                if ($mMode == "login") {
                                                   echo logintable($default_tableclass,"./");
                                                }
                                                if ($mMode == "nothing") {
                                                   echo "nothing designed for this state";
                                                } else
                                                   if ($mMode == "gen_error") {
                                                      echo "<b>critical error:</b><br />" . $mNotice;
                                                   } else
                                                      if ($mMode == "add_sentry") {
                                                         if ($mNotice != "") {
                                                            echo sentrynoticetable($mNotice);
                                                         }
                                                         //addsentryformtable($action,$width,$submitcaption,$caption,$cancelnav)
                                                         echo addsentryformtable("./sentry.php?submitnew","800","add","<b>add schedule entry</b>","./");
                                                      } else
                                                         if ($mMode == "showadd_wprocess") {
                                                            if ($mNotice != "") {
                                                               echo sentrynoticetable($mNotice);
                                                            }
                                                            $mCancelNav = "./";
                                                            echo addsentryformtable("./sentry.php?submitsentry_linkcust","800","next","<b>add schedule entry</b>",$mCancelNav);
                                                         } else
                                                            if ($mMode == "showlinkcust") {
                                                               if ($mNotice != "") {
                                                                  echo sentrynoticetable($mNotice);
                                                               }
                                                               echo sentrynoticetable("<b>link a customer to this schedule entry</b>");
                                                               echo existingcustomerformtable("./sentry.php?linkexistingcustomer=$mSentry_key","800","existing customer lookup");
                                                               echo customeraddformtable("./sentry.php?linknewcustomer=$mSentry_key&next=suggestsite","next");
                                                            } else
                                                               if ($mMode == "linkexistingcustomer_find") {
                                                                  //make the alpha list
                                                                  if ($mNotice != "") {
                                                                     echo sentrynoticetable($mNotice);
                                                                  }
                                                                  if (isset($_GET["alpha"])) {
                                                                     $mAlpha = $_GET["alpha"];
                                                                  } else {
                                                                     $mAlpha = "A";
                                                                  }
                                                                  echo customeralphaforlink($mAlpha,"suggestsite");
                                                                  //echo customerlistforlink("<b>choose customer to link</b>","suggestsite");
                                                                  echo customeraddformtable("./sentry.php?linknewcustomer=$mSentry_key&next=suggestsite","next");
                                                               } else
                                                                  if ($mMode == "suggestsite") {
                                                                     if ($mNotice != "") {
                                                                        echo sentrynoticetable($mNotice);
                                                                     }
                                                                     echo sitesuggest("<b>site for entry</b>","./sentry.php?assocsite=$mSentry_key","");
                                                                  } else
                                                                     if ($mMode == "showsentries") {
                                                                        //echo sentriesbymonthtable("<b>Schedule Entries for $mShowMonthText, $mShowYear</b>",$mShowMonth,$mShowYear);
                                                                        echo sentrymonthv($mShowMonth,$mShowYear,$mShowDay);
                                                                        if ($mNotice != "") {
                                                                           echo sentrynoticetable($mNotice);
                                                                        }
                                                                     } else
                                                                        if ($mMode == "sentrieslist") {
                                                                           //echo sentrymonthv($mShowMonth,$mShowYear,$mShowDay);
                                                                           //$mPaddedWeekno = $mShowWeek;
                                                                           //$mPaddedWeekno = sprintf("%02d",$mShowWeek);
                                                                           $mStartTimestamp = strtotime($mShowYear . "W" . sprintf("%02d",$mShowWeek));
                                                                           $mEndTimestamp = strtotime("+6 days",$mStartTimestamp);
                                                                           $mStartText = date("M j" , $mStartTimestamp);
                                                                           $mEndText = date("M j" , $mEndTimestamp);;
                                                                           $mShowMonth = date("n",$mStartTimestamp);
                                                                           $mShowDay = date("j",$mStartTimestamp);
                                                                           $mTCaption = "" . $mStartText . " - " . $mEndText . date(", Y",$mEndTimestamp);
                                                                           //$mTCaption = "poop";
                                                                           echo sentriesbyweektable($mTCaption,$mShowWeek,$mShowYear);
                                                                           //echo sentriesbymonthtable("all for $mShowMonthText, $mShowYear",$mShowWeek,$mShowYear);
                                                                           if ($mNotice != "") {
                                                                              echo sentrynoticetable($mNotice);
                                                                           }
                                                                        } else
                                                                           if ($mMode == "show_sentry") {
                                                                              echo sentrywithinfotable("",$mShowMap);
                                                                              if ($mNotice != "") {
                                                                                 echo sentrynoticetable($mNotice);
                                                                              }
                                                                           } else
                                                                              if ($mMode == "showedit_site") {
                                                                                 echo sitesuggest("<b>edit site for $mSentry_Heading $mSentry_Startdate</b>","./sentry.php?updatesite=$mSentry_key","./sentry.php?show=$mSentry_key");
                                                                                 if ($mNotice != "") {
                                                                                    echo sentrynoticetable($mNotice);
                                                                                 }
                                                                              } else
                                                                                 if ($mMode == "showedit_sentry") {
                                                                                    sentrygetstartdateparts($mSentry_startdate,$mShowYear,$mShowMonth,$mShowDay);
                                                                                    echo addsentryformtable("./sentry.php?updatesentry=$mSentry_key","800","update","<b>edit schedule entry</b>","./sentry.php?show=$mSentry_key");
                                                                                    if ($mNotice != "") {
                                                                                       echo sentrynoticetable($mNotice);
                                                                                    }
                                                                                 } else
                                                                                    if ($mMode == "editcustlink") {
                                                                                       //$mSentry_Heading,$mSentry_Startdate
                                                                                       echo customerlistforlink("change customer linked $mSentry_heading","show_sentry");
                                                                                       if ($mNotice != "") {
                                                                                          echo sentrynoticetable($mNotice);
                                                                                       }
                                                                                    } else
                                                                                       if ($mMode == "showday") {
                                                                                          echo sentriesbyday("all entries for " . date("F j,Y",$mShowStamp),$mShowMonth,$mShowYear,$mShowDay);
                                                                                          if ($mNotice != "") {
                                                                                             echo sentrynoticetable($mNotice);
                                                                                          }
                                                                                       } else
                                                                                          if ($mMode == "delistconfirm") {
                                                                                             echo confirmsentrydelist("./sentry.php?delist=$mSentry_key&amp;confirm");
                                                                                             if ($mNotice != "") {
                                                                                                echo sentrynoticetable($mNotice);
                                                                                             }
                                                                                          } else
                                                                                             if ($mMode == "printday") {
                                                                                                echo sentriesbydayforprint("all entries for " . date("F j,Y",$mShowStamp),$mShowMonth,$mShowYear,$mShowDay);
                                                                                                if ($mNotice != "") {
                                                                                                   echo sentrynoticetable($mNotice);
                                                                                                }
                                                                                             } else
                                                                                                if ($mMode == "duplicate") {
                                                                                                   //$mSentry_heading,$mSentry_startdate,$mSentry_starttime,$mSentry_sentrytype,$mSentry_custkey,$mSentry_custname
                                                                                                   $mShow_heading = substr($mSentry_heading,0,6);
                                                                                                   $mShow_custname = substr($mSentry_custname,0,6);
                                                                                                   echo actionmonthv("./sentry.php?submitdupe=%entry.key%&amp;month=%date:n%&amp;day=%date:j%&amp;year=%date:Y%",$mShowMonth,$mShowYear,"dupeselect","select day to duplicate sentry $mSentry_key ($mShow_custname:$mSentry_sentrytype $mShow_heading)","duplicate",$mShow_sentries,"./sentry.php?show=$mSentry_key");
                                                                                                   if ($mNotice != "") {
                                                                                                      echo sentrynoticetable($mNotice);
                                                                                                   }
                                                                                                } else
                                                                                                   if ($mMode == "godate") {
                                                                                                      if ($mNotice != "") {
                                                                                                         echo sentrynoticetable($mNotice);
                                                                                                      }
                                                                                                   }
   
   
                                                                                                if ($mMode != "printday") {
                                                                                                   echo bottom();
                                                                                                }
                                                                                                if ($mMode == "printday") {
                                                                                                   echo bottomminimal();
                                                                                                }