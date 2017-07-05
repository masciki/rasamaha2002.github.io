<?php
	$page = 'reminder';
	$file = 'reminder.php';
	$idpg = 4;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_login.php";
	} else {
		include "../template_login_en.php";
	}
?>