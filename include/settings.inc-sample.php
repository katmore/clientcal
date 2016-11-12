<?php
   date_default_timezone_set("America/Los_Angeles");
   $sitename = "ClientCal Scheduler"; // name of the panel
   $sitesubtitle = "schedule your dreams";
   $copyrightnotice = "&copy;2011 Doug Bird"; // copyright notice
   $usenotice = "use of this site subject to terms and conditions"; // terms of use notice
   $loginnotice = "Schedule Sign In";

   $control_dbhost = "localhost";
   $control_dbname = "clientcal";
   $control_dbuser = "clientcal";
   $control_dbpasswd = "";

   $data_dbhost = "localhost";
   $data_dbname = "clientcal";
   $data_dbuser = "clientcal";
   $data_dbpasswd = "";

   $sentry_table = "sentry";
   $sentrytype_table = "sentrytype";
   $supervisor_table = "supervisor";

   $customer_table = "customer";
   $customertype_table = "customertype";
   $customerphone_table = "customer_phone";

   $site_table = "site";

   $supervisor_table = "supervisor";

   $default_tableclass = "";

   $default_customerstate = "CA";

   $show_php_errors = FALSE;

   $error_line_format = "<li>%errmsg%; (%errortype%) on line <b>%linenum%</b><br />&nbsp;&nbsp; %filename%<br />";

   $session_life = 604800;

   $default_customerareacode = "213";

   $dir_custfiles = __DIR__."/../custfiles/";

   $dir_uploadtmp = sys_get_temp_dir();
   
   $dir_custfile_queue = __DIR__."/../custfilequeue/";
   
   $hashalgo_custfile = "sha256";

   $tinysquarethumb_defaultx = 18;
   
   $tinythumb_default_height = 25;
   
   $jpgthumb_default_width = 400;
   
   $custfile_mailup_email = "custfile@example.com";
   
   $custfile_mailup_user = "custfile@example.com";
   $custfile_mailup_pass = "";
   $custfile_mailup_host = "localhost";
   $custfile_mailup_port = 995;
   $custfile_mailup_folder = "INBOX";
   $custfile_mailup_imap_mailbox = "{".$custfile_mailup_host.":".$custfile_mailup_port."}".$custfile_mailup_folder;
   $custfile_mailup_purge = true;
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

