<?php
include "../../cfg.php";
include "../../ini.php";

defined('ACCESS') or die();
if ($login) {
$id = $_GET['id'];

	$sql	= 'SELECT * FROM `output` WHERE `id` = '.$id.' LIMIT 1';
	$rs		= mysql_query($sql);
	$r		= mysql_fetch_array($rs);
	$bitusd1 = file_get_contents('https://blockchain.info/tobtc?currency=USD&value=1');

	if ($_GET['action'] == 'apibut' && $r['status'] == 0) {
		$sum	= sprintf ("%01.2f", str_replace(',', '.', $r['sum']));
		$ps		= intval($r['paysys']);
		$purse	= htmlspecialchars($r['purse'], ENT_QUOTES, '');
						if($ps == 1 && cfgSET('cfgApiButtonPM') == "on") {
							$f = fopen('https://perfectmoney.is/acct/confirm.asp?AccountID='.$cfgPMID.'&PassPhrase='.$cfgPMpass.'&Payer_Account='.$cfgPerfect.'&Payee_Account='.$purse.'&Amount='.$sum.'&PAY_IN=1&PAYMENT_ID='.$id.'&Memo='.$cfgURL.' Account: '.$r['login'].'', 'rb');

							if($f===false){
								print "<html><head><script language=\"javascript\">alert('Временно недоступен API PerfectMoney. Попробуйте пожалуйста позже'); top.location.href='../adminstation.php?a=edit';</script></head></html>";
							} else {
								// getting data
								$out=array(); $out="";
								while(!feof($f)) $out.=fgets($f);

								fclose($f);
								// searching for hidden fields
								if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $out, $result, PREG_SET_ORDER)){

									print "<html><head><script language=\"javascript\">alert('PerfectMoney не дал разрешения на выполнение данной операции'); top.location.href='../adminstation.php?a=edit';</script></head></html>";

								}
								$ar="";
foreach($result as $item){
   $key=$item[1];
   $ar[$key]=$item[2];
}
							}
						} elseif($ps == 2 && cfgSET('cfgApiButtonPE') == "on") {

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
									mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$id.' LIMIT 1');
									print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">ОШИБКА! Заявка будет выполнена в ручном режиме '.$purse.'</p>
								</div>';		
								}
							} else {
								mysql_query('UPDATE `output` SET status = 0 WHERE id = '.$id.' LIMIT 1');
								print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Ошибка авторизации в API Payeer. Заявка будет выполнена в ручном режиме.</p>
								</div>';
							}

						} elseif($ps == 3 && cfgSET('cfgApiButtonBT') == "on") {
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
mysql_query('UPDATE `output` SET status = 2 WHERE id = '.$id.' LIMIT 1');
print 'Средства были успешно выведены!'.$id.' Хэш:'.$txid.'';
} else {
print 'API Bitcoin ошибка.';
}
						} else {
						print "<html><head><script language=\"javascript\">alert('API кнопка выключена для этой платежной системы'); top.location.href='../adminstation.php?a=edit';</script></head></html>";
						}
						mysql_query('UPDATE `output` SET status = 2 WHERE id = '.$id.' LIMIT 1');
						print "<html><head><script language=\"javascript\">alert('Выплачено!'); top.location.href='../adminstation.php?a=edit';</script></head></html>";
			} else {
				print "<html><head><script language=\"javascript\">alert('Неудача!'); top.location.href='../adminstation.php?a=edit';</script></head></html>";
			}
	} else {
	print "<html><head><script language=\"javascript\">alert('Нет доступа!'); top.location.href='../adminstation.php?a=edit';</script></head></html>";
	}
	?>