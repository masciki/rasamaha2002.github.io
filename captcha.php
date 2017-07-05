<?php
include "cfg.php";

session_start();
$login	= $_SESSION['login'];
$sid	= htmlspecialchars(substr(session_id(),0,32), ENT_QUOTES, '');

function getip() {
	if(getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else {
		$ip = getenv("REMOTE_ADDR");
	}
$ip = htmlspecialchars(substr($ip,0,15), ENT_QUOTES, '');
return $ip;
}

function generator($case1, $case2, $case3, $case4, $num1) {
	$password = "";

	$small="abcdefghijkmnpqrstuvwxyz";
	$large="ABCDEFGHJKLMNPQRSTUVWXYZ";
	$numbers="23456789";
	$symbols="~!#$%^&*()_+-=,./<>?|:;@";
	mt_srand((double)microtime()*1000000);

	for ($i=0; $i<$num1; $i++) {

		$type = mt_rand(1,4);
		switch ($type) {
		case 1:
			if ($case1 == "on") { $password .= $large[mt_rand(0,23)]; } else { $i--; }
			break;
		case 2:
			if ($case2 == "on") { $password .= $small[mt_rand(0,23)]; } else { $i--; }
			break;
		case 3:
			if ($case3 == "on") { $password .= $numbers[mt_rand(0,7)]; } else { $i--; }
			break;
		case 4:
			if ($case4 == "on") { $password .= $symbols[mt_rand(0,24)]; } else { $i--; }
			break;
		}
	}
	return $password;
}

$case1	= "on";
$case2	= "on";
$case3	= "on";
$case4	= "off";
$num1	= 5;
$num2	= 1;

$code = generator($case1, $case2, $case3, $case4, $num1);
$code = strtoupper($code);

mysql_query("DELETE FROM `captcha` WHERE sid = '".$sid."' AND ip = '".getip()."'");

$sql = "INSERT INTO captcha (ip, sid, code) VALUES ('".getip()."', '".$sid."', '".$code."')";
mysql_query($sql);
if($_GET['glav']) {
$img_path			= "images/code1.jpg";
} else {
$img_path			= "images/code.jpg";
}
$img				= ImageCreateFromJpeg($img_path);
$img_size			= getimagesize($img_path);
$font			= "font/Bebas/bebas_neue_cyrillic.ttf";
$config_font		= 10;
if($_GET['glav']) {
$colors = array('10','30','50','70','90','110','130','150','170','190','210');
$color = imagecolorallocatealpha($img,$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],$colors[rand(0,sizeof($colors)-1)],rand(20,40));
} else {
$config_code_color	= "ffffff";
$color				= imagecolorallocate($img, hexdec(substr($config_code_color,0,1)), hexdec(substr($config_code_color,2,3)), hexdec(substr($config_code_color,4,5)));
}

$x					= 33;
$y					= 43;

imagettftext($img, 30, 0, $x, $y, $color, $font, $code);
imagejpeg($img);
?>