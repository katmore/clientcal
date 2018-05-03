<?php 

namespace clientcal;

function days_in_month($ignore_cal,$month, $year) { 
 return date('t', mktime(0, 0, 0, $month+1, 0, $year)); 
}

   function AddSentry($My,$Table,$Heading,$Notes,$Startdate,$Starttime,$Supervisorkey,$Sentrytype,&$pKey) {
      if (!is_numeric($Supervisorkey)) {
         throw new error(-10,"bad format for Supervisorkey '$Supervisorkey'");
      }
      $sql = "
      INSERT INTO
         $Table
      SET
         heading='$Heading',
         notes='$Notes',
         startdate='$Startdate',
         starttime='$Starttime',
         supervisor=$Supervisorkey,
         sentrytype='$Sentrytype'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      $pKey = @mysql_insert_id($My);
      return 0;
   }
   function EnumerateSentriesByMonthAndYear($My,$Table,$MonthNo,$Year,$LimitStart,$LimitMax,&$pCount,&$pKey,&$pHeading,&$pNotes,&$pStartdate,&$pStarttime,&$pSupervisor,&$pSentrytype,&$pCustomer,&$pLastUpdated) {
      $pCount = 0;
      $pKey = array();$pHeading = array();$pNotes = array();$pStartdate = array();$pStarttime = array();$pSupervisor = array();$pSentrytype = array();$pCustomer = array();$pLastUpdated = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      $sql = "
      SELECT
         id,
         heading,
         notes,
         startdate,
         starttime,
         supervisor,
         sentrytype,
         customer,
         last_updated
      FROM
         $Table
      WHERE
         MONTH(startdate)=$MonthNo
      AND
         YEAR(startdate)=$Year
      AND
         listlevel>0
      ORDER BY
         startdate
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pKey[$pCount] = $row["id"];
         $pHeading[$pCount] = $row["heading"];
         $pNotes[$pCount] = $row["notes"];
         $pStartdate[$pCount] = $row["startdate"];
         $pStarttime[$pCount] = $row["starttime"];
         $pSupervisor[$pCount] = $row["supervisor"];
         $pSentrytype[$pCount] = $row["sentrytype"];
         $pCustomer[$pCount] = $row["customer"];
         $pLastUpdated[$pCount] = $row["last_updated"];
         $pCount++;
      }
      return 0;
   }
   function UpdateSentry($My,$TableSentry,$Sentrykey,$NewHeading,$NewNotes,$NewStartdate,$NewStarttime,$NewSupervisorkey,$NewSentrytype) {
      if (!is_numeric($Sentrykey)) throw new error(-5,"bad sentry key given");
      if (!is_numeric($NewSupervisorkey)) throw new error(-5,"bad sentry key given");
      $sql = "
      UPDATE
         $TableSentry
      SET
         heading='$NewHeading',
         notes='$NewNotes',
         startdate='$NewStartdate',
         starttime='$NewStarttime',
         supervisor=$NewSupervisorkey,
         sentrytype='$NewSentrytype'
      WHERE
         id=$Sentrykey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      return 0;
   }
   function UpdateSentryCustomer($My,$Table,$Key,$CustomerKey) {
      if (!is_numeric($Key)) throw new error(-5,"bad sentry key given");
      if (!is_numeric($CustomerKey)) throw new error(-5,"bad customer key given");
      $sql = "
      UPDATE
         $Table
      SET
         customer=$CustomerKey
      WHERE
         id=$Key
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      return 0;
   }
   function GetSomeSentryAndSiteInfo($My,$TableSentry,$TableSite,$SentryKey,&$pSentry_Heading,&$pSentry_Startdate,&$pSentry_Starttime,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pSite_sdirections,&$pSentry_LastUpdated,&$pSite_LastUpdated) {
      $pSentry_Heading = "";$pSentry_Startdate = "";$pSentry_Starttime = "";$pSite_streetaddr = "";$pSite_city = "";$pSite_state = "";$pSite_zip = "";$pSite_sdirections = "";$pSentry_LastUpdated = "";$pSite_LastUpdated = "";
      if (!is_numeric($SentryKey)) throw new error(-5,"bad sentry key given");
     $sql = "
      SELECT
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.last_updated AS sentry_lastupdated,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TableSite.sdirections AS site_sdirections,
         $TableSite.last_updated AS site_lastupdated
      FROM
         $TableSentry
      LEFT JOIN
         $TableSite
      ON
         $TableSentry.id=$TableSite.sentry
      WHERE
         $TableSentry.id=$SentryKey
      LIMIT
         0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (!($row = mysql_fetch_assoc($result)))
         throw new error(-1,"no sentry found with that key:$SentryKey");
      $pSentry_Heading = $row["sentry_heading"];
      $pSentry_Startdate = $row["sentry_startdate"];
      $pSentry_Starttime = $row["sentry_starttime"];
      $pSite_streetaddr = $row["site_streetaddr"];
      $pSite_city = $row["site_city"];
      $pSite_state = $row["site_state"];
      $pSite_zip = $row["site_zip"];
      $pSite_sdirections = $row["site_sdirections"];
      $pSentry_LastUpdated = $row["sentry_lastupdated"];
      $pSite_LastUpdated = $row["site_lastupdated"];
      /*
      $pHeading = $row["sentry_heading"];
      $pStartdate = $row["sentry_startdate"];
      $pStarttime = $row["sentry_starttime"];
      $pSite_streetaddr = $row["site_streetaddr"];
      $pSite_city = $row["site_city"];
      $pSite_state = $row["site_state"];
      $pSite_zip = $row["site_zip"];
      $pSite_sdirections = $row["site_sdirections"];
      $pSentry_LastUpdated = $row["sentry_lastupdated"];
      $pSite_LastUpdated = $row["site_lastupdated"];
      */
      return 0;
   }
   function GetSentryHeading($My,$TableSentry,$SentryKey,&$pHeading) {
      $pHeading = "";
      if (!is_numeric($SentryKey)) throw new error(-7,"sentry key given in bad format:$SentryKey");
      $sql = "
      SELECT
         heading
      FROM
         $TableSentry
      WHERE
         id=$SentryKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"no sentry with that key");
      $row = mysql_fetch_assoc($result);
      $pHeading = $row["heading"];
      return 0;
   }
   function DelistSentry($My,$TableSentry,$SentryKey) {
      if (!is_numeric($SentryKey)) throw new error(-7,"sentry key given in bad format:$SentryKey");
      $sql = "
      UPDATE
         $TableSentry
      SET
         listlevel=0
      WHERE
         id=$SentryKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new error(-1,"no sentry by that key:$SentryKey");
      return 0;
   }
   function GetSentryWCustName($My,$TableSentry,$TableCust,$SentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pCust_key,&$pCust_name) {
      $pHeading = "";$pStartdate = "";$pStarttime = "";$pSentrytype = "";$pCust_key = "";$pCust_name = "";
      if (!is_numeric($SentryKey)) throw new error(-7,"sentry key given in bad format:$SentryKey");
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSentry.customer AS customer_key,
         $TableCust.name AS customer_name
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      WHERE
         $TableSentry.id=$SentryKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no record with that sentry");
      }
      $row = mysql_fetch_assoc($result);
      $pHeading = $row["sentry_heading"];
      $pStartdate = $row["sentry_startdate"];
      $pStarttime = $row["sentry_starttime"];
      $pSentrytype = $row["sentry_sentrytype"];
      $pCust_key = $row["customer_key"];
      $pCust_name = $row["customer_name"];
      return 0;
   }
   function GetSentryWSomeSiteAndCustInfoB($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$SentryKey,&$pHeading,&$pNotes,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_key,&$pSupervisor_name,&$pCust_key,&$pCust_name,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pSite_sdirections) {
      $pHeading = "";$pNotes = "";$pStartdate = "";$pStarttime = "";$pSentrytype = "";$pSupervisor_key = "";$pSupervisor_name = "";$pCust_name = "";$pSite_streetaddr = "";$pSite_city = "";$pSite_state = "";$pSite_zip = "";$pSite_sdirections = "";
      if (!is_numeric($SentryKey)) throw new error(-7,"sentry key given in bad format:$SentryKey");
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.notes AS sentry_notes,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSentry.customer AS customer_key,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TableSite.sdirections AS site_sdirections
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      WHERE
         $TableSentry.id=$SentryKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no record with that sentry");
      }
      $row = mysql_fetch_assoc($result);
      $pHeading = $row["sentry_heading"];
      $pNotes = $row["sentry_notes"];
      $pStartdate = $row["sentry_startdate"];
      $pStarttime = $row["sentry_starttime"];
      $pSentrytype = $row["sentry_sentrytype"];
      $pSupervisor_key = $row["sentry_supervisor"];
      $pSupervisor_name = $row["supervisor_name"];
      $pCust_key = $row["customer_key"];
      $pCust_name = $row["customer_name"];
      $pSite_streetaddr = $row["site_streetaddr"];
      $pSite_city = $row["site_city"];
      $pSite_state = $row["site_state"];
      $pSite_zip = $row["site_zip"];
      $pSite_sdirections = $row["site_sdirections"];
      //$pPriphone_num = $row["phone_number"];
      //$pPriphone_type = $row["phone_type"];
      return 0;
   }
   function GetSentryWSomeSiteAndCustInfo($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$SentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pCust_key,&$pCust_name,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pPriphone_num,&$pPriphone_type) {
      $pHeading = "";$pStartdate = "";$pStarttime = "";$pSentrytype = "";$pSupervisor_name = "";$pCust_name = "";$pSite_streetaddr = "";$pSite_city = "";$pSite_state = "";$pSite_zip = "";$pPriphone_num = "";$pPriphone_type = "";
      if (!is_numeric($SentryKey)) throw new error(-7,"sentry key given in bad format:$SentryKey");
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSentry.customer AS customer_key,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TableSite.sdirections AS site_sdirections,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         $TableSentry.id=$SentryKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no record with that sentry");
      }
      $row = mysql_fetch_assoc($result);
      $pHeading = $row["sentry_heading"];
      $pStartdate = $row["sentry_startdate"];
      $pStarttime = $row["sentry_starttime"];
      $pSentrytype = $row["sentry_sentrytype"];
      $pSupervisor_name = $row["supervisor_name"];
      $pCust_key = $row["customer_key"];
      $pCust_name = $row["customer_name"];
      $pSite_streetaddr = $row["site_streetaddr"];
      $pSite_city = $row["site_city"];
      $pSite_state = $row["site_state"];
      $pSite_zip = $row["site_zip"];
      $pPriphone_num = $row["phone_number"];
      $pPriphone_type = $row["phone_type"];
      return 0;
   }
   function EnumerateSentryCountByDay($My,$TableSentry,$MonthNo,$Year,&$pSentryCount) {
      //account for repeat in here (maybe)
      $pSentryCount = array();
      $sql = "
      SELECT
         startdate,
         COUNT(startdate) AS startdate_count
      FROM
         $TableSentry
      WHERE
         MONTH(startdate)=$MonthNo
      AND 
         YEAR(startdate)=$Year
      GROUP BY
         startdate
      ASC 
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      $sCheckCount = mysql_num_rows($result);
      $sDaysInMonth = days_in_month(CAL_GREGORIAN,$MonthNo,$Year);
      $sCurDay = 1;
      if ($sCheckCount > 0) {
         $row = mysql_fetch_assoc($result);
         $sCheckDate = $row["startdate"];
         while ($sCurDay<$sDaysInMonth + 1) {
            if ($sCheckDate !== FALSE) {
               if (date("Y-m-d",mktime(0,0,0,$MonthNo,$sCurDay,$Year)) == $sCheckDate) {
                  $pSentryCount[$sCurDay] = $row["startdate_count"];
                  if ($row = mysql_fetch_assoc($result)) {
                     $sCheckDate = $row["startdate"];
                  } else {
                     $sCheckDate = FALSE;
                  }
               } else {
                  $pSentryCount[$sCurDay] = 0;
               }
            } else {
               $pSentryCount[$sCurDay] = 0;
            }
            $sCurDay++;
         }
      } else {
         while ($sCurDay<$sDaysInMonth + 1) {
            $pSentryCount[$sCurDay] = 0;
            $sCurDay++;
         }
      }
      return 0;
   }
   function EnumerateSentriesWCustNameByDay($My,$TableSentry,$TableSupervisor,$TableCust,$MonthNo,$Day,$Year,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStarttime,&$pSentrytype,&$pSupervisor,&$pSupervisorKey,&$pCustName) {
      //account for repeat in here! (actually not i dont think)
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStarttime = array();$pSentrytype = array();$pSupervisor = array();$pSupervisorKey = array();$pCustName = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      //$sDate = "$Year-$MonthNo-$Day";
      $sStamp = mktime(0,0,0,$MonthNo,$Day,$Year);
      $sDate = date("Y-m-d",$sStamp);
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS supervisor_key,
         $TableSentry.sentrytype AS sentry_typename,
         $TableSentry.customer AS sentry_custkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.name AS cust_name
         
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      WHERE
         startdate='$sDate'
      AND
         listlevel>0
      ORDER BY
         startdate
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      //$pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor = array();$pCust_name = array();
      while($row = mysql_fetch_assoc($result)) {
         $pCustName[$pCount] = $row["cust_name"];
         $pSupervisor[$pCount] = $row["supervisor_name"];
         $pSupervisorKey[$pCount] = $row["supervisor_key"];
         $pSentrytype[$pCount] = $row["sentry_typename"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         //$pStartdate[$pCount] = $row["sentry_startdate"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pCount++;
      }
      //trigger_error($pCount);
      return 0;
   }
   function EnumerateSentriesWSomeSiteAndCustInfoForDayB($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$MonthNo,$Year,$Day,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pSupervisor_key,&$pCust_name,&$pCust_phone,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pPriphone_num,&$pPriphone_type) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor_name = array();$pSupervisor_key = array();$pCust_name = array();$pCust_phone = array();$pSite_streetaddr = array();$pSite_city = array();$pSite_state = array();$pSite_zip = array();$pPriphone_num = array();$pPriphone_type = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      if (!is_numeric($Year)) throw new error(-7,"year given in bad format:$Year");
      if (!is_numeric($Day)) throw new error(-8,"day given in bad format:$Day");
      if ($Day > days_in_month(CAL_GREGORIAN,$MonthNo,$Year)) throw new error(-9,"bad day given based on given month:$Day");
      $sDate = date("Y-m-d",mktime(0,0,0,$MonthNo,$Day,$Year));
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.id AS customer_key,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         startdate='$sDate'
      AND
         listlevel>0
      ORDER BY
         starttime
      ASC,
         sentrytype
      ASC
      ";
      //trigger_error($sql);
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while($row = mysql_fetch_assoc($result)) {
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_startdate"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         $pSupervisor_key[$pCount] = $row["sentry_supervisor"];
         $pSupervisor_name[$pCount] = $row["supervisor_name"];
         $pSentrytype[$pCount] = $row["sentry_sentrytype"];
         $pCust_name[$pCount] = $row["customer_name"];
         $pSite_streetaddr[$pCount] = $row["site_streetaddr"];
         $pSite_city[$pCount] = $row["site_city"];
         $pSite_state[$pCount] = $row["site_state"];
         $pSite_zip[$pCount] = $row["site_zip"];
         $pPriphone_num[$pCount] = $row["phone_number"];
         $pPriphone_type[$pCount] = $row["phone_type"];
         $pCount++;
      }
      return 0;
   }
   function EnumerateSentriesWSomeSiteAndCustInfoForDayC($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$MonthNo,$Year,$Day,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pCust_name,&$pCust_phone,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pSite_sdirections,&$pPriphone_num,&$pPriphone_type) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor_name = array();$pCust_name = array();$pCust_phone = array();$pSite_streetaddr = array();$pSite_city = array();$pSite_state = array();$pSite_zip = array();$pSite_sdirections = array();$pPriphone_num = array();$pPriphone_type = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      if (!is_numeric($Year)) throw new error(-7,"year given in bad format:$Year");
      if (!is_numeric($Day)) throw new error(-8,"day given in bad format:$Day");
      if ($Day > days_in_month(CAL_GREGORIAN,$MonthNo,$Year)) throw new error(-9,"bad day given based on given month:$Day");
      $sDate = date("Y-m-d",mktime(0,0,0,$MonthNo,$Day,$Year));
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.id AS customer_key,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TableSite.sdirections AS site_sdirections,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         startdate='$sDate'
      AND
         listlevel>0
      ORDER BY
         sentrytype
      ASC,
         starttime
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while($row = mysql_fetch_assoc($result)) {
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_startdate"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         $pSupervisor_name[$pCount] = $row["supervisor_name"];
         $pSentrytype[$pCount] = $row["sentry_sentrytype"];
         $pCust_name[$pCount] = $row["customer_name"];
         $pSite_streetaddr[$pCount] = $row["site_streetaddr"];
         $pSite_city[$pCount] = $row["site_city"];
         $pSite_state[$pCount] = $row["site_state"];
         $pSite_zip[$pCount] = $row["site_zip"];
         $pPriphone_num[$pCount] = $row["phone_number"];
         $pPriphone_type[$pCount] = $row["phone_type"];
         $pSite_sdirections[$pCount] = $row["site_sdirections"];
         $pCount++;
      }
      return 0;
   }
   function EnumerateSentriesWSomeSiteAndCustInfoForDay($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$MonthNo,$Year,$Day,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pCust_name,&$pCust_phone,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pPriphone_num,&$pPriphone_type) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor_name = array();$pCust_name = array();$pCust_phone = array();$pSite_streetaddr = array();$pSite_city = array();$pSite_state = array();$pSite_zip = array();$pPriphone_num = array();$pPriphone_type = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      if (!is_numeric($Year)) throw new error(-7,"year given in bad format:$Year");
      if (!is_numeric($Day)) throw new error(-8,"day given in bad format:$Day");
      if ($Day > days_in_month(CAL_GREGORIAN,$MonthNo,$Year)) throw new error(-9,"bad day given based on given month:$Day");
      $sDate = date("Y-m-d",mktime(0,0,0,$MonthNo,$Day,$Year));
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.id AS customer_key,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         startdate='$sDate'
      AND
         listlevel>0
      ORDER BY
         starttime
      ASC,
         sentrytype
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while($row = mysql_fetch_assoc($result)) {
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_startdate"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         $pSupervisor_name[$pCount] = $row["supervisor_name"];
         $pSentrytype[$pCount] = $row["sentry_sentrytype"];
         $pCust_name[$pCount] = $row["customer_name"];
         $pSite_streetaddr[$pCount] = $row["site_streetaddr"];
         $pSite_city[$pCount] = $row["site_city"];
         $pSite_state[$pCount] = $row["site_state"];
         $pSite_zip[$pCount] = $row["site_zip"];
         $pPriphone_num[$pCount] = $row["phone_number"];
         $pPriphone_type[$pCount] = $row["phone_type"];
         $pCount++;
      }
      return 0;
   }
   function EnumerateSentriesWSomeSiteAndCustInfoForWeek($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$WeekNo,$Year,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pCust_name,&$pCust_phone,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pPriphone_num,&$pPriphone_type) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor_name = array();$pCust_name = array();$pCust_phone = array();$pSite_streetaddr = array();$pSite_city = array();$pSite_state = array();$pSite_zip = array();$pPriphone_num = array();$pPriphone_type = array();
      //if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      //if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      //echo "WeekNo=$WeekNo"; die();
      $WeekNo = $WeekNo - 1; //ISO Compliancy!! 
      if (!is_numeric($Year)) throw new error(-7,"year given in bad format:$Year");
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.id AS customer_key,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         WEEK(startdate)=$WeekNo
      AND
         YEAR(startdate)=$Year
      AND
         listlevel>0
      ORDER BY
         startdate
      ASC,
         starttime
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while($row = mysql_fetch_assoc($result)) {
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_startdate"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         $pSupervisor_name[$pCount] = $row["supervisor_name"];
         $pSentrytype[$pCount] = $row["sentry_sentrytype"];
         $pCust_name[$pCount] = $row["customer_name"];
         $pSite_streetaddr[$pCount] = $row["site_streetaddr"];
         $pSite_city[$pCount] = $row["site_city"];
         $pSite_state[$pCount] = $row["site_state"];
         $pSite_zip[$pCount] = $row["site_zip"];
         $pPriphone_num[$pCount] = $row["phone_number"];
         $pPriphone_type[$pCount] = $row["phone_type"];
         $pCount++;
      }
      return 0;
   }
   function EnumerateSentriesWSomeSiteAndCustInfo($My,$TableSentry,$TableSupervisor,$TableCust,$TableSite,$TablePhone,$MonthNo,$Year,$LimitStart,$LimitMax,&$pCount,&$pSentryKey,&$pHeading,&$pStartdate,&$pStarttime,&$pSentrytype,&$pSupervisor_name,&$pCust_name,&$pCust_phone,&$pSite_streetaddr,&$pSite_city,&$pSite_state,&$pSite_zip,&$pPriphone_num,&$pPriphone_type) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pStarttime = array();$pSentrytype = array();$pSupervisor_name = array();$pCust_name = array();$pCust_phone = array();$pSite_streetaddr = array();$pSite_city = array();$pSite_state = array();$pSite_zip = array();$pPriphone_num = array();$pPriphone_type = array();
      if (!is_numeric($MonthNo)) throw new error(-5,"month given in bad format:$MonthNo");
      if (($MonthNo > 12) || ($MonthNo < 1)) throw new error(-6,"bad month given:$MonthNo");
      if (!is_numeric($Year)) throw new error(-7,"year given in bad format:$Year");
      $sql = "
      SELECT
         $TableSentry.id AS sentry_key,
         $TableSentry.heading AS sentry_heading,
         $TableSentry.startdate AS sentry_startdate,
         $TableSentry.starttime AS sentry_starttime,
         $TableSentry.supervisor AS sentry_supervisor,
         $TableSentry.sentrytype AS sentry_sentrytype,
         $TableSentry.customer AS sentry_customerkey,
         $TableSupervisor.name AS supervisor_name,
         $TableCust.id AS customer_key,
         $TableCust.name AS customer_name,
         $TableSite.sentry AS site_sentrykey,
         $TableSite.streetaddr AS site_streetaddr,
         $TableSite.city AS site_city,
         $TableSite.state AS site_state,
         $TableSite.zip AS site_zip,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         $TableSentry
      LEFT JOIN
         $TableCust
      ON
         $TableSentry.customer = $TableCust.id
      LEFT JOIN
         $TableSite
      ON
         $TableSite.sentry = $TableSentry.id
      LEFT JOIN
         $TableSupervisor
      ON
         $TableSentry.supervisor = $TableSupervisor.id
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         MONTH(startdate)=$MonthNo
      AND
         YEAR(startdate)=$Year
      AND
         listlevel>0
      ORDER BY
         startdate
      ASC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      while($row = mysql_fetch_assoc($result)) {
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_startdate"];
         $pStarttime[$pCount] = $row["sentry_starttime"];
         $pSupervisor_name[$pCount] = $row["supervisor_name"];
         $pSentrytype[$pCount] = $row["sentry_sentrytype"];
         $pCust_name[$pCount] = $row["customer_name"];
         $pSite_streetaddr[$pCount] = $row["site_streetaddr"];
         $pSite_city[$pCount] = $row["site_city"];
         $pSite_state[$pCount] = $row["site_state"];
         $pSite_zip[$pCount] = $row["site_zip"];
         $pPriphone_num[$pCount] = $row["phone_number"];
         $pPriphone_type[$pCount] = $row["phone_type"];
         $pCount++;
      }
      return 0;
   }
   function GetSentryLastUpdate($My,$Table,$Key,&$pLastUpdate) {
      $pLastUpdate = "";
      if (!is_numeric($Key)) {
         throw new error(-10,"bad format for key:'$Key' (non numeric)");
      }
      $sql = "
      SELECT
         last_updated
      FROM
         $Table
      WHERE
         id=$Key
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no sentry found with that key:$Key");
      }
      $row = mysql_fetch_assoc($result);
      $pLastUpdate = $row["last_updated"];
      return 0;
   }
   function GetSentryStartdate($My,$Table,$Key,&$pStartdate,&$pLastUpdate) {
      $pStartdate = "";$pLastUpdate = "";
      if (!is_numeric($Key)) {
         throw new error(-10,"bad format for key:'$Key' (non numeric)");
      }
      $sql = "
      SELECT
         startdate,
         last_updated
      FROM
         $Table
      WHERE
         id=$Key
      LIMIT
         0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no sentry found with that key");
      }
      $row = mysql_fetch_assoc($result);
      $pStartdate = $row["startdate"];
      $pLastUpdate = $row["last_updated"];
      return 0;
   }
   function GetSentry($My,$Table,$Key,&$pHeading,&$pNotes,&$pStartdate,&$pStarttime,&$pSupervisor,&$pSentrytype,&$pLastUpdate) {
      $pHeading = "";$pNotes = "";$pStartdate = "";$pStarttime = "";$pSupervisor = "";$pSentrytype = "";$pLastUpdate = "";
      if (!is_numeric($Key)) {
         throw new error(-10,"bad format for key:'$Key' (non numeric)");
      }
      //
      $sql = "
      SELECT 
         heading,
         notes,
         startdate,
         starttime,
         supervisor,
         sentrytype,
         last_updated
      FROM
         $Table
      WHERE
         id=$Key
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no sentry found with that key");
      }
      $row = mysql_fetch_assoc($result);
      $pHeading =  $row["heading"];
      $pNotes =  $row["notes"];
      $pStartdate =  $row["startdate"];
      $pStarttime =  $row["starttime"];
      $pSupervisor =  $row["supervisor"];
      $pSentrytype =  $row["sentrytype"];
      //$pWeekdayrepeat = $row["weekdayrepeat"];
      $pLastUpdate = $row["last_updated"];
   }
   function EnumerateSentrytypes($My,$Table,&$pCount,&$pName,&$pBrief,&$pDescription) {
      $pCount = 0;$pName = array();$pBrief = array();$pDescription = array();
      $sql = "
      SELECT
         name,
         brief,
         description
      FROM
         $Table
      ORDER BY
         weight
      DESC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pName[$pCount] = $row["name"];
         $pBrief[$pCount] = $row["brief"];
         $pDescription[$pCount] = $row["description"];
         $pCount++;
      }
      return 0;
   }

