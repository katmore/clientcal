<?php
   require_once("include/wtfpanel.inc.php");
   require_once("include/html.inc.php");
   require_once("include/html.menu.inc.php");
   require_once("include/wtfpanel.mysql.inc.php");
   require_once("include/settings.inc.php");
   require_once("include/html.customer.inc.php");

   include("include/getsession.inc.php");
   $mMode = "login";
   include("include/getauth.inc.php");

   if ($mAuthorized == "true") {
      $mMode = "nothing";
      if(isset($_POST["custfile_upload"])) {
         $mMode = "custfile_upload";
      } else
      if(isset($_GET["add"])) {
         $mMode = "showadd";
      } else
      if(isset($_GET["submitadd"])) {
         $mMode = "submit_add";
      } else
      if (isset($_GET["edit"])) {
         $mMode = "showedit";
      } else
      if (isset($_GET["submitupdate"])) {
         $mMode = "submitupdate";
      } else
      if (isset($_GET["alpha"])) {
         $mMode = "alpha";
      } else
      if (isset($_GET["search"])) {
         $mMode = "search";
      } else
      if (isset($_GET["jobs"])) {
         $mMode = "jobs";
      } else
      if (isset($_GET["file"])) {
         $mMode = "custfile";
      }
   }

   $mNotice = "";
   $mSubtitle = "";
   $mRet = PanelConnectToDB($data_dbhost,$data_dbname,$data_dbuser,$data_dbpasswd,$mMySched);
   if ($mRet != 0) {
      $mMode = "gen_error";
      $mNotice .= "problem ($mRet) while connecting to the schedule database:<br />$mPanelError<br />";
   }

   //stuff to do before any output occurs

   
   if ($mMode == "custfile") {
      if (isset($_GET["id"])) {
         $mCust_key = $_GET["id"];
      } else {
         $mMode = "gen_error";
         $mNotice .= "no customer key";
      }
   }
   
   if ($mMode == "custfile") {
      $mRet = PanelGetCustomerName($mMySched,$customer_table,$mCust_key,$mCust_name);
      if ($mRet != 0) {
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while connecting to the schedule database:<br />$mPanelError<br />";
      }
   }
   
   if ($mMode == "custfile") {
      if ( 0 != ($mRet = PanelGetCustfileMimetype($mMySched,$mCust_key,$_GET["file"],$mCustfile_mimetype)) ) {
         $mMode = "gen_error";
         $mNotice .= "problem ($mRet) while getting mimetype:<br>$mPanelError<br>";
      }
      //echo $mCustfile_mimetype;die();
      
   }

   if ($mMode == "custfile") {
      if (isset($_GET["wrap"])) {
         $mMode = "custfile_wrap";
      }
   }


   if ($mMode == "custfile") {
      $mCustfile_handle = hash($hashalgo_custfile,$mCust_key . "." . $_GET["file"]);
   }
   
   if ($mMode == "custfile") {
      $mSquareX = -1;
      $mHardWidth = -1;
      $mHardHeight = -1;
      $mDefWidth = $jpgthumb_default_width;
      $mDefHeight = $tinythumb_default_height;
      $mDefX = $tinysquarethumb_defaultx;
   }
   
   if ($mMode == "custfile") {
      if (isset($_GET["jpgthumb"])) {
         if ($mCustfile_mimetype == "application/pdf") {
            $mHardWidth = $mDefWidth;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/jpeg") {
            $mHardWidth = $mDefWidth;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/png") {
            $mHardWidth = $mDefWidth;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/gif") {
            $mHardWidth = $mDefWidth;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "text/html") {
            $mHardWidth = $mDefWidth;
            $mMode = "custfile_magickthumb";
         }
      }
   }
   
   if ($mMode == "custfile") {
      if (isset($_GET["tinysquarejpgthumb"])) {
         if ($mCustfile_mimetype == "application/pdf") {
            $mSquareX = $mDefX;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/jpeg") {
            $mSquareX = $mDefX;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/png") {
            $mSquareX = $mDefX;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/gif") {
            $mSquareX = $mDefX;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "text/html") {
            $mSquareX = $mDefX;
            $mMode = "custfile_magickthumb";
         }
      }
   }
   
   if ($mMode == "custfile") {
      if (isset($_GET["tinyjpgthumb"])) {
         if ($mCustfile_mimetype == "application/pdf") {
            $mHardHeight = $mDefHeight;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/jpeg") {
            $mHardHeight = $mDefHeight;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/png") {
            $mHardHeight = $mDefHeight;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "image/gif") {
            $mHardHeight = $mDefHeight;
            $mMode = "custfile_magickthumb";
         }
         if ($mCustfile_mimetype == "text/html") {
            $mHardHeight = $mDefHeight;
            $mMode = "custfile_magickthumb";
         }
      }
   }
   
   
   $mGenErrJpgMsg = "";
   $mNotCriticalErr = false;
   if ($mMode == "custfile") {
      if (isset($_GET["jpgthumb"])) {
         //echo "couldn't figure type:" . $mCustfile_mimetype;die();
         $mMode = "gen_error_jpeg";
         $mNotCriticalErr = true;
         $mGenErrJpgMsg = "thumbs not available for: $mCustfile_mimetype";
      }
   }
   if ($mMode == "login") {
      if (isset($_GET["jpgthumb"])) {
         $mMode = "gen_error_jpeg";
         $mGenErrJpgMsg = "need to sign in";
      }
   }
   if ($mMode == "gen_error") {
         if (isset($_GET["jpgthumb"])) {
         $mMode = "gen_error_jpeg";
         $mGenErrJpgMsg = strip_tags($mNotice);
      }
   }
   if ($mMode == "login") {
      if (isset($_GET["jpgthumb"])) {
         $mMode = "gen_error_jpeg";
         $mGenErrJpgMsg = "abort:" . substr($mNotice,0,15);
      }
   }
   
   if ($mMode == "custfile_magickthumb") {
      if (!extension_loaded('imagick')) {
         $mMode = "gen_error_jpeg";
         $mGenErrJpgMsg = "imagicklib required";
      }
   }
   
   if ($mMode == "custfile_magickthumb") {
      $im = new imagick($dir_custfiles . $mCustfile_handle . '[0]');
      $im->setImageFormat( "jpg" );
      //add some logic: if orig image width is less than (or equal to) 306, don't rescale
      //if it is greater than 306, figure out other side proportionally
      if ($mSquareX > 0) {
         $mNewWidth = $mSquareX;
         $mNewHeight = $mSquareX;
      } else
      if ($mHardWidth > 0) {
         $mNewWidth = $mHardWidth;
         $mNewHeight = round(($mNewWidth * $im->getImageHeight()) / $im->getImageWidth(),0);
      } else
      if ($mHardHeight > 0) {
         $mNewHeight = $mHardHeight;
         $mNewWidth = round( ($im->getImageWidth() * $mNewHeight) / $im->getImageHeight(),0);
         
      } else {
         $mNewWidth = 306;
         $mNewHeight = round(($mNewWidth * $im->getImageHeight()) / $im->getImageWidth(),0);
      }
      
      $mRet = $im->scaleImage ( $mNewWidth , $mNewHeight );
      if ($mRet !== true) {
         $mMode = "gen_error_jpeg";
         $mGenErrJpgMsg = "rescaling returned false";
      }
      header( "Content-Type: image/jpeg" );
      echo $im;
      die();
   }
   
   if ($mMode == "gen_error_jpeg") {
      $errjpg = imagecreatetruecolor(306, 200);
      $text_color = imagecolorallocate($errjpg, 0, 0, 0);
      $bg_color = imagecolorallocate($errjpg, 254, 254, 127);
      imagefill ( $errjpg , 0 , 0 , $bg_color );
      if ($mNotCriticalErr) {
         $mErrPrefix = "";
      } else {
         $mErrPrefix = "err:";
      }
      imagestring($errjpg, 2, 5, 5,  $mErrPrefix . $mGenErrJpgMsg, $text_color);
      header('Content-Type: image/jpeg');
      imagejpeg ( $errjpg);
      imagedestroy($errjpg);
      die();
   }
   
   if ($mMode == "custfile") {
      //echo $dir_custfiles . $mCustfile_handle;die();
      header ("Content-Type: $mCustfile_mimetype");
      readfile($dir_custfiles . $mCustfile_handle);
      die();
   }

   if ($mMode == "custfile_upload") {
      wtfpanel_customerfilevars();
   }
   
   if ($mMode == "custfile_upload") {
      $tmpfile = tempnam ( $dir_uploadtmp , "Clientcal" );
      
      if (move_uploaded_file($_FILES['userfile']['tmp_name'], $tmpfile)) {
          $mMode = "custfile_upload";
      } else {
          $mMode = "custfile_error_notempfile";
          $mNotice .= "file upload problem<br>";
          $mNotice .= "userfile.tmpname=" . $_FILES['userfile']['tmp_name'] . "<br>";
          $mNotice .= "tmpfile=" . $tmpfile . "<br>";
          $mNotice .= "userfile.error=" . $_FILES['userfile']['error'] . "<br>";
      }
   }
   
   if ( ($mMode == "custfile_upload") || ($mMode == "custfile_error_notempfile") ) {
      if (isset($_GET["id"])) {
         $mCustKey = $_GET["id"];
      } else {
         if ($mMode == "custfile_error_notempfile") {
            $mMode = "gen_error";
         } else {
            $mMode = "gen_error_custfile";
         }
         $mNotice .= "<br>no customer key found<br>";
      }
   }
   if ( ($mMode == "custfile_upload") || ($mMode == "custfile_error_notempfile") ) {
      //PanelGetCustomerName($My,$TableCust,$CustKey,$pName)
      $mRet = PanelGetCustomerName($mMySched,$customer_table,$mCustKey,$mCustName);
      if ($mRet != 0) {
         if ($mMode == "custfile_error_notempfile") {
            $mMode = "gen_error";
         } else {
            $mMode = "gen_error_custfile";
         }
         $mNotice .= "<br>PanelError while getting customer:<br>$mPanelError<br>";
      }
   }
   
   if ($mMode == "custfile_upload") {
      //add entry to customer_files
      if (0 != ($mRet = PanelAddCustfile(
         $mMySched,
         $mCustKey,
         $tmpfile,
         $_FILES['userfile']['name'],
         $mCustfile_type,
         $_FILES['userfile']['type'],
         $_FILES['userfile']
            ) ) ) {
         if ($mRet == -1062) {
            $mMode = "custfile_nodupe";
            $mNotice .= "The file
            '" . $_FILES['userfile']['name'] . "'<br>
            size " . round(($_FILES['userfile']['size']/1024),0) . " KB (" . round((($_FILES['userfile']['size']/1024)/1024),2) . " MB)<br>
            has already been saved to this customer<br>
            file has md5 of '" . substr(md5_file($tmpfile),0,12) . "...'
            
            ";
         } else {
            $mMode = "custfile_error";
            $mNotice .= "<br>PanelError ($mRet) while Adding custfile:<br>$mPanelError<br>";
         }
      }
   }
   

   if ( 
      ($mMode == "custfile_error") ||
      ($mMode == "custfile_nodupe") ||
      ($mMode == "gen_error_custfile")
      //($mMode == "custfile_upload")
   ) {
      //delete the temp file
      unlink($tmpfile);   
   }
   
   if ($mMode == "gen_error_custfile") {
      $mMode = "gen_error";
   }
   
      
   
   if ($mMode == "jobs") {
      wtfpanel_getcustomerjobsvars();
   }
   if ($mMode == "search") {
      wtfpanel_getcustomersearchpostvars();
      if (!$mCust_valid) {
         $mMode = "gen_error";
         $mNotice .= "invalid customer search query";
      }
   }
   if ($mMode == "showedit") {
      $mCust_key = 0;
      if (isset($_GET["edit"])) {
         $mCust_key = $_GET["edit"];
      }
   }
   if ($mMode == "submitupdate") {
      $mCust_key = 0;
      if (isset($_GET["submitupdate"])) {
         $mCust_key = $_GET["submitupdate"];
      }
   }
   if ($mMode == "alpha") {
      $mLetter = "A";
      if (isset($_GET["alpha"])) {
         if ((ord($_GET["alpha"]) > 64) || (ord($_GET["alpha"]) < 91)) {
            $mLetter = $_GET["alpha"];
         }
      }
   }
   if ($mMode == "submitupdate") {
      wtfpanel_globalizecustomerpostvars();
      wtfpanel_globalizecustomerphonepostvars();
      if (!wtfpanel_testcustsomervars($mAddcustomerErr)) {
         $mNotice .= "<li>there were problems adding the customer<br />";
         $mMode = "showedit";
      } else {
         if (wtfpanel_updatecustomerprocess() == 0) {
            $mNotice .= "<li>updated successfully<br />";
            $mMode = "showedit";
            wtfpanel_clearcustomervars();
            wtfpanel_clearcustomerphonevars();
         } else {
            $mNotice .= "<li>there were problems while updating this customer<br />";
            $mMode = "showedit";
         }
      }
   }
   if ($mMode == "submit_add") {
      wtfpanel_globalizecustomerpostvars();
      if (!wtfpanel_testcustsomervars($mAddcustomerErr)) {
         $mNotice .= "<li>there were problems adding the customer<br />";
         $mMode = "showadd";
      }
   }
   if ($mMode == "showedit") {
      wtfpanel_embedvars();
   }
   if ($mMode == "custfile_upload") {
      $mNotice .= "<br>successful upload of '" . htmlentities($_FILES['userfile']['name']) . "'<br>";
      $mCust_key = $mCustKey;
      $mMode = "showedit";
   } 
   if ($mMode == "custfile_error") {
      $mCust_key = $mCustKey;
      $mMode = "showedit";
   }
   
   if ($mMode == "custfile_nodupe") {
      $mCust_key = $mCustKey;
      $mMode = "showedit";
   }
   if ($mMode == "showedit") {
      if (wtfpanel_getcustomerprocess() != 0) {
         $mMode = "gen_error";
      }
   }
   //$mMode = "showedit";
   if ($mMode == "showedit") {
      //custfile_doctype_update
      if (isset($_POST["custfile_doctype_update"])) {
         $mMode = "showedit_update_custfile_doctype";
      }
      //custfile_doctype_submit
      if (isset($_POST["custfile_doctype_submit"])) {
         $mMode = "showedit_submit_custfile_doctype";
      }
      if (isset($_POST["custfile_delete"])) {
         $mMode = "showedit_custfile_delete_confirm";
      }
      if (isset($_POST["custfile_delete_yes"])) {
         $mMode = "custfile_delete_yes";
      }
      //custfile_delete_no
      if (isset($_POST["custfile_delete_no"])) {
         $mMode = "custfile_delete_no";
      }
   }
   
   if ($mMode == "custfile_delete_no") {
      $mNotice .= "delete canceled by user";
      $mMode = "showedit";
   }
   
   //showedit_submit_custfile_doctype
   if ($mMode == "custfile_delete_yes") {
      //get vars, make sure there's the token matching
      wtfpanel_custfile_deleteyesnovars();
      if ($mCustfile_delete_token == $_SESSION["custfile_delete_token_sess"]) {
         //get md5 hash for info
         $mCustfile_handle = hash($hashalgo_custfile,$mCust_key . "." . $mCustfile_embed);
         if (file_exists($dir_custfiles . $mCustfile_handle)) {
            $mCustfile_md5 = md5_file($dir_custfiles . $mCustfile_handle);
         }
         //remove from database & filesystem
         if (0 ==( $mRet = PanelRemoveCustfile($mMySched,$mCust_key,$mCustfile_embed,$mCustfile_name)) ) {
            if (strlen($mCustfile_name) < 21) {
               $mShowName = $mCustfile_name;
            } else {
               $mShowName = substr($mCustfile_name,0,17) . "...";;
            }
            $mNotice .= "deleted file '$mShowName'<br><span style=\"font-size:0.70em;\">md5:$mCustfile_md5</span>";
         } else {
            $mNotice .= "PanelError while removing file:<br>$mPanelError";
         }
         $mCustfile_embed = "-1";
      } else {
         $mNotice .= "delete canceled: no valid token recieved";
      }
      $mMode = "showedit";
   }
   
   if ($mMode == "showedit_custfile_delete_confirm") {
      //$mCustfile_doctype
      wtfpanel_embedvars();
      
      if (0 != ($mRet = PanelGetCustfileName($mMySched,$mCust_key,$mCustfile_embed,$mCustfile_name))) {
         $mNotice .= "Panel Error while getting custfile name:<br>$mPanelError";
         $mMode = "gen_error";
      }
   }
   if ($mMode == "showedit_custfile_delete_confirm") {
      $mNotice .= wtfpanel_custfile_delete_confirm();
      $mMode = "showedit";
   }
   
   if ($mMode == "showedit_submit_custfile_doctype") {
      //$mCustfile_doctype
      wtfpanel_embedvars();
      wtfpanel_custfile_doctypevars();
      //echo $mCustfile_embed;die();
      if (0 != ($mRet = PanelUpdateCustfileDoctype($mMySched,$mCust_key,$mCustfile_embed,$mCustfile_doctype))) {
         //$mMode = "gen_error";
         $mNotice .= "PanelError while updating custfile:<br>$mPanelError";
         
      }
      $mMode = "showedit";
   }
   if ($mMode == "showedit_update_custfile_doctype") {
      $mCustfile_showeditdoctype = true;
      $mMode = "showedit";
   }
   

   
   if ($mMode == "showedit") {
      if (isset($_POST["custfile_name_update"])) {
         $mMode = "showedit_update_custfile_name";
      }
   }
   
   if ($mMode == "showedit_update_custfile_name") {
      wtfpanel_custfileupdate_namevars();
   }
   
   if ($mMode == "showedit_update_custfile_name") { /*should be able to use this any file updates*/
      //get vars
      wtfpanel_embedvars();
      //see if it exists
      if (0 != ($mRet = PanelGetCustfileMimetype($mMySched,$mCust_key,$mCustfile_embed,$mTmpMimetype))) {
         if ($mRet == -1) {
            $mMode = "showedit";
            $mNotice .= "file not found";
         }
      }
   }
   
   if ($mMode == "showedit_update_custfile_name") {
      //$mCustfile_embed
      //attempt to update it
      $mRet = PanelUpdateCustfileName($mMySched,$mCust_key,$mCustfile_embed,$mCustfile_name);
      //display results in notice
      if ($mRet == 0) {
         //$mNotice .= "updated file name";
         $mMode = "showedit";
      } else {
         $mMode = "gen_error";
         $mNotice .= "PanelError ($mRet) during file name update:<br>$mPanelError<br>";
      }
   }
   

   
   
   if ($mMode == "submit_add") {
      $mMode = "showadd";
      $mTmpCustName = "";
      if ($mCust_nametype == "individual") {
         $mTmpCustName = "$mCust_lastname, $mCust_firstname";
      }
      if ($mCust_nametype == "company") {
         $mTmpCustName = $mCust_name;
      }
      $mRet = PanelAddCustomer($mMySched,$customer_table,$mTmpCustName,$mCust_streetaddr,$mCust_city,$mCust_state,$mCust_zip,$mCust_customertype,$mCust_key);
      if ($mRet == 0) {
         $mNotice .= "<li>successfully added customer '$mTmpCustName':$mCust_key<br />";
         wtfpanel_clearcustomervars();
      } else {
         $mNotice .= "<li>problem ($mRet) adding customer:<br />$mPanelError<br />";
      }
      wtfpanel_globalizecustomerphonepostvars();
      wtfpanel_processcustomerphonevars($mPHCount,$mPHType,$mPHNumber);
      for ($i = 0;$i < $mPHCount;$i++) {
         $mRet = PanelAssociateCustomerPhone($mMySched,$customerphone_table,$mCust_key,$mPHType[$i],$mPHNumber[$i]);
         if ($mRet != 0) {
            $mNotice .= "<li>problem ($mRet) while associating phone nubmer:$i," . $mPHType[$i] . "," . $mPHNumber[$i] . "<br />$mPanelError<br />";
         }
         if ($i == 0) { //make this primary until we have a better interface
            $mRet = PanelUpdateCustomerPrimaryPhoneType($mMySched,$customer_table,$mCust_key,$mPHType[$i]);
            if ($mRet != 0) {
               $mNotice .= "<li>problem ($mRet) while updating primary phonetype for customer:$mCust_key on:$i," . $mPHType[$i] . "," . $mPHNumber[$i] . "<br />$mPanelError<br />";
            }
         }
      }
      wtfpanel_clearcustomerphonevars();
   }
   //$mCustfile_showadd
   if ($mMode == "showedit") {
      if (isset($_GET["addcustfile"])) {
         $mCustfile_showadd = true;
         if (isset($_GET["mailup"])) {
            $mCustfile_showemailup = true;
         }
         if (isset($_GET["checkup"])) {
            $mCustfile_showcheckemail = true;
         }
      }
   }
   
   $mSubtitle = "";
   $mHeadExtra = "";
   /*
   $mHeadExtra = "
      <link rel=\"STYLESHEET\" href=\"style.sentry.css.php\" type=\"text/css\">";
      */
   if (($mMode == "login") || ($mMode == "failed_login")) {
      $mSubtitle = "Please Sign In";
   } else
   if ($mMode == "nothing") {
      $mSubtitle = "nothing";
   } else
   if ($mMode == "gen_error") {
      $mSubtitle = "unrecoverable error";
   } else
   if ($mMode == "showadd") {
      $mSubtitle = "add customer";
   } else
   if ($mMode == "showedit") {
      $mSubtitle = "edit customer record";
   } else
   if ($mMode == "alpha") {
      $mSubtitle = "$mLetter | Customers";
   } else
   if ($mMode == "search") {
      $mSubtitle = "search results";
   } else
   if ($mMode == "jobs") {
      $mSubtitle = "jobs for customer";
      $mHeadExtra = "
         <link rel=\"STYLESHEET\" href=\"style.sentry.css.php\" type=\"text/css\">";
   }

   echo wtfpanel_header($mSubtitle);

   echo wtfpanel_top();

   if ($mMode == "login") {
      echo wtfpanel_logintable($default_tableclass,"./");
   }
   if ($mMode == "nothing") {
      echo nl2br($mNotice);
      echo "(nothing else designed for this state)<br />";
   } else
   if ($mMode == "gen_error") {
      echo "<b>unrecoverable error</b><br />" . nl2br($mNotice);
   } else
   if ($mMode == "showadd") {
      if ($mNotice != "") {
         echo wtfpanel_customernoticetable($mNotice);
      }
      echo wtfpanel_customeraddformtable("./customer.php?submitadd","add");
   } else
   if ($mMode == "showedit") {
      $mNoticePre = md5($mNotice);
      if ($mNotice != "") {
         echo wtfpanel_customernoticetable($mNotice);
      }
      echo wtfpanel_customeraddformtable("./customer.php?submitupdate=$mCust_key","update");
      $mNoticePost = md5($mNotice);
      if ($mNoticePre != $mNoticePost) {
         echo wtfpanel_customernoticetable($mNotice);
      }
   } else
   if ($mMode == "alpha") {
      if ($mNotice != "") {
         echo wtfpanel_customernoticetable($mNotice);
      }
      //echo wtfpanel_customersearch();
      echo wtfpanel_customeralpha($mLetter,true);
   }
   if ($mMode == "search") {
      if ($mCust_q == "") {
         echo wtfpanel_customeralpha("A",true);
         $mMode = "done";
      }
   }
   if ($mMode == "search") {

      echo wtfpanel_customersearchresult();
      if ($mNotice != "") {
         echo wtfpanel_customernoticetable($mNotice);
      }
   } else
   if ($mMode == "jobs") {
      echo wtfpanel_customerjobs();
      if ($mNotice != "") {
         echo wtfpanel_customernoticetable($mNotice);
      }
   }

   echo wtfpanel_bottom();
