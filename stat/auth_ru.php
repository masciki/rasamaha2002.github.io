<?php
defined('ACCESS') or die();

$ip			= getip();

function getCOUNTRY($ip) {
	$ipnum	= sprintf("%u", ip2long($ip));
    $result = mysql_query("SELECT cc FROM geoip_db WHERE start <= ".$ipnum." AND end >= ".$ipnum." LIMIT 1");
        if($result) {
			$row = mysql_fetch_array($result);
			if($row) {
				$cc = $row[cc];
			} else {
				$cc = "unknown";
			}
		} else {
			$cc = "unknown";
		}

return $cc;
}
?>
<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-key"></i><h3>История авторизаций</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>Дата</th>
<th>IP</th>
<th>Страна</th>
</tr>
							  	</thead><tbody>
<?php

	$sql	= 'SELECT * FROM logip WHERE user_id = '.$user_id.' order by id DESC';
	$rs		= mysql_query($sql);
	if(mysql_num_rows($rs)) {

		while($a = mysql_fetch_array($rs)) {
				$form_arr	= explode(',', $a['ip']);
				$ip			= trim($form_arr[0]);
				$country = getCOUNTRY($ip);
				print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\" align=\"center\">
				<td align=\"center\">".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$a['ip']."</td>
				<td><img src=\"/images/flags/".$country.".gif\" width=\"18\" height=\"12\" border=\"0\" alt=\"".$country."\" title=\"".$country."\" /> ".$country."</td>
				</tr>";
		}

	} else {
		print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\">Нет данных!</td></tr>";
	}
print "</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>";