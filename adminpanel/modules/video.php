<script language="javascript" type="text/javascript" src="files/alt.js"></script>
 <div class="grid_10">
            <div class="box round first">
                <h2>
                  ����� - <a href="?a=add_video">�������� �����</a></h2>
                <div class="block">
<?php
defined('ACCESS') or die();

$query	= "SELECT * FROM `video`";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
}
?>
<table border="0" align="center" width="100%" cellpadding="1" cellspacing="1" class="tbl">
<colspan><div align="right" style="padding: 2px;">����������� ��: <a href="?a=video&sort=id">ID (����)</a> | <a href="?a=video&sort=username">������(����)</a></div></colspan>
	<tr align="center" height="19" style="background:URL(images/menu.gif) repeat-x top left;">
		<td width="40"><b>ID</b></td>
  		<td><b>����</b></td>
		<td><b>�������</b></td>
		<td><b>��������</b></td>
		<td><b>������</b></td>
		<td><b>�������?</b></td>
		<td><b>������</b></td>
	</tr>
<?php
function users_list($page, $num, $query) {

	$result = mysql_query($query);
	$themes = mysql_num_rows($result);

	if (!$themes) {
		print '<tr><td colspan="9" align="center"><font color="#ffffff"><b>����� ���� ����.</b></font></td></tr>';
	} else {

		$total = intval(($themes - 1) / $num) + 1;
		if (empty($page) or $page < 0) $page = 1;
		if ($page > $total) $page = $total;
		while ($row = mysql_fetch_array($result)) {
		
		if($row['status'] == 0) {
		$statuspr = '�� ���������';
		} elseif($row['status'] == 1) {
		$statuspr = '���������';
		}
		
			print "<tr bgcolor=\"#eeeeee\" align=\"center\">
			<td>".$row['id']." <a href=\"?a=edit_video&id=".$row[id]."\"><img src=\"images/edit_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"�������������\"></a></td>
			<td>".date("d.m.y H:i", $row['date'])."</td>
			<td>".$row['username']."</td>
			<td>".$row['title']."</td>
			<td>".$row['link']."</td>
			<td>".$row['own']."</td>
			<td>".$statuspr."</td>
		</tr>";
		}

		if ($page != 1) $pervpage = "<a href=?a=video&sort=".$_GET['sort']."&page=". ($page - 1) .">��</a>";
		if ($page != $total) $nextpage = " <a href=?a=video&sort=".$_GET['sort']."&page=". ($page + 1) .">��</a>";
		if($page - 2 > 0) $page2left = " <a href=?a=video&sort=".$_GET['sort']."&page=". ($page - 2) .">". ($page - 2) ."</a> | ";
		if($page - 1 > 0) $page1left = " <a href=?a=video&sort=".$_GET['sort']."&page=". ($page - 1) .">". ($page - 1) ."</a> | ";
		if($page + 2 <= $total) $page2right = " | <a href=?a=video&sort=".$_GET['sort']."&page=". ($page + 2) .">". ($page + 2) ."</a>";
		if($page + 1 <= $total) $page1right = " | <a href=?a=video&sort=".$_GET['sort']."&page=". ($page + 1) .">". ($page + 1) ."</a>";
		print "<tr height=\"19\"><td colspan=\"6\" bgcolor=\"#ffffff\"><b>��������: </b>".$pervpage.$page2left.$page1left."[".$page."]".$page1right.$page2right.$nextpage."</td></tr>";
	}
	print "</table>";
}

if($_GET['sort'] == "id") {
	$sort = "ORDER BY id DESC";
} elseif($_GET['sort'] == "username") {
	$sort = "order by username ASC";
} else {
	$sort = "order by id ASC";
}

$sql = "SELECT * FROM video ".$su." ".$sort;
users_list(intval($_GET['page']), 50, $sql);
?>

</div>
</div>
</div>