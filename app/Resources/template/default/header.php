<?php
use clientcal\config;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?=$mHeadExtra?>

 <title><?=$sitename?><?=($subtitle)?" | $subtitle":""?></title>
 <link rel="STYLESHEET" href="style.css" type="text/css">
 <link rel="shortcut icon" href="images/clientcal_favicon.ico" />
 <link rel="STYLESHEET" href="<?=(new config("app"))->getValue("components_url_prefix")?>/font-awesome/css/font-awesome.min.css" type="text/css">
 <script type="text/javascript" src="balive.js.php"></script>
</head>