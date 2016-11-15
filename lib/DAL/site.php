<?php

namespace clientcal;

   function GetSiteLatLon($My,$Table,$Sentry,&$pLat,&$pLon) {
      //echo $Sentry; die();
      //if (!is_int($Sentry)) throw new Error(-2,"invalid Sentry");
      $pLat = 0;$pLon = 0;
      //see if valid lat/lon exists in entry
      $sql = "
SELECT
   site.lat AS lat,
   site.lon AS lon
FROM
   site
WHERE
   site.sentry=$Sentry
LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new Error(-1,"while get: no sentry with that name");
      }
      $row = mysql_fetch_assoc($result);
      $sValid = false;
      //if valid, return them
      if ( (is_numeric($row["lat"])) && (is_numeric($row["lon"])) )
      if ( ($row["lat"] != 0) && ($row["lat"] != 0) ) {
         $sValid = true;
         $pLat = $row["lat"];
         $pLon = $row["lon"];
         return 0;
      }

      //not valid, do geolocate
      $sql = "
SELECT
   site.streetaddr AS streetaddr,
   site.city AS city,
   site.state AS state,
   site.zip AS zip
FROM
   site
WHERE
   site.sentry=$Sentry
LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while get addr: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new Error(-1,"while get addr: no sentry with that name");
      }
      $row = mysql_fetch_assoc($result);
      if ($row["state"] == "") {
         $sState = "MO";
      } else {
         $sState =$row["state"];
      }

      //if streetaddr is blank, don't bother
      if ($row["streetaddr"] == "") {
         return 0;
      }
      $sStrAddr = $row["streetaddr"];
      $sStrAddr = str_replace("FR ","Farm Road ",$sStrAddr);
      $sAddr = $sStrAddr . ", " . $row["city"] . ", " . $sState . ", U.S.A. ";
      
      $sRet = GetGeoReverse($sAddr,$sLat,$sLon);

      //echo "addr:'$sAddr'<br>Loc:$sLat,$sLon";die();
      $pLat = $sLat;
      $pLon = $sLon;
      //update into db so don't hafta use up geoloc queries
      $sql = "
UPDATE
   site
SET
   site.lat='$sLat',
   site.lon='$sLon'
WHERE
   site.sentry=$Sentry
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while update addr: " . mysql_error());


      return 0;
   }
   function GetSiteLastUpdate($My,$Table,$Sentry,&$pLastUpdated) {
      $pLastUpdated = "";
      $sql = "
      SELECT
         last_updated
      FROM
         $Table
      WHERE
         sentry=$Sentry
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         return -1;
         //throw new Error(-1,"no sentry with that name");
      }
      $row = mysql_fetch_assoc($result);
      $pLastUpdated = $row["last_updated"];
      return 0;
   }
   function GetSite($My,$TableSite,$SentryKey,&$pStreetaddr,&$pCity,&$pState,&$pZip,&$pSdirections,&$pLastUpdated) {
      $pStreetaddr = "";$pCity = "";$pState = "";$pZip = "";$pSdirections = "";$pLastUpdated = "";
      if (!is_numeric($SentryKey)) throw new Error(-5,"sentry key given in bad format:$SentryKey");
      $sql = "
      SELECT
         streetaddr,
         city,
         state,
         zip,
         sdriections,
         last_updated
      FROM
         $TableSite
      WHERE
         sentry=$SentryKey
      LIMIT
         0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while get: " . mysql_error());
      if (!($row = mysql_fetch_assoc($result)))
         throw new Error(-1,"nothing found with that SentryKey:$SentryKey");
      $pStreetaddr = $row["streetaddr"];
      $pCity = $row["city"];
      $pState = $row["state"];
      $pZip = $row["zip"];
      $pSdirections = $row["sdriections"];
      $pLastUpdated = $row["last_updated"];
      return 0;
   }
   function UpdateSite($My,$Table,$Sentry,$Streetaddr,$City,$State,$Zip,$Sdirections) {
      if (!is_numeric($Sentry)) throw new Error(-5,"sentry key given in bad format");
      $sql = "
      UPDATE
         $Table
      SET
         streetaddr='$Streetaddr',
         city='$City',
         state='$State',
         zip='$Zip',
         sdirections='$Sdirections',
         lat='',
         lon=''
      WHERE
         sentry=$Sentry
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while update: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new Error(-1,"no customer with that key");
      return 0;
   }
   function AddSite($My,$Table,$Sentry,$Streetaddr,$City,$State,$Zip,$Sdirections) {
      if (!is_numeric($Sentry)) throw new Error(-5,"sentry key given in bad format");
      $sql = "
      INSERT INTO
         $Table
      SET
         sentry=$Sentry,
         streetaddr='$Streetaddr',
         city='$City',
         state='$State',
         zip='$Zip',
         sdirections='$Sdirections'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while get: " . mysql_error());
      return 0;
   }
   function AssociateSite($My,$Table,$Sentry,$Streetaddr,$City,$State,$Zip,$Sdirections) {
      if (!is_numeric($Sentry)) throw new Error(-5,"bad sentry key given");
      $sRet = GetSiteLastUpdate($My,$Table,$Sentry,$sLastUpdated);
      if ($sRet == 0) {
         $sRet = UpdateSite($My,$Table,$Sentry,$Streetaddr,$City,$State,$Zip,$Sdirections);
         if ($sRet != 0) {
            global $mError;
            throw new Error(-300,"problem ($sRet) while updating site info:" . $mError);
         }
      } else
      if ($sRet == -1) {
         $sRet = AddSite($My,$Table,$Sentry,$Streetaddr,$City,$State,$Zip,$Sdirections);
         if ($sRet != 0) {
            global $mError;
            throw new Error(-200,"problem ($sRet) while adding site info:" . $mError);
         }
      } else {
         global $mError;
         throw new Error(-100,"problem ($sRet) while getting last site update" . $mError);
      }
      return 0;
   }
