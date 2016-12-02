<?php 

namespace clientcal;

   function getuserkey($username) {
      $myconfig=(new config("mysql"))->getAssoc();
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
         if (!mysqli_select_db($my,$myconfig['dbname']))
            throw new Error(-4,"while db: " . mysql_error($my));
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
         throw new Error(-4,"while get: " . mysql_error());
      $row = mysql_fetch_assoc($result);
      if (mysql_num_rows($result) < 1)
         return -1;
      return $row['user_key'];
   }
   function adduserprivilege($userkey,$privkey) {
      $myconfig=(new config("mysql"))->getAssoc();
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
         if (!mysqli_select_db($my,$myconfig['dbname']))
            throw new Error(-4,"while db: " . mysql_error($my));
      $sql = "INSERT INTO user_privilege SET user_key=$userkey,privilegetype_key=$privkey";
      if (!($result = @mysql_query($sql,$my)))
         throw new Error(-4,"while get: " . mysql_error());
      return 0;
   }
   function enumerategrantableprivs($username,$privkey,$privapi,$privname,$privdesc,$privcount) {

      $privkey = array();   $privapi = array();   $privname = array();   $privdesc = array();
      
      $myconfig=(new config("mysql"))->getAssoc();
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
         if (!mysqli_select_db($my,$myconfig['dbname']))
            throw new Error(-4,"while db: " . mysql_error($my));
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
         throw new Error(-4,"while get: " . mysql_error());
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
   function userexists($user) {
      $myconfig=(new config("mysql"))->getAssoc();
      
      if (!($my = mysql_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password']))) {
         throw new Error(-4,"while connect: " . mysql_error());
      }
      if (!mysql_select_db($myconfig['dbname'],$my)) {
         throw new Error(-4,"while select: " . mysql_error());
      }
         
      $sql = "SELECT user_key FROM user WHERE username='$user' LIMIT 0,1";
      if (!($result = mysql_query($sql,$my)))
         throw new Error(-4,"while get: " . mysql_error());
      if (mysql_num_rows($result) > 0)
         return 1;
      return 0;
   }
   function submitnewuser($username,$passwd) {
      if (userexists($username)) {
         throw new Error(-3,"that username allready exists ($username)");
      }
      $myconfig=(new config("mysql"))->getAssoc();
      
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
         if (!mysqli_select_db($my,$myconfig['dbname']))
            throw new Error(-4,"while db: " . mysql_error($my));
      
             
            $username = mysqli_real_escape_string($my,$username);
      
            $passwd = mysqli_real_escape_string($my,$passwd);
      
            $sMajorVersion = substr(mysqli_get_server_info($my),0,1);
      if ($sMajorVersion < 4) { //no abstraction :|
         $sql = "INSERT INTO user SET username='$username',password=PASSWORD('$passwd')";
      } else {
         $sql = "INSERT INTO user SET username='$username',password=OLD_PASSWORD('$passwd')";
      }
      if (!($result = @mysql_query($sql,$my)))
         throw new Error(-4,"while get: " . mysql_error());
      return 0;
   }
   function verifyuserinfo($username,$passwd,&$pUsername) {
      $pUsername = "";
      
      $myconfig=(new config("mysql"))->getAssoc();
      
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
      if (!mysqli_select_db($my,$myconfig['dbname']))
         throw new Error(-4,"while db: " . mysql_error($my));
      
         
      $username = mysqli_real_escape_string($my,$username);
      
      $passwd = mysqli_real_escape_string($my,$passwd);
      
      $sMajorVersion = substr(mysqli_get_server_info($my),0,1);
      
      if ($sMajorVersion < 4) { //no abstraction :|
         $sql = "SELECT user_key,username FROM user WHERE username='$username' AND password=PASSWORD('$passwd')";
      } else {
         $sql = "SELECT user_key,username FROM user WHERE username='$username' AND password=OLD_PASSWORD('$passwd')";
      }
      
      if (!($result = mysql_query($sql,$my)))
         throw new Error(-4,"while get: " . mysql_error($my));
         
      if (mysql_num_rows($result) == 0) {
         return FALSE;
      } else {
         $row = mysql_fetch_assoc($result);
         $pUsername = $row["username"];
         return TRUE;
      }
   }
   function userhaspriv($pHasPriv,$userkey,$privapi) {
      $myconfig=(new config("mysql"))->getAssoc();
      if (!($my = mysqli_connect($myconfig['dbhost'],$myconfig['username'],$myconfig['password'])))
         throw new Error(-4,"while connect: " . mysql_error($my));
         if (!mysqli_select_db($my,$myconfig['dbname']))
            throw new Error(-4,"while db: " . mysql_error($my));
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
         throw new Error(-4,"while get: " . mysql_error($my));
      if (mysql_num_rows($result) < 1) {
         $pHasPriv = FALSE;
      } else {
         $pHasPriv = TRUE;
      }
      return 0;
   }
