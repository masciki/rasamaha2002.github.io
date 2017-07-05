<!DOCTYPE html>
<?php 
defined('ACCESS') or die();

$alldep = 0.00 + cfgSET('fakedeposits');
$allout = 0.00 + cfgSET('fakewithdraws');

	if(cfgSET('cfgWriteEntersIp') == 'on') {
	function getipu() {
	if(getenv("HTTP_CLIENT_IP")) {
		$ip = getenv("HTTP_CLIENT_IP");
	} elseif(getenv("HTTP_X_FORWARDED_FOR")) {
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	} else {
		$ip = getenv("REMOTE_ADDR");
	}
$ip = htmlspecialchars(substr($ip,0,15), ENT_QUOTES);
return $ip;
}
if(!mysql_num_rows(mysql_query("SELECT * FROM ipwriter WHERE ip = '".getipu()."'"))) {
$sql = "INSERT INTO ipwriter (date, ip, last_page) VALUES ('".time()."', '".getipu()."', '".$_SERVER['HTTP_REFERER']."')";
mysql_query($sql);
} else {
mysql_query("UPDATE ipwriter SET date = ".time()." WHERE ip = '".getipu()."' AND (date + 600) < '".time()."' LIMIT 1");
}
	}

$resultd	= mysql_query("SELECT * FROM deposits WHERE status = '0' ORDER BY id ASC");
while($rowd = mysql_fetch_array($resultd)) {
	$alldep = $alldep + $rowd['sum'];
	}

$resulto	= mysql_query("SELECT * FROM `output` WHERE status = '2' ORDER BY id ASC");
while($rowo = mysql_fetch_array($resulto)) {
	$allout = $allout + $rowo['sum'];
	}
	
	$resultoi	= mysql_query("SELECT * FROM `users` ORDER BY id ASC");
	$investors = mysql_num_rows($resultoi) + cfgSET('fakeusers');

$action = htmlspecialchars(str_replace("'","",substr($_GET['action'],0,6)));

	if($action == "message") {
	$errormes = '';
		$name		= htmlspecialchars(str_replace("'","",substr($_POST['name'],0,50)), ENT_QUOTES, '');
		$mail		= htmlspecialchars(str_replace("'","",substr($_POST['mail'],0,50)), ENT_QUOTES, '');
		$subj		= htmlspecialchars(str_replace("'","",substr($_POST['subj'],0,100)), ENT_QUOTES, '');
		$textform	= htmlspecialchars(str_replace("'","",substr($_POST['textform'],0,10240)), ENT_QUOTES, '');
		$code		= htmlspecialchars(str_replace("'","",substr($_POST['code'],0,5)), ENT_QUOTES, '');

		    if(!$name) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Введите пожалуйста Ваше имя!</p>";
		}
		elseif(!$mail) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Введите пожалуйста Ваш e-mail!</p>";
		}
		elseif(!$subj) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Введите пожалуйста тему Вашего сообщения!</p>";
		}
		elseif(!$textform) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Введите пожалуйста текст Вашего сообщения!</p>";
		}
		elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Введите пожалуйста Ваш e-mail валидно!</p>";
		} elseif(!mysql_num_rows(mysql_query("SELECT * FROM captcha WHERE sid = '".$sid."' AND ip = '".getip()."' AND code = '".$code."'"))) {
			$errormes = "<p class=\"er\" style=\"color:#000000;\">Не правильно введён код!</b></font></center></p>";
		} else {

			$headers = "From: ".$mail."\n";
			$headers .= "Reply-to: ".$mail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			$textform = "Здравствуйте, ".$name."!<br />Вы писали нам, указав e-mail: ".$mail.", как контактный следующее:<p> >".$textform."</p>";

			$send = mail($adminmail,$subj,$textform,$headers);

			if(!$send) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">Ошибка почтового сервера!<br />Приносим извинения за предоставленные неудобства.</p>";
			} else {

				$errormes = "<p class=\"erok\" style=\"color:#000000;\">Ваше сообщение отправлено!</p>";

				$name		= "";
				$mail		= "";
				$subj		= "";
				$textform	= "";
			}
		}
	}?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class=" js rgba boxshadow csstransitions" lang="ru" style="font-family: 'PancettaPro-Regular';"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><link type="text/css" rel="stylesheet" href="/css/css"><style type="text/css">.gm-style .gm-style-cc span,.gm-style .gm-style-cc a,.gm-style .gm-style-mtc div{font-size:10px}</style><style type="text/css">@media print {  .gm-style .gmnoprint, .gmnoprint {    display:none  }}@media screen {  .gm-style .gmnoscreen, .gmnoscreen {    display:none  }}</style><style type="text/css">.gm-style{font-family:Roboto,Arial,sans-serif;font-size:11px;font-weight:400;text-decoration:none}.gm-style img{max-width:none}</style>
        <title><?php print $title; ?></title>
<meta name="viewport" content="480">
<link href="/css/style_min.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" href="/images/favicon.ico">
<script type="text/javascript" src="/js/jquery.min.js"></script>
<!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <link href="css/ie.css" rel="stylesheet">
        </script>
<![endif]--></head>
 <script type="text/javascript" src="/js/jquery.liTextLength.js"></script>
    <body screen_capture_injected="true" class="home_body  pace-done">
        <div id="actualizar" class="vert-center-wrap"></div>		
        <div class="MainWrapper ">
<header class="trans" id="navigation">
    <i id="nav-ico" style="display:none"></i>
    <div class="col8 txt-rg up" id="menu-mobile" style="display:none">
        <nav>
            <ul>
                <li>
                    <span class="center-wrap Active"><a class="center Active" href="#HeadSlider">Главная</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page2">Почему мы?</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page3">Новости</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page4">Отзывы</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page5">FAQ</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#Contacto">Контакты</a></span>
                </li>
            </ul>
        </nav>
    </div>
    <div class="Master">
        <div class="row">
            <div class="col4" id="brand">
                <img alt="CourseMax" class="flex" height="48" src="/images/logo-head.png" width="233">
            </div>
            <div class="col8 txt-rg" id="menu">
                <nav>
                    <ul>
                        <li>
                            <span class="center-wrap Active"><a class="center Active" href="#HeadSlider" data-link="#HeadSlider">Главная</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page2" data-link="#page2">Почему мы?</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page3" data-link="#page3">Отзывы</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page4" data-link="#page4">Мы принимаем</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page5" data-link="#page5">FAQ</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#Contacto" data-link="#Contacto">Контакты</a></span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col3" id="login">
			<span class="display980"><a href="?lang=en" style="color:white; text-decoration:none; font-size:20px;">English</a></span>
                <ul>
                   <li>
                        <span id="LangBtn" class="center-wrap trans  en"><a class="center" href="javascript:void(0)">Русский</a></span>
                        <div id="LangMenuWrapper" style="display:none">
                            <ul>
                                                                <li><a class="es" href="?lang=en">English</a></li>								
                            </ul>
                        </div>
                    </li>
                    <li>
                        <span id="LoginBtn" class="center-wrap"><?php if(!$login) {?><a id="loginLink" class="center modal-link login btnBox" href="javascript:void(0)" data-layer="reg" data-ref="login">Войти</a><?php } else {?><a id="loginLink" class="center modal-link btnBox login" href="/exit.php">Выход</a><?php }?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>      
      <div class="slider" id="HeadSlider" data-speed="2" data-type="background" style="height: 599px;">
                <div class="Master">
                      <div class="row">
                            <div class="col16 center txt-center top">
                                  <h1 class="title white cursiva wow bounceInLeft animated animated" data-wow-delay="0.8s" style="visibility: visible; animation-delay: 0.8s; animation-name: bounceInLeft; font-family: 'PancettaPro-Regular';">Держи правильный курс на пути к богатству!</h1>
                                  <p class="col12 txt-center block wow bounceInRight animated animated" id="p1" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: bounceInRight;">
                                         Управляй своим будущим сам! А мы тебе поможем.                                </p>
                                   <div class="wow pulse animated animated" data-wow-delay="1.2s" style="visibility: visible; animation-delay: 1.2s; animation-name: pulse;">
                                      <?php if (!$login) {?><a class="Bigbtn btn white regCall btnRegister btnBox" href="javascript:void(0)" data-layer="reg" data-ref="reg">Регистрация!</a><?php } else {?> <a class="Bigbtn btn white regCall btnRegister" href="/lk" data-layer="reg" data-ref="reg">В кабинет!</a> <?php }?>
									  <?php if (!$login) {?><a class="displaymob Bigbtn btn white modal-link login btnBox" href="javascript:void(0)" data-layer="reg" data-ref="login">Вход</a><?php } ?>
                                   </div>
                             </div>
                       </div>
                 </div>
                <div id="green-socket">
                    <div class="Master">
                        <div class="row">
                            <div class="TresCol white txt-center">
                                <div class="icon-round block minicon-tools">
                                </div>
                                <h3 class="title green block">Пакет «O-max»</h3>
                                <p class="white wrapper xlight">
                                    Данный пакет включает в себя сумму инвестиций от $5.00 до $199.00 под 4.10% в день, сроком 5 дней.                               </p>
                                <a class="Smallbtn btn white" href="/deposit">Инвестировать</a>
                            </div>
                            <div class="TresCol white txt-center">
                                <div class="icon-round block minicon-com">
                                </div>
                                <h3 class="title green block">Пакет «A-max»</h3>
                                <p class="white wrapper xlight">
                                    Данный пакет включает в себя сумму инвестиций от $200.00 до $999.00 под 5.20% в день, сроком 15 дней.                            </p>
                                <a class="Smallbtn btn white" href="/deposit">Инвестировать</a>
                            </div>
                            <div class="TresCol white txt-center">
                                <div class="icon-round block minicon-rew">
                                </div>
                                <h3 class="title green block">Пакет «L-max»</h3>
                                <p class="white wrapper xlight">
                                    Данный пакет включает в себя сумму инвестиций от $1000.00 до $10000.00 под 6.30% в день, сроком 30 дней.                                </p>
                                <a class="Smallbtn btn white" href="/deposit">Инвестировать</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			
		<main id="main">
			<section id="videopage">
                    <div style="background: #FFFFFF !important; border-top: #AADA65 solid 3px;" id="videoss">
                         <div class="Master">					
							<div class="row">
                                <div class="col9 wrapper" style="position: relative;">
						<div style="width: 100%; text-align: center; height: 280px;"><hr style="position: relative; border-radius: 50%; float: left; top: 148px; width: 20%; border: 3px solid #AADA65;"><hr style="position: relative; float: right; top: 103px; width: 20%; border-radius: 50%; border: 3px solid #AADA65;"><span id="videoof"><div id="videoinn" style="background: #AADA65; height: 115px; width: 45%; margin: 0 auto; position: relative; top: 105px; padding: 24px 24px; color: white; font-size: 24px; border-radius: 50%; border-width: 10px 10px 10px 10px; border-style: solid; cursor:pointer; border-color: #AADA65;"><span style="position: relative; top: 8px;">Официальный видеоролик проекта</span></div></span></div>
                            					<script>
$( "#videoof" ).click(function() {
$( "#videoinn" ).animate({
    width: "560px",
    height: "315px",
	top: "0px",
	marginTop: "10px",
	borderRadius: "3%"
  }, 1000, function() {
  $( "#videoof" ).html('<iframe style="border-width: 10px 10px 10px 10px; border-style: solid; border-color: #AADA65; border-radius: 3%; margin-top: 10px;" width="560" height="315" src="https://www.youtube.com/embed/7sq76LMXHmo" frameborder="0" allowfullscreen></iframe>');
  });
});
					</script>
                        </div>
                    </div>
				 </div>
               </div>
					</section>
			
        
                <section id="page2">
                    <div class="back-grey" style="margin-top: 30px;" id="Beneficios">
                        <div class="Master">					
							<div class="row">
                                <div class="col9 wrapper" style="position: relative; top: -50px;">
                                    <h2>Почему мы?</h2>
                                    <p>
										-Вы сами регулируете прибыль.<br>
-Фиксированное время закрытия сделки.<br>
-Компания «CourseMax» для профессионалов и новичков.<br>
-Полный доступ к возможностям системы.<br>
-Персональный менеджер.<br>
-Гарантия выплаты средств, в тот момент, когда и как Вам это удобно.<br>
-Вам не нужно никуда идти,ведь всё что нужно- это свободный доступ к интернету.<br>
-Стабильный курс,Вы ничего не теряете,ведь это не биржа,мы не меняем прибыль от курса $.<br>
-Получение бесплатной рекомендации, а так же торговые сигналы от профессиональных финансовых аналитиков компании «CourseMax».<br>
А самое главное - Вы никогда не опуститесь ниже вложенной Вами суммы,так же в любой момент сможете вывести Ваши средства любым удобным Вам способом.<br>
                                    </p>
                                </div>
                                <div class="col7 txt-rg">
                                    <div id="circular-animation">
                                        <div id="cartoon-man">
                                        </div>
                                        <div class="icon-light">
                                        </div>
                                        <div class="icon-pork">
                                        </div>
                                        <div class="icon-link">
                                        </div>
                                        <div class="icon-graph">
                                        </div>
                                        <div class="icon-weel">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <section id="Carrousel1" class="Master carrousel-wrap">
                        <div class="row">
                            <div class="col16">
                                <div class="carrousel">
                                <div class="flex-viewport" style="overflow: hidden; display:none; position: relative;"><ul class="slides txt-center" style="width: 1000%; transition-duration: 0.6s; transform: translate3d(-764px, 0px, 0px);">
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-blue-light minicon-adn">
                                            </div>
                                            <h3 class="title block">Вы сами регулируете свою прибыль</h3>
                                            <p class="wrapper xlight">
Это означает что за выбранным Вами тарифным планом предусмотрено насколько приумножиться Ваш капитал. Например: пакет «О-мах» включает в себя депозит от 5$ до 199$ под 4,10% в день. Что позволяет Вам самим выбрать сумму которую Вы пожелаете положить под депозит и подсчитать на сколько приумножиться Ваш капитал.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-green minicon-pork">
                                            </div>
                                            <h3 class="title block">Фиксированное время закрытия сделки</h3>
                                            <p class="wrapper xlight">
Эта функция позволит Вам не только заранее знать когда закончится сделка, но и быть уверенным что Вас не обманут, ведь заключая с нами сделку Вы будете иметь полное право на компенсацию в случае несвоевременной выплаты средств. Например: пакет «О-мах», включает в себя срок действия сделки 5 дней.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-yellow minicon-op">
                                            </div>
                                            <h3 class="title block">Полный доступ к возможностям системы</h3>
                                            <p class="wrapper xlight">
Данная возможность, предусматривает не ограниченный доступ к системе «CourseMax»,а именно:
-Ваш депозит контролируете Вы и только Вы;
-Вывести деньги удобным Вам способом;
-Контроль и возможности видеть прозрачность работы компании «CourseMax»;
Это дает гарантии выплаты средств ,в тот момент ,когда и как Вам это удобно.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-violet minicon-reward">
                                            </div>
                                            <h3 class="title block">Персональный менеджер</h3>
                                            <p class="wrapper xlight">
Эта услуга включает в себя :
-возможность получить консультации по любому поводу и с любыми интересующими Вас вопросами с личным менеджером;
-А так же экономические уведомления от профессиональных финансовых аналитиков компании «CourseMax» ,например: каким образом Вы должны пополнить баланс счета для депозита? в каком размере, чем выгодней тот или иной Вами выбранный тариф. Так же это является очень удобной услугой как для новичков так и для профессионалов в данной сфере заработка.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-blue2 minicon-light2">
                                            </div>
                                            <h3 class="title block">Удобный график и способ работы</h3>
                                            <p class="wrapper xlight">
Потому что Вам не нужно никуда идти, ведь всё что нужно- это свободный доступ к интернету. График работы Вы так же выбираете сами, когда Вам удобно :вечером или утром, час или 3, какой тарифный план выбрать так же зависит лишь от Вас. Ведь Вы сами себе хозяин.                                            </p>
                                        </li>
										<li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-red minicon-light">
                                            </div>
                                            <h3 class="title block">Стабильный курс $</h3>
                                            <p class="wrapper xlight">
Данная функция гарантирует что ,Вы ничего не теряете, ведь это не биржа, мы не меняем прибыль от курса $. Ведь Именно поэтому мы предлагаем Вам инвестиционную площадку для заработка. Система по которой мы работаем - самая растущая ниша на депозитных вкладах. К Вашим депозитам не относится рост курса $ ,а так же экономические изменения, которые происходят каждый день в банках и на биржах. Это еще один + к тому ,что депозитный вклад в компании «CourseMax» - это стабильный и выгодный шаг для тех людей которые хотят приумножить свой капитал.                                            </p>
                                        </li>
                                    </ul></div><ol class="flex-control-nav flex-control-paging"><li><a class="">1</a></li><li><a class="">2</a></li><li><a class="flex-active">3</a></li></ol></div>
                            </div>
                        </div>
                    </section>
                </section>
                <section id="page3">
                    <div data-speed="12" data-type="background" id="Herramientas">
                        <div id="Htop">
                            <div class="Master" style="margin-top: -65px;">
                                <div class="row">
                                    <div class="col16 wrapper txt-center">
                                        <h2 class="title wow bounceInLeft animated" style="visibility: hidden; animation-name: none;">Здесь вы можете <a href="/answers" style="color:#aada65;"><u>оставить</u></a> свой отзыв</h2>
                                        <p class="xlight wow bounceInRight animated" style="visibility: hidden; animation-name: none;">
                                        Оставив красочный,информативный отзыв у Вас появляется возможность получить денежное вознаграждение.                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="Master carrousel-wrap" id="Hbottom">
                            <div class="row">
                                <div class="col16">
                                    <div class="carrousel">
                                        
                                    <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides txt-center" style="width: 1400%; transition-duration: 0.6s; transform: translate3d(-764px, 0px, 0px); padding-bottom: 80px;">
									<?php $querya	= "SELECT id,username,text,view FROM answers WHERE view = 1 LIMIT 10";
$resulta	= mysql_query($querya);
$numia = 0;
while($row = mysql_fetch_array($resulta)) {
	print '<li style="width: 382px; float: left; display: block;">
                                                <h3 class="title block green">Отзыв оставил '.$row['username'].'</h3>
                                                <p class="wrapper xlight textansw" id="answ'.$row['id'].'">
                                                '.$row['text'].'                                                </p>
                                            </li>
											
											<script>
											var size = 330,
    newsContent= $("#answ'.$row['id'].'"),
    newsText = newsContent.text();
    
if(newsText.length > size){
	newsContent.text(newsText.slice(0, size) + "...");
}
											</script>
											';
	$numia++;
	}
	if($numia <= 3 && $numia > 0) {
	$numans = $numia;
	} elseif($numia < 0) {
	$numans = 1;
	} else {
	$numans = 3;
	}
?>

                                        </ul></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="page4">
                    <div class="back-grey" data-speed="1" data-type="background" id="Productos">
                        <div class="Master">
                            <div class="row">
                                <div class="col8 wrapper">
                                    <h2>Мы работаем с самыми популярными и безопасными платежными системами</h2>
                                    <p class="xlight">
                                    Perfect Money - это сервис, позволяющий пользователям производить моментальные платежи и финансовые операции в Интернете, и открывающий уникальные возможности для Интернет пользователей и владельцев интернет бизнеса.
Цель Perfect Money вывести финансовые операции в Интернете на идеальный уровень!                                     </p>
                                    <p class="xlight">
                                    Bitcoin — виртуальная электронная валюта, регулируемая только интернетом с помощью хитрого алгоритма. Bit означает единицу информации (бит), а coin в переводе с английского значит «монета».
Главное преимущество биткоинов в том, что их нельзя «напечатать» слишком много, поскольку их выпуск заложен в программные алгоритмы и не подчинен ни одному из правительств.                                    </p>
									<p class="xlight">
                                    PAYEER – это международная система электронных платежей, а точнее, полноценный платежный портал с быстрой регистрацией, удобным интерфейсом, универсальными возможностями и востребованными функциями.                                    </p>
                                </div>
                                <div class="col8 txt-center">
                                    <div class="slider wow bounceInUp animated" data-wow-delay="0.5s" id="RoundSlider" style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
                                        <div class="flexslider">
                                            
                                        <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1200%; transition-duration: 0.6s; transform: translate3d(0px, 0px, 0px);">
										<li class="flex-active-slide" style="width: 400px; float: left; display: block;">
                                                    <div id="EmpireLogo">
                                                    </div>
													<p>Payeer - объединяет множество платежных систем.</p>
                                                     <a class="btn Smallbtn" href="https://payeer.com">Payeer</a>
                                                </li><li class="" style="width: 400px; float: left; display: block;">
												<div id="RoyalLogo">
                                                    </div>
													<p>PerfectMoney - самое надежное хранилище долларовой валюты</p>
                                                    <a class="btn Smallbtn" href="https://perfectmoney.is/">PerfectMoney</a>
                                                </li><li class="clone" style="width: 400px; float: left; display: block;">
												<div id="MakeMoney">
                                                    </div>
													<p>Bitcoin - самая популярная криптовалюта на просторах интернет.</p>
                                                     <a class="btn Smallbtn" href="https://blockchain.info">Bitcoin</a>
                                                </li></ul></div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="Master" id="productos-banner">
                        <div class="row">
                            <div class="col16" style="height: 400px;">
							                                    <h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none; color: rgb(51, 51, 51); margin-top: -70px;">Видео-отзывы наших инвесторов</h1>
                                									<?php $queryv	= "SELECT id,title,link FROM video WHERE status = 1 ORDER BY RAND() LIMIT 3";
$resultv	= mysql_query($queryv);
while($row = mysql_fetch_array($resultv)) {
print '<span><span id="video" linke="'.$row['link'].'"><img class="wow bounceInRight animated" src="https://img.youtube.com/vi/'.$row['link'].'/0.jpg" tabindex="2" alt="'.$row['title'].'" style="max-height: 200px; width: 32.7%; margin: 4px 0 0 4px; border-radius: 50px; border: 3px #AADA65 solid; cursor:pointer;" title="'.$row['title'].'"/></span></span>';
}
?>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="page5">
                    <div class="back-green-light" id="GreenCall">
                        <section class="Master">
                            <article class="row">
                                <div class="col16 txt-center">
								<h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none; color: rgb(255, 255, 255); margin-top: -45px;">А теперь о цифрах</h1>
                                    <h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none;"></h1>
                                    <p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Участников<br> <span id="userss"><?php print $investors;?></span></p>
									<p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Инвестировали<br> <span id="inves">$<?php print sprintf("%01.2f", $alldep);?></span></p>
									<p class="wow bounceInRight animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Выплат<br> <span id="withd">$<?php print sprintf("%01.2f", $allout);?></span></p>
                                </div>
                            </article>
                        </section>
                    </div>
					<script>
					function isIntoView(elem) { 
    if(!$(elem).length) return false; // element not found

    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom = docViewTop));
}

$(window).scroll(function(){ if(isIntoView('#userss'))
	$('#userss').animate({ num: <?php print $investors;?> - 0/* - начало */ }, {
    duration: 6000,
    step: function (num){
        this.innerHTML = (num).toFixed(0)
    }
});
$('#inves').animate({ num: <?php print sprintf("%01.2f", $alldep);?> - 0/* - начало */ }, {
    duration: 6000,
    step: function (num){
        this.innerHTML = '$'+(num).toFixed(2)
    }
});
$('#withd').animate({ num: <?php print sprintf("%01.2f", $allout);?> - 0/* - начало */ }, {
    duration: 6000,
    step: function (num){
        this.innerHTML = '$'+(num).toFixed(2)
    }
});
});
					</script>
                    <div data-speed="3" data-type="background" id="CPA">
                        <div class="Master">
                            <div class="row">
                                <div class="col4">
								<h3 class="title green" style="margin-left: -5px;">Граждане каких стран могут принять участие?</h3>
                                    <p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; margin-left: 25px;">
                                    К участию в системе допускаются граждане всех стран мира.                                   </p>
                                    <div class="GreenCirlce wow bounceInLeft animated en" data-wow-delay="0.6s" id="gc1" style="visibility: hidden; animation-delay: 0.6s; animation-name: none;">
                                    </div>
                                </div>
                                <div class="col6 top wrapper">
                                    <div class="GreenCirlce wow bounceInRight animated en" id="gc2" style="visibility: hidden; animation-name: none;">
                                    </div>
									<h3 class="title green">Какая минимальная и максимальная сумма вклада?</h3>
                                    <p class="wow bounceInRight animated" data-wow-delay="0.6s" style="visibility: hidden; animation-delay: 0.6s; animation-name: none;">
                                    Минимальная сумма составляет <a href="http://pkstemplate.ru/">5 долларов.</a>
Максимальная сумма составляет 10 000 долларов.                                    </p>
                                </div>
                                <div class="col6">
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1s" style="visibility: hidden; animation-delay: 1s; animation-name: none;">
                                        <h3 class="title green">Как начать работу с компанией CourseMax?</h3>
                                        <p class="white xlight">
Вам следует пройти простую процедуру регистрации в системе. Далее Вам необходимо внести свой первый депозит, выбрав соответствующий раздел в своем личном кабинете. После этого вся инвестиционная работа будет проводиться нашей компанией, а Вы в свою очередь будете получать доход.                                       </p>
                                    </div>
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1.1s" style="visibility: hidden; animation-delay: 1.1s; animation-name: none;">
                                        <h3 class="title green">Насколько быстро производятся выплаты?</h3>
                                        <p class="white xlight">
Обработка заявок на вывод средств обрабатывается в автоматическом режиме через платежную систему Payeer и Bitcoin. Однако выплата на PerfectMoney всего пару часов, так как она проходит в ручном режиме.                                        </p>
                                    </div>
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1.2" style="visibility: hidden; animation-name: none;">
                                        <h3 class="title green">Имеется возможность заработка без вложений?</h3>
                                        <p class="white xlight">
Разумеется! У нас предусмотрена пяти уровневая партнерская программа для активных участников системы. Вы получаете 5%-3%-1%-1%-1% от каждого пополнения в вашей структуре.                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="Master" id="Contacto">
                    <article class="row">
                        <div class="col16" style="display: table;">
                            <h2 style="text-align: center;">Контакты</h2>
                            <div align="center" class="Master w100">
                                <div class="row">
								<div class="col4 top">
                                        <script type="text/javascript" src="//vk.com/js/api/openapi.js?117"></script>
<!-- VK Widget -->
<div id="vk_groups" style="margin: 0 auto;"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 2, width: "280", height: "450"}, 68035496);
</script>
                                    </div>
                                       <div class="col4 top">
                                        <div id="contact-info">
                                            <div id="mail">
                                                <span><a href="mailto:webmaster@coursemax.net">webmaster@coursemax.net</a></span>
                                            </div>
                                           <!-- <div id="tel">
                                                <span>(+598) 95 50 20 40</span>
                                            </div>-->
                                            <div id="skype">
                                                <span>coursemax.net</span>
                                            </div>
                                        </div>
										<center>
									   <img onclick="window.open('https://goo.gl/G8fHRI')" border=1 src="/img/rate2.jpg" style="cursor:pointer;"  alt="Rate Our status on all hyip monitors"><br>
									   <img onclick="window.open('https://goo.gl/oUFSW8')" border=1 src="/img/mmgp.gif" style="cursor:pointer;" width="120px" height="61px" alt="Money Maker Group - CourseMax">
									   </center>
                                    </div>
                                    <div class="col8 top">
                                        <form action="?action=message" id="contactForm" method="post">
                                            <ul>
                                                <li>
                                                    <h3>Связаться с нами</h3>
                                                </li>
                                                <li>
                                                    <input class="trans" name="name" placeholder="Ваше имя" value="<?php if(!$name) { print $login; } else { print $name; } ?>" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
                                                <li>
                                                    <input class="trans" name="mail" placeholder="E-mail" value="<?php if(!$mail) { print $user_mail; } else { print $mail; } ?>" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
												<li style="margin-top: -15px;">
                                                    <img id="captchaImg3" style="cursor:pointer;height: 40px;position: relative;top: 15px;" src="/captcha.php?glav=1"><input class="trans" name="code" placeholder="Проверочный код" onfocus="cleanStyles(this)" style="width: calc(100% - 95px);" type="text">
                                                </li>
                                                <li>
                                                    <input class="trans" name="subj" placeholder="Тема сообщения" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
                                                <li>
                                                    <textarea class="trans" cols="" name="textform" onfocus="cleanStylesTA(this)" style="width: 100%;" placeholder="Текст сообщения" rows=""></textarea>
                                                </li>
                                                <li>
                                                    <input class="trans" name="send" id="btnContactMail" type="button"  onClick='submit();' style="width: 100%;" value="Отправить">
                                                </li>
												<?php if($errormes) { print $errormes; }?>
                                            </ul>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </section>
            </main>
            <!----------- Footer ------------------>
            <footer class="back-green-dark">
<div class="Master" id="menu-footer">
	<div class="row">
		<div class="TresCol white">
			<ul>
				<li><a href="#HeadSlider">Главная</a></li>
				<li><a href="#page2">Почему мы?</a></li>
				<li><a href="/news">Новости</a></li>
			</ul>
		</div>
		<div class="TresCol txt-center white">
			<img alt="CourseMax" height="45" src="/images/logo-head.png" width="241">
		</div>
		<div class="TresCol txt-right white">
			<ul>
				<li><a href="#page3">Отзывы</a></li>
				<li><a href="#page5">FAQ</a></li>
				<li><a href="#Contacto">Контакты</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="Master" id="copyright">
	<div class="row">
		<div class="col16 green txt-center">
			 Copyright © 2015 CourseMax.net<br>
			<a class="alpha-link btnBox" href="javascript:void(0)" data-layer="TyC">Наши правила</a>
         <span>•</span>
			<a class="alpha-link btnBox" href="http://velgerstudio.com/">Разработка VelgerStudio</a>
		</div>
	</div>
</div>
</footer>            <!----------- / Footer ---------------->
        </div>
         <div class="loginOverlay OverlayBox reg" id="loginOverlay" style="display:none">
<a href="javascript:void(0)" class="close-box">Закрыть</a>
<div id="login-register-password" class="LayerBox reg">
    <ul class="tabs_login">
        <li id="loginBtn" class="active_login"><a data-link="#tab1_login" href="javascript:void(0)">Вход</a></li>
        <li id="RegBtn" class="tabBtn"><a data-link="#tab2_login" href="javascript:void(0)">Регистрация</a></li>
        <li id="RecBtn"><a data-link="#tab3_login" href="javascript:void(0)">Забыли пароль?</a></li>
    </ul>
    <div class="tab_container_login">
        <div id="tab1_login" class="tab_content_login" style="display: block;">
            <div id="LoginWrapper" class="" data-wrong="" data-btn="Continue">
                <form action="/login/index.php" class="login_form modal" id="loginbox" method="post">
                    <div class="inputCase" id="UserName">
                        <input type="text" placeholder="Логин" id="userLogin" name="user">
                    </div>
                    <div class="inputCase" id="PassWord">
                        <input type="password" placeholder="Пароль" id="passLogin" name="pass">
                    </div>
                    <div class="inputCase" id="LogIn">
                        <button type="submit" class="title">Войти</button>
                    </div>
                </form>
            </div>
            <div id="logoWrapper"></div>     
        </div>
        <div id="tab2_login" class="tab_content_login register" style="display:none;">
            <div id="RegWrapper">
                <p style="text-align:center;">
                Регистируясь вы соглашаетесь с <a class="alpha-link btnBox" href="javascript:void(0)" data-layer="TyC">условиями</a> нашего проекта.           
											<?php if($referal) {
		print '<br><p style="background: #B7D1A2; text-align: center; color: #5B823D;">Ваш пригласитель: '.$referal.'</p>';
	}?></p>
                <div class="RegForm"> 
                    <form action="/registration/?action=save" method="post" class="login_form modal" id="regbox">
						<input type="hidden" name="yes" value="1">
                        <div id="StepsWrapper">
                       		 <div id="step1">
                            <div class="inputCase user" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="Логин" autocomplate="off" name="ulogin" style="width: 100%;">
                            </div>

                            <div class="inputCase pass" style="width: 100%; margin-bottom: 2px;">
                                <input type="password" placeholder="Пароль" autocomplate="off" name="pass" style="width: 100%;">
                            </div>

                            <div class="inputCase rePass" style="width: 100%; margin-bottom: 2px;">
                                <input type="password" placeholder="Повторите пароль" autocomplate="off" name="repass" style="width: 100%;">
                            </div>

                            <div class="inputCase Mail" style="width: 100%; margin-bottom: 2px;">
                                <input type="email" placeholder="E-mail" name="email" style="width: 100%;">
                            </div>

                            <div class="inputCase skype" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="Skype" name="skype" style="width: 100%;">
                            </div>

                            <div class="inputCase PerfectMoney" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="PerfectMoney" autocomplate="off" name="pm" style="width: 100%;">
                            </div>
							
							<div class="inputCase Payeer" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="Payeer" autocomplate="off" name="pe" style="width: 100%;">
                            </div>
							
							<div class="inputCase Bitcoin" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="Bitcoin" autocomplate="off" name="bt" style="width: 100%;">
                            </div>
<div id="SecCodeReg" data-txt="Проверочный код " class="">
                                <div class="col3">
                                    <img id="captchaImg" style="height:37px; top:2px;" src="/captcha.php?glav=1" width="90" height="30">
                                </div>
                                <div id="reload" class="col2" data-txt="Обновить код"></div>
                                <div class="col11">
                                    <input type="text" style="height:38px;" placeholder="Введите код здесь" name="code">
                                </div>
                            </div>
							
                            <button id="nextStep" type="submit" class="trans title">Создать аккаунт</button>
                        </div>
                        	 <div id="step2">
							 </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="tab3_login" class="tab_content_login" style="display:none;">
            <div id="ChangePassWrapper">
                <h4 class="title">Забыли свой пароль?</h4>
                <p>
                Новый пароль будет выслан Вам на почту.                </p>
                <form class="login_form modal" id="loginbox" action="/reminder/?action=send" method="post">
                    <div id="RegLogin" data-txt="Codigo incorrecto">
                        <input type="text" name="ulogin" placeholder="Логин">
                    </div>
					<div id="RegEmail" data-txt="Codigo incorrecto">
                        <input type="email" name="email" placeholder="E-mail">
                    </div>
                    <div id="SecCodeWrapper" class="txt-left">
						<div class="col3" id="SecCode">
                            <img id="captchaImg2" style="cursor:pointer;" src="/captcha.php?glav=1" width="90" height="30">
                        </div>
						<div class="col11">
                        <input name="code" style="margin-left:92px; width:250px;" type="text" placeholder="Проверочный код">
						</div>
                    </div>
                    <div class="inputCase" id="LogIn">
                        <button type="submit" class="title">Отправить пароль</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>    
         
<div id="TyC" class="OverlayBox TyC" style="display: none">  
    <a href="javascript:void(0)" class="close-box">Close</a>
        <div class="LayerBox standard txt"> 
        			
           	<h1>Правила и соглашение</h1>
            <div class="block" style="font-size:15px;">
                <p>Перед регистрацией в компании CourseMax, администрация компании настоятельно рекомендует прочесть нижеприведенный документ. В нем описаны все основные, обязательные к исполнению обеими сторонами правила, права и обязанности, а также условия взаимодействия клиента с инвестиционным проектом компании CourseMax Регистрируйтесь в проекте компании CourseMax только при условии безоговорочного принятия всех описанных ниже положений.
Клиент — лицо, которое полностью согласилось с условиями действующего соглашения, своевременно прошедшее регистрацию на сайте CourseMax
Проект — web-ресурс, расположенный по адресу CourseMax.net</p>
            </div>
            <div class="block">
            	<pre style="font-size:13px;">1. Право на создание аккаунта в рамках инвестиционного проекта CourseMax имеют все физические лица, не относящиеся к следующим категориям:
1.1 Несовершеннолетние лица (в соответствии с законодательством страны проживания);
1.2 Лица, признанные на основании медицинского заключения недееспособными;
2. Ответственность за принятие решения присоединиться к проекту компании CourseMax, за все свои действия в рамках проекта (соблюдение / не соблюдение правил, финансовые операции и т.п.) и их последствия лежит целиком на самом клиенте.
3. Права и обязанности клиента проекта компании CourseMax:
3.1 Каждый клиент проекта в равной степени с другими такими же клиентами имеет право пользоваться всеми услугами, предоставляемыми ему проектом компании CourseMax: пользоваться личным кабинетом на сайте проекта, инвестировать средства и получать доход согласно условиям инвестиционного плана, пользоваться бонусами реферальной программы проекта, обращаться за помощью в службу поддержки проекта.
3.2 Клиент имеет право создавать и управлять аккаунтами в рамках проекта. Если администрацией проекта будет установлен факт того, что клиент будет управлять нескольким аккаунтами, связанными реферальной программой, то все его аккаунты будут незамедлительно заблокированы, а имеющиеся средства перейдут в распоряжении компании, как выплата морального вреда за мошенничество в её отношении.
3.3 Клиент обязуется обеспечивать полную конфиденциальность любой информации, полученной от администрации компании.
3.4 Клиент обязуется не разглашать свои регистрационные данные третьим лицам. В случае, если клиент предоставил свой пароль доступа третьим лицам, что привело в дальнейшем ко взлому аккаунта и потере средств, ответственность лежит только на нем самом.
3.5 Клиент обязуется обеспечивать надежную защиту своему компьютеру, планшету, смартфону и любому другому устройству, с которого осуществляет доступ к своему аккаунту. В случае кражи средств у клиента в виду отсутствия надлежащей защиты, компания CourseMax ответственности за это не несет.
3.6 Клиент самостоятельно принимает решение о выборе инвестиционного плана, вкладываемой в проект суммы и осознает, что всякое инвестирование сопряжено с определенными рисками, а потому при любом результате не станет предъявлять претензий компании CourseMax.
3.7 Клиенту рекомендуется осуществлять периодический доступ к своему аккаунту в период действия инвестиционного плана для мониторинга безопасности своего аккаунта. В случае, если Вы обнаружите следы несанкционированного доступа к Вашему аккаунту, проведение финансовых операций, осуществленных без вашего ведома и т.п. немедленно обратитесь в службу поддержки компании или напрямую в администрацию.
3.8 Клиент обязан соблюдать установленные правила в полной мере. В случае нарушения любого из <a href="http://pkstemplate.ru/">положений</a>  данного документа, компания CourseMax вправе прекратить сотрудничество с данным клиентом в одностороннем порядке.
4. Права и обязанности администрации проекта компании CourseMax:
4.1 Компания CourseMax гарантирует своим клиентам предоставление всех заявленных услуг, в полном объеме и согласно указанным условиям.
4.2 Компания CourseMax обязуется обеспечивать работоспособность своего инвестиционного проекта, оказывать консультационную поддержку клиентам проекта, своевременно устранять любые технические неполадки, вызвавшие
затруднения работы клиента с сайтом проекта
4.3 Компания CourseMax гарантирует своим клиентам максимальный уровень защиты их личных данных и вкладываемых финансовых средств.
4.4 Компания CourseMax обязуется обеспечивать полную конфиденциальность личной информации клиента проекта, полученной при регистрации, а также за весь период участия клиента в проекте.
4.5 В случае выявления фактов мошенничества со стороны клиента (попытки взлома аккаунтов других клиентов, представление себя в качестве сотрудника CourseMax для привлечения новых клиентов по реферальной программе и др.) или нарушения любого из пунктов данного документа, администрация CourseMax имеет полное право в одностороннем порядке прекратить оказание инвестиционных услуг данному клиенту, заблокировать его аккаунт(ы) без возможности возврата имеющихся на нем денежных средств. В случае каких-либо мелких нарушений со стороны клиента или возникновения спорных ситуаций, администрация вправе временно заблокировать аккаунт клиента. В дальнейшем с ним свяжется представитель компании для разрешения сложившейся ситуации.
4.6 Администрация проекта CourseMax имеет право по своему усмотрению изменять содержание настоящего документа без предварительного согласия клиентов проекта. О планируемых изменениях клиентам будет сообщено в частном порядке или на сайте проекта. Клиент вправе не принимать нововведения, но тогда должен сообщить об этом в службу поддержки проекта или напрямую администрации. Сотрудничество с таким клиентом будет прекращено по обоюдному согласию.
4.7 Компания CourseMax обладает всеми правами на содержание и любую информацию и материалы, размещаемые на сайте проекта. Любое незаконное использование личной информации и интеллектуальной собственности компании преследуется законом о защите авторских прав.
5. Все условия получения прибыли (период действия плана, сумма депозита, процент прибыли, дополнительные возможности) клиентом оговорены в содержании инвестиционного плана, который клиент выбирает самостоятельно.
6. В случае действия форс-мажорных обстоятельств (любых обстоятельств, лежащих вне компетенции компании) администрация проекта CourseMaxосвобождается от ответственности перед своими клиентами за невыполнение всех заявленных функций и услуг. Клиент не имеет права предъявлять претензии и требовать от компании CourseMax материальной компенсации в случае, если по причине действия данных обстоятельств, результат участия клиента в проекте не будет соответствовать ожидаемому.
7. Конфликтные / спорные ситуации (не относящиеся к п.4.5. настоящего документа) любого рода между клиентом и администрацией проекта CourseMax должны решаться только путем переговоров с заключением впоследствии взаимовыгодного соглашения.</pre>
            </div>
		</div>
		
        </div>



        <div class="move-up" id="gotoup" style="display: none;"><a class="alpha-link trans" id="goup-btn" href="#brand"></a></div>
        <script type="text/javascript" src="/js/modernizr.js" defer="defer"></script>
<script type="text/javascript" src="/js/respond.src.js" defer="defer"></script>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/script.js"></script>
<script type="text/javascript" src="/js/pace.js" type="text/javascript"></script>        <script type="text/javascript" src="/js/jquery.flexslider.js"></script>
        <script type="text/javascript" src="/js/menu-switcher.js"></script>
        <script type="text/javascript" src="/js/wow.min.js" defer="defer"></script>
        <script type="text/javascript" src="/js/validation.js" defer="defer"></script>
		<!— BEGIN JIVOSITE CODE {literal} —>
<script type='text/javascript'>
(function(){ var widget_id = '7H3Lg2VVGi';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!— {/literal} END JIVOSITE CODE —>
        <script>
            $(document).ready(function() {
					
					$( "span #video" ).click(function() {
  var link =  $( this ).attr("linke");
  $( this ).html('<iframe style="max-height: 200px; height: 200px; border: 3px #AADA65 solid; width: 32.7%; margin: 4px 0px 0px 4px;" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe>')
});
				/*---------------- LightBox -----------------------*/
				
					function reposLightbox() {
						
						LoginW = $('#login-register-password').width();
						LoginH = $('#login-register-password').height();
	
						$('#login-register-password').css({ 
								top: '50%',
								marginLeft: -LoginW / 2,
								marginTop: -LoginH / 2
							}); 
												
					 }
				
                $('.btnBox').click(function(e) {
                    var BtnRef = $(this).attr('data-layer');
						  var Ref = $(this).attr('data-ref');
						  
                    $(".OverlayBox."+BtnRef).css('display', "block");
                    $(".MainWrapper").addClass('blur');
                    $(".OverlayBox."+BtnRef+" .LayerBox").addClass('fallDown');
						 
						 if($(this).hasClass("btnRegister")){
												$('#login-register-password').addClass('regCall');		
									}else{
										$('#login-register-password').addClass('logCall');
										}		
										 
								reposLightbox();			  
						 });
					
					/*---------------- Login -----------------------*/
					
					$('.btnRegister').click(function() {
							$('#RegBtn').trigger('click');
						 });
						 
					$('#loginLink').click(function() {
							$('#loginBtn').trigger('click');
						 });
						 
					 $("ul.tabs_login li:nth-child(1), ul.tabs_login li:nth-child(3)").click(function() {
																$('#login-register-password').removeClass('regCall');
													 });
						 
				    $('ul.tabs_login li:nth-child(2), ul.tabs_login li:nth-child(3)').click(function() { 
									       $('#login-register-password').removeClass('logCall');
											 }); 
											 
                $(".close-box, .continue-box").click(function(){
                                      $(".OverlayBox").hide();
                                      $(".MainWrapper").removeClass('blur');
                               });

                $('.inputCase input, .inputCase textarea').focusin(function() {
                    $(this).parent().addClass('icoRot');
                });
                $('.inputCase input').focusout(function() {
                    $(this).parent().removeClass('icoRot');
                });
                $(".tab_content_login").hide();
                $("ul.tabs_login li:first").addClass("active_login").show();
                $(".tab_content_login:first").show();
                $("ul.tabs_login li").click(function(e) {

                    $("ul.tabs_login li").removeClass("active_login");
                    $(this).addClass("active_login");
                    $(".tab_content_login").hide();
                    var activeTab = $(this).find("a").data("link");
                    if ($.browser.msie) {
                        $(activeTab).show();
								 reposLightbox();
                    }
                    else {
                        $(activeTab).show();
								 reposLightbox();
                    }
                    return false;
                });

                /*-------- Sliders -------*/
                $(window).load(function() {
             
                    $('.carrousel').flexslider({
                        animation: "slide",
                        animationLoop: true,
                        itemWidth: 400,
                        minItems: <?php print $numans;?>,
                        move: 1,
								start: function(carrousel) {
                            $('body').removeClass('loading');
                        }
                    });
						  
						   $('.flexslider').flexslider({
                        animation: "slide",
                        startAt: 0,
                        reverse: true,
						touch: true,
                        start: function(slider) {
                            $('body').removeClass('loading');
                        }
                    });
                });
                /*-------- WOW Animations -------*/
                new WOW().init();
                /*--------------------------------------------- SafariFix --------------------------------------*/
                if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
						  $('body').addClass('safariFix');
                }
                /*----------------------- Mensaje para navegadores Viejos ----------------------------*/
                if (navigator.appName.indexOf("Internet Explorer") != -1) {     //yeah, he's using IE
                    var badBrowser = (
                            navigator.appVersion.indexOf("MSIE 9") == -1 &&   //v9 is ok
                            navigator.appVersion.indexOf("MSIE 1") == -1  //v10, 11, 12, etc. is fine too
                            );
                    if (badBrowser) {
                        $('#actualizar').addClass('actualizar');
                        $('#actualizar').load('http://poweredbydokier.com/empiremoney_beta/old_nav/old_nav_es.php');	    
                    }
                }


                $("#nextStep").click(function() {
                    User = checkUser();
                    Pass = checkPass();
                    RePass = checkRePass();

                    Email = checkEmail();

                    if (!User && !Pass && !RePass && !Email)
                    {
                        nextStepTransition();
                     }
                });

                  $("#reload").click(function() {
                   
                    $("input[name=code]").val('');
                $('#captchaImg').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$('#captchaImg2').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$('#captchaImg3').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
                //$("#captchaImg").html('<img  src="captcha/CaptchaSecurityImages.php" style="" width="90" height="30">') ;

                 
                }); 
				$("#captchaImg2").click(function() {
                  
                $('#captchaImg').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$(this).attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$('#captchaImg3').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
                //$("#captchaImg").html('<img  src="captcha/CaptchaSecurityImages.php" style="" width="90" height="30">') ;

                 
                }); 
				$("#captchaImg3").click(function() {
                  
                $('#captchaImg').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$('#captchaImg2').attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
				$(this).attr('src', 'captcha.php?glav=1random=' + (new Date).getTime());
                //$("#captchaImg").html('<img  src="captcha/CaptchaSecurityImages.php" style="" width="90" height="30">') ;

                 
                }); 
                $("#endRegistration").click(function() {

                    FName = checkFName();
                    LName = checkLName();
                    Birthday = checkBirthday();
                    Captcha = checkCaptcha();

                    if (!FName && !LName && !Birthday && !Captcha)
                    {
                        
                        sendRegistration();
                    }
                });
                $("#btnContactMail").click(function() {

                if (isValidContactForm()) 
                {
                    $.ajax({
                        type: "POST",
                        url: "ajax/sendContactMail.php",
                        data: $("#contactForm").serialize(),
                        cache: false,
                        success:
                                function(data) {

                                    if (data <=501)
                                    {
                                        $("#ErrorMail").show();
                                    }
                                    else
                                    {
                                        $("input[name=name]").val('');
                                        $("input[name=email]").val('');
                                        $("input[name=subj]").val('');
                                        $('textarea[name=textform]').val('');
                                        $(".MainWrapper").addClass('blur');
                                        $("#ThanksMail").show();
                                    }
                                }
                    });
                }

                return false;
            });
            
            
            });

            /**********************************************************************************************************************/
            /***************************************** Validation functions *******************************************************/
            /**********************************************************************************************************************/

            function sendPassword()
            {
                
                
                Email= checkEmailRecPass( $("input[name=email-rec-pass]").val());
                if(Email)
                {
                    Captcha = checkCaptchaRecPass();
                    if (Captcha)
                    {
                        sendMailPassword();
                    }
                }
//                return false;
            }
            
            function sendMailPassword(){
                
                
               $("#ChangePassWrapper").addClass('Ajax');
               
                    $.ajax({
                        type: "POST",
                        url: "ajax/sendPassword.php",
                        data: {email: $("input[name=email-rec-pass]").val(), security_code:$("input[name=captcha-rec-pass]").val()},
                        async: false,
                        cache: false,
                        success:
                                function(data) {
                                    $('.close-box').trigger('click');
                                     $("#tab3_login").removeClass('Ajax');
                                    window.console.log(data);
                                    if (data == 1)
                                    {
                                       setTimeout(function() {
                                                $("#ExitoPass").css('display', "block");
                                            $(".MainWrapper").addClass('blur');
                                            $("#ExitoPass .LayerBox").addClass('fallDown');
                                        }, 300);
                                    }
                                    else
                                    {
                                         setTimeout(function() {
                                                $("#ErrorPass").css('display', "block");
                                                $(".MainWrapper").addClass('blur');
                                                $("#ErrorPass .LayerBox").addClass('fallDown');
                                        }, 300);

                                    }
                                }
                    });
  
         
                
                
            }
            
            function checkLoginForm() 
            {
                    var username = $("#userLogin").val();
                    var password = $("#passLogin").val();

                   if(username=="cpa"){
                     
                       $("#loginbox").attr('action', "http://<?php print $cfgURL;?>/login/index.php");   
                    } else {
                           $("#loginbox").attr('action', "http://<?php print $cfgURL;?>/login/index.php");
                   }
                    if(username != "" && password != "")
                    {
                            return true;
                    }
                    else
                    {
                            return false;	
                    }
            }
            
            
            
            function cleanStyles(input)
            {
                $("input[name="+input.name+"]").removeClass('error-contact');
            }
            function cleanStylesTA(input)
            {
                $("textarea[name="+input.name+"]").removeClass('error-contact');
            }
            function isValidContactForm() {

                var ret = true;

                fname = $("input[name=contact-fname]").val().length;
               
                lname = $("input[name=contact-lname]").val().length;
                email = $("input[name=contact-email]").val().length;
                message = $('textarea[name=contact-message]').val().length;

                $("input[name=contact-fname]").removeClass('error-contact');
                $("input[name=contact-lname]").removeClass('error-contact');
                $("input[name=contact-email]").removeClass('error-contact');
                $('textarea[name=contact-message]').removeClass('error-contact');

                if (fname < 2)
                {
                    $("input[name=contact-fname]").addClass('error-contact');
                    ret = false;
                }
                if (lname < 2)
                {
                    $("input[name=contact-lname]").addClass('error-contact');
                    ret = false;
                }
                if (email < 2)
                {
                    $("input[name=contact-email]").addClass('error-contact');
                    ret = false;
                }
                if (message < 2)
                {
                   $('textarea[name=contact-message]').addClass('error-contact');
                    ret = false;
                }
                return ret;
            }

            function sendRegistration()
            {
                $("#RegWrapper").addClass('Ajax');
               
                    $.ajax({
                        type: "POST",
                        url: "ajax/saveUser.php",
                        data: $("#regbox").serialize(),
                        async: false,
                        cache: false,
                        success:
                                function(data) {
                                    $('.close-box').trigger('click');
                                     $("#RegWrapper").removeClass('Ajax');
                                    if (data == 1)
                                    {
//                                       setTimeout(function() {
//                                            $("#ThanksReg").css('display', "block");
//                                            $(".MainWrapper").addClass('blur');
//                                            $("#ThanksReg .LayerBox").addClass('fallDown');
//                                        }, 300);
                                            window.location.replace("thanks.php");
                                    }
                                    else
                                    {
                                         setTimeout(function() {
                                                $("#ErrorReg").css('display', "block");
                                                $(".MainWrapper").addClass('blur');
                                                $("#ErrorReg .LayerBox").addClass('fallDown');
                                        }, 300);

                                    }
                                }
                    });
  
            }
            
            function checkEmailRecPass(email_val)
            {
                if(email_val!="")
                {
                    var ret=true;
                    $.ajax({
                        type: "POST",
                        url: "ajax/checkEmail.php",
                        data: {email: email_val},
                        async: false,
                        cache: false,
                        success:
                                function(data) {

                                    if (data == 1)
                                    {
                                        $("#RegEmail").attr('data-txt', 'E-mail не существует');
                                        $("#RegEmail").addClass('Wrong');
                                        ret=false;
                                    }


                                }
                    });
                    
                }  
                else{
                    $("#RegEmail").attr('data-txt', 'Введите свой E-mail');
                     $("#RegEmail").addClass('Wrong');
                     ret=false;
                }
                    return ret;
            }
            
            function checkCaptchaRecPass()
            {
                captcha = validCaptcha($("input[name=captcha-rec-pass]").val(), 0);

                if (!captcha)
                {
                    $("#RegEmail").attr('data-txt', 'Неверный код!');
                    $("#RegEmail").addClass('Wrong');
                    return  false;
                }
                else
                {
                    $("#RegEmail").attr('data-txt', '');
                    $("#RegEmail").removeClass('Wrong');
                    return true;
                }
            }
            
            
            function checkCaptcha()
            {
                captcha = validCaptcha($("input[name=captcha]").val(), 1);

                if (!captcha)
                {
                    $("#SecCodeReg").attr('data-txt', 'Проверочный код введен не верно!');
                    $("#SecCodeReg").addClass('Wrong');
                    form2Error = true;
                }
                else
                {
                    $("#SecCodeReg").attr('data-txt', "Введите код!");
                    $("#SecCodeReg").removeClass('Wrong');
                    form2Error = false;
                }
                return form2Error;
            }

            function validCaptcha(captcha, isRegisterForm)
            {

                $.ajax({
                    type: "POST",
                    url: "ajax/checkCaptcha.php",
                    data: {captcha: captcha, register:isRegisterForm},
                    async: false,
                    cache: false,
                    success:
                            function(data) {

                                if (data == 1)
                                {
                                    ret = true;
                                } else {
                                    ret = false;
                                }
                            }
                });

                return ret;
            }
            function optional(field, add)
            {
                $("#input-messagge-" + field).attr('data-tip', 'Opcional');
                if (add) {
                    $("#input-messagge-" + field).addClass('opt');
                } else {
                    $("#input-messagge-" + field).removeClass('opt');
                }
            }

            function checkFName()
            {

                fname = $("input[name=fname]").val();

                $("#input-messagge-fname").removeClass('error');
                $("#input-messagge-fname").removeClass('ok');

                if (fname.length < 1 || !validateOnlyLetters(fname))
                {
                    $("#input-messagge-fname").attr('data-tip', 'Только буквы');
                    $("#input-messagge-fname").addClass('error');
                    form2Error = true;
                }
                else
                {
                    $("#input-messagge-fname").attr('data-tip', "Успешно!");
                    $("#input-messagge-fname").addClass('ok');
                    form2Error = false;
                }
                return form2Error;
            }
            function checkLName()
            {

                lname = $("input[name=lname]").val();

                $("#input-messagge-lname").removeClass('error');
                $("#input-messagge-lname").removeClass('ok');

                if (lname.length < 1 || !validateOnlyLetters(lname))
                {
                    $("#input-messagge-lname").attr('data-tip', 'Только буквы');
                    $("#input-messagge-lname").addClass('error');
                    form2Error = true;
                }
                else
                {
                    $("#input-messagge-lname").attr('data-tip', "Успешно!");
                    $("#input-messagge-lname").addClass('ok');
                    form2Error = false;
                }
                return form2Error;
            }

            function checkEmail()
            {

                email = validateEmail($("input[name=email]").val());
                email_val = $("input[name=email]").val();

                $("#input-messagge-email").removeClass('error');
                $("#input-messagge-email").removeClass('ok');

                if (!email)
                {
                    $("#input-messagge-email").attr('data-tip', "E-mail введен не корректно!");
                    $("#input-messagge-email").addClass('error');
                    formError = true;
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        url: "ajax/checkEmail.php",
                        data: {email: email_val},
                        async: false,
                        cache: false,
                        success:
                                function(data) {

                                    if (data == 0)
                                    {
                                        dataTip = "El email ya existe";
                                        $("#input-messagge-email").attr('data-tip', "El email ya existe");
                                        $("#input-messagge-email").addClass('error');
                                        formError = true;
                                    }
                                    else
                                    {
                                        $("#input-messagge-email").attr('data-tip', "Correcto!");
                                        $("#input-messagge-email").addClass('ok');
                                        formError = false;
                                    }


                                }
                    });



                }
                return formError;
            }
            function checkUser()
            {

                username = checkUsername($("input[name=reg-username]").val());

                $("#input-messagge-reg-username").removeClass('error');
                $("#input-messagge-reg-username").removeClass('ok');

                if (username.exito == 0)
                {
                    $("#input-messagge-reg-username").attr('data-tip', username.text);
                    $("#input-messagge-reg-username").addClass('error');
                    formError = true;
                }
                else
                {
                    $("#input-messagge-reg-username").attr('data-tip', "Успешно!");
                    $("#input-messagge-reg-username").addClass('ok');
                    formError = false;
                }
                return formError;
            }
            function checkPass()
            {
                pass = $("input[name=pass]").val();
                $("#input-messagge-pass").removeClass('error');
                $("#input-messagge-pass").removeClass('ok');

                if (pass.length < 6)
                {
                    $("#input-messagge-pass").attr('data-tip', "Минимально 6 символов.");
                    $("#input-messagge-pass").addClass('error');
                    formError = true;
                }
                else
                {
                    $("#input-messagge-pass").attr('data-tip', "Успешно!");
                    $("#input-messagge-pass").addClass('ok');
                    formError = false;
                }

                return formError;

            }
            function checkRePass()
            {
                repass = $("input[name=repass]").val();
                pass = $("input[name=pass]").val();
                $("#input-messagge-repass").removeClass('error');
                $("#input-messagge-repass").removeClass('ok');

                if (pass != repass || repass == "")
                {
                    $("#input-messagge-repass").attr('data-tip', "Las contrasenas deben conicidir");
                    $("#input-messagge-repass").addClass('error');
                    formError = true;
                }
                else
                {
                    $("#input-messagge-repass").attr('data-tip', "Успешно!!");
                    $("#input-messagge-repass").addClass('ok');
                    formError = false;
                }
                return formError;
            }

            function nextStepTransition()
            {
				  $("#StepsWrapper").css("left","-100%"); 
            }
        </script>
</body></html>