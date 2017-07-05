<?php
defined('ACCESS') or die();

	if(cfgSET('cfgWriteEntersIp') == 'on') {
	function getipu() {
	if(getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else {
		$ip = getenv("REMOTE_ADDR");
	}
$ip = htmlspecialchars(substr($ip,0,15), ENT_QUOTES);
return $ip;
}
if(!mysql_num_rows(mysql_query("SELECT * FROM ipwriter WHERE ip = '".getipu()."'"))) {
$sql = "INSERT INTO ipwriter (date, ip, last_page) VALUES ('".time()."', '".getipu()."', '".$_SERVER['HTTP_REFERER']."')";
mysql_query($sql);
} else {
mysql_query("UPDATE ipwriter SET date = ".time()." WHERE ip = '".getipu()."' AND (date + 600) < '".time()."' LIMIT 1");
}
	}

if($login) {
	print'<meta http-equiv="refresh" content="0;/lk/">';
	exit();
}

if(cfgSET('cfgOnOff') == "off" && $status != 1) {
	include "includes/errors/tehwork.php";
	exit();
} elseif(cfgSET('cfgOnOff') == "off" && $status == 1) {
	print '<p align="center" class="warn">Сайт отключен и недоступен для остальных пользователей!</p>';
}
?>
<!DOCTYPE html>
<!-- saved from url=(0050)http://themes-pixeden.com/demos/glazzed/login.html -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CourseMax - <?php print $title;?></title>
	
	<link rel="icon" sizes="192x192" href="http://themes-pixeden.com/demos/glazzed/img/touch-icon.png"> 
	<link rel="apple-touch-icon" href="http://themes-pixeden.com/demos/glazzed/img/touch-icon-iphone.png"> 
	<link rel="apple-touch-icon" sizes="76x76" href="http://themes-pixeden.com/demos/glazzed/img/touch-icon-ipad.png"> 
	<link rel="apple-touch-icon" sizes="120x120" href="http://themes-pixeden.com/demos/glazzed/img/touch-icon-iphone-retina.png">
	<link rel="apple-touch-icon" sizes="152x152" href="http://themes-pixeden.com/demos/glazzed/img/touch-icon-ipad-retina.png">
	
	<link rel="shortcut icon" type="image/x-icon" href="http://themes-pixeden.com/demos/glazzed/img/favicon.ico">

	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/main.min.css">
	
	<!-- Pixeden Icon Fonts -->
	<link rel="stylesheet" type="text/css" href="/css/pe-icon-7-filled.css">
	<link rel="stylesheet" type="text/css" href="/css/pe-icon-7-stroke.css">
</head>
<body>
	<div id="loading" style="display: none;">
		<div class="loader loader-light loader-large"></div>
	</div>
<div class="main-brand" style="position: relative; width: 100%;">
			<div class="main-brand__container" style="width: calc(100% - 50px);">
				<div class="main-logo" style=" font-size: 27px; margin: 16px 0 16px 16px;"><a href="/" style="text-decoration:none; color:#ffffff;"><span style="font-family:Mouse; font-weight: 700;">COURSE</span><span style="font-family:Berlin Sans FB;">MAX</span></a></div>
			</div>
		</div>
									<?php
	defined('ACCESS') or die();
	if(!$page) {
		include "includes/index.php";
	} elseif(file_exists("../".$page."/index.php")) {

		include "../".$page."/".$page.".php";
	} else {
		include "includes/errors/404.php";
	}
?>

	<script type="text/javascript" src="/js/main1.js"></script>
	<script type="text/javascript" src="/js/amcharts.js"></script>
	<script type="text/javascript" src="/js/serial.js"></script>
	<script type="text/javascript" src="/js/pie.js"></script>
	<script type="text/javascript" src="/js/chart.js"></script>

</body></html>