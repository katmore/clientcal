<?php 

namespace clientcal;

   function EnumerateSupervisors($My,$Table,&$pCount,&$pKey,&$pName,&$pFirst,&$pLast) {
      $pCount = 0;
      $pKey = array();
      $pName = array();
      $pFirst = array();
      $pLast = array();
      $sql = "
      SELECT
         id,
         name,
         first,
         last,
         concat(last,', ',first) AS full
      FROM
         $Table
      ORDER BY
         weight
      DESC
      ";
      if (!($result = @mysql_query($sql,$My)))
         throw new Error(-4,"while insert: " . mysql_error());
      while ($row = mysql_fetch_assoc($result)) {
         $pKey[$pCount] = $row["id"];
         $pName[$pCount] = $row["name"];
         $pFirst[$pCount] = $row["first"];
         $pLast[$pCount] = $row["last"];
         $pCount++;
      }
      return 0;
   }
