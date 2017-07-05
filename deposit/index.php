<?php
	$page = 'deposit';
	$file = 'deposit.php';
	$idpg = 13;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>