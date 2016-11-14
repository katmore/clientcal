<?php
function ConnectToDB($Dbhost,$Dbname,$Dbuser,$Dbpass,&$pMy) {
   if (!($pMy = @mysql_connect($Dbhost,$Dbuser,$Dbpass)))
      return PanelError(-4,"while connect: " . mysql_error() . "<br />");
      if (!@mysql_select_db($Dbname))
         return PanelError(-4,"while db: " . mysql_error() . "<br />");
         return 0;
}
function MyEscape($My,$Value) {
   if (get_magic_quotes_gpc()) {
      $value = stripslashes($Value);
   }
   $Value = mysql_real_escape_string($Value);
   return $Value;
}