<?php

namespace clientcal;

   function panelmenu($tableclass) {
      //require("settings.php");
      $ret = "
      <table width=\"300\">
         <tr>
            <td class=\"heading\"><b>features</b></td>
         </tr>
         <tr>
            <td class=\"project\">
                  <li><b><a href=\"sentry.php?showsentries=CurMonth\">[month]</a></b><br />
                  schedule in monthly calendar view
            </td>
         </tr>
         <tr>
            <td class=\"project\">
                  <li><b><a href=\"sentry.php?sentrieslist\">[week]</a></b><br />
                  schedule in weekly view
            </td>
         </tr>
         <tr>
            <td class=\"project\">
               <li><b><a href=\"./customer.php?alpha=A\">[customers]</a></b><br />
               " . customersearch() . "<br>
               lookup customer
            </td>
         </tr>
      </table>
      ";
      //showsentries
      return $ret;
   }
