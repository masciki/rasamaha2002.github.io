<?php
	$page = 'rateus';
	$file = 'rateus.php';
	$idpg = 24;
	include '../cfg.php';
	include '../ini.php';
	 if($lng == "ru") {
		include "../template_ru.php";
	} else {
		include "../template_en.php";
	}
?>