<?php 
   require __DIR__.'/../vendor/autoload.php';
   include ("settings.inc.php");
   require_once("wtfpanel.err.inc.php");
   function escapesafe($My,$Value) {
      if (get_magic_quotes_gpc()) {
         $value = stripslashes($Value);
      }
      $Value = mysql_real_escape_string($Value);
      return $Value;
   }
   function getuserkey($username) {
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_connect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sql = "
      SELECT
         user_key 
      FROM
         user 
      WHERE
         username='$username'
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      $row = mysql_fetch_assoc($result);
      if (mysql_num_rows($result) < 1)
         return -1;
      return $row['user_key'];
   }
   function adduserprivilege($userkey,$privkey) {
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_connect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sql = "INSERT INTO user_privilege SET user_key=$userkey,privilegetype_key=$privkey";
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      return 0;
   }
   function enumerategrantableprivs($username,$privkey,$privapi,$privname,$privdesc,$privcount) {
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      $privkey = array();   $privapi = array();   $privname = array();   $privdesc = array();
      if (!($my = @mysql_pconnect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sql =
      "SELECT
          user_grantright.privilegetype_key AS privkey,
          privilegetype.api AS api,
	  privilegetype.value AS name,
	  privilegetype.description AS description
             FROM user_grantright
                LEFT JOIN privilegetype
                   ON privilegetype.privilegetype_key=user_grantright.privilegetype_key
                LEFT JOIN user
                   ON user_grantright.user_key=user.user_key
             WHERE user.username='$username'";
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      $i = 0;
      while($row = mysql_fetch_assoc($result)) {
         $privkey[$i] = $row['privkey'];
	 $privapi[$i] = $row['api'];
	 $privname[$i] = $row['name'];
	 $privdesc[$i] = $row['description'];
	 $i++;
      }
      $privcount = $i;
      return 0;
   }
   /*
   function continuesession() {
         session_id();
         session_start();
   }
   */
   function killsession() {
      //$_SESSION = array();
      if (isset($_COOKIE[session_name()])) {
         setcookie(session_name(), '', time()-42000, '/');
      }
      session_unset();
      session_destroy();
   }
   function newsession() {
      session_regenerate_id();
      session_start();
   }
   function isvalidsessionid($id) {
      if ($id == "")
         return FALSE;
      return TRUE;
   }
   function userexists($user) {
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_connect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sql = "SELECT user_key FROM user WHERE username='$user' LIMIT 0,1";
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) > 0)
         return 1;
      return 0;
   }
   function submitnewuser($username,$passwd) {
      if (userexists($username)) {
         return PanelError(-3,"that username allready exists ($username)");
      }
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_pconnect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sMajorVersion = substr(mysql_get_server_info(),0,1);
      if ($sMajorVersion < 4) { //no abstraction :|
         $sql = "INSERT INTO user SET username='$username',password=PASSWORD('$passwd')";
      } else {
         $sql = "INSERT INTO user SET username='$username',password=OLD_PASSWORD('$passwd')";
      }
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      return 0;
   }
   function verifyuserinfo($username,$passwd,$pUsername) {
      $pUsername = "";
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_connect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $username = escapesafe($my,$username);
      $passwd = escapesafe($my,$passwd);
      $sMajorVersion = substr(mysql_get_server_info(),0,1);
      if ($sMajorVersion < 4) { //no abstraction :|
         $sql = "SELECT user_key,username FROM user WHERE username='$username' AND password=PASSWORD('$passwd')";
      } else {
         $sql = "SELECT user_key,username FROM user WHERE username='$username' AND password=OLD_PASSWORD('$passwd')";
      }
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) == 0) {
         return FALSE;
      } else {
         $row = mysql_fetch_assoc($result);
         $pUsername = $row["username"];
         return TRUE;
      }
   }
   function userhaspriv($pHasPriv,$userkey,$privapi) {
      global $control_dbhost,$control_dbname,$control_dbuser,$control_dbpasswd;
      if (!($my = @mysql_connect($control_dbhost,$control_dbuser,$control_dbpasswd)))
         return PanelError(-4,"while connect: " . mysql_error());
      if (!@mysql_select_db($control_dbname))
         return PanelError(-4,"while db: " . mysql_error());
      $sql = "
      SELECT
         privilegetype.api AS privapi,
         user_privilege.user_key AS userkey,
         user_privilege.privilegetype_key AS privkey
      FROM
         user_privilege
      LEFT JOIN
         privilegetype
      ON
         privilegetype.privilegetype_key = user_privilege.privilegetype_key
      WHERE
         user_privilege.user_key=$userkey
      AND
         privilegetype.api='$privapi'
      LIMIT 0,1
      ";
      if (!($result = @mysql_query($sql,$my)))
         return PanelError(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) < 1) {
         $pHasPriv = FALSE;
      } else {
         $pHasPriv = TRUE;
      }
      return 0;
   }
