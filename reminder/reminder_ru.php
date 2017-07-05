<?php
defined('ACCESS') or die();
?>
<div align="center">
<p class="warn" style="margin-top:5px; position:relative; font-size: 23px;">Восстановление доступа</p>
<?php
function generator($case1, $case2, $case3, $case4, $num1) {
	$password = "";

	$small="abcdefghijklmnopqrstuvwxyz";
	$large="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$numbers="1234567890";
	$symbols="~!#$%^&*()_+-=,./<>?|:;@";
	mt_srand((double)microtime()*1000000);

	for ($i=0; $i<$num1; $i++) {

		$type = mt_rand(1,4);
		switch ($type) {
		case 1:
			if ($case1 == "on") { $password .= $large[mt_rand(0,25)]; } else { $i--; }
			break;
		case 2:
			if ($case2 == "on") { $password .= $small[mt_rand(0,25)]; } else { $i--; }
			break;
		case 3:
			if ($case3 == "on") { $password .= $numbers[mt_rand(0,9)]; } else { $i--; }
			break;
		case 4:
			if ($case4 == "on") { $password .= $symbols[mt_rand(0,24)]; } else { $i--; }
			break;
		}
	}
	return $password;
}

if($_GET['action'] == "send" AND isset($_POST['email']) AND isset($_POST['ulogin']) AND isset($_POST['code'])) {
	$email	= htmlspecialchars($_POST['email'], ENT_QUOTES, '');
	$ulogin = htmlspecialchars($_POST['ulogin'], ENT_QUOTES, '');
	$code	= htmlspecialchars($_POST["code"], ENT_QUOTES, '');
	
if(preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
		$sql	= 'SELECT login, pass, status FROM users WHERE mail = "'.$email.'" AND login = "'.$ulogin.'" LIMIT 1';
		$rs		= mysql_query($sql);
		$a		= mysql_fetch_array($rs);
		$s		= $a['status'];

		if (!$a) {
			print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Введённый Вами e-mail не найден в базе!</p>
								</div>';
		} else {

			$case1	= on;
			$case2	= on;
			$case3	= on;
			$case4	= off;
			$num1	= 8;
			$num2	= 1;

			$newpass = generator($case1, $case2, $case3, $case4, $num1);

			$text = "<p>Здравствуйте <b>".$a['login']."</b>!</p><p>По Вашей просьбе высылаем новый пароль к аккаунту ".$a['login']."<br /><p>Новый пароль: <b>".$newpass."</b></p>С Уважением, администрация проекта ".$cfgURL;

			$subject	 = "Новый пароль к аккаунту ".$a['login'];
			$headers = "From: ".$adminmail."\n";
			$headers .= "Reply-to: ".$adminmail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			mysql_query("UPDATE users SET pass = '".as_md5($key, $newpass)."' WHERE login = '".$a['login']."' LIMIT 1");
			if (mail($email,$subject,$text,$headers)) {
				print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text"> Новый пароль был выслан на Ваш E-mail!</p>
								</div>';
			} else {
				print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Не удаётся отправить письмо!</p>
								</div>';
			}
		}
	} else {
		print '<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> Введите валидный e-mail!</p>
								</div>';
	}
}
?>
					<form method="post" action="?action=send">
					<div class="col-md-4  col-md-offset-4">
						<article class="widget widget__login">
							<header class="widget__header one-btn">
								<div class="butik1 widget__config">
									<a style="width: 100%;margin-left: 0px;" href="/login/" onclick="window.location.href = /login/"><i style="position: relative; left: -5px; margin-left:-5px; font-size: 27px; top: 5px;" class="pe-7f-back"></i> Вход</a>
								</div>
								<div class="butik2 widget__config">
									<a style="width: 100%;margin-left: 0px;" href="/registration/" onclick="window.location.href = /registration/"><i style="position: relative; left: -5px; margin-left:-5px; font-size: 27px; top: 5px;" class="pe-7f-add-user"></i> Регистрация</a>
								</div>
							</header>

							<div class="widget__content">
								<input name="ulogin" type="text" onblur="if (value == '') {value='Логин'}" onfocus="if (value == 'Логин') {value =''}" placeholder="Логин">
								<input name="email" type="text" onblur="if (value == '') {value='Email'}" onfocus="if (value == 'Email') {value =''}" placeholder="Email">
								<input name="code" type="text" onblur="if (value == '') {value='Проверочный код'}" onfocus="if (value == 'Проверочный код') {value =''}" placeholder="Проверочный код">
								<button type="submit">Отправить</button>
							</div>
						</article><!-- /widget -->
					</div>
					</form>
				</div>