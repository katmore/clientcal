<?php
   $balive_ping_url = "https://example.com/clientcal/balive.xml.php";
   $balive_interval = 60000; //interval in ms, 0 means balive inactive
   $balive_login_url = "https://example.com/clientcal/?logout";
   $error_line_format = "
      <phperror type=\"%errortype%\" line=\"%linenum%\">
         <msg>%errmsg%</msg>
         <filename>%filename%</filename>
      </phperror>";
