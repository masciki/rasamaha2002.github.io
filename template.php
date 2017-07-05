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
				$errormes = "<p class=\"er\" style=\"color:#000000;\">¬ведите пожалуйста ¬аше им€!</p>";
		}
		elseif(!$mail) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">¬ведите пожалуйста ¬аш e-mail!</p>";
		}
		elseif(!$subj) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">¬ведите пожалуйста тему ¬ашего сообщени€!</p>";
		}
		elseif(!$textform) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">¬ведите пожалуйста текст ¬ашего сообщени€!</p>";
		}
		elseif(!preg_match("/^[a-z0-9_.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|edu|gov|arpa|info|biz|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})$/is",$mail)) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">¬ведите пожалуйста ¬аш e-mail валидно!</p>";
		} elseif(!mysql_num_rows(mysql_query("SELECT * FROM captcha WHERE sid = '".$sid."' AND ip = '".getip()."' AND code = '".$code."'"))) {
			$errormes = "<p class=\"er\" style=\"color:#000000;\">Ќе правильно введЄн код!</b></font></center></p>";
		} else {

			$headers = "From: ".$mail."\n";
			$headers .= "Reply-to: ".$mail."\n";
			$headers .= "X-Sender: < http://".$cfgURL." >\n";
			$headers .= "Content-Type: text/html; charset=windows-1251\n";

			$textform = "«дравствуйте, ".$name."!<br />¬ы писали нам, указав e-mail: ".$mail.", как контактный следующее:<p> >".$textform."</p>";

			$send = mail($adminmail,$subj,$textform,$headers);

			if(!$send) {
				$errormes = "<p class=\"er\" style=\"color:#000000;\">ќшибка почтового сервера!<br />ѕриносим извинени€ за предоставленные неудобства.</p>";
			} else {

				$errormes = "<p class=\"erok\" style=\"color:#000000;\">¬аше сообщение отправлено!</p>";

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
                    <span class="center-wrap Active"><a class="center Active" href="#HeadSlider">Home</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page2">Why us?</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page3">News</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page4">Reviews</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#page5">FAQ</a></span>
                </li>
                <li>
                    <span class="center-wrap"><a class="center Inactive" href="#Contacto">Contacts</a></span>
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
                            <span class="center-wrap Active"><a class="center Active" href="#HeadSlider" data-link="#HeadSlider">Home</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page2" data-link="#page2">Why choose us?</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page3" data-link="#page3">Reviews</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page4" data-link="#page4">We accept</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#page5" data-link="#page5">FAQ</a></span>
                        </li>
                        <li>
                            <span class="center-wrap"><a class="center Inactive" href="#Contacto" data-link="#Contacto">Contacts</a></span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col3" id="login">
			<span class="display980"><a href="?lang=ru" style="color:white; text-decoration:none; font-size:20px;">–усский</a></span>
                <ul>
                   <li>
                        <span id="LangBtn" class="center-wrap trans  es"><a class="center" href="javascript:void(0)">English</a></span>
                        <div id="LangMenuWrapper" style="display:none">
                            <ul>
                                                                <li><a class="en" href="?lang=ru">–усский</a></li>							
                            </ul>
                        </div>
                    </li>
                    <li>
                        <span id="LoginBtn" class="center-wrap"><?php if(!$login) {?><a id="loginLink" class="center modal-link login btnBox" href="javascript:void(0)" data-layer="reg" data-ref="login">Login</a><?php } else {?><a id="loginLink" class="center modal-link btnBox login" href="/exit.php">Exit</a><?php }?></span>
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
                                  <h1 class="title white cursiva wow bounceInLeft animated animated" data-wow-delay="0.8s" style="visibility: visible; animation-delay: 0.8s; animation-name: bounceInLeft; font-family: 'PancettaPro-Regular';">Keep the right course on the road to wealth!</h1>
                                  <p class="col12 txt-center block wow bounceInRight animated animated" id="p1" data-wow-delay="1s" style="visibility: visible; animation-delay: 1s; animation-name: bounceInRight;">
                                         Steer your own future with our assistance.                                </p>
                                   <div class="wow pulse animated animated" data-wow-delay="1.2s" style="visibility: visible; animation-delay: 1.2s; animation-name: pulse;">
                                      <?php if (!$login) {?><a class="Bigbtn btn white regCall btnRegister btnBox" href="javascript:void(0)" data-layer="reg" data-ref="reg">Registration!</a><?php } else {?> <a class="Bigbtn btn white regCall btnRegister" href="/lk" data-layer="reg" data-ref="reg">To the cabinet!</a> <?php }?>
									  <?php if (!$login) {?><a class="displaymob Bigbtn btn white modal-link login btnBox" href="javascript:void(0)" data-layer="reg" data-ref="login">Login</a><?php } ?>
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
                                <h3 class="title green block">Package ЂO-maxї</h3>
                                <p class="white wrapper xlight">
                                    This package includes the total value of investments of $5 to $199 with 4.10% interest a day for 5 days.                              </p>
                                <a class="Smallbtn btn white" href="/deposit">Invest</a>
                            </div>
                            <div class="TresCol white txt-center">
                                <div class="icon-round block minicon-com">
                                </div>
                                <h3 class="title green block">Package ЂA-maxї</h3>
                                <p class="white wrapper xlight">
                                    This package includes the total value of investments of $200 to $999 with 5.20% interest a day for 15 days.                            </p>
                                <a class="Smallbtn btn white" href="/deposit">Invest</a>
                            </div>
                            <div class="TresCol white txt-center">
                                <div class="icon-round block minicon-rew">
                                </div>
                                <h3 class="title green block">Package ЂL-maxї</h3>
                                <p class="white wrapper xlight">
                                    This package includes the total value of investments of $1000 to $10000 with 6.30% interest a day for 30 days.                                </p>
                                <a class="Smallbtn btn white" href="/deposit">Invest</a>
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
						<div style="width: 100%; text-align: center; height: 280px;"><hr style="position: relative; border-radius: 50%; float: left; top: 148px; width: 20%; border: 3px solid #AADA65;"><hr style="position: relative; float: right; top: 103px; width: 20%; border-radius: 50%; border: 3px solid #AADA65;"><span id="videoof"><div id="videoinn" style="background: #AADA65; height: 115px; width: 45%; margin: 0 auto; position: relative; top: 105px; padding: 24px 24px; color: white; font-size: 24px; border-radius: 50%; border-width: 10px 10px 10px 10px; border-style: solid; cursor:pointer; border-color: #AADA65;"><span style="position: relative; top: 8px;">Official video</span></div></span></div>
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
                                    <h2>Why choose us?</h2>
                                    <p>
										- You regulate profits by yourself.<br>
- Fixed end time oftransaction.<br>
-ЂCourseMaxї company is suitable for both professionals and beginners.<br>
- Full access to the systemТscapabilities.<br>
-Personal Manager.<br>
- Payout is guaranteed at your convenience.<br>
- You do not have to go anywhere as the only thing you need is access to the Internet.<br>
- Stable exchange rate. You do not lose anything as it`s not a stock market and we do not alter the income depending on the dollar exchange rate.<br>
- Get free advice and trading signals from professional financial analysts of the ЂCourseMaxї company.<br>
- And the most important thing is that you will never have balance lower than the initial deposit, and you can withdraw funds at your convenience and by any convenient means.<br>
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
                                            <h3 class="title block">You control your income</h3>
                                            <p class="wrapper xlight">
It means that depending on a package you choose your income is varied. For example: "O-max" package includes the total value of investments of $5 to $199 with 4.10% interest a day during 5 days. This allows you to choose the sum you are willing to put to a deposit and calculate how much your capital will grow.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-green minicon-pork">
                                            </div>
                                            <h3 class="title block">Fixed end time of transaction</h3>
                                            <p class="wrapper xlight">
It involves unlimited access to CourseMax system, namely: your deposit is controlled only by you; you can withdraw funds by any convenient means; full control and the possibility to observe the clarity of CourseMax; payout guarantee at your convenience.                                            </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-yellow minicon-op">
                                            </div>
                                            <h3 class="title block">Full access to system facilities</h3>
                                            <p class="wrapper xlight">
It involves unlimited access to CourseMax system, namely: your deposit is only controlled by you; you can withdraw funds by any convenient means; full control and the possibility to observe the clarity of CourseMax; payout guarantee at your convenience.                                          </p>
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-violet minicon-reward">
                                            </div>
                                            <h3 class="title block">Personal manager</h3>
                                            <p class="wrapper xlight">
This service includes: you can consult about anything with your personal manager and ask any questions you have; you get economics tips from the companyТs financial analysts. For instance: УHow can I top up account balance for deposit?Ф УWhat package is more profitable for me?Ф This service is extremely convenient for both newbies and professionals in the field. 
                                        </li>
                                        <li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-blue2 minicon-light2">
                                            </div>
                                            <h3 class="title block">Convenient schedule and means of work</h3>
                                            <p class="wrapper xlight">
You donТt even have to leave your flat; all you need is access to the Internet. You can pick your schedule at your convenience: in the morning or in the evening, one or three hours, what package to pick is also your choice. Now you are your own boss.                                            </p>
                                        </li>
										<li style="width: 382px; float: left; display: block;">
                                            <div class="icon-round block back-red minicon-light">
                                            </div>
                                            <h3 class="title block">Stable exchange rate</h3>
                                            <p class="wrapper xlight">
This facility guarantees that you donТt lose anything, because itТs not a stock market and we do not alter the interest depending on the dollar exchange rate. That is why we offer you an investment platform for income. System we are working with is the fastest growing niche in a deposit field. Your deposits are independent of the dollar exchange rate or any economic changes taking place in banks or stock markets. It is yet another confirmation of the fact that deposit in CourseMax is a stable and beneficial step for those who wish to multiply their income.                                            </p>
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
                                        <h2 class="title wow bounceInLeft animated" style="visibility: hidden; animation-name: none;">Here you <a href="/answers" style="color:#aada65;"><u>can add</u></a> your review </h2>
                                        <p class="xlight wow bounceInRight animated" style="visibility: hidden; animation-name: none;">
                                        Leave a vivid and informative rewiew and get a chance to have a money reward.                                       </p>
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
                                                <h3 class="title block green"> Writes '.$row['username'].'</h3>
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
											</script>';
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
                                    <h2>We work with the most popular and reliable payment systems</h2>
                                    <p class="xlight">
                                    PerfectMoney -  is a service that allows its users to make instant payments and financial transactions via the Internet and that opens unique possibilities for the Internet users and the owners of online-business. The aim of Perfect Money is to lead the Internet transactions to the highest level.                                    </p>
                                    <p class="xlight">
                                    Bitcoin - is a virtual currency regulated only via the Internet, where УbitФ stands for a unit of information. The main advantage of Bitcoin is that they cannot be overproduced for their supply growth is controlled by an algorithm and is not subordinated to any of the governments.                                    </p>
									<p class="xlight">
                                    PAYEER -  is an international electronic payment system. To be more exact, itТs a full-fledged payment portal with fast registration, convenient interface, versatile facilities and functions in demand.                                    </p>
                                </div>
                                <div class="col8 txt-center">
                                    <div class="slider wow bounceInUp animated" data-wow-delay="0.5s" id="RoundSlider" style="visibility: hidden; animation-delay: 0.5s; animation-name: none;">
                                        <div class="flexslider">
                                            
                                        <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1200%; transition-duration: 0.6s; transform: translate3d(0px, 0px, 0px);">
										<li class="flex-active-slide" style="width: 400px; float: left; display: block;">
                                                    <div id="EmpireLogo">
                                                    </div>
													<p>PAYEER - unites numerous payment systems</p>
                                                     <a class="btn Smallbtn" href="https://payeer.com">Payeer</a>
                                                </li><li class="" style="width: 400px; float: left; display: block;">
												<div id="RoyalLogo">
                                                    </div>
													<p>PerfectMoney Ц the most reliable dollar storage.</p>
                                                    <a class="btn Smallbtn" href="https://perfectmoney.is/">PerfectMoney</a>
                                                </li><li class="clone" style="width: 400px; float: left; display: block;">
												<div id="MakeMoney">
                                                    </div>
													<p>Bitcoin Ц the most popular cryptocurrency on the Internet.</p>
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
							                                    <h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none; color: rgb(51, 51, 51); margin-top: -70px;">Video Reviews our investors</h1>
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
								<h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none; color: rgb(255, 255, 255); margin-top: -45px;">Talking about figures</h1>
                                    <h1 class="title txt-center cursiva white wow bounceInLeft animated" style="visibility: hidden; animation-name: none;"></h1>
                                    <p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Members<br> <span id="userss"><?php print $investors;?></span></p>
									<p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Money invested<br> <span id="inves">$<?php print sprintf("%01.2f", $alldep);?></span></p>
									<p class="wow bounceInRight animated" style="visibility: hidden; animation-name: none; width:32%; margin-right: 5px; float:left; font-size: 29px; background: rgba(26, 96, 25, 0.25); border-radius: 50px;">
                                    Total payout<br> <span id="withd">$<?php print sprintf("%01.2f", $allout);?></span></p>
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
								<h3 class="title green" style="margin-left: -5px;">What countriesТ citizens can participate?</h3>
                                    <p class="wow bounceInLeft animated" style="visibility: hidden; animation-name: none; margin-left: 25px;">
                                    Citizens of all countries are allowed to join the program.                                   </p>
                                    <div class="GreenCirlce wow bounceInLeft animated en" data-wow-delay="0.6s" id="gc1" style="visibility: hidden; animation-delay: 0.6s; animation-name: none;">
                                    </div>
                                </div>
                                <div class="col6 top wrapper">
                                    <div class="GreenCirlce wow bounceInRight animated en" id="gc2" style="visibility: hidden; animation-name: none;">
                                    </div>
									<h3 class="title green">What are the minimal and the maximal deposits?</h3>
                                    <p class="wow bounceInRight animated" data-wow-delay="0.6s" style="visibility: hidden; animation-delay: 0.6s; animation-name: none;">
                                    Minimal deposit is $5. Maximal deposit is %10 000                                    </p>
                                </div>
                                <div class="col6">
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1s" style="visibility: hidden; animation-delay: 1s; animation-name: none;">
                                        <h3 class="title green">How can I start working with COURSEMAX?</h3>
                                        <p class="white xlight">
Fill in a simple registration form and then make your first deposit in the correspondent section in your private cabinet. After that all the investment routine will be done by the company leaving you nothing but to receive incomes.                                        </p>
                                    </div>
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1.1s" style="visibility: hidden; animation-delay: 1.1s; animation-name: none;">
                                        <h3 class="title green">How fast can I get a payout?</h3>
                                        <p class="white xlight">
Payout applications are processed automatically via PAYEER and Bitcoin. However, it only takes a couple of hours via PerfectMoney for it is done manually.                                        </p>
                                    </div>
                                    <div class="wrapper block wow bounceInRight animated" data-wow-delay="1.2" style="visibility: hidden; animation-name: none;">
                                        <h3 class="title green">Can I earn without placing a deposit?</h3>
                                        <p class="white xlight">
Sure! There is a five-level partnership program for the active participants. You gain 6%-3%-1%-1%-1% income for every member in your structure.                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="Master" id="Contacto">
                    <article class="row">
                        <div class="col16" style="display:table;">
                            <h2 style="text-align: center;">Contacts</h2>
                            <div class="Master w100">
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
									   <img onclick="window.open('https://goo.gl/G8fHRI')" border=1 src="/img/rate.jpg" style="cursor:pointer;"  alt="Rate Our status on all hyip monitors"><br>
									   <img onclick="window.open('https://goo.gl/oUFSW8')" border=1 src="/img/mmgp.gif" style="cursor:pointer;" width="120px" height="61px" alt="Money Maker Group - CourseMax">
									   </center>
                                    </div>
                                    <div class="col8 top">
                                        <form action="?action=message" id="contactForm" method="post">
                                            <ul>
                                                <li>
                                                    <h3>Contact us</h3>
                                                </li>
                                                <li>
                                                    <input class="trans" name="name" placeholder="Your name" value="<?php if(!$name) { print $login; } else { print $name; } ?>" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
                                                <li>
                                                    <input class="trans" name="mail" placeholder="E-mail" value="<?php if(!$mail) { print $user_mail; } else { print $mail; } ?>" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
												<li style="margin-top: -15px;">
                                                    <img id="captchaImg3" style="cursor:pointer;height: 40px;position: relative;top: 15px;" src="/captcha.php?glav=1"><input class="trans" name="code" placeholder="Code" onfocus="cleanStyles(this)" style="width: calc(100% - 95px);" type="text">
                                                </li>
                                                <li>
                                                    <input class="trans" name="subj" placeholder="Message topic" onfocus="cleanStyles(this)" style="width: 100%;" type="text">
                                                </li>
                                                <li>
                                                    <textarea class="trans" cols="" name="textform" onfocus="cleanStylesTA(this)" style="width: 100%;" placeholder="Text" rows=""></textarea>
                                                </li>
                                                <li>
                                                    <input class="trans" name="send" id="btnContactMail" type="button"  onClick='submit();' style="width: 100%;" value="Send">
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
				<li><a href="#HeadSlider">Home</a></li>
				<li><a href="#page2">Why choose us ?</a></li>
				<li><a href="/news">News</a></li>
			</ul>
		</div>
		<div class="TresCol txt-center white">
			<img alt="CourseMax" height="45" src="/images/logo-head.png" width="241">
		</div>
		<div class="TresCol txt-right white">
			<ul>
				<li><a href="#page3">Rewiews</a></li>
				<li><a href="#page5">FAQ</a></li>
				<li><a href="#Contacto">Contacts</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="Master" id="copyright">
	<div class="row">
		<div class="col16 green txt-center">
			 Copyright © 2015 CourseMax.net<br>
			<a class="alpha-link btnBox" href="javascript:void(0)" data-layer="TyC">Rules</a>
         <span>Х</span>
			<a class="alpha-link btnBox" href="http://velgerstudio.com/">By VelgerStudio</a>
		</div>
	</div>
</div>
</footer>            <!----------- / Footer ---------------->
        </div>
         <div class="loginOverlay OverlayBox reg" id="loginOverlay" style="display:none">
<a href="javascript:void(0)" class="close-box">Close</a>
<div id="login-register-password" class="LayerBox reg">
    <ul class="tabs_login">
        <li id="loginBtn" class="active_login"><a data-link="#tab1_login" href="javascript:void(0)">Sign in</a></li>
        <li id="RegBtn" class="tabBtn"><a data-link="#tab2_login" href="javascript:void(0)">Registration</a></li>
        <li id="RecBtn"><a data-link="#tab3_login" href="javascript:void(0)">Forgot password?</a></li>
    </ul>
    <div class="tab_container_login">
        <div id="tab1_login" class="tab_content_login" style="display: block;">
            <div id="LoginWrapper" class="" data-wrong="" data-btn="Continue">
                <form action="/login/index.php" class="login_form modal" id="loginbox" method="post">
                    <div class="inputCase" id="UserName">
                        <input type="text" placeholder="Login" id="userLogin" name="user">
                    </div>
                    <div class="inputCase" id="PassWord">
                        <input type="password" placeholder="Password" id="passLogin" name="pass">
                    </div>
                    <div class="inputCase" id="LogIn">
                        <button type="submit" class="title">Login</button>
                    </div>
                </form>
            </div>
            <div id="logoWrapper"></div>     
        </div>
        <div id="tab2_login" class="tab_content_login register" style="display:none;">
            <div id="RegWrapper">
                <p style="text-align:center;">
                When registering you agree to the <a class="alpha-link btnBox" href="javascript:void(0)" data-layer="TyC">terms</a> our project.           
											<?php if($referal) {
		print '<br><p style="background: #B7D1A2; text-align: center; color: #5B823D;">Your inviter: '.$referal.'</p>';
	}?></p>
                <div class="RegForm"> 
                    <form action="/registration/?action=save" method="post" class="login_form modal" id="regbox">
						<input type="hidden" name="yes" value="1">
                        <div id="StepsWrapper">
                       		 <div id="step1">
                            <div class="inputCase user" style="width: 100%; margin-bottom: 2px;">
                                <input type="text" placeholder="Login" autocomplate="off" name="ulogin" style="width: 100%;">
                            </div>

                            <div class="inputCase pass" style="width: 100%; margin-bottom: 2px;">
                                <input type="password" placeholder="Password" autocomplate="off" name="pass" style="width: 100%;">
                            </div>

                            <div class="inputCase rePass" style="width: 100%; margin-bottom: 2px;">
                                <input type="password" placeholder="Repeat password" autocomplate="off" name="repass" style="width: 100%;">
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
<div id="SecCodeReg" data-txt="Verification code " class="">
                                <div class="col3">
                                    <img id="captchaImg" style="height:37px; top:2px;" src="/captcha.php?glav=1" width="90" height="30">
                                </div>
                                <div id="reload" class="col2" data-txt="Reload code"></div>
                                <div class="col11">
                                    <input type="text" style="height:38px;" placeholder="Enter that code here" name="code">
                                </div>
                            </div>
							
                            <button id="nextStep" type="submit" class="trans title">Create an account</button>
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
                <h4 class="title">Forgot your password?</h4>
                <p>
                A new password will be sent to your e-mail.                </p>
                <form class="login_form modal" id="loginbox" action="/reminder/?action=send" method="post">
                    <div id="RegLogin" data-txt="Codigo incorrecto">
                        <input type="text" name="ulogin" placeholder="Login">
                    </div>
					<div id="RegEmail" data-txt="Codigo incorrecto">
                        <input type="email" name="email" placeholder="E-mail">
                    </div>
                    <div id="SecCodeWrapper" class="txt-left">
						<div class="col3" id="SecCode">
                            <img id="captchaImg2" style="cursor:pointer;" src="/captcha.php?glav=1" width="90" height="30">
                        </div>
						<div class="col11">
                        <input name="code" style="margin-left:92px; width:250px;" type="text" placeholder="Verification code">
						</div>
                    </div>
                    <div class="inputCase" id="LogIn">
                        <button type="submit" class="title">Send password</button>
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
        			
           	<h1>Rules and convention</h1>
            <div class="block" style="font-size:15px;">
                <p>Before registering the company CourseMax, administration of the company strongly recommends to read the documents below. It covers all the basic, binding both parties rules, rights and responsibilities, as well as the conditions of customer interaction with the company's investment projects CourseMax Sign in project companies CourseMax only if unconditional acceptance of the following provisions.
Client - a person who is fully agree with the terms of the current agreement, promptly registered on the website CourseMax
The project - web-resource, located at CourseMax.net</p>
            </div>
            <div class="block">
            	<pre style="font-size:13px;">1. The right to create an account in the framework of the investment project CourseMax are all individuals that do not belong to the following categories:
1.1 Minors ( under the laws of the country of residence );
1.2 Those found on the basis of the medical report incompetent;
2. The responsibility for making the decision to join the project company CourseMax, for all their actions in the framework of the project (compliance / non- compliance with the rules , financial transactions , etc.) and their consequences lies entirely on the client.
3. Rights and obligations of the project company's customer CourseMax:
3.1 Each client project equally with other similar clients has the right to enjoy all the services provided by him to the project company CourseMax: use of a private office on the project site , to invest and to earn income under the terms of the investment plan , use the bonus referral program design , seek help support the project.
3.2 The client has the right to create and manage accounts within the project. If the administration of the project will be set the fact that the client will manage multiple accounts associated referral program, all his accounts are immediately blocked , and the available funds will be transferred at the disposal of the company, as payment of moral damages for fraud in its relation.
3.3 The Customer undertakes to ensure full confidentiality of any information received from the administration of the company.
3.4 Customer agrees not to disclose their registration information to third parties . If the customer has given your access to third parties , which resulted in further to cracking and loss account funds , the responsibility rests only on himself.
3.5 The client is obliged to provide protection for your computer , tablet , smartphone or any other device that accesses your account. In case of theft of funds from the customer due to lack of adequate protection , the company CourseMax responsibility for it can not be held.
3.6 The client alone decides on the choice of the investment plan , the amount contributed to the project and is aware that any investment involves certain risks , and therefore for any resulting claim will not become the company CourseMax.
3.7 Customer recommended to perform periodic access to his account during the term of the investment plan for monitoring the safety of your account. If you find traces of unauthorized access to your account , financial transactions undertaken without your knowledge , etc. immediately contact the customer support of the company or directly to the administration.
3.8 The customer is obliged to comply with the rules in full. In case of violation of any provision of this document , the company has the right to terminate CourseMax cooperation with the client to unilaterally.
4. Rights and duties of administration of the project company CourseMax:
4.1 CourseMax company guarantees its customers the supply of all the declared services , in full and in accordance with specified conditions.
4.2 CourseMax Company undertakes to provide performance of the investment project to provide consulting support to clients of the project in a timely manner to eliminate any technical problems that caused
difficulties with the site of the client project.
4.3 CourseMax company guarantees its customers the highest level of protection of their personal data , and invested funds.
4.4 CourseMax Company undertakes to ensure privacy of personal information of the client project produced at registration as well as for the entire period of the client's participation in the project.
4.5 In case of fraud on the part of the customer ( attempts to hack accounts of other clients , representing himself as an employee CourseMax to attract new customers by referral program , etc . ) Or violation of any of the paragraphs of this document , the administration CourseMax has every right to unilaterally terminate the provision of investment services to this client , block the account (s) without the possibility of return available on its cash. In the event of any minor violations on the part of the client or of a dispute, shall be entitled to temporarily block the administration account of the client. In the future, it will be contacted by a representative of the company to resolve the situation.
4.6 Administration of the project CourseMax has the discretion to modify the contents of this document without the prior consent of the customer project. On the planned changes will be communicated to clients in private or on the project site . The client has the right not to accept innovation , but then must inform the customer service or directly to the project administration. Cooperation with the client is terminated by mutual consent.
4.7 CourseMax company has all the rights to the content and any information and materials posted on the project website . Any unauthorized use of personal information and the company's intellectual property is punishable by law on copyright protection.
5. All terms and conditions of the profit (the period of the plan , the amount of the deposit , interest income , additional features ) specified by the client in the content of the investment plan , which the client chooses their own.
6. In the case of force majeure ( any circumstances beyond the control of the company ) the administration of the project CourseMaxosvobozhdaetsya responsibility to its customers for the failure to implement all declared functions and services. The customer has no right to complain and to demand from the company CourseMax financial compensation in the event that because of such circumstances , the result of the client's participation in the project will not match the expected.
7. Conflicts / disputes (not included in item 4.5 . Of this document) of any kind between the client and the administration of the project CourseMax should be resolved only through negotiations with the conclusion of mutually beneficial agreements later.</pre>
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
		<!Ч BEGIN JIVOSITE CODE {literal} Ч>
<script type='text/javascript'>
(function(){ var widget_id = '7H3Lg2VVGi';
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
<!Ч {/literal} END JIVOSITE CODE Ч>
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
                if (navigator.appName.indexOf("Internet Explorer") != -1) { † † //yeah, he's using IE
                    var badBrowser = (
                            navigator.appVersion.indexOf("MSIE 9") == -1 && † //v9 is ok
                            navigator.appVersion.indexOf("MSIE 1") == -1† //v10, 11, 12, etc. is fine too
                            );
                    if (badBrowser) {
                        $('#actualizar').addClass('actualizar');
                        $('#actualizar').load('http://poweredbydokier.com/empiremoney_beta/old_nav/old_nav_es.php');	† † 
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
                    $("#RegEmail").attr('data-txt', '¬ведите свой E-mail');
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
                    $("#RegEmail").attr('data-txt', 'Ќеверный код!');
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
                    $("#SecCodeReg").attr('data-txt', 'ѕроверочный код введен не верно!');
                    $("#SecCodeReg").addClass('Wrong');
                    form2Error = true;
                }
                else
                {
                    $("#SecCodeReg").attr('data-txt', "¬ведите код!");
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
                    $("#input-messagge-fname").attr('data-tip', '“олько буквы');
                    $("#input-messagge-fname").addClass('error');
                    form2Error = true;
                }
                else
                {
                    $("#input-messagge-fname").attr('data-tip', "”спешно!");
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
                    $("#input-messagge-lname").attr('data-tip', '“олько буквы');
                    $("#input-messagge-lname").addClass('error');
                    form2Error = true;
                }
                else
                {
                    $("#input-messagge-lname").attr('data-tip', "”спешно!");
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
                    $("#input-messagge-reg-username").attr('data-tip', "”спешно!");
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
                    $("#input-messagge-pass").attr('data-tip', "ћинимально 6 символов.");
                    $("#input-messagge-pass").addClass('error');
                    formError = true;
                }
                else
                {
                    $("#input-messagge-pass").attr('data-tip', "”спешно!");
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
                    $("#input-messagge-repass").attr('data-tip', "”спешно!!");
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