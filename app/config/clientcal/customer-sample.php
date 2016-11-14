<?php
return (function() {
   
   $config['default_customerstate'] = "CA";
   $config['default_customerareacode'] = "213";
   $config['default_tableclass'] = "";
   
   $config['dir_uploadtmp'] = sys_get_temp_dir();
   $config['dir_custfiles'] = __DIR__."/../../custfiles/";
   $config['dir_custfile_queue'] = __DIR__."/../../custfilequeue/";
   $config['hashalgo_custfile'] = "sha256";
   $config['tinysquarethumb_defaultx'] = 18;
   $config['tinythumb_default_height'] = 25;
   $config['jpgthumb_default_width'] = 400;
   
   // $config['custfile_mailup_email'] = "custfile@clientcal.com";
   // $config['custfile_mailup_user'] = "custfile.clientcal";
   // $config['custfile_mailup_pass'] = "7eb7e7c473b5037726d46f16cc6f340e";
   // $config['custfile_mailup_host'] = "localhost";
   // $config['custfile_mailup_port'] = 995;
   // $config['custfile_mailup_folder'] = "INBOX";
   // $config['custfile_mailup_imap_mailbox'] = "{".$config['custfile_mailup_host'].":".$config['custfile_mailup_port']."/pop3/ssl/novalidate-cert}".$config['custfile_mailup_folder'];
   // $config['custfile_mailup_purge'] = true;
   
   return $config;
})();

