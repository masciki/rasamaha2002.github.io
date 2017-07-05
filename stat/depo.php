<?php
defined('ACCESS') or die();
?>
<div class="col-md-8" style=" width: 100%;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style=" width: 100%;">
									<i class="pe-7s-up-arrow"></i><h3>Deposits history</h3>
								</div>
							</header>
							
							<div class="widget__content table-responsive">
								
								<table class="table table-striped media-table">
	<thead>
							  		<tr>
<th>Date</th>
<th>Name</th>
<th>The amount</th>
</tr>
							  	</thead><tbody>
<?php

	$sql	= 'SELECT * FROM deposits WHERE user_id = '.$user_id.' order by id DESC';
	$rs		= mysql_query($sql);
	if(mysql_num_rows($rs)) {

		while($a = mysql_fetch_array($rs)) {

				$row = mysql_fetch_array(mysql_query('SELECT name FROM plans WHERE id = '.$a['plan'].' LIMIT 1'));

				print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\" align=\"center\">
				<td align=\"center\">".date("d.m.Y H:i", $a['date'])."</td>
				<td>".$row['name']."</td>
				<td>".$a['sum']."</td>
				</tr>";
		}

	} else {
		print "<tr style=\"background-color: transparent;\" bgcolor=\"#ffffff\"><td colspan=\"3\" align=\"center\">There is no data!</td></tr>";
	}
print "</table>
</div> <!-- /widget__content -->
</article><!-- /widget -->
</div>";