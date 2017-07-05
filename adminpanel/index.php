<?php
/*
Данный скрипт разработан Михайленко Виктором Леонидовичем, далее автор.
Любое использование данного скрипта, разрешено только с письменного согласия автора.
Скрипт защещён законом: http://adminstation.ru/images/docs/doc1.jpg
Дата разработки: 14.10.2007 г.

-> Страница с формой аторизации администратора (Главная)
*/
include "../cfg.php";
include "../ini.php";
if(($status == 1 || $status == 2) && $login) {
	print "<html><head><script language=\"javascript\">top.location.href='adminstation.php';</script></head><body><a href=\"adminstation.php\"><b>Enter</b></a></body></html>";
} else {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="files/styles.css" rel="stylesheet">
<link href="files/favicon.ico" type="image/x-icon" rel="shortcut icon">
<title>FinancialCircle - Администраторская</title>
</head>
<body bgcolor="#ffffff" style="background:url(img/pattern11.jpg) repeat right top;">
<table width="100%" height="100%">
<tr height="11">
	<td></td>
</tr>
<form action="login.php" method="post">
	<tr>
		<td align="center">
<?php
$error = intval($_GET['error']);
if($error == 1) {
	print "<p class=\"er\" style=\"width: 292px;\">Введите логин/пароль</p>";
} elseif($error == 2) {
	print "<p class=\"er\" style=\"width: 292px;\">Введите правильный логин/пароль</p>";
}

?>
<style>
.inputText {
	width:90%;
	padding:2px 10px;
	height: 35px;
	border:1px solid #DEDEDE;
	outline-color:#348EDA;
	color:#777777;
	text-align:center;
}
.inbox {
	color:#777777;
	margin:0 auto;
	margin-top:60px;
	width:400px;
	padding:15px;
	border:10px solid rgba(253,254,254,0.97);
	background: rgba(251,251,251,0.97);
	-webkit-box-shadow:0 1px 6px rgba(122,132,142,0.27);
    -moz-box-shadow:0 1px 6px rgba(122,132,142,0.27);
    box-shadow:0 1px 6px rgba(122,132,142,0.27);
}
.inputSubmit {
	color:#C0C0CB;
	padding: 8px 15px 8px 15px;
	font-size: 11px;
	font-weight: 600;
	letter-spacing: 0.07rem;
	border:4px solid #DEDEDE;
	text-transform:uppercase;
	background:#FFF;
	cursor:pointer;
	-webkit-transition: all 0.1s linear;
	-moz-transition: all 0.1s linear;
	-o-transition: all 0.1s linear;
	transition: all 0.1s linear;
}

.inputSubmit:hover {
	background:#348EDA;
	color:#FFF;
	border:4px solid #4B93D4;
}
}
</style>
			
			    <div class="bg">
	<div class="inbox">
		<div class="tlParam"><br><img src="img/key.png" height="128"></div><br>
               <center> 
        <form action="login.php" method="post">

        	<input type="text" class="inputText" value="" maxlength="20" name="login" placeholder="Login"><br><br>

        	<input type="password" class="inputText" value="" maxlength="50" name="pass" placeholder="Password"><br>
  
        		<br><br>
        	<input type="submit" class="inputSubmit" value="Войти в систему" value="go">
        </form>
		</center>

		<br>
		</div>

		</td>
	</tr>
	</form>
	<tr height="25">
		<td align="center">
<font color="#999999">&copy; 2013 - <?php print date(Y); ?> CMS <a style="font-weight: normal;">FinancialCircle</a> v2.0 Все права защищены!</font>
		</td>
	</tr>
</table>
</body>
</html>
<?php } ?>