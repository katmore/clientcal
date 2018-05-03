<?php

namespace clientcal;

   function UpdateCustfileMailqueueStatus($My,$CustKey,$Hash,$NewStatus) {
      if (!is_numeric($CustKey)) throw new error(-20,"invalid customer key given");
      if (!is_numeric($NewStatus)) throw new error(-21,"invalid status given");
      $sql = "
      UPDATE
      	customer_mailqueue
      SET
      	status=$NewStatus
      WHERE
      	hash='".mysql_real_escape_string($Hash,$My)."'
      AND
      	customer=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My))) {
         throw new error(-4,"while update: (" . mysql_errno() . ")" . mysql_error());
      }
      if (mysql_affected_rows($My) < 1) {
         throw new error(-1,"no match for update status");
      }
      return 0;
   }
   
   function GetCustfileMailqueueCustKeyStatus($My,$Hash,&$pCustKey,&$pStatus) {
      
      $sql = "
      SELECT
      	status,
      	customer
      FROM
      	customer_mailqueue
      WHERE
      	hash='".mysql_real_escape_string($Hash,$My)."'
      ";
      if (!($result = @mysql_query($sql,$My))) {
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());
      }
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"no mailqueue match found");
      $row = mysql_fetch_assoc($result);
      $pStatus = $row["status"];
      $pCustKey = $row["customer"];
      return 0;
   }
   
   function AddCustfileMailqueue($My,$CustKey,$Username,&$pToken) {
      
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      
      //require("settings.php");
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      
      $sSalt1 = openssl_random_pseudo_bytes ( 1024 );
      $sSalt2 = uniqid ("",true );
      $sSalt3 = mt_rand(100000,999999);
      
      //which one is token, which one is hash
      $pToken = hash($hashalgo_custfile,$sSalt1 . $sSalt2 . $sSalt3 . $Username . $CustKey);
      $sHash = hash($hashalgo_custfile,$pToken);
      
      $sql = "
      INSERT INTO
      	customer_mailqueue
      SET
      	customer=$CustKey,
      	username='" . mysql_real_escape_string($Username,$My) . "',
      	
      	hash='" . $sHash . "'
      ";
      if (!($result = @mysql_query($sql,$My))) {
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());
      }
      
      return 0;
   }
   function UpdateCustfileDoctype($My,$CustKey,$Hash,$NewDoctype) {
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      $sql = "
      UPDATE
      	customer_file
      SET
      	doctype='" . mysql_real_escape_string($NewDoctype,$My) . "'
      WHERE
      	hash='" . mysql_real_escape_string($Hash,$My) . "'
      AND
      	customer=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());
      //if (mysql_affected_rows ($My) < 1)
      //   throw new error(-1,"nothing affected while update doctype");
      return 0;
   }
   function UpdateCustfileName($My,$CustKey,$Hash,$NewName) {
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      $sql = "
      UPDATE
      	customer_file
      SET
      	name='" . mysql_real_escape_string($NewName,$My) . "'
      WHERE
      	hash='" . mysql_real_escape_string($Hash,$My) . "'
      AND
      	customer=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update name: (" . mysql_errno() . ")" . mysql_error());
      //if (mysql_affected_rows ($My) < 1)
      //   throw new error(-1,"nothing affected while update name");
      return 0;
   }
   
   function GetCustfileName($My,$CustKey,$Hash,&$pName) {
      $pMimetype = "";
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      $sql = "
      SELECT
         name
      FROM
         customer_file
      WHERE
         customer=$CustKey
      AND
         hash='" . mysql_real_escape_string($Hash) . "'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());
      
      if (mysql_num_rows($result) < 1) throw new error(-1,"while fetch: no record found");
      $row = mysql_fetch_assoc($result);
      $pName = $row["name"];
      return 0;
   }
   
   function GetCustfileMimetype($My,$CustKey,$Hash,&$pMimetype) {
      $pMimetype = "";
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      $sql = "
      SELECT
         mimetype
      FROM
         customer_file
      WHERE
         customer=$CustKey
      AND
         hash='" . mysql_real_escape_string($Hash) . "'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());
      
      if (mysql_num_rows($result) < 1) throw new error(-1,"while fetch: no record found");
      $row = mysql_fetch_assoc($result);
      $pMimetype = $row["mimetype"];
      return 0;
   }
   function EnumerateCustfiles(
   $My,
   $CustKey,
   $LimitStart,
   $LimitMax,
   &$pCount,
   &$pHash,
   &$pDoctype,
   &$pTimestampAdded,
   &$pMimetype,
   &$pName
   ) {
      if (!is_numeric($CustKey)) throw new error(-2,"invalid customer key given");
      $pCount=0;$pHash=array();$pDoctype=array();$pTimestampAdded=array();$pMimetype=array();$pName=array();
      $sql = "
      SELECT
         hash,
         name,
         doctype,
         mimetype,
         UNIX_TIMESTAMP(created) AS created
      FROM
         customer_file
      WHERE
         customer=$CustKey
      ORDER BY
      	created DESC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: (" . mysql_errno() . ")" . mysql_error());

      $i=0;
      while ($row = mysql_fetch_assoc($result)) {
         //hash($hashalgo_custfile,$CustKey . "." . $row["hash"]);
         $pHash[$i] = $row["hash"];
         $pDoctype[$i] = $row["doctype"];
         $pTimestampAdded[$i] = $row["created"];
         $pMimetype[$i] = $row["mimetype"];
         $pName[$i] = $row["name"];
         $i++;
      }
      $pCount = $i;
      return 0;
   }
   
   function AddCustfile(
   $My,
   $CustKey,
   $Filename,
   $Name,
   $Doctype,
   $Mimetype,
   $Filedebug
   ) { //assuming $Filedebug = $_FILES['userfile'] or something similar for filedebugstr
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      
      //create hash of $File
      $file_hash = hash_file ( $hashalgo_custfile , $Filename);
      
      $filedebugstr = "";
      if (is_array($Filedebug))
      foreach ($Filedebug as $key => $value) {
         if (is_array($value)) {
            $filedebugstr .= $key . ":" . "{ARRAY:" . implode(",",base64_encode($value)) . "}";
         } else {
            $filedebugstr .= $key . ":" . base64_encode ( $value ) . ";";
         }
      }
      
      $serverdebugstr = "";
      foreach ($_SERVER as $key => $value) {
         if (is_array($value)) {
            $serverdebugstr .= $key . ":" . "{ARRAY:" . base64_encode(implode($value)) . "}";
         } else {
            $serverdebugstr .= $key . ":" . base64_encode ( $value ) . ";";
         }
      }
      //add entry in db
      $sql = "
      INSERT INTO
         customer_file
      SET
         hash='" . mysql_real_escape_string($file_hash)  . "',
         customer=$CustKey,
         name='" . preg_replace("/[^a-zA-Z0-9.]/", "-", $Name) . "',
         doctype='" . mysql_real_escape_string($Doctype) . "',
         mimetype='" . mysql_real_escape_string($Mimetype) . "',
         filedebug='" . mysql_real_escape_string($filedebugstr) . "',
         serverdebug='" . mysql_real_escape_string($serverdebugstr) . "',
         created=NOW()
      ";
      if (!($result = @mysql_query($sql,$My))) {
         if (1062 == mysql_errno()) {
            throw new error(-1062,"file already exists for customer");
         } else
         throw new error(-4,"while insert: (myerr:" . mysql_errno() . ") " . mysql_error());
      }
      
      //create file handle from hash of string '$CustKey.$file_hash' ('.' is literal)
      $sHandle = hash($hashalgo_custfile,$CustKey . "." . $file_hash);
      
      //copy file to $dir_custfiles
      rename ( $Filename , $dir_custfiles . $sHandle);
      
      return 0;
   }
   function GetImapMimetypeStr($Idx) {
      if ($Idx == 0) {
         return "text";
      } else
      if ($Idx == 1) {
         return "multipart";
      } else
      if ($Idx == 2) {
         return "message";
      } else
      if ($Idx == 3) {
         return "application";
      } else
      if ($Idx == 4) {
         return "audio";
      } else
      if ($Idx == 5) {
         return "image";
      } else
      if ($Idx == 6) {
         return "video";
      } else
      return "other";
   }
   function GetImapEncodingStr($Idx) {
      if ($Idx == 0) {
         return "7bit";
      } else
      if ($Idx == 1) {
         return "8bit";
      } else
      if ($Idx == 2) {
         return "binary";
      } else
      if ($Idx == 3) {
         return "base64";
      } else
      if ($Idx == 4) {
         return "quoted-printable";
      }
      return "other";
   }
   function ProcessCustfileMailqueue($My,&$pReport=NULL) {
      //require("settings.php");
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      //lock table to prevent race condition
      $sql = "
      LOCK TABLES
      	customer_mailqueue
      WRITE,
      	customer_file
      WRITE
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while lock: (" . mysql_errno() . ")" . mysql_error());
         
      if (false === ($sImap = imap_open(
         $custfile_mailup_imap_mailbox,
         $custfile_mailup_user,
         $custfile_mailup_pass
      ))) {
         throw new error(-10,"problem connect to mailbox");
      }
      $sEmailTotal = 0;
      $sAttachTotal = 0;
      $sCheck = imap_check($sImap);
      global $mNotice;
      $message = imap_fetch_overview($sImap,"1:{$sCheck->Nmsgs}",0);
      $i = 0;
      foreach ($message as $overview) {
         $iIgnore = true;
         $iHash = hash($hashalgo_custfile,$overview->subject);
         $iStatus = 10;
         //see if hash of message is mailup queue
         //if (0 == ($sRet = GetCustfileMailqueue($My,$CustKey,$iHash,&$iStatus))) {
         //GetCustfileMailqueueCustKeyStatus
         if (0 == ($sRet = GetCustfileMailqueueCustKeyStatus($My,$iHash,$iCustKey,$iStatus))) {
            if (is_numeric($iStatus))
            if ($iStatus < 1) {
               $iIgnore = false;
               $mNotice .= "found hash match (".substr($iHash,0,5)."...), will check for attachments<br>";
            } else {
               $mNotice .= "marking uid:".$overview->uid." for deletion (already processed)<br>";
               if (!imap_delete ( $sImap ,$overview->uid,FT_UID)) {
                  //".imap_last_error ( )."
                  $mNotice .= "could not tag ".$overview->uid." for deletion ".imap_last_error ( )."<br>";
               }
            }
         } else {
            if ($sRet == -1) {
               $mNotice .= "marking uid:".$overview->uid." for deletion (no match in queue)<br>";
               if (!imap_delete ( $sImap ,$overview->uid,FT_UID)) {
                  //".imap_last_error ( )."
                  $mNotice .= "could not tag ".$overview->uid." for deletion ".imap_last_error ( )."<br>";
               }
            } else {
               global $mError;
               $mNotice .= "error ($sRet) while checking uid:".$overview->uid." for hash match:<br>$mError<br>";
            }
         }
         
         $sFetch = imap_fetchstructure($sImap,$overview->uid,FT_UID);
         if (  (!$iIgnore) &&  (!isset($sFetch->parts))  ) {
            $sFetch->parts = array($sFetch);
            $mNotice .= "only one part, we think<br>";
         }
         if (  (!$iIgnore) &&  (isset($sFetch->parts))  ) {
            $mNotice .= " mail ".$overview->uid." has ".count($sFetch->parts)." parts with
            sub:'".substr($overview->subject,0,6)."...'
            ($hashalgo_custfile:'".substr($iHash,0,6)."...')<br>
            ";
            
            $j = 0;
            foreach ($sFetch->parts as $part) {
               if ($part->ifsubtype) {
                  
                 // $jDisposition = strtolower($part->subtype);
                  $jMimetype = GetImapMimetypeStr($part->type)."/".strtolower($part->subtype);
                  $jEncoding = GetImapEncodingStr($part->encoding);
                  $mNotice .= "
                  &nbsp;&nbsp;part $j overview:<br>
                   
                   &nbsp;&nbsp;&nbsp;&nbsp;mimetype:$jMimetype<br>
                   &nbsp;&nbsp;&nbsp;&nbsp;encoding:$jEncoding<br>
                   ";
                  
                  if (  ($jEncoding == "base64")  ) {
                     $sAttachTotal++;
                     if ($jEncoding == "base64") {//if it's base64 encoding
                        $jName = "{no name}";
                        if (isset($part->dparameters[0]->value))
                        $jName = $part->dparameters[0]->value;
                        $mNotice .= "&nbsp;&nbsp;&nbsp;&nbsp;attachment:'$jName'<br>";
                        $jAttachment = imap_fetchbody ( $sImap , $overview->uid , $j + 1 ,FT_UID );
                        $mNotice .= "base64 attachment value (truncated):<br>'".htmlentities(substr($jAttachment,0,10))."...'<br>";
                        $jAttachment = imap_base64 ($jAttachment);
                     }
                     //add other decoding strategies here (if needed)
                     //if ($jEncoding == "whatever") {}
                     
                     $jUniqueFilename = tempnam ( $dir_custfile_queue , "CCMailQueue" );
                     //save to tmp place
                     $mNotice .= "writing value to '".$jUniqueFilename."'<br>";
                     $jFileHandle = fopen ( $jUniqueFilename , "w");
                     $jBytes = fwrite ( $jFileHandle, $jAttachment);
                     $mNotice .= "wrote $jBytes bytes<br>";
                     fclose($jFileHandle);
                     $jFiledebug["name"] = $jName;
                     $jFiledebug["type"] = $jMimetype;
                     $jFiledebug["tmp_name"] = $jUniqueFilename;
                     $jFiledebug["size"] = $jBytes;
                     
                     $jFiledebug["via"] = "mailqueue:".$overview->from.";";
                     /*
                     = 
                     "name:".base64_encode($jName).
                     ";type:".base64_encode($jMimetype).
                     ";tmp_name:".base64_encode($jUniqueFilename).
                     ";size:".base64_encode($jBytes).
                     ";via:".base64_encode("mailqueue").
                     ";";
                     */
                     $jSuccess = false;
                     if (0 == 
                        ($sRet =
                           AddCustfile(
                              $My,
                              $iCustKey,
                              $jUniqueFilename,
                              $jName,
                              "unknown",
                              $jMimetype,
                              $jFiledebug
                           )
                        )
                     ) {
                        $jSuccess = true;
                     } else {
                        if ($sRet == -1062) {
                           
                           $mNotice .= "ignoring '".$jName."' (already exists for customer)</b>:<br>
                           &nbsp;&nbsp;md5:".md5_file ( $jUniqueFilename )."<br>";
                           unlink($jUniqueFilename);
                           $jSuccess = true;
                        } else {
                           global $mError;
                           $mNotice .= "<b>err ($sRet) while adding custfile:</b><br>$mError<br>";
                        }
                     }
                     
                     
                     //if added custfile successfully, add to purge list
                     if ($jSuccess) {
                        $sInboxPurge[$overview->uid] = $iCustKey;
                        $sDbasePurge[$iHash] = $iCustKey;
                     }
                     
                        
                     
                     
                  }
               } else {
                  $mNotice .= "no disposition, assuming no uid:".$overview->uid." has no attachment<br>";
                  $sInboxPurge[$overview->uid] = $iCustKey;
               }
               $j++;
            }/*end foreach message 'part'*/
            
         }
         $i++;    
      }/*end foreach message*/
      $sEmailTotal = $i;
      
      $sDoPurge = false;
      $sPurgeWhyStr = "implicit";
      if (isset($custfile_mailup_purge)) {
         if ($custfile_mailup_purge === true) {
            $sDoPurge = true;
            $sPurgeWhyStr = "explicit";
         } else
         if ($custfile_mailup_purge === false) {
            $sDoPurge = false;
            $sPurgeWhyStr = "explicit";
         }
      }
      if (!$sDoPurge)
         $mNotice .= "not doing purge ($sPurgeWhyStr)<br>";
      
      //purge successful attachments from inbox
      if ($sDoPurge)
      if (isset($sInboxPurge))
      foreach($sInboxPurge as $cuid => $cckey) {
         if (is_numeric($cckey)) {
            $mNotice .= "due to success, marking uid:$cuid for deletion<br>";
            if (!imap_delete ( $sImap , $cuid ,FT_UID )) {
               $mNotice .= "<br>could not tag uid:'$cuid' for deletion<br>";
            }
         }
      }
      
      
      //purge successful attachments from database (by changing status)
      if ($sDoPurge)
      if (isset($sDbasePurge))
      foreach($sDbasePurge as $chash => $cckey) {
         if (is_numeric($cckey)) {
            if (0 != ($sRet = UpdateCustfileMailqueueStatus($My,$cckey,$chash,1))) {
               global $mError;
               $mNotice .= "problem while updating status:<br>$mError<br>";
            }
         }
      }
      //run expunge
      if (!imap_expunge ( $sImap) )
         throw new error(-99,"problem ".imap_last_error ( )." while closing mailbox");
      
      //close imap stream
      if (!imap_close($sImap) )
         throw new error(-99,"problem ".imap_last_error ( )." while closing mailbox");
      
      //unlock tables
      $sql = "
      UNLOCK TABLES
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while unlock: (" . mysql_errno() . ")" . mysql_error());
      
      $pReport = "found $sAttachTotal attachments in $sEmailTotal messages";   
      return 0;
   }
   
   function RemoveCustfile($My,$CustKey,$Hash,&$pName) {
      $sql = "
      SELECT 
      	name
      FROM
      	customer_file
      WHERE
      	customer=$CustKey
      AND
      	hash='" . mysql_real_escape_string($Hash,$My) . "'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while finding file (" . mysql_errno() . "): " . mysql_error());
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"file not found");
         
      $row = mysql_fetch_assoc($result);
      $pName = $row["name"];
      
      
      $sql = "
      DELETE FROM
      	customer_file
      WHERE
      	customer=$CustKey
      AND
      	hash='" . mysql_real_escape_string($Hash,$My) . "'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new error(-1,"no rows deleted");
         
      foreach(enumcustomerconfig() as $k=>$v) $$k=$v;
      
      //get file handle
      $sHandle = hash($hashalgo_custfile,$CustKey . "." . $Hash);
      
      //delete from filesystem
      if (!unlink($dir_custfiles . $sHandle))
         throw new error(-7,"could not delete file");
      
      
      return 0;
   }
   
   function EnumerateCustomerJobs(
   $My,
   $CustKey,
   &$pCount,
   &$pSentryKey,
   &$pHeading,
   &$pStartdate,
   &$pSentrytype
   ) {
      $pCount = 0;
      $pSentryKey = array();$pHeading = array();$pStartdate = array();$pSentrytype = array();
      $sql_where = "
sentry.customer=" . mysql_real_escape_string($CustKey,$My);
      //create sql statement
      $sql = "
SELECT
 customer.id AS cust_key,
 customer.name AS cust_name,
 customer.streetaddr AS cust_addr,
 customer.city AS cust_city,
 customer.state AS cust_state,
 customer.zip AS cust_zip,
 sentry.id AS sentry_key,
 sentry.heading AS sentry_heading,
 sentry.startdate AS sentry_start,
 sentrytype.brief AS sentry_type,
 sentry.listlevel AS sentry_list
FROM
 customer
LEFT JOIN
   sentry
ON
   sentry.customer=customer.id
LEFT JOIN
   sentrytype
ON
   sentrytype.name=sentry.sentrytype
";
      $sql .= "
WHERE
   $sql_where
AND
   sentry.listlevel > 0
ORDER BY
   sentry.startdate DESC";
      //echo nl2br($sql); die();
      $pCount = 0;
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         //$pCustKey[$pCount] = $row["cust_key"];
         $pSentryKey[$pCount] = $row["sentry_key"];
         $pHeading[$pCount] = $row["sentry_heading"];
         $pStartdate[$pCount] = $row["sentry_start"];
         $pSentrytype[$pCount] = $row["sentry_type"];
         $pCount++;
      }
      return 0;
   }

   function FormatCustomerPhone($number) {
      $sNo = $number;

      //filter only numeric
      $sNo = preg_replace("/[^0-9]/", "", $sNo );

      //how many digits long is it
      if (strlen($sNo) == 7) {
         $sNo = preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $sNo);
         $default_customerareacode = "000";
         //require("settings.php");
         foreach((new config("customer"))->getAssoc() as $k=>$v) $$k=$v;
         
         $sNo = "($default_customerareacode) " . $sNo;
         return $sNo;
      } else
      if (strlen($sNo) == 10) {
         return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $sNo);
      } else
      if (strlen($sNo) == 11) {
         return preg_replace("/([0-9a-zA-Z]{1})([0-9a-zA-Z]{3})([0-9a-zA-Z]{3})([0-9a-zA-Z]{4})/", "$1($2) $3-$4", $sNo);
      } else {
         return $number;
      }

   }
   function FormatCustomerName($name) {
      $sNme = $name;
      $sNme = strtolower($sNme);
      $sNme = ucwords($sNme);
      return $sNme;
   }
   function AddCustomer($My,$Table,$Name,$Streetaddr,$City,$State,$Zip,$Customertype,&$pKey) {
      $pKey = -1;
      $sql = "
      INSERT INTO
         $Table
      SET
         name='$Name',
         streetaddr='$Streetaddr',
         city='$City',
         state='$State',
         zip='$Zip',
         customertype='$Customertype'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      $pKey = @mysql_insert_id($My);
      return 0;
   }

   function SearchQCustomerContains(
   $My,$TableCust,$CustQ,
   &$pCount,
   &$pCustKey,
   &$pName,
   &$pCusttype,
   &$pPhone,
   &$pPhonetype,
   &$pAddr,
   &$pCity,
   &$pState,
   &$pZip
   ) {
      $pCount = 0;$pCustKey = array();$pName = array();$pCusttype = array();$pPhone = array();$pPhonetype = array();$pAddr=array();$pCity=array();$pState=array();$pZip=array();
      //break down query into words by spaces

         //remove anything not alpha or space
         $sFiltered = preg_replace("/[^A-Za-z0-9\s\s+]/", "", $CustQ );
         //echo "$sFiltered ($CustQ)"; die();
         //split into words
         $sWrd = explode(" ",$sFiltered);

         //add each word to where clause
         $sql_where = "
WHERE
         ";
         $iWrd = 0;
         foreach ($sWrd as $sWrdVal) {
            if ($iWrd == 0) {
               $sql_where .= "
 customer.name LIKE '%" . mysql_real_escape_string($sWrdVal,$My) . "%'
";
            } else {
               $sql_where .= "
OR
 customer.name LIKE '%" . mysql_real_escape_string($sWrdVal,$My) . "%'
";
            }
            $iWrd++;
         }

      //create sql statement
      $sql = "
SELECT
 customer.id AS cust_key,
 customer.name AS cust_name,
 customer_phone.number AS phone_number,
 customer_phone.type AS phone_type,
 customer.streetaddr AS cust_addr,
 customer.city AS cust_city,
 customer.state AS cust_state,
 customer.zip AS cust_zip,
 customer.customertype AS cust_type
FROM
 customer
LEFT JOIN
 customer_phone
ON
 customer.id=customer_phone.customer";
      $sql .= $sql_where;
      $sql .= "
AND customer.active=1
AND (
 (customer_phone.customer IS NULL)
 OR
 (customer.primaryphonetype=customer_phone.type)
)

      ";
   //$pStreetaddr,$pCity,$pState,$pZip,$pCusttype,$pPriphonetype,$pPriphone

      $pCount = 0;
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pCustKey[$pCount] = $row["cust_key"];
         $pName[$pCount] = $row["cust_name"];
         $pAddr[$pCount] = $row["cust_addr"];
         $pCity[$pCount] = $row["cust_city"];
         $pState[$pCount] = $row["cust_state"];
         $pZip[$pCount] = $row["cust_zip"];
         $pCusttype[$pCount] = $row["cust_type"];
         $pPhonetype[$pCount] = $row["phone_type"];
         $pPhone[$pCount] = $row["phone_number"];
         $pCount++;
      }
      //echo $sql;die();
      return 0;

   }

   function SearchQCustomerStartWords(
   $My,$TableCust,$CustQ,
   &$pCount,
   &$pCustKey,
   &$pName,
   &$pCusttype,
   &$pPhone,
   &$pPhonetype,
   &$pAddr,
   &$pCity,
   &$pState,
   &$pZip
   ) {
      $pCount = 0;$pCustKey = array();$pName = array();$pCusttype = array();$pPhone = array();$pPhonetype = array();$pAddr=array();$pCity=array();$pState=array();$pZip=array();
      //break down query into words by spaces

         //remove anything not alpha or space
         $sFiltered = preg_replace("/[^A-Za-z0-9\s\s+]/", "", $CustQ );
         //echo "$sFiltered ($CustQ)"; die();
         //split into words
         $sWrd = explode(" ",$sFiltered);

         //add each word to where clause
         $sql_where = "
WHERE
         ";
         $iWrd = 0;
         foreach ($sWrd as $sWrdVal) {
            if ($iWrd == 0) {
               $sql_where .= "
 customer.name LIKE '" . mysql_real_escape_string($sWrdVal,$My) . "%'
";
            } else {
               $sql_where .= "
OR
 customer.name LIKE '" . mysql_real_escape_string($sWrdVal,$My) . "%'
";
            }
            $iWrd++;
         }

      //create sql statement
      $sql = "
SELECT
 customer.id AS cust_key,
 customer.name AS cust_name,
 customer_phone.number AS phone_number,
 customer_phone.type AS phone_type,
 customer.streetaddr AS cust_addr,
 customer.city AS cust_city,
 customer.state AS cust_state,
 customer.zip AS cust_zip,
 customer.customertype AS cust_type
FROM
 customer
LEFT JOIN
 customer_phone
ON
 customer.id=customer_phone.customer";
      $sql .= $sql_where;
      $sql .= "
AND customer.active=1
AND (
 (customer_phone.customer IS NULL)
 OR
 (customer.primaryphonetype=customer_phone.type)
)

      ";
   //$pStreetaddr,$pCity,$pState,$pZip,$pCusttype,$pPriphonetype,$pPriphone

      $pCount = 0;
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pCustKey[$pCount] = $row["cust_key"];
         $pName[$pCount] = $row["cust_name"];
         $pAddr[$pCount] = $row["cust_addr"];
         $pCity[$pCount] = $row["cust_city"];
         $pState[$pCount] = $row["cust_state"];
         $pZip[$pCount] = $row["cust_zip"];
         $pCusttype[$pCount] = $row["cust_type"];
         $pPhonetype[$pCount] = $row["phone_type"];
         $pPhone[$pCount] = $row["phone_number"];
         $pCount++;
      }
      //echo $sql;die();
      return 0;

   }


   //has the where forming loop
   function SearchQCustomerFulltext(
   $My,$TableCust,$CustQ,
   &$pCount,
   &$pCustKey,
   &$pName,
   &$pCusttype,
   &$pPhone,
   &$pPhonetype,
   &$pAddr,
   &$pCity,
   &$pState,
   &$pZip
   ) {
         $pCount = 0;$pCustKey = array();$pName = array();$pCusttype = array();$pPhone = array();$pPhonetype = array();$pAddr=array();$pCity=array();$pState=array();$pZip=array();

      //break down query into words by spaces

         //remove anything not alpha or space
         $sFiltered = preg_replace("/[^A-Za-z0-9\s\s+]/", "", $CustQ );
         //echo "$sFiltered ($CustQ)"; die();
         //split into words
         $sWrd = explode(" ",$sFiltered);

         //add each word to where clause
         $sql_where = "
WHERE
         ";
         $iWrd = 0;
         foreach ($sWrd as $sWrdVal) {
            if ($iWrd == 0) {
               /*
               $sql_where .= "
 customer.name='" . mysql_real_escape_string($sWrdVal,$My) . "'
";*/
               $sql_where .= "
MATCH(
 customer.name
)
AGAINST
('$sWrdVal'
)";
            } else {
               /*
               $sql_where .= "
OR
 customer.name='" . mysql_real_escape_string($sWrdVal,$My) . "'
";*/
               $sql_where .= "
OR
MATCH(
 customer.name
)
AGAINST
('$sWrdVal'
)";
            }
            $iWrd++;
         }

      //create sql statement
      $sql = "
SELECT
 customer.id AS cust_key,
 customer.name AS cust_name,
 customer_phone.number AS phone_number,
 customer_phone.type AS phone_type,
 customer.streetaddr AS cust_addr,
 customer.city AS cust_city,
 customer.state AS cust_state,
 customer.zip AS cust_zip,
 customer.customertype AS cust_type
FROM
 customer
LEFT JOIN
 customer_phone
ON
 customer.id=customer_phone.customer";
      $sql .= $sql_where;
      $sql .= "
AND (
 (customer_phone.customer IS NULL)
 OR
 (customer.primaryphonetype=customer_phone.type)
)
AND
 customer.active=1


      ";
      //see if a whole word is in there
/*
      $sql = "
SELECT
 customer.id, customer.name
FROM
 customer
WHERE
MATCH(
 customer.name
)
AGAINST
('Bird'
)";
*/

   //$pStreetaddr,$pCity,$pState,$pZip,$pCusttype,$pPriphonetype,$pPriphone
      //echo nl2br($sql); die();
      $pCount = 0;
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pCustKey[$pCount] = $row["cust_key"];
         $pName[$pCount] = $row["cust_name"];
         $pAddr[$pCount] = $row["cust_addr"];
         $pCity[$pCount] = $row["cust_city"];
         $pState[$pCount] = $row["cust_state"];
         $pZip[$pCount] = $row["cust_zip"];
         $pCusttype[$pCount] = $row["cust_type"];
         $pPhonetype[$pCount] = $row["phone_type"];
         $pPhone[$pCount] = $row["phone_number"];
         $pCount++;
      }
      return 0;

   }


   function SearchQCustomerExact(
   $My,$TableCust,$CustQ,
   &$pCount,
   &$pCustKey,
   &$pName,
   &$pCusttype,
   &$pPhone,
   &$pPhonetype,
   &$pAddr,
   &$pCity,
   &$pState,
   &$pZip
   ) {
      $pCount = 0;
      //see if query is exact match
      $sql = "
SELECT
 customer.id AS cust_key,
 customer.name AS cust_name,
 customer_phone.number AS phone_number,
 customer_phone.type AS phone_type,
 customer.streetaddr AS cust_addr,
 customer.city AS cust_city,
 customer.state AS cust_state,
 customer.zip AS cust_zip,
 customer.customertype AS cust_type
FROM
 customer
LEFT JOIN
 customer_phone
ON
 customer.id=customer_phone.customer
WHERE
 customer.name='" . mysql_real_escape_string($CustQ,$My) . "'
AND (
 (customer_phone.customer IS NULL)
 OR
 (customer.primaryphonetype=customer_phone.type)
)
AND
 customer.active=1

      ";
/*
   &$pCustKey,
   &$pName,
   &$pCusttype,
   &$pPhone,
   &$pPhonetype
   &$pAddr,
   &$pCity,
   &$pState,
   &$pZip
   */
   //$pStreetaddr,$pCity,$pState,$pZip,$pCusttype,$pPriphonetype,$pPriphone
      //echo nl2br($sql);die();
      $pCount = 0;
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pCustKey[$pCount] = $row["cust_key"];
         $pName[$pCount] = $row["cust_name"];
         $pAddr[$pCount] = $row["cust_addr"];
         $pCity[$pCount] = $row["cust_city"];
         $pState[$pCount] = $row["cust_state"];
         $pZip[$pCount] = $row["cust_zip"];
         $pCusttype[$pCount] = $row["cust_type"];
         $pPhonetype[$pCount] = $row["phone_type"];
         $pPhone[$pCount] = $row["phone_number"];
         $pCount++;
      }
      return 0;
      //see if query is full last name

      //see if query is full first name

      //see if query is co name
   }


   function UpdateCustomerType($My,$TableCust,$CustKey,$NewType) {
      if (!is_numeric($CustKey)) throw new error(-5,"bad format customer key given");
      $sql = "
      UPDATE
         $TableCust
      SET
         customertype='$NewType'
      WHERE
         id=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new error(-1,"no customer with that key");
      return 0;
   }
   function UpdateAddr($My,$TableCust,$CustKey,$NewStreetaddr,$NewCity,$NewState,$NewZip) {
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      UPDATE
         $TableCust
      SET
         streetaddr='$NewStreetaddr',
         city='$NewCity',
         state='$NewState',
         zip='$NewZip'
      WHERE
         id=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new error(-1,"no customer with that key");
      return 0;
   }
   function UpdateCustomerName($My,$TableCust,$CustKey,$NewName) {
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      UPDATE
         $TableCust
      SET
         name='$NewName'
      WHERE
         id=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      if (mysql_affected_rows($My) < 1)
         throw new error(-1,"no customer with that key");
      return 0;
   }
   function EnumerateCustomerWPriPhoneByAlpha($My,$TableCust,$TablePhone,$Alpha,&$pCount,&$pCustKey,&$pName,&$pStreetaddr,&$pCity,&$pState,&$pZip,&$pCusttype,&$pPriphonetype,&$pPriphone,&$pLastUpdated) {
      $pCount = 0;
      $pCustKey = array();$pName = array();$pStreetaddr = array();$pCity = array();$pState = array();$pZip = array();$pCusttype = array();$pPriphonetype = array();$pPriphone = array();$pLastUpdated = array();
      if (strlen($Alpha) > 1) throw new error(-5,"bad format given for alpha lookup");
      $sql = "
      SELECT
         $TableCust.id AS cust_key,
         $TableCust.name AS cust_name,
         $TableCust.streetaddr AS cust_streetaddr,
         $TableCust.city AS cust_city,
         $TableCust.state AS cust_state,
         $TableCust.zip AS cust_zip,
         $TableCust.last_updated AS cust_lastupdated,
         $TableCust.customertype AS cust_type,
         $TablePhone.number AS phone_number,
         $TablePhone.type AS phone_type
      FROM
         customer
      LEFT JOIN
         $TablePhone
      ON
         $TablePhone.customer = $TableCust.id
      AND
         $TablePhone.type = $TableCust.primaryphonetype
      WHERE
         $TableCust.name LIKE '$Alpha%'
      ORDER BY
         UCASE(cust_name)
      ASC

      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pCustKey[$pCount] = $row["cust_key"];
         $pName[$pCount] = $row["cust_name"];
         $pStreetaddr[$pCount] = $row["cust_streetaddr"];
         $pCity[$pCount] = $row["cust_city"];
         $pState[$pCount] = $row["cust_state"];
         $pZip[$pCount] = $row["cust_zip"];
         $pCusttype[$pCount] = $row["cust_type"];
         $pPriphonetype[$pCount] = $row["phone_type"];
         $pPriphone[$pCount] = $row["phone_number"];
         $pLastUpdated[$pCount] = $row["cust_lastupdated"];
         $pCount++;
      }
      return 0;
   }
   function GetCustomerPrimaryPhone($My,$CustKey,&$pPhonenumber,&$pPhonetype) {
      $pCount = 0;
      $pPhonenumber = "";$pPhonetype = "";
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
SELECT
   customer_phone.number AS cust_phone,
   customer_phone.type AS cust_phonetype
FROM
   customer
LEFT JOIN
   customer_phone
ON
   customer.id=customer_phone.customer
WHERE
   customer.id=$CustKey
AND (
 (customer_phone.customer IS NULL)
 OR
 (customer.primaryphonetype=customer_phone.type)
)
      LIMIT 0,1
      ";
      //echo nl2br($sql);die();
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());

      while ($row = mysql_fetch_assoc($result)) {
         $pPhonetype = $row["cust_phonetype"];
         $pPhonenumber = $row["cust_phone"];
         $pCount++;
      }
      return 0;
   }
   function EnumerateCustomerPhonenumbers($My,$TablePhone,$CustKey,&$pCount,&$pPhonenumber,&$pPhonetype) {
      $pCount = 0;
      $pPhonenumber = array();$pPhonetype = array();
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      SELECT
         type,
         number
      FROM
         $TablePhone
      WHERE
         customer=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pPhonetype[$pCount] = $row["type"];
         $pPhonenumber[$pCount] = $row["number"];
         $pCount++;
      }
      return 0;
   }
   function GetCustomerPhoneLastUpdated($My,$Table,$CustKey,$PhoneType,&$pLastUpdate) {
      $pLastUpdate = "";
      $sql = "
      SELECT
         last_updated
      FROM
         $Table
      WHERE
         customer=$CustKey
      AND
         type='$PhoneType'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         //throw new error(-1,"no phone number by that customer and type");
         return -1;
      }
      $row = mysql_fetch_assoc($result);
      $pLastUpdate = $row["last_updated"];
      return 0;
   }
   function DeleteCustomerPhone($My,$TablePhone,$CustKey,$PhoneType) {
      $sql = "
      DELETE FROM
         $TablePhone
      WHERE
         customer=$CustKey
      AND
         type='$PhoneType'
      LIMIT 1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      return 0;
   }
   function AddCustomerPhone($My,$TablePhone,$CustKey,$PhoneType,$PhoneNumber) {
      $sql = "
      INSERT INTO
         $TablePhone
      SET
         customer=$CustKey,
         type='$PhoneType',
         number='$PhoneNumber'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      return 0;
   }
   function UpdateCustomerPrimaryPhoneType($My,$TableCust,$CustKey,$PhoneType) {
      if (!is_numeric($CustKey)) throw new error(-5,"bad format customer key given");
      $sql = "
      UPDATE
         $TableCust
      SET
         primaryphonetype='$PhoneType'
      WHERE
         id=$CustKey
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      return 0;
   }

   function UpdateCustomerPhone($My,$TablePhone,$CustKey,$PhoneType,$PhoneNumber) {
      $sql = "
      UPDATE
         $TablePhone
      SET
         customer=$CustKey,
         type='$PhoneType',
         number='$PhoneNumber'
      WHERE
         customer=$CustKey
      AND
         type='$PhoneType'
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while update: " . mysql_error());
      return 0;
   }
   function AssociateCustomerPhone($My,$TablePhone,$CustKey,$PhoneType,$PhoneNumber) {
      if (!is_numeric($CustKey)) throw new error(-5,"bad customer key given");
      $sRet = GetCustomerPhoneLastUpdated($My,$TablePhone,$CustKey,$PhoneType,$sLastUpdate);
      if ($sRet == 0) {
         $sRet = UpdateCustomerPhone($My,$TablePhone,$CustKey,$PhoneType,$PhoneNumber);
         if ($sRet != 0) {
            global $mError;
            throw new error(-200,"while update customer phone:$mError");
         }
      } else
      if ($sRet == -1) {
         $sRet = AddCustomerPhone($My,$TablePhone,$CustKey,$PhoneType,$PhoneNumber);
         if ($sRet != 0) {
            global $mError;
            throw new error(-210,"while add customer phone:$mError");
         }
      } else {
         if ($sRet != 0) {
            global $mError;
            throw new error(-100,"while add customer phone:$mError");
         }
      }
      return 0;
   }
   function EnumerateCustomersByName($My,$Table,$LimitStart,$LimitMax,&$pCount,&$pKey,&$pName,&$pStreetaddr,&$pCity,&$pState,&$pZip,&$pCustomertype,&$pLastUpdated) {
      $pKey = array();$pName = array();$pStreetaddr = array();$pCity = array();$pState = array();$pZip = array();$pCustomertype = array();$pLastUpdated = array();
      $pCount = 0;
      $sql = "
      SELECT
         id,
         name,
         streetaddr,
         city,
         state,
         zip,
         customertype,
         last_updated
      FROM
         $Table
      ORDER BY
         name ASC
      ";
      if (is_numeric($LimitStart) && is_numeric($LimitMax)) {
         $sql .= "
      LIMIT
         $LimitStart,$LimitMax
      ";
      }
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pKey[$pCount] = $row["id"];
         $pName[$pCount] = $row["name"];
         $pStreetaddr[$pCount] = $row["streetaddr"];
         $pCity[$pCount] = $row["city"];
         $pState[$pCount] = $row["state"];
         $pZip[$pCount] = $row["zip"];
         $pCustomertype[$pCount] = $row["customertype"];
         $pLastUpdated[$pCount] = $row["last_updated"];
         $pCount++;
      }
      return 0;
   }
   function GetCustomerAddrByKey($My,$Table,$Key,&$pStreetaddr,&$pCity,&$pState,&$pZip) {
      $pStreetaddr = "";$pCity = "";$pState = "";$pZip = "";
      if (!is_numeric($Key)) throw new error(-5,"bad customer key given");
      $sql = "
      SELECT
         streetaddr,
         city,
         state,
         zip
      FROM
         $Table
      WHERE
         id=$Key
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         throw new error(-1,"no customer found with that key:$Key");
      }
      if (!($row = mysql_fetch_assoc($result))) {
         throw new error(-100,"problem getting result");
      }
      $pStreetaddr = $row["streetaddr"];
      $pCity = $row["city"];
      $pState = $row["state"];
      $pZip = $row["zip"];
      return 0;
   }
   function GetCustomerType($My,$TableCust,$CustKey,&$pType) {
      $pType = "";
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      SELECT
         customertype
      FROM
         $TableCust
      WHERE
         id=$CustKey
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"no customer found with that key:$CustKey");
      if (!($row = mysql_fetch_assoc($result)))
         throw new error(-100,"problem getting result");
      $pType = $row["customertype"];
      return 0;
   }
   function GetCustomerName($My,$TableCust,$CustKey,&$pName) {
      $pName = "";
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      SELECT
         name
      FROM
         $TableCust
      WHERE
         id=$CustKey
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"no customer found with that key:$CustKey");
      if (!($row = mysql_fetch_assoc($result)))
         throw new error(-100,"problem getting result");
      $pName = $row["name"];
      return 0;
   }
   function GetCustomer($My,$TableCust,$CustKey,&$pName,&$pStreetaddr,&$pCity,&$pState,&$pZip,&$pCustomertype,&$pLastUpdated) {
      $pName = "";$pStreetaddr = "";$pCity = "";$pState = "";$pZip = "";$pCustomertype = "";$pLastUpdated = "";
      if (!is_numeric($CustKey)) throw new error(-5,"customer key given in bad format");
      $sql = "
      SELECT
         name,
         streetaddr,
         city,
         state,
         zip,
         customertype,
         last_updated
      FROM
         $TableCust
      WHERE
         id=$CustKey
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1)
         throw new error(-1,"no customer found with that key:$CustKey");
      if (!($row = mysql_fetch_assoc($result)))
         throw new error(-100,"problem getting result");
      $pName = $row["name"];
      $pStreetaddr = $row["streetaddr"];
      $pCity = $row["city"];
      $pState = $row["state"];
      $pZip = $row["zip"];
      $pCustomertype = $row["customertype"];
      $pLastUpdated = $row["last_updated"];
      return 0;
   }
   function GetCustomerLastUpdate($My,$Table,$Key,&$pLastUpdate) {
      $pLastUpdate = "";
      if (!is_numeric($Key)) throw new error(-5,"bad customer key given");
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
         throw new error(-1,"no customer found with that key:$Key");
      }
      $row = mysql_fetch_assoc($result);
      $pLastUpdate = $row["last_updated"];
      return 0;
   }
   function EnumerateCustomertypes($My,$Table,&$pCount,&$pName,&$pBrief,&$pDescription) {
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
         throw new error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pName[$pCount] = $row["name"];
         $pBrief[$pCount] = $row["brief"];
         $pDescription[$pCount] = $row["description"];
         $pCount++;
      }
      return 0;
   }
