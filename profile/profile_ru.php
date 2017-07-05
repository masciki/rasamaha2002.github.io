<?php
defined('ACCESS') or die();
if ($login) {
	if ($_GET['action'] == 'save') {
		$get_user_info = mysql_query("SELECT pe FROM users WHERE id = ".$user_id." LIMIT 1");
		$row = mysql_fetch_array($get_user_info);
		 $upe		= $row['pe'];

		$pass_1 = $_POST['pass_1'];
		$pass_2 = $_POST['pass_2'];
		$email	= addslashes(htmlspecialchars($_POST['email'], ENT_QUOTES, ''));
		$icq	= addslashes(htmlspecialchars($_POST['icq'], ENT_QUOTES, ''));
		$pm		= addslashes(htmlspecialchars($_POST['pm'], ENT_QUOTES, ''));
		$pe		= addslashes(htmlspecialchars($_POST['pe'], ENT_QUOTES, ''));
		$bt		= addslashes(htmlspecialchars($_POST['bt'], ENT_QUOTES, ''));
		$skype	= addslashes(htmlspecialchars($_POST['skype'], ENT_QUOTES, ''));

		if($upm) { $pm = $upm; } 
		if($upe) { $pe = $upe; } 

		if (!$email) {
			echo '<p class="er">Следует ввести E-mail!</p>';
		} else {
			if ($pass_1 != $pass_2) {
				echo '<p class="er">Пароль и подтверждение не совпадают!</p>';
			} else {
				if (!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is", $email)) {
					print '	<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите правильно e-mail</p>
								</div>';
				} elseif (strlen($pm) != 8 && $pm) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректный PM кошелёк!</p>
								</div>';
				} elseif (strlen($bt) < 10 && $bt) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректный Bitcoin кошелёк!</p>
								</div>';
				} elseif ($pm[0] != 'U' && $pm) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректный PM кошелёк!</p>
								</div>';
				} elseif ($pe[0] != 'P' && $pe) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Введите корректный PM кошелёк!</p>
								</div>';
				} elseif(mysql_num_rows(mysql_query("SELECT pm FROM users WHERE pm = '".$pm."' AND id != ".$user_id)) && $pm) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Такой PM уже есть в базе!</p>
								</div>';
				} elseif(mysql_num_rows(mysql_query("SELECT pe FROM users WHERE pe = '".$pe."' AND id != ".$user_id)) && $pe) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Такой Payeer уже есть в базе!</p>
								</div>';
				} elseif(mysql_num_rows(mysql_query("SELECT bt FROM users WHERE bt = '".$bt."' AND id != ".$user_id)) && $bt) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Такой Bitcoin уже есть в базе!</p>
								</div>';
				} elseif(mysql_num_rows(mysql_query("SELECT mail FROM users WHERE mail = '".$email."' AND id != ".$user_id))) {
					print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Такой e-mail уже есть в базе!</p>
								</div>';
				} else {
					$sql = 'UPDATE users SET ';
					if($pass_1) { $sql .= 'pass = "'.as_md5($key, $pass_1).'", '; }

					$sql .= 'mail = "'.$email.'", icq = "'.$icq.'", pm = "'.$pm.'", pe = "'.$pe.'", bt = "'.$bt.'", skype = "'.$skype.'" WHERE id = '.$user_id.' LIMIT 1';
					if (mysql_query($sql)) {
						print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Данные были успешно обновлены!</p>
								</div>';
					} else {
						print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text">Не удаётся изменить данные</p>
								</div>';
					}
			}
		}
	}
}

$sql	= 'SELECT * FROM users WHERE login = "'.$login.'" LIMIT 1';
$rs		= mysql_query($sql);
$a		= mysql_fetch_array($rs);
?>
<form action="?action=save" method="post">
<table align="center" width="100%" border="0" cellpadding="2" cellspacing="1" class="widget__form" style="position: relative; left: calc(-25% - 15px);">
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Пароль: </td>
		<td align="left" width="20%"><input type='password' name='pass_1' size="30" /></td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Подтверждение: </td>
		<td align="left" width="20%"><input type='password' name='pass_2' size="30" /></td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >E-mail:</td>
		<td align="left" width="20%"><input type='text' name='email' value='<?php print $a['mail']; ?>' size="30" maxlength="30" /></td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Skype:</td>
		<td align="left" width="20%"><input type='text' name='skype' value='<?php print $a['skype']; ?>' size="30" maxlength="50" /></td>
	</tr>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Телефон:</td>
		<td align="left" width="20%"><input type='text' name='icq' value='<?php print $a['icq']; ?>' size="30" maxlength="15" /></td>
	</tr>
<?php
if($cfgPerfect) {	
?>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >PerfectMoney: </td>
		<td align="left" width="20%"><input type='text' name='pm' value='<?php print $a['pm']; ?>' size="30" maxlength="8" <?php if($a['pm']) { print 'disabled'; } ?> /></td>
	</tr>
<?php
}
if(cfgSET('cfgPEsid') && cfgSET('cfgPEkey')) {	
?>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Payeer:</td><td align="left" width="20%"><input type="text" name="pe" value="<?php print $a['pe']; ?>" size="30" maxlength="50" <?php if($a['pe']) { print 'disabled'; } ?>  /></td>
	</tr>
<?php
}
?>
<?php
if(cfgSET('cfgBitcoin') && cfgSET('cfgBitcoinSecretKey')) {	
?>
	<tr>
		<td style="padding-right: 10px; vertical-align:middle;" align="right" width="20%" >Bitcoin:</td><td align="left" width="20%"><input type="text" name="bt" value="<?php print $a['bt']; ?>" size="30" maxlength="60" <?php if($a['bt']) { print 'disabled'; } ?> /></td>
	</tr>
<?php
}
?>
</table>
<div align="center" style="padding-top: 10px;"><button type="submit" style="width:220px; margin: 2px 0 0 0;" class="btn green fixed">Сохранить</button></div>

</form>
<?php
} else {
	print "<p class=\"er\">Вы должны авторизироваться для доступа к этой странице!</p>";
}
?>