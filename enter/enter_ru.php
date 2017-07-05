<?php
defined('ACCESS') or die();
if ($login) {

	if($_GET['pay'] == "no") {
		print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Не удалось пополнить баланс</p>
								</div>'; 
								
	}

	if($_GET['conf']) {

		print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> Ваша заявка отправлена администраторам на проверку</p>
								</div>';

		$conf		= intval($_GET['conf']);
		$purse		= addslashes(htmlspecialchars($_POST["purse"], ENT_QUOTES, ''));

		mysql_query("UPDATE enter SET status = 1, purse = '".$purse."' WHERE id = ".$conf." LIMIT 1");

	} elseif ($_GET['action'] == 'save') {
		$sum	= sprintf ("%01.2f", str_replace(',', '.', $_POST['sum']));
		$ps		= intval($_POST['ps']);
			
		if ($sum <= 0) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректную сумму (от $0.10 до $10 000)!</p>
								</div>';
		} elseif ($sum < 0.10 || $sum > 10000) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">За один раз разрешено пополнять от $0.10 до $10 000!</p>
								</div>';
		} elseif($ps < 1 || $ps > 3) {
			print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Укажите платежную систему!</p>
								</div>';
		} else {

				// Форма пополнения
					if($ps == 1) {

					// PM

					$sql = 'INSERT INTO enter (sum, date, login, paysys, service) VALUES ('.$sum.', '.time().', "'.$login.'", "PerfectMoney", "bal")';
					mysql_query($sql);
					$lid = mysql_insert_id();

					if(cfgSET('cfgSSL') == "on") {
						$http = "https";
					} else {
						$http = "http";
					}

					print '		
					<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-info"></i><h3>Подтверждение платежа</h3>
								</div>
							</header>
							<div class="widget__content table-responsive" style="background-color: rgba(0,0,0,0.25); text-align: center;">
							<form action="https://perfectmoney.is/api/step1.asp" method="POST">
					<input type="hidden" name="PAYEE_ACCOUNT" value="'.$cfgPerfect.'">
					<input type="hidden" name="PAYEE_NAME" value="'.$cfgPAYEE_NAME.'">
					<input type="hidden" name="PAYMENT_ID" value="'.$lid.'">
					<input type="hidden" name="PAYMENT_AMOUNT" value="'.$sum.'">
					<input type="hidden" name="PAYMENT_UNITS" value="USD">
					<input type="hidden" name="STATUS_URL" value="'.$http.'://'.$cfgURL.'/pmresult.php">
					<input type="hidden" name="PAYMENT_URL" value="'.$http.'://'.$cfgURL.'/">
					<input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="NOPAYMENT_URL" value="'.$http.'://'.$cfgURL.'/enter/?pay=no">
					<input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
					<input type="hidden" name="BAGGAGE_FIELDS" value="">
					<input type="hidden" name="SUGGESTED_MEMO" value="'.$cfgURL.'">
					<center>
					Вы переводите <strong>'.$sum.'</strong> USD на счёт <strong>'.$cfgPerfect.'</strong> PerfectMoney<br />Пополнение баланса в проекте '.$cfgURL.'<br />
					<p align="center"><button type="submit" name="PAYMENT_METHOD" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Подтверждаю!</button></p>
					</center>
					</form>
							</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>';

					} elseif($ps == 2) {


					$get_ps	= mysql_query("SELECT * FROM paysystems WHERE id = ".intval($ps)." LIMIT 1");
					$rowps	= mysql_fetch_array($get_ps);

					$sum2 = sprintf("%01.2f", $sum * $rowps['percent']);

					$sql = 'INSERT INTO enter (sum, date, login, paysys, service) VALUES ('.$sum.', '.time().', "'.$login.'", "'.$rowps['name'].'", "bal")';

						if(mysql_query($sql)) {

						$m_orderid = mysql_insert_id();

							if($rowps['name'] == "PAYEER.com") {

								$desc = base64_encode($cfgURL);

								$cu = 'USD';

								$cid	= cfgSET('cfgPEsid');
								$m_key	= cfgSET('cfgPEkey');

								$arHash = array(
									$cid,
									$m_orderid,
									$sum,
									$cu,
									$desc,
									$m_key
								);

								$sign = strtoupper(hash('sha256', implode(":", $arHash)));

								print '
								<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-info"></i><h3>Подтверждение платежа</h3>
								</div>
							</header>
							<div class="widget__content table-responsive" style="background-color: rgba(0,0,0,0.25); text-align: center;">
							<form method="GET" action="//payeer.com/api/merchant/m.php" accept-charset="utf-8">
								<input type="hidden" name="m_shop" value="'.$cid.'">
								<input type="hidden" name="m_orderid" value="'.$m_orderid.'">
								<input type="hidden" name="m_amount" value="'.$sum.'">
								<input type="hidden" name="m_curr" value="USD">
								<input type="hidden" name="m_desc" value="'.$desc.'">
								<input type="hidden" name="m_sign" value="'.$sign.'">

								<center>
								Вы переводите <strong>'.$sum.'</strong> USD с платежной системы Payeer<br />Пополнение баланса в проекте '.$cfgURL.'<br /><br />
								<p align="center"><button type="submit" style="width:100%; name="m_process" margin: 2px 0 0 0;" class="btn green fixed">Подтверждаю!</button></p>
								</center>
								</form>
							</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>';

							} else {

								print '
								<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-info"></i><h3>Подтверждение платежа</h3>
								</div>
							</header>
							<div class="widget__content table-responsive" style="background-color: rgba(0,0,0,0.25); text-align: center;">
							<form method="POST" action="?conf='.$m_orderid.'">
								<center>Вам необходимо перевести <b>'.$sum2.'</b> '.$rowps['abr'].' на счет <b>'.$rowps['purse'].'</b> в примечании к платежу, укажите ваш логин: <b>'.$login.'</b>.  После оплаты, укажите ваш номер счета, с которого вы совершили платеж в форме ниже и нажмите кнопку подтверждения платежа.

								<input type="text" name="purse" size="20" />
								<br /><br />
								<p align="center"><input class="subm" type="submit" value="Я совершил платеж" /></p>
								</center>
								</form>
							</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>';
							}

						} else {
							print '<p class="er">Не удаётся отправить заявку!</p>';
						}

				
				
					} else {
					$get_ps	= mysql_query("SELECT * FROM paysystems WHERE id = ".intval($ps)." LIMIT 1");
					$rowps	= mysql_fetch_array($get_ps);

					if($rowps['percent'] == 0) {
					$sum2 = $sum;
					} else {
					$sum2 = sprintf("%01.2f", $sum * $rowps['percent']);
					}
					mysql_query('INSERT INTO enter (sum, date, login, paysys, service) VALUES ('.$sum2.', '.time().', "'.$login.'", "'.$rowps['name'].'", "bal")');
					$idishka = mysql_insert_id();
					$secret = cfgSET('cfgBitcoinSecretKey');

$my_address = cfgSET('cfgBitcoin');

$my_callback_url = 'http://uhalitus.com/btresult.php?invoice_id='.$idishka.'&username='.$login.'&summ='.$sum2.'&secret='.$secret;

$root_url = 'https://blockchain.info/api/receive';

$parameters = 'method=create&address=' . $my_address .'&callback='. urlencode($my_callback_url);

$response = file_get_contents($root_url . '?' . $parameters);

$courseusdbit = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$sum.'');

$courseusdbit = $courseusdbit + 0.0001;

$object = json_decode($response);

echo '
<script>
$(document).ready(function () {
  $(".selectabled").hover(function () {
    var w = window.getSelection();
   w.removeAllRanges();  // старые промежутки удаляются автоматически при каждом клике
    var range = document.createRange();
    range.selectNode(this);
    w.addRange(range);
  });
});
// selectabled - класс тех div-элементов, которые будут выделяться целиком при клике на них
</script>

<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-info"></i><h3>Подтверждение платежа</h3>
								</div>
							</header>
							<div class="widget__content table-responsive" style="background-color: rgba(0,0,0,0.25); text-align: center;">
							<img class="chartbitc" src="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl='.$object->input_address.'" /><p class="ermes">Деньги поступают на баланс в автоматическом режиме.
Как только ваш перевод пройдет подтверждение от администрации любого ресурса BTC <b>6 раз(бывает и больше)</b>,деньги будут зачислены на баланс.Обычно это занимает от 1 минуты до 1 часа.</p>
<br><center>Переведите денежные средства в размере <b><span style="padding: 0 5px; font-size: 20px; background: rgba(59, 126, 137, 0.3); border-radius: 30px;" class="selectabled">'.$courseusdbit.'</span>BTC</b> на счет: <br><br><span style="padding: 2px 5px; font-size: 20px; background: rgba(59, 126, 137, 0.3); border-radius: 30px; position: relative;" class="selectabled">' . $object->input_address . '</span> </center>
							</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>
							
';
					}
		}
	} else {
	}
	?> 
		<form action="?action=save" method="post">
			<link href='http://fonts.googleapis.com/css?family=Raleway:400,300,500,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-select.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-slide.css" />
		<link rel="stylesheet" type="text/css" href="/css/cs-skin-boxes.css" />
	
	<table align="center" style="margin:0 auto;">
	<tr><td>
	
	
	<b>Сумма пополнения</b>:</td></tr><tr class="widget__form"><td align="center">
	<input style="width: 100%;" type='text' name='sum' value='' size="30" maxlength="7" /></td></tr>
	<tr class="widget__form"><td><b>Платежная система</b>: </td></tr>
	<tr class="widget__form"><td align="right">
	<select class="cs-select cs-skin-boxes" name="ps">
		<?php
			if($cfgPerfect) { 
				print '<option data-class="color-da645a cs-selected" value="1" selected>PerfectMoney</option>';
			} 
			if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {
				print '<option data-class="color-bdd1c8" value="2">Payeer</option>';
			} 

			$result	= mysql_query("SELECT * FROM `paysystems` WHERE id > 2 ORDER BY id ASC");
			while($row = mysql_fetch_array($result)) {
				print '<option data-class="color-f3ae73" value="'.$row['id'].'">'.$row['name'].'</option> ';
			}
		?>
	</select></td></tr>
	<tr><td align="right"><button type="submit" style="width:100%; margin: 2px 0 0 0;" class="btn green fixed">Пополнить баланс</button></td></tr>
	</table><hr />
		<script src="/js/classie.js"></script>
		<script src="/js/selectFx.js"></script>
		<script>
			(function() {
				[].slice.call( document.querySelectorAll( 'select.cs-select' ) ).forEach( function(el) {	
					new SelectFx(el);
				});
			})();
		</script>
			</form>
<?php


	$page	= intval($_GET['page']);
	$query	= "SELECT * FROM `enter` WHERE login = '".$login."'";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;

	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." ORDER BY id DESC LIMIT ".$start.", ".$num);

	if(!$themes) {
		print "<p class=\"er\">Вы не подавали заявок на пополнение баланса!</p>";
	} else {

		print '<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-up-arrow"></i><h3>История пополнений</h3>
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
		while ($row = mysql_fetch_array($result)) {

		if($i % 2) { $bg = ""; } else { $bg = " bgcolor=\"#eeeeee\""; }

		print "<tr".$bg." style=\"background-color: transparent; text-align:center;\" align=\"center\">
		<td style=\"padding: 3px;\">".$row['id']."</td>
		<td>".date("d.m.Y H:i", $row['date'])."</td>
		<td>$".$row['sum']."</td>
		<td><b>".$row['purse']."</b></td>
		<td>".$row['paysys']."</td>
		<td>";

		if($row['status'] == 0) {
			print '<span class="tool"><span class="tip" style="color: #FECF86;">Ожидание</span></span>';
		} elseif($row['status'] == 1) {
			print '<span class="tool"><span class="tip" style="color:white;">На рассмотрении</span></span>';
		} elseif($row['status'] == 2) {
			print '<span class="tool"><span class="tip" style="color:#86FE86;">Одобрено</span></span>';
		} else {
			print '<span class="tool"><span class="tip" style="color: #FE8686;">Отклонено</span></span>';
		}

		print "</td>

		</tr>";

			$i++;
			$s = $s + $row['sum'];
		}

		print "</table>
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
	print "<div class=\"pages\"><b></b>".$pervpage.$page2left.$page1left." <b>".$page."</b> ".$page1right.$page2right.$nextpage."</div>";


} else {
	print "<p class=\"er\">Вы должны авторизироваться для доступа к этой странице!</p>";
}

?>