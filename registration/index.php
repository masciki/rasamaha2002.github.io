<?php
	$page = 'registration';
	$file = 'registration.php';
	$idpg = 3;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_login.php";
	} else {
		include "../template_login_en.php";
	}
?>