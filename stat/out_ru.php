<?php
defined('ACCESS') or die();
?>
<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-bottom-arrow"></i><h3>История выплат</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>Дата</th>
<th>Сумма</th>
</tr>
							  	</thead><tbody>
<?php

	$sql	= 'SELECT * FROM output WHERE login = "'.$login.'" order by id DESC';
	$rs		= mysql_query($sql);
	if(mysql_num_rows($rs)) {

		while($a = mysql_fetch_array($rs)) {
				print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\" align=\"center\">
				<td align=\"center\">".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$a['sum']."</td>
				</tr>";
		}

	} else {
		print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\">Нет данных!</td></tr>";
	}
print "</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>";