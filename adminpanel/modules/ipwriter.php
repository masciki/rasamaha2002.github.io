<?php
/*
Данный скрипт разработан Гущенко Виктором Васильевичем, далее автор.
Любое использование данного скрипта, разрешено только с письменного согласия автора.
Дата разработки: 07.12.2014 г.

-> Файл вывода и управление базой посетителей
*/
defined('ACCESS') or die();

function textFunc( $str, $maxLen )
	{
	if ( mb_strlen( $str ) > $maxLen )
		{
		preg_match( '/^.{0,'.$maxLen.'} .*?/ui', $str, $match );
		return $match[0].'...';
		}
	else {
		return $str;
		}
	}

if(cfgSET('cfgWriteEntersIp') == 'on') {
$ipwristatus = '';
} else {
$ipwristatus = '<a href="?a=settings">(<font color="#FF0000">Отслеживатель IP выключен</font>)</a>';
}
?>
<script language="javascript" type="text/javascript" src="files/alt.js"></script>
 <div class="grid_10">
            <div class="box round first">
                <h2>
                    <center>Управление посетителями<?php print $ipwristatus;?></center></h2>
                <div class="block">
<table class="tbl" border="0" align="center" width="100%" cellpadding="1" cellspacing="1">
<colspan><div align="right" style="padding: 0px;">Сортировать по: <a href="?a=ipwriter">ID</a> | <a href="?a=ipwriter&sort=ip">IP</a> | <a href="?a=ipwriter&sort=date">Дате входа</a></div></colspan>
	<tr align="center" height="19" style="background:URL(images/menu.gif) repeat-x top left;">
		<td width="40"><b>ID</b></td>
		<td><b>IP</b></td>
  		<td><b>Дата последнего входа</b></td>
		<td><b>Перешли со страницы</b></td>
		<td width="110"><b>EDIT</b></td>
	</tr>
<?php
function users_list($page, $num, $query) {
if($_GET['sort'] == "ip") {
	$sort = "ORDER BY ip ASC";
} elseif($_GET['sort'] == "date") {
	$sort = "order by date DESC";
} else {
	$sort = "order by id ASC";
}

if($_GET['action'] == "search") {
	$su = 'WHERE ip LIKE "%'.$_POST['search'].'%"';
}

$query	= 'SELECT * FROM `ipwriter`'.$su.$sort;
	$result = mysql_query($query);
	$themes = mysql_num_rows($result);

	if (!$themes) {
		print '<tr><td colspan="9" align="center"><font color="#ffffff"><b>Посетителей пока не было.</b></font></td></tr>';
	} else {

		$total = intval(($themes - 1) / $num) + 1;
		if (empty($page) or $page < 0) $page = 1;
		if ($page > $total) $page = $total;
		$start = $page * $num - $num;
		$result = mysql_query($query." LIMIT ".$start.", ".$num);
		while ($row = mysql_fetch_array($result)) {	
if($row['date'] > intval(time() - 300) ) {
	$online = 'style="background: rgba(31, 204, 36, 0.25);"';
	} else { 
	$online = '';
	}		
	print "<tr $online align=\"center\">
		<td width=\"10%\">".$row['id']."</td>
		<td width=\"10%\"><b>".$row['ip']."</b></td>
		<td width=\"10%\">".date("d.m.y H:i", $row['date'])."</td>
		<td width=\"30%\">".textFunc( $row['last_page'], 250 )."</td>
		<td width=\"10%\"><a href=\"?a=blacklist&ip=".$row['ip']."\"><img src=\"./images/delite_small.gif\" title=\"Заблокировать IP\" /></a></td>
		</tr>";
}

		if ($page != 1) $pervpage = "<a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page - 1) .">««</a>";
		if ($page != $total) $nextpage = " <a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page + 1) .">»»</a>";
		if($page - 2 > 0) $page2left = " <a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page - 2) .">". ($page - 2) ."</a> | ";
		if($page - 1 > 0) $page1left = " <a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page - 1) .">". ($page - 1) ."</a> | ";
		if($page + 2 <= $total) $page2right = " | <a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page + 2) .">". ($page + 2) ."</a>";
		if($page + 1 <= $total) $page1right = " | <a href=?a=ipwriter&sort=".$_GET['sort']."&page=". ($page + 1) .">". ($page + 1) ."</a>";
		print "<tr height=\"19\"><td colspan=\"9\" bgcolor=\"#ffffff\"><b>Страницы: </b>".$pervpage.$page2left.$page1left."[".$page."]".$page1right.$page2right.$nextpage."</td></tr>";
	}
	print "</table>";
}
users_list(intval($_GET[page]), 50, $sql);
?>
<form action="?a=ipwriter&action=search" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 10px;">
<LEGEND><b>Найти посетителя по IP</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td width="60"><strong>Поиск IP:</strong></td>
		<td><input class="inp" style="background-color: #ffffff; width: 625px;" type="text" name="search" size="93" /></td>
		<td align="center"><input type="image" src="images/search.gif" width="28" height="29" border="0" title="Поиск!" /></td>
	</tr>
</table>
</FIELDSET>
</form>
</div>
</div>
</div>