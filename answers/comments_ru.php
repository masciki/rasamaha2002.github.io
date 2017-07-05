<?php
defined('ACCESS') or die();

// ---------------------------------------------------------------------
if($_GET['pid']) {
	$pid = intval($_GET['pid']);
} else {
	$pid = intval($_GET['id']);
}

if($_GET['a'] == "pollno") {
	// Голосование (ПРОТИВ)

	if(!$login) {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Для голосования вам необходимо зарегистрироваться!</p>
								</div>';
	} elseif(mysql_num_rows(mysql_query("SELECT * FROM answers_poll WHERE answer_id = ".$pid." AND (user_id = ".$user_id." || ip = '".getip()."') LIMIT 1"))) {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Вы уже голосовали за данный отзыв</p>
								</div>';
	} else {

		mysql_query("INSERT INTO answers_poll (`user_id`, `date`, `ip`, `answer_id`, `poll`) VALUES (".$user_id.", ".time().", '".getip()."', ".$pid.", 1)");
		mysql_query("UPDATE answers SET poll_no = poll_no + 1, poll_count = poll_count + 1 WHERE id = ".$pid." LIMIT 1");
		print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">Ваше мнение учтено!</p>
								</div>';

	}

} elseif($_GET['a'] == "pollyes") {
	// Голосование (ЗА)

	if(!$login) {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Для голосования вам необходимо зарегистрироваться!</p>
								</div>';
	} elseif(mysql_num_rows(mysql_query("SELECT * FROM answers_poll WHERE answer_id = ".$pid." AND (user_id = ".$user_id." || ip = '".getip()."') LIMIT 1"))) {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Вы уже голосовали за данный отзыв</p>
								</div>';
	} else {

		mysql_query("INSERT INTO answers_poll (`user_id`, `date`, `ip`, `answer_id`, `poll`) VALUES (".$user_id.", ".time().", '".getip()."', ".$pid.", 2)");
		mysql_query("UPDATE answers SET poll_yes = poll_yes + 1, poll_count = poll_count + 1 WHERE id = ".$pid." LIMIT 1");
		print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">Ваше мнение учтено!</p>
								</div>';

	}

} elseif($status == 1 && $_GET['v']) {
	mysql_query("UPDATE answers SET view = 0 WHERE id = ".intval($_GET['v'])." LIMIT 1");
	print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">Сообщение скрыто</p>
								</div>';
} 

// Добавление отзыва
if ($_GET['action'] == "send") {
	if ($login) {
		if (!$_POST['text'] || $_POST['text'] == " ") {
				print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Введите текст сообщения</p>
								</div>';
			} else {
			$text = nl2br(htmlspecialchars(substr($_POST['text'], 0, 10000), ENT_QUOTES, ''));

			$text = str_replace(":001:","<img src=\"/images/smiles/01.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":002:","<img src=\"/images/smiles/02.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":003:","<img src=\"/images/smiles/03.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":004:","<img src=\"/images/smiles/04.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":005:","<img src=\"/images/smiles/05.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":006:","<img src=\"/images/smiles/06.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":007:","<img src=\"/images/smiles/07.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":008:","<img src=\"/images/smiles/08.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":009:","<img src=\"/images/smiles/09.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":010:","<img src=\"/images/smiles/10.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":011:","<img src=\"/images/smiles/11.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":012:","<img src=\"/images/smiles/12.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":013:","<img src=\"/images/smiles/13.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":014:","<img src=\"/images/smiles/14.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":015:","<img src=\"/images/smiles/15.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":016:","<img src=\"/images/smiles/16.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":017:","<img src=\"/images/smiles/17.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":018:","<img src=\"/images/smiles/18.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":019:","<img src=\"/images/smiles/19.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":020:","<img src=\"/images/smiles/20.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":021:","<img src=\"/images/smiles/21.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":022:","<img src=\"/images/smiles/22.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":023:","<img src=\"/images/smiles/23.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":024:","<img src=\"/images/smiles/24.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":025:","<img src=\"/images/smiles/25.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":026:","<img src=\"/images/smiles/26.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":027:","<img src=\"/images/smiles/27.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);
			$text = str_replace(":028:","<img src=\"/images/smiles/28.gif\" height=\"20\" width=\"20\" border=\"0\" alt=\":)\" />",$text);

			$temp = strtok($text, " ");


			if (!$text || $text == " ") {
				print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Введите текст сообщения</p>
								</div>';
			} elseif (strlen($temp) > 100) {
				print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Текст Вашего сообщение содержит слишком много символов без пробелов!</p>
								</div';
			} elseif (mysql_num_rows(mysql_query("SELECT id FROM answers WHERE date > ".(time() - 300)." AND username = '".$login."' LIMIT 1"))) {
				print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Комментарий нельзя добавлять чаще одного раза в 5 минут.</p>
								</div>';
			} else {

				if(mysql_num_rows(mysql_query("SELECT user_id FROM clients WHERE user_id = ".$user_id." LIMIT 1"))) {

					$get_user	= mysql_query("SELECT id FROM clients WHERE user_id = ".$user_id." LIMIT 1");
					$row		= mysql_fetch_array($get_user);
					$client_id	= $row['id'];

				} else {
					$client_id 	= 0;
				}

				$sql = "INSERT INTO answers (`part`, `username`, `client_id`, `text`, `date`, `view`, `ip`, `poll`) VALUES (".intval($_GET['id']).", '".$login."', ".$client_id.", '".$text."', ".time().", 1, '".getip()."', 5)";

				if (mysql_query($sql)) {
					print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">Комментарий добавлен!</p>
								</div>';
					mysql_query("UPDATE answers SET comments = comments + 1 WHERE id = ".intval($_GET['id'])." LIMIT 1");
				} else {
					print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Произошла ошибка при записи данных в БД</p>
								</div>';
				}

				$text = "";
			}
		}
	} else {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Вы должны авторизироваться для доступа на эту страницу!</p>
								</div>';
	}
}

// ---------------------------------------------------------------------

	$query	= "SELECT * FROM answers WHERE view = 1 AND id = ".intval($_GET['id'])." LIMIT 1";
	$result	= mysql_query($query);
	$row	= mysql_fetch_array($result);

	if($row) {

  		if ($status == 1) {
			$admin_but  = "<a href=\"/adminpanel/adminstation.php?a=admin_answers&id=".$row['id']."\"><img src=\"/adminpanel/images/comment_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Комментировать\" /></a> ";
			$admin_but .= "<a href=\"/adminpanel/adminstation.php?a=edit_answers&id=".$row['id']."\"><img src=\"/adminpanel/images/edit_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Редактировать сообщение\" /></a> ";
			$admin_but .= "<img style=\"cursor: hand;\" onclick=\"if(confirm('Вы уверены?')) top.location.href='/adminpanel/del/answers.php?id=".$row['id']."'\"; src=\"/adminpanel/images/delite_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Удалить сообщение\" />";
			$admin_but .= " IP: ".$row['ip'];
		} else {
			$admin_but	= "";
		}

		if ($row['yes'] == 1) {
			$smile = '<img src="/images/yes.gif" width="15" height="15" border="0" alt="Положительный отзыв" title="Положительный отзыв" />';
			$style = "background-color: rgba(0,0,0,.3); margin-top: 1px;";
			$styleback = 'background: rgba(159, 195, 160, 0.25); padding-left: 5px;';
		} else {
			$smile = '<img src="/images/no.gif" width="15" height="15" border="0" alt="Отрицательный отзыв" title="Отрицательный отзыв" />';
			$style = "background-color: rgba(0,0,0,.3); margin-top: 1px;";
			$styleback = 'background: rgba(243, 93, 93, 0.25); padding-left: 5px;';
		}



print '
	<table width="100%" border="0" style="'.$style.'">
		<tr>
			<td style="width: 20%; vertical-align: middle;"><p style="text-align:center;">'.date("d.m.Y H:i", $row['date']).' <br> <b>'.$row['username'].'</b>';

			if($row['client_id'] == 0) {
				print '<br> [Прохожий]';
			} else {
				print '<br> [Инвестор]';
			}

print ' <br>'.$admin_but.'</p></td><td style="vertical-align: middle;'.$styleback.'">
			<div class="hline"></div>
			<p align="justify" style="position: relative; top: 5px;">'.$row['text'].'</p>';

		if($row['answer']) { print "<div style='border: 2px solid rgba(146, 145, 143, 0.48); background-color: rgba(140, 156, 173, 0.18); padding: 3px;border-radius: 12px;margin-top: 9px;'><i style=\"color: rgba(255, 255, 255, 0.37);\">Комментарий от администрации:</i><br /><font style=\"color: rgb(197, 197, 197);\">".$row['answer']."</font></div>"; }

print '	<div style="margin-top: 3px;" class="hline"></div>
		<div style="float: left;">
		</div>
		<div align="right">
			<i class="pe-7f-comment" style="position: relative; font-size: 20px; bottom: -6px;"></i> <span class="badge badge--line badge--blue" style="padding: 1px 4px; font-family: PancettaPro-Italic; font-size: 10px;">'.$row['comments'].'</span> | <a style="color: red; bottom: 2px; position: relative;"  href="?a=pollno&id='.$row['id'].'"><i class="pe-7f-like2" style="position: relative; font-size: 20px; bottom: -6px; -moz-transform: scale(-1, 1) rotate(-180deg); -webkit-transform: scale(-1, 1) rotate(-180deg); -o-transform: scale(-1, 1) rotate(-180deg); transform: scale(-1, 1) rotate(-180deg); filter: FlipH; -ms-filter: "FlipH";"></i> <span class="badge badge--line badge--red" style="padding: 1px 4px; font-family: PancettaPro-Italic; font-size: 10px;">'.$row['poll_no'].'</span></a> | <a style="color: green; bottom: 2px; position: relative;" href="?a=pollyes&id='.$row['id'].'"><i class="pe-7f-like2" style="position: relative; font-size: 20px; bottom: -6px;"></i> <span class="badge badge--line badge--green" style="padding: 1px 4px; font-family: PancettaPro-Italic; font-size: 10px;">'.$row['poll_yes'].'</span></a>
		</div>
			</td>
		</tr>
	</table><div class="hline" style="margin-bottom: 3px;"></div>
';

print '<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7f-comment"></i><h3>Комментарии к отзыву</h3>
								</div>
							</header>';

// Вывод отзывов
function topics_list($page, $num, $status)
{
	$query	= "SELECT * FROM answers WHERE view = 1 AND part = ".intval($_GET['id'])." ORDER BY id ASC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);

	if(!$themes) {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> К данному отзыву нет комментариев.</p>
								</div>';
	}

	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);
	while ($row = mysql_fetch_array($result))
	{
  		if ($status == 1) {
			$admin_but  = "<a href=\"/adminpanel/adminstation.php?a=admin_answers&id=".$row['id']."\"><img src=\"/adminpanel/images/comment_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Комментировать\" /></a> ";
			$admin_but .= "<a href=\"/adminpanel/adminstation.php?a=edit_answers&id=".$row['id']."\"><img src=\"/adminpanel/images/edit_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Редактировать сообщение\" /></a> ";
			$admin_but .= "<img style=\"cursor: hand;\" onclick=\"if(confirm('Вы уверены?')) top.location.href='/adminpanel/del/answers.php?id=".$row['id']."'\"; src=\"/adminpanel/images/delite_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Удалить сообщение\" />";
			$admin_but .= " IP: ".$row['ip'];
		} else {
			$admin_but	= "";
		}

print '
	<table width="100%" border="0" style="background-color: rgba(0,0,0,.3); margin-top: 1px;">
		<tr>
			<td style="width: 20%; vertical-align: middle;"><p style="text-align:center;">'.$smile.' '.date("d.m.Y H:i", $row['date']).' <br> <b>'.$row['username'].'</b>';

			if($row['client_id'] == 0) {
				print '<br> [Прохожий]';
			} else {
				print '<br> [Инвестор]';
			}

print ' <br>'.$admin_but.'</p></td><td style="vertical-align: middle;">
			<div class="hline"></div>
			<p align="justify" style="position: relative; top: 5px;">'.$row['text'].'</p>';

		if($row['answer']) { print "<div style='border: 2px solid rgba(146, 145, 143, 0.48); background-color: rgba(140, 156, 173, 0.18); padding: 3px;border-radius: 12px;margin-top: 9px;'><i style=\"color: rgba(255, 255, 255, 0.37);\">Комментарий от администрации:</i><br /><font style=\"color: rgb(197, 197, 197);\">".$row['answer']."</font></div>"; }

print '	<div style="margin-top: 3px;" class="hline"></div>
		<div align="right">
			<a style="color: red; bottom: 2px; position: relative;"  href="?a=pollno&id='.$row['id'].'"><i class="pe-7f-like2" style="position: relative; font-size: 20px; bottom: -6px; -moz-transform: scale(-1, 1) rotate(-180deg); -webkit-transform: scale(-1, 1) rotate(-180deg); -o-transform: scale(-1, 1) rotate(-180deg); transform: scale(-1, 1) rotate(-180deg); filter: FlipH; -ms-filter: "FlipH";"></i> <span class="badge badge--line badge--red" style="padding: 1px 4px; font-family: PancettaPro-Italic; font-size: 10px;">'.$row['poll_no'].'</span></a> | <a style="color: green; bottom: 2px; position: relative;" href="?a=pollyes&id='.$row['id'].'"><i class="pe-7f-like2" style="position: relative; font-size: 20px; bottom: -6px;"></i> <span class="badge badge--line badge--green" style="padding: 1px 4px; font-family: PancettaPro-Italic; font-size: 10px;">'.$row['poll_yes'].'</span></a>
		</div>
			</td>
		</tr>
	</table><div class="hline" style="margin-bottom: 3px;"></div>
';

	}


	if($page != 1) { $pervpage = "<a href=\"?page=". ($page - 1) ."\">««</a> "; }
	if($page != $total) { $nextpage = " <a href=\"?page=".$total."\">»»</a>"; }
	if($page - 2 > 0) { $page2left = " <a href=\"?page=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
	if($page - 1 > 0) { $page1left = " <a href=\"?page=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
	if($page + 2 <= $total) { $page2right = " <a href=\"?page=". ($page + 2) ."\">". ($page + 2) ."</a>"; }
	if($page + 1 <= $total) { $page1right = " <a href=\"?page=". ($page + 1) ."\">". ($page + 1) ."</a>"; }

	print "<div class=\"pages\">".$pervpage.$page2left.$page1left."".$page."".$page1right.$page2right.$nextpage."</div>";
}

$page = intval($_GET['page']);
topics_list($page, $num, $status);



if ($login) {
// Форма добавления комментариев
?>
<div class="hline"></div>
<center style="background: rgba(0,0,0,0.25); margin-top: 3px;">
<table width="100%" border="0" align="center">
	<form action="?action=send" method="post" name="msg_form">
	<tr>
		<td colspan="1"><textarea placeholder="Текст сообщения..." style="min-width: 278px; width: 100%; background: rgba(0,0,0,0.25);" name="text" rows="7" cols="48"><?php print htmlspecialchars(substr($_POST['text'], 0, 10000), ENT_QUOTES, ''); ?></textarea></td>
	</tr>
	<tr>
		<td align="right"><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Отправить</button></td>
	</tr>
	</form>
</table>
</center>
<?php
		} else {
			print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Для добавления комментария вам необходимо авторизироваться!</p>
								</div>';
		}

	} else {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Отзыв не найден!</p>
								</div>';
	}
?>