<?php
defined('ACCESS') or die();
if($_GET['id']) {
	include "comments_ru.php";
} else {
	include "answers_list_ru.php";
}
?>