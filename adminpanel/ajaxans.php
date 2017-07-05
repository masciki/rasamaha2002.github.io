<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<?php
include "../cfg.php";
include "../ini.php";
if($status == 1 || $status == 2) {
$withdrday = 0.00;
$enterday = 0.00;
$ipwallday1 = 0;

$withdr = 0.00;
$query	= "SELECT * FROM `output` WHERE status = 0 ORDER BY `id` ASC";
$result	= mysql_query($query);
while($row = mysql_fetch_array($result)) {
$withdr = $withdr + $row['sum'];
$nik = $row['login'];
}
$rowsenter = mysql_num_rows($result);

$queryw	= "SELECT * FROM `output` WHERE status = 2 AND date > '".(time() - 86400)."'";
$resultw	= mysql_query($queryw);
while($roww = mysql_fetch_array($resultw)) {
if(date( 'l', $roww['date'] ) == date( 'l', time() )) {
$withdrday = $withdrday + $roww['sum'];
}
}

$querye	= "SELECT * FROM `enter` WHERE status = 2 AND date > '".(time() - 86400)."' AND purse <> 'АДМИНИСТРАТОР'";
$resulte	= mysql_query($querye);
while($rowe = mysql_fetch_array($resulte)) {
if(date( 'l', $rowe['date'] ) == date( 'l', time() )) {
$enterday = $enterday + $rowe['sum'];
}
}
$profitday = $enterday - $withdrday;

$queryipwon1	= "SELECT * FROM ipwriter WHERE date > '".(time() - 300)."'";
$resultipwon1	= mysql_query($queryipwon1);
$ipwallon1 = mysql_num_rows($resultipwon1);

$queryipwday1	= "SELECT * FROM ipwriter WHERE date > '".(time() - 86400)."'";
$resultipwday1	= mysql_query($queryipwday1);
while($rowu = mysql_fetch_array($resultipwday1)) {
if(date( 'l', $rowu['date'] ) == date( 'l', time() )) {
$ipwallday1 = $ipwallday1 + 1;
}
}

if($rowsenter > 0) { $colored = 'color:red;'; } else { $colored = '';}
if($ipwallon1) { $colored1 = 'color:green;'; } else { $colored1 = ''; }
if($profitday < 0) { $colored2 = 'color:red;'; } elseif($profitday > 0) { $colored2 = 'color:green;'; } else { $colored2 = ''; }
if($ipwallday1) { $colored3 = 'color:green;'; } else { $colored3 = ''; }
if(cfgSET('cfgWriteEntersIp') == 'on') {
print'<td width="30" style="text-align: center; vertical-align: middle; '.$colored1.'">Посетители онлайн: '.$ipwallon1.'</td>
					<td width="10"></td>
					<td width="30" style="text-align: center; vertical-align: middle; '.$colored3.'">Посетителей за день: '.$ipwallday1.'</td>';}
print'
					<td width="10"></td>
					<td width="70" style="text-align: center; vertical-align: middle; '.$colored2.'">Профит за день: $<span id="profitdayi">'.sprintf ("%01.2f", str_replace(',', '.', $profitday)).'</span></td>
					<td width="10"></td>
					<td width="30" style="text-align: center; vertical-align: middle; '.$colored.'">Выплаты: $<span id="withda">'.$withdr.'</span>(<span id="withdanum">'.$rowsenter.'</span>)</td>
					<td width="10"></td><td style="display:none;" id="niknamewithd" width="0">'.$nik.'</td>';
					
} else {
exit;
}

?>