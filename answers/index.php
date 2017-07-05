<?php
	$page = 'answers';
	$file = 'answers.php';
	$idpg = 18;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>