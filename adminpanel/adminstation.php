<?php
/*
������ ������ ���������� ���������� �������� ������������, ����� �����.
����� ������������� ������� �������, ��������� ������ � ����������� �������� ������.
������ ������� �������: http://adminstation.ru/images/docs/doc1.jpg
���� ����������: 14.10.2007 �. - �������������� 17.04.2009 �.

-> ������� ���� ��������� AdminStation
*/

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

$querye	= "SELECT * FROM `enter` WHERE status = 2 AND date > '".(time() - 86400)."' AND purse <> '�������������'";
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
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>AdminStation || ������� ���������� �������� �<?php print $cfgURL; ?>�</title>
<link href="files/favicon.ico" type="image/x-icon" rel="shortcut icon">
<link href="files/styles.css" rel="stylesheet" type="text/css" />
<script src="files/jquery.js"></script>
<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
<script type="text/javascript">

           /**
             * ������� ��� �������� ����� ���������� Ajax By Vitiook
             **/
            function AjaxFormRequest(result_id,form_id,url,fade,urlloc) {
			var formData = new FormData($("#"+form_id)[0]);
                jQuery.ajax({
                    url:     url, //����� ������������ ��������
                    type:     "POST", //��� �������
					dataType: "html", //��� ������
					processData: false,
                    contentType: false,
                    data: formData, 
                    success: function(response) { //���� ��� ���������
                    document.getElementById(result_id).innerHTML = response;
					if(fade > 0){
					setTimeout(function(){$('#'+result_id).fadeIn('fast')},0);
					setTimeout(function(){$('#'+result_id).fadeOut('easy')},5000);  //30000 = 30 ������
					}
					if(urlloc) {
					top.location.href= urlloc;
					}
                },
                error: function(response) { //���� ������
				if(document.getElementById(result_id).className == 'loaderblocks_on') {
				document.getElementById(result_id).className = 'loaderblocks_off';
				} else {
                document.getElementById(result_id).innerHTML = "������";
			}	
                }
             });
        }

   </script>

<script language="JavaScript">
<!--
function popUP(url,width,height) {
	if(!width) { width = 780; }
	if(!height) { height = 450; }
	var posx = 200;
	var posy = 200;
	var w=window.open(url,'wind','left='+posx+',top='+posy+',width='+width+',height='+height+',status:no, help:no');
	return false;
}
//-->
</script>
</head>
<body>
<table align="center" width="990" height="100%" border="0" cellpadding="0" cellspacing="0">
	<tr height="65">
		<td>
			<table align="center" width="990" height="65" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr valign="top" style="display: inline-table;">
					<td><a href="adminstation.php"><img src="images/logo.jpg" width="202" height="65" alt="" border="0" /></a></td></tr>
					<tr valign="top" style="display: inline-table; float: right;">
					<td width="30"><a href="/" target="_blank"><img src="images/home.gif" width="30" height="48" border="0" alt="������� �� ������� �������� �����" title="������� �� ������� �������� �����" /></a></td>
					<td width="10"></td>
					<td width="30"><a href="adminstation.php"><img src="images/stat_menu.gif" width="30" height="48" border="0" alt="����������" title="����������" /></a></td>
					<td width="10"></td>
					<td width="30"><a href="?a=antivirus"><img src="images/antivirus.gif" width="30" height="48" border="0" alt="���������" title="���������" /></a></td>
					<td width="10"></td>
					<td width="30"><img style="cursor: pointer;" onclick="popUP('http://adminstation.ru/help/index.html',775,450);" src="images/help.gif" width="30" height="48" border="0" alt="������" title="������" /></td>
					<td width="10"></td>
					<td width="30"><img style="cursor: pointer;" onclick="if(confirm('�� ������������� ������ �����?')) top.location.href='/exit.php';" src="images/exit.gif" width="30" height="48" border="0" alt="�����" title="�����" /></td>
				</tr>
				<tr valign="top" id="answerajax" style="display: inline-table; height: 48px; float: right;"><?php if(cfgSET('cfgWriteEntersIp') == 'on') {?><td width="10"></td>
					<td width="30" style="text-align: center; vertical-align: middle; <?php if($ipwallon1) { print 'color:green;'; }?>">���������� ������: <?php print $ipwallon1;?></td>
					<td width="10"></td>
					<td width="30" style="text-align: center; vertical-align: middle; <?php if($ipwallday1) { print 'color:green;'; }?>">����������� �� ����: <?php print $ipwallday1;?></td><?php }?>
					<td width="10"></td>
					<td width="70" style="text-align: center; vertical-align: middle; <?php if($profitday < 0) { print 'color:red;'; } elseif($profitday > 0) { print 'color:green;'; } else {}?>">������ �� ����: <?php print '$<span id="profitdayi">'.sprintf ("%01.2f", str_replace(',', '.', $profitday)).'</span>';?></td>
					<td width="10"></td>
					<td width="30" style="text-align: center; vertical-align: middle; <?php if($rowsenter) { print 'color:red;'; }?>">�������: <?php print '$<span id="withda">'.$withdr.'</span>(<span id="withdanum">'.$rowsenter.'</span>)';?></td>
					<td width="10"></td><td style="display:none;" id="niknamewithd" width="0"><?php print $nik;?></td></tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td height="1">
			<table class="menu">
				<tr>
					<td width="25%"><a class="menutop" href="?a=news">�������� �������</a></td>
					<td width="25%"><a class="menutop" href="?a=add_page">������� ��������</a></td>
					<td width="25%"><a class="menutop" href="?a=pages">��������� ��������</a></td>
					<td width="25%"><a class="menutop" href="?a=paysystems">��������� �������</a></td>
				</tr>

				<tr>
					<td><a class="menutop" href="?a=deposits">��������</a></td>
					<td><a class="menutop" href="?a=plans">�������������� �����</a></td>
					<td><a class="menutop" href="?">����������� ������</a></td>
					<td><a class="menutop" href="?a=fake">�������� ����������</a></td>
				</tr>
				<tr>
					<td><a class="menutop" href="?a=users">���������� ��������������</a></td>
					<td><a class="menutop" href="?a=mailto">�������� �������������</a></td>
					<td><a class="menutop" href="?a=reftop">������� ���������</a></td>
					<td><a class="menutop" href="?a=change_pass">������� ������</a></td>
				</tr>
				<tr>					
					<td><a class="menutop" href="?a=settings">��������� �������</a></td>
					<td><a class="menutop" href="?a=serverinf">���������� � �������</a></td>
					<td><a class="menutop" href="?a=blacklist">������ ������ IP</a></td>
					<td><a class="menutop" href="?a=logip">���������� IP</a></td>
				</tr>
				<tr>					
					<td><a class="menutop" href="?a=accounting">�����������</a></td>
					<td><a class="menutop" href="?a=edit&s=2">���������� �����</a></td>
					<td><a class="menutop" href="?a=edit">����� �������</a></td>
					<td><a class="menutop" href="?a=antivirus">���������</a></td>
				</tr>
				<tr>
					<td width="25%"><a class="menutop" href="?a=video">�����</a></td>
					<td width="25%"><a class="menutop" href="?a=ipwriter">������������� IP</a></td>
					<td width="25%"><a class="menutop" href="?a=logpass">������</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td valign="top" style="border-radius: 4 4 0 0px; padding: 10 10 10 10px; border: 1px solid #547898; background:URL(images/logo_down.jpg) no-repeat bottom right;">
<?php
$a	= substr(addslashes(htmlspecialchars($_GET['a'], ENT_QUOTES, '')), 0, 15);

	if(!$a) {
		include "modules/index.php";
	} elseif(file_exists("modules/".$a.".php")) {
		include "modules/".$a.".php";
	} else {
		include "modules/error.php";
	}

?>&nbsp;
		</td>
	</tr>
	<tr height="33" bgcolor="#5e87a9">
		<td align="center" style="color: #ffffff;">&copy; 2007-<?php print date(Y); ?> CMS FinancialCircles Module(Modify Adminstation) by Vitiook(Velgerstudio)<br />
		��� ����� ��������!</td>
	</tr>
</table>
<form method="post" id="withdraformans" action="" name="typeanswer">
<input name="typeanswer" id="typeanswer" value="withdrawanswersum" type="hidden">
<input name="withh" id="withh" value="<?php print $withdr;?>" type="hidden">
<input name="profitdayii" id="profitdayii" value="<?php print sprintf ("%01.2f", str_replace(',', '.', $profitday));?>" type="hidden">
</form>
<script>
setInterval(function() {
var withsound = parseFloat($('#withh').val());
var profitsound = parseFloat($('#profitdayii').val());
AjaxFormRequest('answerajax', 'withdraformans', 'ajaxans.php', '0');
setTimeout(function() {
if(parseFloat($('#withda').html()) != withsound && parseFloat($('#withda').html()) > withsound) {
if(parseFloat($('#withdanum').html()) < 2) {
  responsiveVoice.speak("������ �� ������� - ����� "+parseFloat($('#withda').html())+" $. �� "+$('#niknamewithd').html()+"", "Russian Female");
  } else if(parseFloat($('#withdanum').html()) > 1 && parseFloat($('#withdanum').html()) < 5) {
  responsiveVoice.speak(""+parseFloat($('#withdanum').html())+" ������ �� ������� - ����� "+parseFloat($('#withda').html())+" $", "Russian Female");
  } else if(parseFloat($('#withdanum').html()) > 4 && parseFloat($('#withdanum').html()) < 21) {
  responsiveVoice.speak(""+parseFloat($('#withdanum').html())+" ������ �� ������� - ����� "+parseFloat($('#withda').html())+" $", "Russian Female");
  }
}
$('#withh').val($('#withda').html());
if(parseFloat($('#profitdayi').html()) != profitsound && parseFloat($('#profitdayi').html()) > profitsound) {
var raznits = parseFloat($('#profitdayi').html()) - profitsound;
var audio = new Audio(); // ������ ����� ������� Audio
  audio.src = 'enter.mp3'; // ��������� ���� � ����� "�����"
  audio.volume = 0.1; // ��������� ���������
  audio.autoplay = true; // ������������� ���������
  responsiveVoice.speak("������ ���������� - �� ����� "+Math.floor(raznits.toFixed(2)*100)/100+" $", "Russian Female");
}
$('#profitdayii').val($('#profitdayi').html());
}, 800);
$('#withh').val($('#withda').html());
$('#profitdayii').val($('#profitdayi').html());
}, 7000);
</script>

</body>
</html>
<?php
} else {
print "<html><head><script language=\"javascript\">top.location.href='index.php';</script></head><body><a href=\"index.php\"><b>Index</b></a></body></html>";
}
?>