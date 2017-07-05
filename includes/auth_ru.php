<titt>Профиль</titt><div class="progress-content"><?php
if(!$login) {
?>

<?php
} else {
    print "<p align=\"center\"><b>Добро пожаловать <b style=\"color: green\">".$login."</b>!</b><br /> Баланс: $<b>".$balance."</b></p>";
	print '<ul>';
	if($status == 1) {
		print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/adminpanel/"><b>Администратору</b></a></li>';
	}
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0 "><a href="/enter/">Пополнить баланс</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/deposit/">Открыть депозит</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/deposits/">Ваши депозиты</a></li>';
	if(cfgSET('cfgTrans') == "on") {
		print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/transfer/">Перевод средств</a></li>';
	}
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/withdrawal/">Вывести средства</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/ref/">Партнерская программа</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/stat/">Статистика</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/profile/">Ваш профиль</a></li>';
	print '<li class="subm2" style="width: 220px; margin:3px 0 0 0"><a href="/exit.php">Выход</a></li>';
	print '</ul>';
}
?>
</div>