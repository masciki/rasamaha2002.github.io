<?php
defined('ACCESS') or die();
if($login) {

	if($_GET['close']) {

		$result	= mysql_query("SELECT * FROM deposits WHERE id = ".intval($_GET['close'])." AND user_id = ".$user_id." AND status = 0 LIMIT 1");
		$row	= mysql_fetch_array($result);

		$result2	= mysql_query("SELECT * FROM plans WHERE id = ".$row['plan']." LIMIT 1");
		$row2		= mysql_fetch_array($result2);

		if(!$row['id'] || !$row2['id']) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Произошла ошибка при закрытии депозита.</p>
								</div>';
		} elseif($row2['back'] != 1 || $row2['close'] != 1) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Этот депозит невозможно закрыть досрочно.</p>
								</div>';
		} else {
			$sum = sprintf("%01.2f", $row['sum'] - $row['sum'] / 100 * $row2['close_percent']);
			mysql_query('UPDATE users SET pm_balance = pm_balance + '.$sum.' WHERE id = '.$row['user_id'].' LIMIT 1');
			mysql_query("DELETE FROM deposits WHERE id = ".$row['id']." LIMIT 1");
			print '<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Депозит был закрыт досрочно.</p>
								</div>';
		}

	}
?>
<?php
$it = 0;
$s = 0;
$result	= mysql_query("SELECT * FROM deposits WHERE user_id = ".$user_id." ORDER BY id ASC");

	print '	<div class="col-md-8" style=" width: 100%; margin-top: -14px;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7f-piggy"></i><h3>Открытые депозиты</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>Тариф</th>
<th>Дата вклада</th>
<th>Окончание</th>
<th>Вклад</th>
<th>% в день</th>
<th>Вывод в день</th>
<th>Заработано</th>
<th>Статус выплат</th>
<th>Ред.</th>
</tr>
							  	</thead><tbody>';
while($row = mysql_fetch_array($result)) {

	$result2	= mysql_query("SELECT * FROM plans WHERE id = ".$row['plan']." LIMIT 1");
	$row2		= mysql_fetch_array($result2);
$finish = $row['date'] + ($row2['days']*86400);
$percinday = $row['sum'] * ($row2['percent'] / 100);
$allpercin = $percinday * $row['count'];
$statall = $row2['days'] / $row2['period'];
	
	if($it > 0) {
	$itnot1 = 'margin-top: 5px;';
	} else {
	$itnot1 = 'margin-top: 0px;';
	}
	
print "
<tr style=\"background-color:transparent; text-align: center;\">
<td style=\"padding: 0; text-align: center; width: 130px; vertical-align: middle;\">".$row2['name']."</td>
<td>".date("d.m.Y H:i", $row['date'])."</td>
<td>".date("d.m.Y H:i", $finish)."</td>
<td>$".$row['sum']."</td>
<td>".$row2['percent']."%</td>
<td>".sprintf("%01.2f", $percinday)."</td>
<td>".sprintf("%01.2f", $allpercin)."</td>
<td style=\"color: white;\">".$row['count']."/".$statall."</td><td>";
if($row2['back'] == 1 && $row2['close'] == 1) {
		print " <a href=\"javascript: if(confirm('Вы действительно хотите закрыть депозит? При закрытии депозита, с вас будет вычтена комиссия в размере ".$row2['close_percent']."%')) top.location.href='?close=".$row['id']."';\"><i class=\"pe-7s-close\" style=\"color: white; font-size: 35px;\"></i></a>";
	}
	print"
	</td>
</tr>
";
$it++;
$s = $s + $row['sum'];
}
print'<div style="background-color: rgba(0, 0, 0, 0.25); border-bottom: 1px transparent solid; text-align:center;" bgcolor="#ffffff">Всего открытых депозитов на сумму <b>$'.$s.'</b></div>';
print'</tbody>
</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>';
?>
<?php 

	if($s == 0) {
		print '<p style="text-align:center;">У вас нет открытых депозитов, но вы можете <a href="/deposit/">открыть</a> его.</p>';
	} else {
	}

} else {
	print "<p class=\"er\">Для доступа к данной странице вам необходимо авторизироваться</p>";
	include "../login/login_ru.php";
}
?>