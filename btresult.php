<?php
include "cfg.php";
include "ini.php";

$real_secret = cfgSET('cfgBitcoinSecretKey');
$btcpurse = cfgSET('cfgBitcoin');
$invoice_id = $_GET['invoice_id']; //invoice_id is passed back to the callback URL
$transaction_hash = $_GET['transaction_hash'];
$input_transaction_hash = $_GET['input_transaction_hash'];
$input_address = $_GET['input_address'];
$adresss = $_GET['address'];
$value_in_satoshi = $_GET['value'];
$value_in_btc = $value_in_satoshi / 100000000;
$loginik = $_GET['username'];
$summik = $_GET['summ'];

$get_ps	= mysql_query("SELECT * FROM enter WHERE id = ".intval($invoice_id)." LIMIT 1");
					$rowps	= mysql_fetch_array($get_ps);

//Commented out to test, uncomment when live

if ($_GET['test'] == true) {
  echo 'Ignoring Test Callback';
  return;
}
if ($_GET['address'] != $btcpurse) {
    echo 'Incorrect Receiving Address';
  return;
}
if ($_GET['secret'] != $real_secret) {
  echo 'Invalid Secret';
  return;
}

//Add the invoice to the database
if($rowps['status'] <> 2){
			$sql1 = 'UPDATE users SET bt_balance = bt_balance + '.$summik.' WHERE login = "'.$loginik.'" LIMIT 1';
			mysql_query("UPDATE enter SET status = 2, purse = '".htmlspecialchars($input_address, ENT_QUOTES, '')."', paysys = 'Bitcoin' WHERE id = '".$invoice_id."' LIMIT 1");
if(mysql_query($sql1)) {
   echo "*ok*";
}
}
?>