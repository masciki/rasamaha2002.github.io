<script language="javascript" type="text/javascript" src="files/alt.js"></script>
<?php
/*
������ ������ ���������� ���������� �������� ������������, ����� �����.
����� ������������� ������� �������, ��������� ������ � ����������� �������� ������.
������ ������� �������: http://adminstation.ru/images/docs/doc1.jpg
*/
defined('ACCESS') or die();


if($_GET['action'] == "addpercent") {
$date = time() - 86400;
	$percent	= sprintf("%01.2f", $_POST['percent']);

	if($percent > 0) {

		$query	= "SELECT * FROM users WHERE pm_balance > 0";
		$result	= mysql_query($query);
		while($row = mysql_fetch_array($result)) {
		$uref = $row['ref'];
$uid	= $row['id'];
			$p	= sprintf("%01.2f", $row['pm_balance'] / 100 * $percent);

			
				mysql_query('UPDATE users SET pm_balance = pm_balance + '.$p.' WHERE id = '.$row['id'].' LIMIT 1');
			

			// ������ � ����������
			mysql_query('INSERT INTO stat (user_id, date, plan, sum) VALUES ('.$uid.', '.time().', '.$percent.', '.$p.')');
			mysql_query("UPDATE perc SET perc_a = '$percent', full_perc = full_perc + '$percent' WHERE id = 1");
			
			
						// ��������� ����� "�������" ���������
	if($uref > 0) {

				// ������������ ���-�� �������
				$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

				if($countlvl > 0) {
					$i		= 0;
					
					$queryq	= "SELECT * FROM reflevels ORDER BY id ASC";
					$resultq	= mysql_query($queryq);
					while($roww = mysql_fetch_array($resultq)) {
						if($i < $countlvl) {
							$lvlperc = $roww['sum'];		// ������� ������
							$ps		 = sprintf("%01.2f", $p / 100 * $lvlperc); // ����� �������

							if($uref > 0) {
									mysql_query('UPDATE users SET pm_balance = pm_balance + '.$ps.', reftop = reftop + '.$ps.' WHERE id = '.$uref.' LIMIT 1');

								mysql_query('UPDATE users SET ref_money = ref_money + '.$ps.' WHERE id = '.$uid.' LIMIT 1');

								// �������� ������ ���������� ������������

								$get_ref	= mysql_query("SELECT id, ref FROM users WHERE id = ".intval($uref)." LIMIT 1");
								$rowref		= mysql_fetch_array($get_ref);
								$uref		= $rowref['ref'];
								$uid		= $rowref['id'];
								echo $ps;

							}

						}
						$i++;
					}
				}

			
			// ��������� � ����������

		}
		
	}
		
		print '<p class="erok">�������� ���� ���������! �� ���������� ��������!</p>';
	} else {
		print '<p class="er">������� ������� ����������</p>';
	}
}

$money = 0.00;
$query	= "SELECT `sum`  FROM `deposits`";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$money = $money + $row['sum'];
}
?>
<center><b>����� �������� ��������� �� �����: $<?php print sprintf("%01.2f", $money); ?></b></center>
<hr />
 
 <table border="0" align="center" width="100%" cellpadding="1" cellspacing="1" class="tbl">

<colspan><div align="right" style="padding: 2px;">����������� ��: <a href="?a=deposits&sort=id">ID (����)</a> | <a href="?a=deposits&sort=sum">�����</a> | <a href="?a=deposits&sort=username">������</a></div></colspan>

	<tr align="center" height="19" style="background:URL(images/menu.gif) repeat-x top left;">

		<td width="40"><b>ID</b></td>

  		<td><b>����</b></td>

		<td><b>�����</b></td>

		<td><b>�����</b></td>

		<td><b>�������� ����</b></td>

	</tr>

<?php

function users_list($page, $num, $query) {



	$result = mysql_query($query);

	$themes = mysql_num_rows($result);



	if (!$themes) {

		print '<tr><td colspan="9" align="center"><font color="#ffffff"><b>��������� ���� ���.</b></font></td></tr>';

	} else {



		$total = intval(($themes - 1) / $num) + 1;

		if (empty($page) or $page < 0) $page = 1;

		if ($page > $total) $page = $total;

		$start = $page * $num - $num;

		$result = mysql_query($query." LIMIT ".$start.", ".$num);

		while ($row = mysql_fetch_array($result)) {



		$result2	= mysql_query("SELECT name FROM plans WHERE id = ".$row['plan']." LIMIT 1");

		$row2		= mysql_fetch_array($result2);



			print "<tr bgcolor=\"#eeeeee\" align=\"center\">

			<td>".$row['id']."</td>

			<td>".date("d.m.y H:i", $row['date'])."</td>

			<td align=\"left\"><a href=\"?a=edit_user&id=".$row['user_id']."\"><b>".$row['username']."</b></a></td>

			<td>".$row['sum']."</td>

			<td>".$row2['name']."</td>

		</tr>";

		}



		if ($page != 1) $pervpage = "<a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page - 1) .">��</a>";

		if ($page != $total) $nextpage = " <a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page + 1) .">��</a>";

		if($page - 2 > 0) $page2left = " <a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page - 2) .">". ($page - 2) ."</a> | ";

		if($page - 1 > 0) $page1left = " <a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page - 1) .">". ($page - 1) ."</a> | ";

		if($page + 2 <= $total) $page2right = " | <a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page + 2) .">". ($page + 2) ."</a>";

		if($page + 1 <= $total) $page1right = " | <a href=?a=deposits&sort=".$_GET['sort']."&page=". ($page + 1) .">". ($page + 1) ."</a>";

		print "<tr height=\"19\"><td colspan=\"5\" bgcolor=\"#ffffff\"><b>��������: </b>".$pervpage.$page2left.$page1left."[".$page."]".$page1right.$page2right.$nextpage."</td></tr>";

	}

	print "</table>";

}



if($_GET['sort'] == "id") {

	$sort = "ORDER BY id DESC";

} elseif($_GET['sort'] == "sum") {

	$sort = "order by sum DESC";

} elseif($_GET[sort] == "username") {

	$sort = "order by username ASC";

} else {

	$sort = "order by id ASC";

}



if($_GET['action'] == "search") {

	$su = " AND username = '".htmlspecialchars($_POST['name'], ENT_QUOTES, '')."'";

}



$sql = "SELECT * FROM deposits WHERE status = 0 AND id != 999 ".$su." ".$sort;

users_list(intval($_GET['page']), 50, $sql);

?>


<form action="?a=deposits&action=addpercent" method="post">
<FIELDSET style="border: solid #666666 1px; padding: 10px; margin-top: 20px;">
<LEGEND><b>��������� �������� �� ��������� �������</b></LEGEND>
<table width="100%" border="0">
	<tr>
		<td><strong>������� �� ����� ������:</strong></td>
		<td><input style="width: 720px;" type="text" name="percent" size="93" /></td>
		<td></td>
	</tr>
	<tr>
		<td><strong>�������� ����:</strong></td>
		<td><select name="plan" style="width: 720px;">
		<option value="0">��������� �� ���� �������� ������</option>
<?php
$result	= mysql_query("SELECT * FROM plans ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {
	print '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
?></select></td>
		<td align="center"><input type="image" src="images/save.gif" width="28" height="29" border="0" title="���������!" /></td>
	</tr>
</table>
</FIELDSET>
</form>