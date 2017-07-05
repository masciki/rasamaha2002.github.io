<?php
	$page = 'video';
	$file = 'video.php';
	$idpg = 26;
	include '../cfg.php';
	include '../ini.php';
	if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>