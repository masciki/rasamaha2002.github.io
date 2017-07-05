<?php
	$page = 'withdrawal';
	$file = 'withdrawal.php';
	$idpg = 7;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>