<?php
if($_GET['id']) {
	include "comments.php";
} else {
	include "answers_list.php";
}
?>