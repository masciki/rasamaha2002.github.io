<?php
/*
Данный скрипт разработан Михайленко Виктором Леонидовичем, далее автор.
Любое использование данного скрипта, разрешено только с письменного согласия автора.
Скрипт защищён законом: http://adminstation.ru/images/docs/doc1.jpg
#
# Контакты: ICQ: 451699555; E-mail: support@adminstation.ru; URL: www.adminstation.ru
#
*/
defined('ACCESS') or die();

if($login) {
?>
<p style="text-align:center; text-indent: 0px;">
	<a <?php if($_GET['sort'] == 1) {?> class="buttoniker2" <?php } else {?> href="?sort=1" class="buttoniker" <?php }?>>Начисление %</a> <a <?php if($_GET['sort'] == 2) {?> class="buttoniker2" <?php } else {?> href="?sort=2" class="buttoniker" <?php }?>>Открытие депозитов</a> <a <?php if($_GET['sort'] == 3) {?> class="buttoniker2" <?php } else {?> href="?sort=3" class="buttoniker" <?php }?>>Пополнение счета</a> <a <?php if($_GET['sort'] == 4) {?> class="buttoniker2" <?php } else {?> href="?sort=4" class="buttoniker" <?php }?>>Вывод</a> <a <?php if($_GET['sort'] == 5) {?> class="buttoniker2" <?php } else {?> href="?sort=5" class="buttoniker" <?php }?>>Авторизации</a>
</p>
<?php
	if($_GET['sort'] == 2) {
		include "depo_ru.php";
	} elseif($_GET['sort'] == 3) {
		include "enter_ru.php";
	} elseif($_GET['sort'] == 4) {
		include "out_ru.php";
	} elseif($_GET['sort'] == 5) {
		include "auth_ru.php";
	} else {
		include "percent_ru.php";
	}

} else {
	print "<p class=\"er\">Для доступа к данной странице, вам необходимо авторизироваться!</p>";
}
?>