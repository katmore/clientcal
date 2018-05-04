<?php
return (function() {
   
   $config['default_customerstate'] = "CA";
   $config['default_customerareacode'] = "213";
   $config['default_tableclass'] = "";
   
   $config['dir_uploadtmp'] = sys_get_temp_dir();
   $config['dir_custfiles'] = __DIR__."/../../../custfiles/";
   $config['dir_custfile_queue'] = __DIR__."/../../../custfilequeue/";
   $config['hashalgo_custfile'] = "sha256";
   $config['tinysquarethumb_defaultx'] = 18;
   $config['tinythumb_default_height'] = 25;
   $config['jpgthumb_default_width'] = 400;
   
   return $config;
})();