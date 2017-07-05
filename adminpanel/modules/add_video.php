<?php
/*
Данный скрипт разработан Гущенко Виктором Васильевичем, далее автор.
Любое использование данного скрипта, разрешено только с письменного согласия автора.
Скрипт защищён законом!
Дата разработки: 28.11.2014 г.

-> Файл публикации видео
*/
defined('ACCESS') or die();
if ($_GET['go'] == 'govideo') {
	$title	 	 = htmlspecialchars($_POST['title']);
	$link 	 = htmlspecialchars($_POST['link']);
	$own	= intval($_POST['own']);
	$usernam	= htmlspecialchars($_POST['usernam']);
	
	if($usernam <> $login) {
	$useradd	== $usernam;
	} elseif($usernam == $login) {
	$useradd	== $login;
	} elseif(!$usernam) {
	$useradd	== $login;
	}
	$statusadd == 1;

	if (!$title) {
		print '<p class="er">Укажите название видео!</p>';
	} elseif (!$link) {
		print '<p class="er">Введите ссылку на видео Youtube!</p>';
	} else {

	if($own == 1) { 
				mysql_query('UPDATE `video` SET `own` = 0 WHERE own = 1');
		}
		$now	=  time();
		$sql	= "INSERT INTO video (username, date, link, title, own, status) values ('".$useradd."','".$now."','".$link."','".$title."', '".$own."', '".$statusadd."')";
		$result	= mysql_query($sql);

		include "modules/rss.php";

		print '<p class="erok">Видео добавлено!</p>';

	}
}
?>
<script type="text/javascript" src="editor/tiny_mce_src.js"></script>
<script type="text/javascript">
	tinyMCE.init({

		mode : "exact",
		elements : "elm1,elm2",
		theme : "advanced",
		plugins : "cyberfm,safari, inlinepopups,advlink,advimage,advhr,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
		language: "ru",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,hr,|,forecolor,backcolor,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "pasteword,|,bullist,numlist,|,link,image,media,|,tablecontrols,|,replace,charmap,cleanup,fullscreen,preview,code",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		content_css : "/files/styles.css",

		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
 <div class="grid_10">
            <div class="box round first">
                <h2>
                   Добавить видео</h2>
                <div class="block">
<form action="?a=add_video&go=govideo" method="post" name="mainForm">
<table bgcolor="#eeeeee" width="612" align="center" border="0" style="border: solid #cccccc 1px; width: 612px;">
	<tr>
		<td style="padding-left: 2px;">Название видео:</td>
		<td align="right"><input class="inp" style="width: 200px;" type="text" name="title" size="97" /></td>
	</tr>
	<tr>
		<td style="padding-left: 2px;">Ссылка на видео Youtube (Например: xXp0XQYJAT4):</td>
		<td align="right"><input class="inp" style="width: 200px;" type="text" name="link" maxlength="250" /></td>
	</tr>
	<tr>
		<td style="padding-left: 2px;">Добавил(Логин):</td>
		<td align="right"><input class="inp" style="width: 200px;" type="text" name="usernam" maxlength="250" value="<?php print $login; ?>" /></td>
	</tr>
		<tr>
		<td style="padding-left: 2px;">Главное видео?</td>
		<td align="right"><input type="checkbox" value="1" name="own"/></td>
	</tr>
</table>
<table align="center" width="624" border="0">
	<tr>
		<td align="right"><input type="image" src="images/save.gif" width="28" height="29" border="0" title="Сохранить!" /></td>
	</tr>
</table>
</form>
</div>
 </div>
</div>