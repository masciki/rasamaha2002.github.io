<?
session_start();
header("Content-type: text/html; charset=utf-8");
include('cfg.php');
include('ini.php');
if(isset($_POST['codeqiwi'])) {
	$codeqiwi = sf($_POST['codeqiwi']);
	if(!empty($codeqiwi)) {
		
		$sql = mysql_query("SELECT * FROM inserts WHERE `komment` = '$codeqiwi'");
		if(mysql_num_rows($sql) == 0) {
		
include($_SERVER['DOCUMENT_ROOT']."/data/qiwi.class.php");
$qiwiacc = $conf['qiwi']; // Киви кошелек в формате 796545645645
$qiwipass = $conf['qiwipass'];  // Пароль от киви
$qiwi = new Qiwi($qiwiacc, $qiwipass, $_SERVER['DOCUMENT_ROOT'].'/data/cookie.txt');
$date1 = date( 'd.m.Y', strtotime( '-1 day' ) );
$date2 = date( 'd.m.Y', strtotime( '+1 day' ) );
$operations = $qiwi->GetHistory($date1,$date2);
		
foreach($operations as $value) {
if($value['iID'] == $_POST['codeqiwi'] && $value["sType"] == "INCOME" && $value["sStatus"] == "SUCCESS") {
$ok = true;
$summ = $value['dAmount'];
} }
		
if($ok == true) {
	if($summ >= 10 and $summ <= 9999){
		mysql_query("UPDATE `users` SET `balance` = `balance` + '$summ' WHERE id = '$userid'");
		$sql = mysql_query("INSERT INTO inserts (login, userid, summa, komment, date, status) VALUES ('$login', '$userid', '$summ', '$codeqiwi', '".time()."', '1') ");
		#Зачисляем рефские
		$userss = mysql_query("SELECT * FROM users WHERE id = '".$userid."'") or die(mysql_error());
		$datauser = mysql_fetch_assoc($userss);
		$ref = $datauser['ref_id'];
		$sql = mysql_query("SELECT * FROM users WHERE id = '$ref'");
		$qq = mysql_fetch_array($sql);
		$ref_sum = $summ / 100 * $ref_perc;
		mysql_query("UPDATE users SET balance = balance + '$ref_sum' WHERE id = '$ref' LIMIT 1");
		mysql_query("UPDATE users SET sum_ref = sum_ref + '$ref_sum' WHERE id = '$ref' LIMIT 1");
		echo '<font color="green">Сумма в размере '.$summ.' руб. Зачислена на ваш баланс!</font>';
		exit();
	}else echo '<font color="red">Минимум для оплаты '.$minqiwi.' руб.</font><br>';
}else echo '<font color="red">Неизвестная ошибка!</font><br>';


	}else echo '<font color="red">Данная транкзация уже существует!</font>';
	}else echo '<font color="red">Введите номер транзакции.</font>';
	exit();
	}
	exit();
?>