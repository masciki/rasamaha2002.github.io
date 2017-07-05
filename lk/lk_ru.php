			<?php
defined('ACCESS') or die();



if($login) {
			$sql8	= 'SELECT * FROM users WHERE login = "'.$login.'" LIMIT 1';
$rs8		= mysql_query($sql8);
$a8		= mysql_fetch_array($rs8);
$refersi = 0;

$get_user_inforef = mysql_query("SELECT id, login, reg_time FROM users WHERE ref = ".$user_id." ORDER BY reg_time DESC");
	while ($rowref = mysql_fetch_array($get_user_inforef)) {
	$refersi = $refersi + 1;
	}

$get_user_inforefp = mysql_query("SELECT id, login, reg_time FROM users WHERE ref = ".$user_id." ORDER BY reg_time DESC LIMIT 1");
$rowref = mysql_fetch_array($get_user_inforefp);

$plan1 = 0;
$plan2 = 0;
$plan3 = 0;
$depsallplan = 0;

$alldep = 0.00;
$allout = 0.00;
$alloutb = 0.0000;
$resultd	= mysql_query("SELECT * FROM deposits WHERE user_id = ".$user_id." ORDER BY id ASC");
while($rowd = mysql_fetch_array($resultd)) {
	$alldep = $alldep + $rowd['sum'];
	$depsallplan = $depsallplan + 1;
	if($rowd['plan'] == 4) {
	$plan1 = $plan1 + 1;
	} elseif($rowd['plan'] == 5) {
	$plan2 = $plan2 + 1;
	}if($rowd['plan'] == 6) {
	$plan3 = $plan3 + 1;
	} else {
	}
	}
	$resultde	= mysql_query("SELECT * FROM `enter` WHERE `login` = '".$login."' AND status = '2' ORDER BY `id` DESC");
	$rowdeposp = mysql_fetch_array($resultde);
	
	$resulto	= mysql_query("SELECT * FROM `output` WHERE `login` = '".$login."' AND status = '2' AND paysys <> '3' ORDER BY id ASC");
while($rowo = mysql_fetch_array($resulto)) {
	$allout = $allout + $rowo['sum'];
	}
	$resultob	= mysql_query("SELECT * FROM `output` WHERE `login` = '".$login."' AND `status` = '2' AND `paysys` = '3' ORDER BY id ASC");
while($rowob = mysql_fetch_array($resultob)) {
	$alloutb = $alloutb + $rowob['sum'];
	}
	$resultol	= mysql_query("SELECT `date`,`sum`,`paysys` FROM `output` WHERE `login` = '".$login."' AND status = '2' ORDER BY id DESC LIMIT 1");
	if(mysql_num_rows($resultol)) {
$rowol = mysql_fetch_array($resultol);
if($rowol['paysys'] == '3') { 
$outl = date("d-m-Y H:i:s", $rowol['date']).',на сумму: '.sprintf ("%01.4f", str_replace(',', '.', $rowol['sum'])).' BTC';
} else {
$outl = date("d-m-Y H:i:s", $rowol['date']).',на сумму: '.sprintf ("%01.2f", str_replace(',', '.', $rowol['sum'])).' USD';
}
} else {
$outl = 'Нет Выплат!';
}
$resulten	= mysql_query("SELECT `sum`,`paysys` FROM `enter` WHERE `login` = '".$login."' AND status = '2' ORDER BY id DESC LIMIT 1");
	if(mysql_num_rows($resulten)) {
$rowen = mysql_fetch_array($resulten);
if($rowen['paysys'] == 'Bitcoin') {
$courseusdbitok = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$rowen['sum'].'');
$bitok = ' / '.$courseusdbitok.' BTC';
} else {
$bitok = '';
}
$ent = sprintf ("%01.2f", str_replace(',', '.', $rowen['sum'])).' USD'.$bitok.' - '.$rowen['paysys'];
} else {
$ent = 'Нет Пополнений!';
}

$courseusdbit = file_get_contents('https://blockchain.info/tobtc?currency=USD&value='.$a8['bt_balance'].'');

//Список пополнений PerfectMoney
  $resultopm	= mysql_query("SELECT * FROM `enter` WHERE `login` = '".$login."' AND status = '2' AND (paysys = 'PerfectMoney' OR paysys = 'PM') ORDER BY id DESC LIMIT 6");
$histpaydo = '<table class="tablehist"><tr><td style="width: 33.33333%;">Дата</td><td style="width: 33.33333%;">Сумма</td><td style="width: 33.33333%;">Счет</td></tr>';
$histpayposl = '</table>';
  $histpay = ''; 
 while($rowopm = mysql_fetch_array($resultopm)) {
	$histpay = $histpay.'<tr style="line-height: 35px; border-top: #BBB7BE solid 1px;"><td>'.date('d.m.y H:i:s', $rowopm['date']).'</td><td>$'.$rowopm['sum'].'</td><td>'.$rowopm['purse'].'</td></tr>';
	} 
	if($histpay) {
	$historypm = $histpaydo.$histpay.$histpayposl;
	} else {
	$historypm = 'Пополненний не обнаружено.';
	}
	
	//Список пополнений Payeer
	$resultope	= mysql_query("SELECT * FROM `enter` WHERE `login` = '".$login."' AND status = '2' AND (paysys = 'Payeer' OR paysys = 'PAYEER.com' OR paysys = 'PE') ORDER BY id DESC LIMIT 6");
$histpaydo1 = '<table class="tablehist"><tr><td style="width: 33.33333%;">Дата</td><td style="width: 33.33333%;">Сумма</td><td style="width: 33.33333%;">Счет</td></tr>';
$histpayposl1 = '</table>';
  $histpay1 = ''; 
 while($rowope = mysql_fetch_array($resultope)) {
	$histpay1 = $histpay1.'<tr style="line-height: 35px; border-top: #BBB7BE solid 1px;"><td>'.date('d.m.y H:i:s', $rowope['date']).'</td><td>$'.$rowope['sum'].'</td><td>'.$rowope['purse'].'</td></tr>';
	} 
	if($histpay1) {
	$historype = $histpaydo1.$histpay1.$histpayposl1;
	} else {
	$historype = 'Пополненний не обнаружено.';
	}
	
	//Список пополнений Bitcoin
	$resultobt	= mysql_query("SELECT * FROM `enter` WHERE `login` = '".$login."' AND status = '2' AND (paysys = 'Bitcoin' OR paysys = 'BT') ORDER BY id DESC LIMIT 6");
$histpaydo2 = '<table class="tablehist"><tr><td style="width: 33.33333%;">Дата</td><td style="width: 33.33333%;">Сумма</td><td style="width: 33.33333%;">Счет</td></tr>';
$histpayposl2 = '</table>';
  $histpay2 = ''; 
 while($rowobt = mysql_fetch_array($resultobt)) {
	$histpay2 = $histpay2.'<tr style="line-height: 35px; border-top: #BBB7BE solid 1px;"><td>'.date('d.m.y H:i:s', $rowobt['date']).'</td><td>$'.$rowobt['sum'].'</td><td>'.$rowobt['purse'].'</td></tr>';
	} 
	if($histpay2) {
	$historybt = $histpaydo2.$histpay2.$histpayposl2;
	} else {
	$historybt = 'Пополненний не обнаружено.';
	}
	
	$histfirst = max($a8['pm_balance'], $a8['lr_balance'], $a8['bt_balance']);
	if($histfirst == $a8['pm_balance']) {
	$histfitex = 'PerfectMoney';
	$histfi = $historypm;
	} elseif($histfirst == $a8['lr_balance']) {
	$histfitex = 'Payeer';
	$histfi = $historype;
	} elseif($histfirst == $a8['bt_balance']) {
	$histfitex = 'Bitcoin';
	$histfi = $historybt;
	} else {
	$histfitex = 'PerfectMoney';
	$histfi = $historypm;
	}
	
	$allmoneyss = $a8['lr_balance'] + $a8['pm_balance'] + $a8['bt_balance'];
	if($allmoneyss <= 0) {
	$perfi = 0.01;
	$biti = 0.01;
	$payei = 0.01;
	} else {
	$perfi = $a8['pm_balance'];
	$biti = $a8['bt_balance'];
	$payei = $a8['lr_balance'];
	}
?>
    <link rel="stylesheet" href="/css/prettify.min.css">
  <link rel="stylesheet" href="/css/morris.css">
			
			<header class="main-header">
				<div class="main-header__nav">
					<h1 class="main-header__title">
						<i class="pe-7f-graph1"></i>
						<span>Статистика <?php print $login;?></span>
					</h1>
				</div>
			<div class="media message checked">
										
										<div align="center" class="otstp">
											<img class="media-object" width="100%" style="max-height:250px; max-width:250px;" src="<?php print $imga;?>" alt="user">
											</div>
										<div class="media-body" style="text-align: center;">
											<h4 class="media-heading message__heading">Состояние на <?php print date("d.m.y");?></h4>

											<input type="checkbox" style="display:none;" class="btn-more-check" id="more2" checked="">

											<div class="message__details">
												<table>
													<tbody><tr>
														<td>Email:</td>
														<td><?php print $a8['mail'];?></td>
													</tr>
													<tr>
														<td>Дата регистрации:</td>
														<td><?php print date("d-m-Y H:i:s", $a8['reg_time']);?></td>
													</tr>
													<tr>
														<td>Последний вход:</td>
														<td><?php print date("d-m-Y H:i:s", $a8['go_time']);?>,IP: <?php print $a8['ip'];?></td>
													</tr>
													<tr>
														<td>Общий баланс:</td>
														<td><span class="badge badge--line badge--violet"><?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['lr_balance'] + $a8['pm_balance'] + $a8['bt_balance'])));?></b> USD</span></td>
													</tr>
													<tr style="border-top: 1px dashed rgba(255,255,255,.4);">
														<td>Баланс PerfectMoney:</td>
														<td><span id="perfectcol" class="badge badge--line badge--red"><?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['pm_balance'])));?></b> USD</span></td>
													</tr>
													<tr style="border-top: 1px dashed rgba(255,255,255,.4);">
														<td>Баланс Payeer:</td>
														<td><span id="payeercol" class="badge badge--line badge--blue"><?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['lr_balance'])));?></b> USD</span></td>
													</tr>
													<tr style="border-top: 1px dashed rgba(255,255,255,.4);">
														<td>Баланс Bitcoin:</td>
														<td><span id="bitcoincol" class="badge badge--line badge--orange"><small><span><b><?php print sprintf ("%01.2f", str_replace(',', '.', $a8['bt_balance']));?></b> USD</span> / <b><?php print sprintf ("%01.4f", str_replace(',', '.', $courseusdbit));?></b> BTC</small></span></td>
													</tr>
													<tr>
														<td>Последнее пополнение:</td>
														<td><span><b><?php print $ent;?></b></span></td>
													</tr>
													<tr>
														<td>Последняя выплата:</td>
														<td><span><b><small><?php print $outl;?></small>
											</b></span></td>
													</tr>
													<tr>
														<td>Всего выплачено:</td>
														<td><span><b><?php print sprintf ("%01.2f", str_replace(',', '.', $allout));?> USD / <?php print sprintf ("%01.4f", str_replace(',', '.', $alloutb));?> BTC</b></span></td>
													</tr>
													<tr>
														<td>Активных депозитов:</td>
														<td><span><b><?php print sprintf ("%01.2f", str_replace(',', '.', $alldep));?> USD</b></span></td>
													</tr>
													
												</tbody></table>
											</div>
											<div style="float:left; width:50%;">
											<div class="pie-container" style="padding-top: 0px;width: 100%;">
									<div id="donut" class="donutgraph"></div>

											</div>
											<div id="histpays" class="history">
											<center>Последние 6 пополнений счета <?php print $histfitex;?></center><br><?php print $histfi;?>
							</div>
											
											<div class="dysplayno">
								<div class="row">
									<div class="col-md-4 col-sm-4 col-xs-4 mini-stats">
										<div class="pie-small" style="border-color: #4b8dfb;"></div>
										<p>
											<span>$<?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['lr_balance'])));?></span><br>
											Payeer
										</p>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4 mini-stats">
										<div class="pie-small" style="border-color: #f7931a;"></div>
										<p>
											<span>$<?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['bt_balance'])));?></span><br>
											Bitcoin
										</p>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4 mini-stats">
										<div class="pie-small" style="border-color: #f00f0f;"></div>
										<p>
											<span>$<?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['pm_balance'])));?></span><br>
											PerfectMoney
										</p>
									</div>
								</div>
							</div>

										</div>
									</div>
			</header> <!-- /main-header -->

			<div data-tab-radio="tab-radio" class="tab-radio-content row" id="today" style="display: block;">
				  <div class="main-stats__stat col-md-3 col-sm-3 col-xs-4">
						<div class="stat-circle" style="width:150px; height:150px;">
							<h3 style="line-height: 146px;">$<?php print sprintf ("%01.2f", str_replace(',', '.', ($a8['lr_balance'] + $a8['pm_balance'] + $a8['bt_balance'])));?></h3>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
								<circle style="opacity:0.16;fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;" cx="46" cy="46" r="45"></circle>
							</svg>
						</div> <!-- /stat-circle -->
						<h4 class="main-stats__subtitle">Баланс<br>
							<span class="main-stats__resume"><?php if($rowdeposp['sum']) { print '+ $'.$rowdeposp['sum']; }?></span>
						</h4>
					</div> <!-- /col -->

					<div class="main-stats__stat col-md-3 col-sm-3 col-xs-4" style="padding-top: 0px;">
						<div class="stat-circle" style="width:150px; height:150px;">
							<h3 style="line-height: 146px;"><?php print $refersi;?></h3>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
							<circle style="opacity:0.16;fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;" cx="46" cy="46" r="45"></circle>
							</svg>
						</div> <!-- /stat-circle -->
						<h4 class="main-stats__subtitle">Пригласили<br>
						<span class="main-stats__resume"><?php if($rowref['login']) { print '+'.$rowref['login']; }?></span>
						</h4>

					</div> <!-- /col -->

					<div class="main-stats__stat col-md-3 col-sm-3 col-xs-4" style="padding-top: 0px;">
						<div class="stat-circle" style="width:150px; height:150px;">
							<h3 style="line-height: 146px;"><?php print $depsallplan;?></h3>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92 92">
								<circle style="opacity:0.16;fill:none;stroke:#ffffff;stroke-width:2;stroke-miterlimit:10;" cx="46" cy="46" r="45"></circle>
							</svg>
						</div> <!-- /stat-circle -->
						<h4 class="main-stats__subtitle">Открытые<br> депозиты<br>
							<span class="main-stats__resume"><?php if($plan1) { print '«O-max» - '.$plan1.'<br>'; }?><?php if($plan2) { print '«A-max» - '.$plan2.'<br>'; }?><?php if($plan3) { print '«L-max» - '.$plan3.'<br>'; }?></span>
						</h4>
					</div> <!-- /col -->

				</div> <!-- row -->
			<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="/js/raphael-min.js"></script>
  <script src="/js/morris.js"></script>
  <script src="/js/prettify.min.js"></script>
  <script src="/js/example.js"></script>
  <script src="/js/jquery.liTextLength.js"></script>
  			<script>
			$("#donut").mousemove(function(event) {
	if($('tspan[dy=6]').html() == 'PerfectMoney') {
	$('#histpays').html('<center>Последние 6 пополнений счета PerfectMoney</center><br><?php print $historypm;?>');
	$( ".message__details table tr td #perfectcol" ).stop().animate({ backgroundColor: "#F35857" }, 250);
	$( ".message__details table tr td #payeercol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #bitcoincol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	} else if($('tspan[dy=6]').html() == 'Payeer') {
	$('#histpays').html('<center>Последние 6 пополнений счета Payeer</center><br><?php print $historype;?>');
$( ".message__details table tr td #perfectcol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #payeercol" ).stop().animate({ backgroundColor: "rgb(28, 125, 250)" }, 250);
	$( ".message__details table tr td #bitcoincol" ).stop().animate({ backgroundColor: "transparent" }, 250);
} else if($('tspan[dy=6]').html() == 'Bitcoin') {
$('#histpays').html('<center>Последние 6 пополнений счета Bitcoin</center><br><?php print $historybt;?>');
$( ".message__details table tr td #perfectcol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #payeercol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #bitcoincol" ).stop().animate({ backgroundColor: "rgb(255, 156, 75)" }, 250);
} else {
$( ".message__details table tr td #perfectcol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #payeercol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #bitcoincol" ).stop().animate({ backgroundColor: "transparent" }, 250);
}
	});
	
	$("#donut").mouseout(function(event) {
$( ".message__details table tr td #perfectcol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #payeercol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	$( ".message__details table tr td #bitcoincol" ).stop().animate({ backgroundColor: "transparent" }, 250);
	});
			
			
			Morris.Donut({
  element: 'donut',
  colors: ['#f00f0f', '#4b8dfb', '#f7931a'],
  labelColor: '#ffffff',
  backgroundColor: 'none',
  resize: true,
  data: [
    {label: "PerfectMoney", value: <?php print sprintf ("%01.2f", str_replace(',', '.', ($perfi)));?>},
    {label: "Payeer", value: <?php print sprintf ("%01.2f", str_replace(',', '.', ($payei)));?>},
    {label: "Bitcoin", value: <?php print sprintf ("%01.2f", str_replace(',', '.', ($biti)));?>}
  ],
  formatter: function (x, data) { 
  var uslov = <?php print $allmoneyss;?>;
  if(uslov > 0) {ui = '$'+x;} else {ui = '$0.00';}
  return ui; }
});
			</script>
			
			<?php } else {
	print "<p class=\"er\">Для доступа к данной странице вам необходимо авторизироваться</p>";
	include "../login/login_ru.php";
}
?>