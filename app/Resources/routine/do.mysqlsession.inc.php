<?php
   require __DIR__."/../../../include/settings.inc.php";
   $SESS_DBHOST  = $control_dbhost;
   $SESS_DBNAME  = $control_dbname;
   $SESS_DBTABLE = "session";
   $SESS_DBUSER  = $control_dbuser;
   $SESS_DBPASS  = $control_dbpasswd;
   //$SESS_LIFE = ini_get("session.gc_maxlifetime");
   $SESS_LIFE = $session_life;//48 * 3600; //48 hours (60 min, 60 sec)
   function sess_open($save_path, $session_name) {
      global $SESS_DBHOST, $SESS_DBNAME, $SESS_DBUSER, $SESS_DBPASS, $SESS_DBH;
      $SESS_DBH = mysql_connect($SESS_DBHOST, $SESS_DBUSER, $SESS_DBPASS);
      if ($SESS_DBH == FALSE) {
         print "Can't connect to $SESS_DBHOST as $SESS_DBUSER";
         print "MySQL Error: ";
         print mysql_error();
         die;
      }
      if (! mysql_select_db($SESS_DBNAME, $SESS_DBH)) {
         print "Unable to select database $SESS_DBNAME";
         die;
      }
      return true;
   }
   function sess_read($key) {
     global $SESS_DBH, $SESS_LIFE, $SESS_DBTABLE;
     $sql = "SELECT value FROM $SESS_DBTABLE WHERE sesskey = '$key' AND expiry > " . time();
     $result = mysql_query($sql, $SESS_DBH);
     if (list($value) = mysql_fetch_row($result)) {
         $expiry = time() + $SESS_LIFE;
         $sql = "UPDATE $SESS_DBTABLE SET expiry = $expiry WHERE sesskey = '$key'";
         $result = mysql_query($sql, $SESS_DBH);
         return $value;
     }

     return "";
   }
   function sess_write($key, $val) {
     global $SESS_DBH, $SESS_LIFE, $SESS_DBTABLE;
     $expiry = time() + $SESS_LIFE;
     $value = addslashes($val);
     $sql = "INSERT INTO $SESS_DBTABLE VALUES ('$key', $expiry, '$value')";
     $result = mysql_query($sql, $SESS_DBH);
     if (! $result) {
       $sql = "UPDATE $SESS_DBTABLE SET expiry = $expiry, value = '$value' WHERE sesskey = '$key' AND expiry > " . time();
       $result = mysql_query($sql, $SESS_DBH);
     }

     return $result;
   }
   function sess_destroy($key) {
     global $SESS_DBH,$SESS_DBTABLE;
     $sql = "DELETE FROM $SESS_DBTABLE WHERE sesskey = '$key'";
     $result = mysql_query($sql, $SESS_DBH);
     return $result;
   }
   function sess_gc ($maxlifetime) {
     global $SESS_DBH, $SESS_LIFE,$SESS_DBTABLE;
     $sql = "DELETE FROM $SESS_DBTABLE WHERE expiry < " . time();
     $result = mysql_query($sql, $SESS_DBH);
     return mysql_affected_rows($SESS_DBH);
   }
   function sess_selfgc() {
      global $mSelfGC,$mSelfGCFactor;
      if ($mSelfGC == TRUE) {
         if (1 == mt_rand(1,$mSelfGCFactor)) {
            global $SESS_DBH,$SESS_DBTABLE;
            $sql = "DELETE FROM $SESS_DBTABLE WHERE expiry < " . time();
            $result = mysql_query($sql, $SESS_DBH);
         }
      }
   }
   function sess_close() {
      sess_selfgc();
      return true;
   }
   ini_set("session.save_handler","user");
   $mSelfGC = TRUE; //do own garbage collection, or leave it up to php server
   $mSelfGCFactor = 1; //chance is 1:SelfGCFactor (1 is every session, 100 is a 1 in 100 chance)
   session_set_save_handler
     (
          "sess_open",
          "sess_close",
          "sess_read",
          "sess_write",
          "sess_destroy",
          "sess_gc"
     );
