<?php
if($login) {
defined('ACCESS') or die();
	print $body;

	$get_user_info = mysql_query("SELECT ref, ref_money FROM users WHERE id = ".$user_id." LIMIT 1");
	$row = mysql_fetch_array($get_user_info);
	 $ref			= $row['ref'];
	 $ref_money		= $row['ref_money'];	

	if($ref) {

		$get_user_info2	= mysql_query("SELECT login FROM users WHERE id = ".$ref." LIMIT 1");
		$row2 			= mysql_fetch_array($get_user_info2);
		 $uplogin	= $row2['login'];
		
		$get_user_info3	= mysql_query("SELECT icq FROM users WHERE id = ".$ref." LIMIT 1");
		$row3 			= mysql_fetch_array($get_user_info3);
		 $upicq	= $row3['icq'];
		$get_user_info4	= mysql_query("SELECT skype FROM users WHERE id = ".$ref." LIMIT 1");
		$row4 			= mysql_fetch_array($get_user_info4);
		 $upskype	= $row4['skype'];
		 
}
		if($upicq) {
			print " <p>ICQ Куратора: ".$upicq."</p>";}
        if($upskype) {
			print "<p>Skype Куратора:  ".$upskype."</p>";}
	
?>

<hr color="#cccccc" size="2">
<div class="col-md-8" style=" width: 100%; margin-top: -14px;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-network"></i><h3>Приглашенные вами рефералы</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
		<?php if($ref) {?><tr style="background-color: rgba(0, 0, 0, 0.25); border-bottom: 1px transparent solid;" bgcolor="#ffffff"><td colspan="4" align="center">Вас пригласил: <?php print $uplogin;?> </b></td></tr><?php }?>
	<tr style="background-color: rgba(0, 0, 0, 0.25); border-bottom: 1px transparent solid;" bgcolor="#ffffff"><td colspan="4" align="center">Ваша партнёрская ссылка: http://<?php print $cfgURL; ?>/?ref=<?php print $login; ?></td></tr>
							  		<tr>
<th>#</th>
<th>Логин</th>
<th>Skype</th>
<th>Доход</th>

</tr>
							  	</thead><tbody>
<?php

function PrintRef($refid, $i, $c, $refl) {
$sqlr	= 'SELECT id, sum FROM reflevels WHERE id = '.$refl;
	$rsr		= mysql_query($sqlr);
	$ar = mysql_fetch_array($rsr);

	$sql	= 'SELECT id, login,icq,skype, ref_money FROM users WHERE ref = '.$refid;
	$rs		= mysql_query($sql);
		$n 	= 1;
		while($a = mysql_fetch_array($rs)) {

			if($i == 1) {
				print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\" align=\"center\"><td>".$n."</td><td align=\"left\">".$a['login']."</font></td><td align=\"left\">".$a['skype']."</font></td><td>$".sprintf("%01.2f", $a['ref_money'])."</td></tr>";

				if($i <= $c) {
					PrintRef($a['id'], intval($i + 1), $c, intval($refl + 1));
				}

			} elseif($i <= 5) {
				print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\" align=\"center\"><td></td><td align=\"left\" style=\"padding-left: ".$i."0px;\"><font color=\"#999999\">L".$i."» ".$a['login']."</font></td><td align=\"left\">".$a['skype']."</td><td>$".sprintf("%01.2f", $a['ref_money'])."</td></tr>";

				if($i <= $c) {
					PrintRef($a['id'], intval($i + 1), $c, intval($refl + 1));
				}

			}
		$n++;
		}
		
}

	$countlvl = mysql_num_rows(mysql_query("SELECT * FROM reflevels"));

	PrintRef($user_id, 1, $countlvl, 1);

	$sql	= 'SELECT login, ref_money FROM users WHERE ref = '.$user_id;
	$rs		= mysql_query($sql);

	if(mysql_num_rows($rs)) {

		$m = 0;
		while($a = mysql_fetch_array($rs)) {
			$m = $m + $a['ref_money'];
		}

		print "<tr style=\"background-color: transparent;\" align=\"center\" bgcolor=\"#dddddd\"><td><td></td></td><td align=\"right\" colspan=\"1\" style=\"padding: 3px;\"><b>Всего:</b></td><td><b>".sprintf("%01.2f", $m)."</b></td></tr>";

	} else {
		print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\"><td colspan=\"5\" align=\"center\">Вы пока никого не пригласили!</td></tr>";
	}

print '</tbody>
</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>';
} else {
	print '<p class="er">Вам необходимо авторизироваться для доступа к данной странице</p>';;
}
?>