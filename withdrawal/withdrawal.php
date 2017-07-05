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
								  <p class="alert__text">The application is cancelled, funds returned to your balance</p>
								</div>';
			} else {
				print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">It is impossible to cancel the application</p>
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
								  <p class="alert__text">Enter the correct amount (from $'.$cfgMinOut.' до $'.cfgSET('cfgMaxOut').')!</p>
								</div>';
		} elseif ($sum < $cfgMinOut || $sum > cfgSET('cfgMaxOut')) {
			print '<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-info"></i>
								  </div>
								  <p class="alert__text">At a time are allowed to withdraw from $'.$cfgMinOut.' до $'.cfgSET('cfgMaxOut').'!</p>
								</div>';
		} elseif ($ps == 1 && $r['pm_balance'] < $sum) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">You dont have enough money in the account Perfectmoney!</p>
								</div>';
		} elseif ($ps == 2 && $r['lr_balance'] < $sum) {
			print '	<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">You dont have that much money to Payeer account!</p>
								</div>';
		} elseif ($ps == 3 && $r['bt_balance'] < $sum) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">You dont have that much money to Bitcoin account!</p>
								</div>';
		} elseif ($ps == 3 && $bitusd < 0.0005) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">The minimum withdrawal of 0.0005 BTC</p>
								</div>';
		} elseif(cfgSET('cfgCountOut') != 0 && cfgSET('cfgCountOut') <= mysql_num_rows(mysql_query("SELECT * FROM output WHERE login = '".$login."' AND (status = 2 OR status = 0)"))) {
			print '	<div class="alert alert-fixed alert-info alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-info"></i>
								  </div>
								  <p class="alert__text">Today you have exhausted your limit of withdrawal requests. Please try tomorrow.</p>
								</div>';	
		} elseif($ps < 1 || $ps > 3) {
			print '	<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Indicate the payment system! The account number specify in'.$ps.'|'.$sum.' <a href="/profile">your profile</a>.</p>
								</div>';
		} elseif(!$purse) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Indicate the account number in your profile settings</p>
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

				$text = "<p>Hello! In <a href=\"http://".$cfgURL."\">your project</a> filed application for withdrawal of funds. Please treat her.</p>";

				$subject	= "Application for withdrawal of funds";
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
								  <p class="alert__text">At the moment the maximum amount of withdrawal of Your funds in the automatic mode is<b>$'.cfgSET('cfgAPImaxwith').'</b>,the limit is established for <b>to protect</b> Your funds.Your application will be reviewed and processed manually. </p>
								</div>';
			} elseif($minus < 0) {
			print '	<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Not correct payment amount.</p>
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
								  <p class="alert__text">PerfectMoney API is temporarily unavailable. Please try later.</p>
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
								  <p class="alert__text">PerfectMoney has not given permission to perform this operation</p>
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
								  <p class="alert__text"> Translation No.'.$arTransfer["historyId"].' successfully completed</p>
								</div>';
								} else {
									mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
									print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">ERROR! Application will be made in manual mode '.$purse.'</p>
								</div>';		
								}
							} else {
								mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$lid.' LIMIT 1');
								print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Authorization error in the API Payeer. The application will be executed in manual mode.</p>
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
								  <p class="alert__text"> The funds were successfully withdrawn!'.$lid.' Хэш:'.$txid.'</p>
								</div>';
} else {
mysql_query('UPDATE `users` SET bt_balance = bt_balance + '.$minus.' WHERE id = '.$user_id.' LIMIT 1');
mysql_query('UPDATE `output` SET status = 6 WHERE id = '.$lid.' LIMIT 1');
print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Bitcoin API error. Funds returned'.$lid.' on the balance sheet.</p>
								</div>';
}
						} else {
						}

					print '	<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Your request has been sent for processing!</p>
								</div>';
}
			} else {
				print '	<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">You cannot apply for a withdrawal!</p>
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
	<tr><td><b>Withdrawal amount</b>: </td></tr>
	<tr class="widget__form"><td align="right"><input id="sum" style="width: 100%;" type='text' name='sum' value='<?php print $r['pm_balance']; ?>' size="30" maxlength="7" /></td></tr>
	<tr><td><b>Payment system</b>: </td></tr>
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
	<tr><td><b>Account number:</b> </td></tr>
	<tr class="widget__form"><td align="right"><input  style="width: 100%;" type='text' disabled id="purse" name='purse' value='<?php print $r['pm']; ?>' size="30" maxlength="30" /></td></tr>
	<tr><td align="right"><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Apply</button></td></tr>
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
		print "<p class=\"er\">You have not filed applications for withdrawal!</p>";
	} else {

		print '<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-bottom-arrow"></i><h3>The history of withdrawals</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>ID</th>
<th>Date</th>
<th>The amount</th>
<th>Account</th>
<th>System</th>
<th>Status</th>
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
			print '<span class="tool"><a onclick="return confirm(\'Are you sure you want to cancel the request?\')" href="?cancel='.$row['id'].'"><span class="tip" style="color:white;">Consideration!</span></a></span>';
		} elseif($row['status'] == 2) {
			print '<span class="tool"><span class="tip" style="color:#86FE86;">Paid!</span></span>';
		} elseif($row['status'] == 6) {
			print '<span class="tool"><span class="tip" style="color: #FECF86;">Funds returned!</span></span>';
		} else {
			print '<span class="tool"><span class="tip" style="color: #FE8686;">Rejected!</span></span>';
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
								  <p class="alert__text"> You must login to access this page!</p>
								</div>';
}
?>