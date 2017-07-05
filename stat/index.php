<?php
	$page = 'stat';
	$file = 'stat.php';
	$idpg = 14;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>