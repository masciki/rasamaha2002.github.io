<?php
defined('ACCESS') or die();

$action = $_GET['action'];

// Добавление отзыва
	if ($login) {
	if ($_GET['video'] == 'send') {
	$title	 	 = $_POST['titleer'];
	$link 	 = htmlspecialchars($_POST['linker']);
	
	$own	= 0;
	$statusadd == 0;

	if (!$title) {
		print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Укажите название видео.</p>
								</div>';
	} elseif (!$link) {
		print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Укажите ID видео Youtube!</p>
								</div>';
	} elseif (mysql_num_rows(mysql_query("SELECT id FROM video WHERE date > ".(time() - 3600)." AND username = '".$login."' LIMIT 1")) && !$status == 1) {
		print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Предложить видео возможно лишь один раз в час!</p>
								</div>';
	} else {

		$now	=  time();
		$sql	= "INSERT INTO video (username, date, link, title, own, status) values ('".$login."','".$now."','".$link."','".$title."', '".$own."', '".$statusadd."')";
		$result	= mysql_query($sql);
print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Вы успешно отправили видео на модерацию,спасибо за сотрудничество!</p>
								</div>';
	}
}
	} else {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Вы должны авторизироваться для доступа к этой странице!</p>
								</div>';
	}


// Вывод видео-отзывов 
function topics_list($page, $num, $status)
{
	$query	= "SELECT * FROM video WHERE status = 1 AND username <> '' ORDER BY id DESC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);
	
	print'<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7f-film"></i><h3>Видео-отзывы</h3>
								</div>
							</header>';
	
	while ($row = mysql_fetch_array($result))
	{



print '
	<table width="100%" border="0" style="background-color: rgba(0,0,0,.3); margin-top: 1px;">
		<tr>
			<td style="width: 20%; vertical-align: middle;"><p style="text-align:center;"> Видео-отзыв '.$row['title'].' добавил: <b>'.$row['username'].'</b> <br> в '.date("d.m.Y H:i", $row['date']).'';


print ' </p></td><td style="vertical-align: middle;padding-left: 5px;">
			<div class="hline"></div>
			<p align="justify" style="text-align:center; position:relative;"><span id="video" linke="'.$row['link'].'"><img src="/images/play_button.png" style="position:absolute; left: 0; right: 0; margin: 0 auto; top:29%; height: 45%; cursor:pointer;" /><img src="https://img.youtube.com/vi/'.$row['link'].'/0.jpg" tabindex="2" alt="'.$row['title'].'" style="max-height: 200px; max-width: 400px; width: 100%; margin-top: 3px;" title="'.$row['title'].'('.$row['username'].')"/></span></p>';

print '			</td>
		</tr>
	</table><div class="hline" style="margin-bottom: 3px;"></div>
';

	}


	if($page != 1) { $pervpage = "<a href=\"?page=". ($page - 1) ."\">««</a> "; }
	if($page != $total) { $nextpage = " <a href=\"?page=".$total."\">»»</a>"; }
	if($page - 2 > 0) { $page2left = " <a href=\"?page=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
	if($page - 1 > 0) { $page1left = " <a href=\"?page=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
	if($page + 2 <= $total) { $page2right = " <a href=\"?page=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
	if($page + 1 <= $total) { $page1right = " <a href=\"?page=". ($page + 1) ."\">". ($page + 1) ."</a> "; }

	print "<div class=\"pages\">".$pervpage.$page2left.$page1left." ".$page." ".$page1right.$page2right.$nextpage."</div>";
}

$page = intval($_GET['page']);
topics_list($page, $num, $status);

if ($login) {
// Форма добавления
?>
<div class="hline"></div>
<center style="background: rgba(0,0,0,0.25); margin-top: 3px;">
<table width="100%" border="0" align="center">
	<form action="?video=send" method="post" name="mainForm">
<table bgcolor="#eeeeee" width="100%" align="center" border="0" style="width: 100%;">
	<tr class="widget__form">
		<td align="right"><input class="inp" placeholder="Название видео:" type="text" name="titleer" size="97" /></td>
	</tr>
	<tr class="widget__form">
		<td align="right"><input class="inp" placeholder="Ссылка на видео Youtube(ID) - Vz3pdjd8So8" type="text" name="linker" maxlength="250" /></td>
</table>
<table align="center" width="100%" border="0">
	<tr>
		<td align="right"><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Предложить</button></td>
	</tr>
</table>
</form>
</table>
</center>
<script>
$( "p #video" ).click(function() {
  var link =  $( this ).attr("linke");
  $( this ).html('<iframe style="height: 200px; max-width: 400px; width: 100%; margin-top: 3px;" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe>')
});
</script>
<?php
} else {
	print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Для добавления видео-отзывов вам необходимо авторизироваться!</p>
								</div>';
}
?>