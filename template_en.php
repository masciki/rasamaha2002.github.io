<?php
defined('ACCESS') or die();

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

if(!$login && ($idpg <> 3 && $idpg <> 4)) {
	print'<meta http-equiv="refresh" content="0;/login/?mes=nopage">';
	exit();
}

if(cfgSET('cfgOnOff') == "off" && $status != 1) {
	include "includes/errors/tehwork.php";
	exit();
} elseif(cfgSET('cfgOnOff') == "off" && $status == 1) {
	print '<p align="center" class="warn">Сайт отключен и недоступен для остальных пользователей!</p>';
}
$sql8	= 'SELECT * FROM users WHERE login = "'.$login.'" LIMIT 1';
$rs8		= mysql_query($sql8);
$a8		= mysql_fetch_array($rs8);

$resultd	= mysql_query("SELECT * FROM deposits WHERE user_id = ".$user_id." ORDER BY id ASC");
while($rowd = mysql_fetch_array($resultd)) {
	$alldep = $alldep + $rowd['sum'];
	}

$resulto	= mysql_query("SELECT * FROM `output` WHERE `login` = '".$login."' AND status = '2' ORDER BY id ASC");
while($rowo = mysql_fetch_array($resulto)) {
	$allout = $allout + $rowo['sum'];
	}
	
if($status == 1) {
$imga = '/img/profile.jpg';
$statuus = '<span style="color:#E29191;"><i>Administrator</i></span>';
} elseif($status <> 1 && $alldep <= 0) {
$imga = '/img/profilen.jpg';
$statuus = '<span style="color:#ffffff;"><i>Member</i></span>';
} elseif($status <> 1 && $alldep > 0 && $alldep <= 999) {
$imga = '/img/profile1.jpg';
$statuus = '<span style="color:#91E293;"><i>Investor</i></span>';
} elseif($status <> 1 && $alldep >= 1000) {
$imga = '/img/profile2.jpg';
$statuus = '<span style="color:#BE66FF;"><i>VIP Investor</i></span>';
}
?>

<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Private office - <?php print $login;?></title>
	
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">

	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/main.min.css">
	<script src="/js/jquery.min.js"></script>
	
	<!-- Pixeden Icon Fonts -->
	<link rel="stylesheet" type="text/css" href="/css/pe-icon-7-filled.css">
	<link rel="stylesheet" type="text/css" href="/css/pe-icon-7-stroke.css">
</head>
<body>
	<div id="loading" style="display: none;">
		<div class="loader loader-light loader-large"></div>
	</div>
	<header class="top-bar">
		
		<ul class="profile"> 
			<li>
				<a href="#" class="btn-circle no-circle">
					<i class="pe-7f-back"></i>
				</a>
			</li>
			<li style="width: 50px;">
				<a href="/news" class="btn-circle no-circle">
					<i class="pe-7f-news-paper"></i>
				</a>
			</li>
			<li style="width: 50px;">
				<a href="/exit.php" class="btn-circle no-circle">
					<i class="pe-7f-power"></i>
				</a>
			</li>
			<li class="mobile-nav">
				<a href="#" onclick="return false;" class="btn-circle btn-sm">
					<i class="pe-7f-menu"></i>
				</a>
			</li>
		</ul>

		<div class="main-brand">
			<div class="main-brand__container" style="width: calc(100% - 50px);">
				<div class="main-logo" style=" font-size: 27px; margin: 16px 0 16px 16px;"><a href="/lk/" style="text-decoration:none; color:#ffffff;"><span style="font-family:Mouse; font-weight: 700;">COURSE</span><span style="font-family:Berlin Sans FB;">MAX</span></a></div>
			</div>
		</div>
		
	</header> <!-- /top-bar -->


	<div class="wrapper">

		<aside class="sidebar">
			
			<div class="user-info">
					<figure class="rounded-image profile__img">
						<img class="media-object" style="width: 100%; height: 100%;" src="<?php print $imga;?>" alt="<?php print $login;?>">
					</figure>
					<h2 class="user-info__name"><?php print $login;?></h2>
					<h3 class="user-info__role"><?php print $statuus;?></h3>
					<ul class="user-info__numbers">
						<li>
							<i class="pe-7s-cash"></i>
							<p><?php print '$'.sprintf ("%01.2f", str_replace(',', '.', ($a8['lr_balance'] + $a8['pm_balance'] + $a8['bt_balance'])));?></p>
							<p>Balance</p>
						</li>
						<li>
							<i class="pe-7s-up-arrow"></i>
							<p><?php print '$'.sprintf ("%01.2f", str_replace(',', '.', ($alldep)));?></p>
							<p>Deposits</p>
						</li>
						<li>
							<i class="pe-7s-bottom-arrow"></i>
							<p><?php print '$'.sprintf ("%01.2f", str_replace(',', '.', ($allout)));?></p>
							<p>Withdrawals</p>
						</li>
					</ul>
				</div> <!-- /user-info -->

				<ul class="main-nav">
					<li>
						<a class="main-nav__link" href="/">
							<span class="main-nav__icon"><i class="pe-7f-home"></i></span>
							Home
						</a>
					</li>
					<li class="<?php if($page == 'lk') {print 'main-nav--active';} else {print '';}?>">
						<a class="main-nav__link" href="/lk">
							<span class="main-nav__icon"><i class="pe-7f-graph1"></i></span>
							Stats
						</a>
					</li>
					<li class="main-nav--collapsible">
						<a class="main-nav__link" onclick="return false;">
							<span class="main-nav__icon"><i class="pe-7f-wallet"></i></span>
							Investments <span class="badge badge--line badge--blue">3</span>
						</a>
						<ul class="main-nav__submenu">
							<li><a href="/deposit"><span>Make a contribution</span></a></li>
							<li><a href="/deposits"><span>Your contributions</span></a></li>
							<li><a href="/stat"><span>History</span></a></li>
						</ul>
					</li>
					<li class="main-nav--collapsible">
						<a class="main-nav__link" onclick="return false;">
							<span class="main-nav__icon"><i class="pe-7f-cash"></i></span>
							Finances <span class="badge badge--line badge--blue">2</span>
						</a>
						<ul class="main-nav__submenu">
							<li><a href="/enter"><span>Add funds</span></a></li>
							<li><a href="/withdrawal"><span>Withdrawal</span></a></li>
						</ul>
					</li>
					<li class="main-nav--collapsible">
						<a class="main-nav__link" onclick="return false;">
							<span class="main-nav__icon"><i class="pe-7f-ribbon"></i></span>
							Reviews <span class="badge badge--line badge--blue">2</span>
						</a>
						<ul class="main-nav__submenu">
							<li><a href="/answers"><span>Reviews</span></a></li>
							<li><a href="/video"><span>Video-reviews</span></a></li>
						</ul>
					</li>
					<li class="main-nav--collapsible">
						<a class="main-nav__link" onclick="return false;">
							<span class="main-nav__icon"><i class="pe-7f-wallet"></i></span>
							Your referrals <span class="badge badge--line badge--blue">2</span>
						</a>
						<ul class="main-nav__submenu">
							<li><a href="/ref"><span>Your referral list</span></a></li>
							<li><a href="/banners"><span>Promotional materials</span></a></li>
						</ul>
					</li>
					<li class="<?php if($page == 'profile') {print 'main-nav--active';} else {print '';}?>">
						<a class="main-nav__link" href="/profile">
							<span class="main-nav__icon"><i class="pe-7f-config"></i></span>
							Settings
						</a>
					</li>
					<li>
						<a class="main-nav__link" href="/exit.php">
							<span class="main-nav__icon"><i class="pe-7f-power"></i></span>
							Exit
						</a>
					</li>
				</ul> <!-- /main-nav -->
			
		</aside> <!-- /sidebar -->
		
		<section class="content">

					<?php
	defined('ACCESS') or die();
	if(!$page) {
		include "includes/index.php";
	} elseif(file_exists("../".$page."/index.php")) {

		include "../".$page."/".$page.".php";
	} else {
		include "includes/errors/404.php";
	}
?>

			<footer class="footer-brand">
				<img style="width: 180px; position: relative; height: 25px; left: 0px;" src="/img/logo.png">
				<p>© <?php print date('Y');?> CourseMax. All rights reserved</p>
			</footer>


		</section> <!-- /content -->

	</div>


	<script type="text/javascript" src="/js/main1.js"></script>

</body></html>