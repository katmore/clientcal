<?php
return (function() {
    
   $config['balive_ping_url'] = "./balive.xml.php";
   $config['balive_interval'] = 60000; //interval in ms, 0 means balive inactive
   $config['balive_login_url'] = "./?logout";
   $config['error_line_format'] = "
         <phperror type=\"%errortype%\" line=\"%linenum%\">
            <msg>%errmsg%</msg>
            <filename>%filename%</filename>
         </phperror>";
   
   return $config;
})();