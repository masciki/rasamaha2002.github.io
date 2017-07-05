<?php
defined('ACCESS') or die();
if ($login) {

	$sql	= 'SELECT `pe`, `pm`, `pm_balance`, `ref`, `bt`, `lr_balance`, `bt_balance` FROM `users` WHERE `id` = '.$user_id.' LIMIT 1';
	$rs		= mysql_query($sql);
	$r		= mysql_fetch_array($rs);

	if($_GET['cancel']) {
			$sql2	= 'SELECT * FROM `output` WHERE `id` = '.intval($_GET['cancel']).' AND status = 0 AND login = "'.$login.'" LIMIT 1';
			$rs2	= mysql_query($sql2);
			$r2		= mysql_fetch_array($rs2);
			
			$bitusd1 = file_get_contents('https://blockchain.info/tobtc?currency=USD&value=1');

			if($r2['sum']) {
			
		if($cfgPercentOut) {
					$sum = sprintf("%01.2f", $r2['sum'] + ($r2['sum'] / (100 - $cfgPercentOut) * $cfgPercentOut));
				} else {
					$sum = $r2['sum'];
				}
				
if($r2['paysys'] == 1) {
				mysql_query('UPDATE `users` SET pm_balance = pm_balance + '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
				mysql_query('UPDATE `output` SET status = 6 WHERE id = '.intval($_GET['cancel']).' LIMIT 1');
				} elseif($r2['paysys'] == 2) {
				mysql_query('UPDATE `users` SET lr_balance = lr_balance + '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
				mysql_query('UPDATE `output` SET status = 6 WHERE id = '.intval($_GET['cancel']).' LIMIT 1');
				} elseif($r2['paysys'] == 3) {
				mysql_query('UPDATE `users` SET bt_balance = bt_balance + '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
				mysql_query('UPDATE `output` SET status = 6 WHERE id = '.intval($_GET['cancel']).' LIMIT 1');
				} else {
				mysql_query('UPDATE `users` SET pm_balance = pm_balance + '.$sum.' WHERE id = '.$user_id.' LIMIT 1');
				mysql_query('UPDATE `output` SET status = 6 WHERE id = '.intval($_GET['cancel']).' LIMIT 1');
				}
				print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Заявка отменена, средства возвращены на баланс</p>
								</div>';
			} else {
				print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Невозможно отменить заявку</p>
								</div>';
			}
	}

	if ($_GET['action'] == 'save') {
		$sum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));
		$ps		= intval($_POST['ps']);
		$purse	= htmlspecialchars($_POST['purse'], ENT_QUOTES, '');
$bitusd = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$sum.'');
		if($ps == 1) {
			$purse = $r['pm'];
		} elseif($ps == 2) {
		$purse = $r['pe'];
		} elseif($ps == 3) {
		$purse = $r['bt'];
		} else {
		}

		if ($sum <= 0) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректную сумму (от $'.$cfgMinOut.' до $'.cfgSET('cfgMaxOut').')!</p>
								</div>';
		} elseif ($sum < $cfgMinOut || $sum > cfgSET('cfgMaxOut')) {
			print '<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-info"></i>
								  </div>
								  <p class="alert__text">За один раз разрешено выводить от $'.$cfgMinOut.' до $'.cfgSET('cfgMaxOut').'!</p>
								</div>';
		} elseif ($ps == 1 && $r['pm_balance'] < $sum) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">У Вас нет столько денег на счету Perfectmoney!</p>
								</div>';
		} elseif ($ps == 2 && $r['lr_balance'] < $sum) {
			print '	<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">У Вас нет столько денег на счету Payeer!</p>
								</div>';
		} elseif ($ps == 3 && $r['bt_balance'] < $sum) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">У Вас нет столько денег на счету Bitcoin!</p>
								</div>';
		} elseif ($ps == 3 && $bitusd < 0.0005) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Минимальный вывод 0.0005 BTC</p>
								</div>';
		} elseif(cfgSET('cfgCountOut') != 0 && cfgSET('cfgCountOut') <= mysql_num_rows(mysql_query("SELECT * FROM output WHERE login = '".$login."' AND (status = 2 OR status = 0)"))) {
			print '	<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-info"></i>
								  </div>
								  <p class="alert__text">Вы на сегодня исчерпали свой лимит заявок на вывод средств. Попробуйте пожалуйста завтра.</p>
								</div>';	
		} elseif($ps < 1 || $ps > 3) {
			print '	<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Укажите платежную систему! Номер счета укажите в'.$ps.'|'.$sum.' <a href="/profile">вашем профиле</a>.</p>
								</div>';
		} elseif(!$purse) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Укажите номер счета в настройках профиля</p>
								</div>';
		} else {

			$minus = $sum;

			if($cfgPercentOut) {
				$sum = sprintf("%01.2f", $sum - $sum / 100 * $cfgPercentOut);
			}

			if($ps == 1) {
			$sql	= 'UPDATE `users` SET pm_balance = pm_balance - '.$minus.' WHERE id = '.$user_id.' LIMIT 1';
			mysql_query($sql);
			} elseif($ps == 2) {
			$sql	= 'UPDATE `users` SET lr_balance = lr_balance - '.$minus.' WHERE id = '.$user_id.' LIMIT 1';
			mysql_query($sql);
			} elseif($ps == 3) {
			$sql	= 'UPDATE `users` SET bt_balance = bt_balance - '.$minus.' WHERE id = '.$user_id.' LIMIT 1';
			mysql_query($sql);
			}

			if(($cfgAutoPay == "on" && $ps == 1) && $minus < cfgSET('cfgAPImaxwith') || (cfgSET('cfgAutoPayPE') == "on" && $ps == 2) && $minus < cfgSET('cfgAPImaxwith') || (cfgSET('cfgAutoPayBT') == "on" && $ps == 3) && $minus < cfgSET('cfgAPImaxwith')) { 
				$st	= 2; 
			} else { 
				$st = 0; 

				$text = "<p>Здравствуйте! В <a href=\"http://".$cfgURL."\">вашем проекте</a> подана заявка на вывод средств. Обработайте её пожалуйста.</p>";

				$subject	= "Заявка на вывод средств";
				$headers 	= "From: ".$adminmail."\n";
				$headers 	.= "Reply-to: ".$adminmail."\n";
				$headers 	.= "X-Sender: < http://".$cfgURL." >\n";
				$headers 	.= "Content-Type: text/html; charset=windows-1251\n";

				mail($adminmail,$subject,$text,$headers);
			}

			if($ps == 1) { $purse = $r['pm']; }
			if($ps == 2) { $purse = $r['pe']; }
			if($ps == 3) { $purse = $r['bt'];
			}

			$sql = 'INSERT INTO `output` (`sum`, `date`, `login`, `paysys`, `purse`, `status`) VALUES("'.$sum.'", "'.time().'", "'.$login.'", '.$ps.', "'.$purse.'", '.$st.')';

			if (mysql_query($sql)) {
			
			$lid = mysql_insert_id();
			
			if($minus > cfgSET('cfgAPImaxwith')) {
			print '<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-info"></i>
								  </div>
								  <p class="alert__text">На данный момент сумма максимального вывода Ваших средств в автоматическом режиме составляет <b>$'.cfgSET('cfgAPImaxwith').'</b>,ограничение установлено в целях <b>обезопасить</b> Ваши средства.Ваша заявка будет рассмотрена и обработана в ручную. </p>
								</div>';
			} elseif($minus < 0) {
			print '	<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Не корректная сумма выплаты.</p>
								</div>';
			} else {

					// АВТОВЫПЛАТЫ
						if($ps == 1 && $cfgAutoPay == "on") {
							$f = fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.$cfgPMID.'&PassPhrase='.$cfgPMpass.'&Payer_Account='.$cfgPerfect.'&Payee_Account='.$purse.'&Amount='.$sum.'&PAY_IN=1&PAYMENT_ID='.$lid.'&Memo='.$cfgURL, 'rb');

							if($f===false){
								mysql_query('UPDATE `users` SET pm_balance = pm_balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
								mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');

								print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Временно недоступен API PerfectMoney. Попробуйте пожалуйста позже.</p>
								</div>';
							} else {
								// getting data
								$out=array(); $out="";
								while(!feof($f)) $out.=fgets($f);

								fclose($f);
								// searching for hidden fields
								if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){

									mysql_query('UPDATE `users` SET pm_balance = pm_balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
									mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');

									print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">PerfectMoney не дал разрешения на выполнение данной операции</p>
								</div>';

								}
								$ar="";
foreach($result as $item){
   $key=$item[1];
   $ar[$key]=$item[2];
}
							}
						} elseif($ps == 2 && cfgSET('cfgAutoPayPE') == "on") {

							require_once('../includes/cpayeer.php');
							$accountNumber	= cfgSET('cfgPEAcc');
							$apiId			= cfgSET('cfgPEidAPI');
							$apiKey			= cfgSET('cfgPEapiKey');
							$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
							if ($payeer->isAuth()) {
								$arTransfer = $payeer->transfer(array(
								'curIn' => 'USD',	// счет списания 
								'sum' => $sum,		// Сумма получения 
								'curOut' => 'USD',	// валюта получения  
								'to' => $purse,		// Получатель
								'comment' => 'API '.$cfgURL,
							));

								if(!empty($arTransfer["historyId"])) {
									print '	<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Перевод №'.$arTransfer["historyId"].' успешно завершен</p>
								</div>';
								} else {
									mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
									print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">ОШИБКА! Заявка будет выполнена в ручном режиме '.$purse.'</p>
								</div>';		
								}
							} else {
								mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
								print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Ошибка авторизации в API Payeer. Заявка будет выполнена в ручном режиме.</p>
								</div>';
							}

						} elseif($ps == 3 && cfgSET('cfgAutoPayBT') == "on") {
			$sum = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$sum.'');
						$guid=cfgSET('cfgBitcoinAPIguid');
$firstpassword=cfgSET('cfgBitcoinAPIpass');
$amounta = 100000000 * $sum;
$addressa = $purse;
$recipients = urlencode('{
                  "'.$addressa.'": '.$amounta.'
               }');

$json_url = "https://blockchain.info/ru/merchant/$guid/sendmany?password=$firstpassword&recipients=$recipients";

$json_data = file_get_contents($json_url);

$json_feed = json_decode($json_data);

$message = $json_feed->message;
$txid = $json_feed->tx_hash;

if($message == 'Sent To Multiple Recipients') {
mysql_query('UPDATE `output` SET status = 2 WHERE id = '.$lid.' LIMIT 1');
print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Средства были успешно выведены!'.$lid.' Хэш:'.$txid.'</p>
								</div>';
} else {
mysql_query('UPDATE `users` SET bt_balance = bt_balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');
print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">API Bitcoin ошибка. Средства возвращены '.$lid.' на баланс.</p>
								</div>';
}
						} else {
						}

					print '	<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Ваша заявка отправлена в обработку!</p>
								</div>';
}
			} else {
				print '	<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Не удаётся отправить заявку на снятие денег!</p>
								</div>';
			}
		}
	}
	?>
	<form action="?action=save" method="post">
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-select.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-slide.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-boxes.css" />
		<script language="JavaScript">
<!--
	function CheBal(val) {

		document.getElementById("sum").value = "<?php print $r['pm_balance']; ?>"

		if(val == 1) {
			document.getElementById("purse").value = '<?php print $r['pm']; ?>';
			document.getElementById("purse").disabled = true;
			document.getElementById("sum").value = '<?php print $r['pm_balance']; ?>';
		} else if(val == 2) {
			document.getElementById("purse").value = '<?php print $r['pe']; ?>';
			document.getElementById("purse").disabled = true;
			document.getElementById("sum").value = '<?php print $r['lr_balance']; ?>';
		} else if(val == 3) {
			document.getElementById("purse").value = '<?php print $r['bt']; ?>';
			document.getElementById("purse").disabled = true;
			document.getElementById("sum").value = '<?php print $r['bt_balance']; ?>';
		} else {
			document.getElementById("purse").value = '';
			document.getElementById("purse").disabled = false;
		}
	}
//-->
</script>
	<table align="center" style="margin:0 auto;">
	<tr><td><b>Сумма вывода</b>: </td></tr>
	<tr class="widget__form"><td align="right"><input id="sum" style="width: 100%;" type='text' name='sum' value='<?php print $r['pm_balance']; ?>' size="30" maxlength="7" /></td></tr>
	<tr><td><b>Платежная система</b>: </td></tr>
	<tr class="widget__form"><td align="right"><select class="cs-select cs-skin-boxes" id="ps" name="ps">
<?php
if($r['pm']) {
	print '<option data-class="color-da645a cs-selected" value="1" selected>PerfectMoney</option>';
}
if($r['pe']) {
	print '<option data-class="color-bdd1c8" value="2">Payeer</option>';
}
if($r['bt']) {
$result	= mysql_query("SELECT * FROM `paysystems` WHERE id > 2 ORDER BY id ASC");
while($row = mysql_fetch_array($result)) {
	print '<option data-class="color-f3ae73" value="'.$row['id'].'">'.$row['name'].'</option> ';
}
}
?>
	</select></td></tr>
	<tr><td><b>Номер счета:</b> </td></tr>
	<tr class="widget__form"><td align="right"><input  style="width: 100%;" type='text' disabled id="purse" name='purse' value='<?php print $r['pm']; ?>' size="30" maxlength="30" /></td></tr>
	<tr><td align="right"><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Подать заявку</button></td></tr>
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
		</script>
	</table>
		</form>
<?php


	$page	= intval($_GET['page']);
	$query	= "SELECT * FROM `output` WHERE login = '".$login."'";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;

	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." ORDER BY id DESC LIMIT ".$start.", ".$num);

	if(!$themes) {
		print "<p class=\"er\">Вы не подавали заявок на вывод!</p>";
	} else {

		print '<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-bottom-arrow"></i><h3>История вывода средств</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>ID</th>
<th>Дата</th>
<th>Сумма</th>
<th>Счет</th>
<th>Система</th>
<th>Статус</th>
</tr>
							  	</thead><tbody>';

		$i = 1;
		$s = 0;
		$bitki = 0;
		$for1usdbit = file_get_contents('https://blockchain.info/tobtc?currency=USD&value=1');
		while ($row = mysql_fetch_array($result)) {

		if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

		$get_ps	= mysql_query("SELECT name FROM paysystems WHERE id = ".intval($row['paysys'])." LIMIT 1");
		$rowps	= mysql_fetch_array($get_ps);

		if($row['paysys'] == 3) {
		$usbt = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$row['sum'].'');
		$wordd = '<span style="font-size:11px">$</span>'.sprintf ("%01.2f", str_replace(',', '.', $row['sum'])).'<br>'.sprintf ("%01.4f", str_replace(',', '.', $usbt)).' <span style="font-size:11px">BTC</span>';
		} else {
		$wordd = '<span style="font-size:11px">$</span>'.sprintf ("%01.2f", str_replace(',', '.', $row['sum']));
		}
		
		print "<tr".$bg." style=\"background-color: transparent;\" align=\"center\">
		<td style=\"padding: 3px;\">".$row['id']."</td>
		<td>".date("d.m.Y H:i", $row['date'])."</td>
		<td>".$wordd."</td>
		<td><b>".$row['purse']."</b></td>
		<td>".$rowps['name']."</td>
		<td>";

		if($row['status'] == 0) {
			print '<span class="tool"><a onclick="return confirm(\'Вы уверены что хотите отменить заявку?\')" href="?cancel='.$row['id'].'"><span class="tip" style="color:white;">На рассмотрении!</span></a></span>';
		} elseif($row['status'] == 2) {
			print '<span class="tool"><span class="tip" style="color:#86FE86;">Выплачено!</span></span>';
		} elseif($row['status'] == 6) {
			print '<span class="tool"><span class="tip" style="color: #FECF86;">Средства возвращены!</span></span>';
		} else {
			print '<span class="tool"><span class="tip" style="color: #FE8686;">Отклонено!</span></span>';
		}

		print "</td>

		</tr>";

			$i++;
			if($row['paysys'] <> 3) {
			$s = $s + $row['sum'];
			} else {
			$bitki = $bitki + $row['sum'];
			$bitki = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$bitki.'');
			$bitki = sprintf ("%01.4f", str_replace(',', '.', $bitki));
			}
		}
		print "<tr style=\"background-color:transparent;\"><td colspan=\"2\" align=\"center\"><b>Итого:</b></td><td align=\"center\"><b>$".$s."</b><br><b>".$bitki." <span style=\"font-size:11px\">BTC</span></b></td><td style=\"background: none; display: none;\"></td><td style=\"background: none; display: none;\"></td><td style=\"background: none; display: none;\"></td></tr></tbody>
</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>";

	}

	if ($page) {
		if($page != 1) { $pervpage = "<a style=\"color: white;\" href=\"?page=". ($page - 1) ."\">««</a>"; }
		if($page != $total) { $nextpage = " <a style=\"color: white;\" href=\"?page=". ($page + 1) ."\">»»</a>"; }
		if($page - 2 > 0) { $page2left = " <a style=\"color: white;\" href=\"?page=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
		if($page - 1 > 0) { $page1left = " <a style=\"color: white;\" href=\"?page=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
		if($page + 2 <= $total) { $page2right = " <a style=\"color: white;\" href=\"?page=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
		if($page + 1 <= $total) { $page1right = " <a style=\"color: white;\" href=\"?page=". ($page + 1) ."\">". ($page + 1) ."</a> "; }
	}
	print "<div class=\"pages\"><b>Страницы:  </b>".$pervpage.$page2left.$page1left." <b>".$page."</b> ".$page1right.$page2right.$nextpage."</div>";
} else {
	print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Вы должны авторизироваться для доступа к этой странице!</p>
								</div>';
}
?>