<div class="col-md-8" style=" width: 100%; margin-top: -14px;">
						<article class="widget">
							<header class="widget__header one-btn">
								<div class="widget__title" style="width: 100%;">
									<i class="pe-7f-news-paper"></i><h3>News</h3>
								</div>
							</header>
							<div class="widget__content table-responsive">
								<table class="table table-striped media-table">
							  	<thead>
							  		<tr>
							  			<th width="270">Name</th>
							  			<th width="120">Date</th>
							  			<th>Description</th>
										<?php if($status == 1){?><th>Ed.</th><?php }?>
							  		</tr>
							  	</thead>
							  	<tbody>
<?php
defined('ACCESS') or die();
if(!intval($_GET[id])) {
function show_topics ($id, $subj, $msg, $date, $status)
{
	$text = substr($msg, 0, 1000);
	$text = str_replace("\n", "", $text);
	$text = preg_replace('/(<)(.+?)(>)/', '', $text);
	$text = substr($text, 0, 500);

	print'								  		<tr class="spacer"></tr><a href="?id='.$id.'">
							  		<tr>
							  			<td>
							  				<div class="media">
													<figure class="pull-left post__img">
														<img width="50px" height="50px" class="media-object" src="/img/profile.jpg" alt="user">
													</figure>
													<div class="media-body post_desc">
														<h3>'.$subj.'</h3>
													</div>
												</div>
							  			</td>
							  			<td>
							  				<p class="post__date">'.$date.'</p>
							  			</td>
							  			<td class="not-padding">
							  				<p class="post__info">'.$text.'</p>
							  			</td>';
											if ($status == 1 || $status == 2)
	{
		print " <td><a href=\"/adminpanel/adminstation.php?a=edit_news&id=".$id."\"><img src=\"/adminpanel/images/edit_small.gif\" width=\"12\" height=\"12\" border=\"0\" alt=\"Редактировать новость\" /></a> ";
		print "<img style=\"cursor: hand;\" onclick=\"if(confirm('Вы уверены?')) top.location.href='/adminpanel/del/news.php?id=".$id."'\";  width=\"12\" height=\"12\" border=\"0\" src=\"/adminpanel/images/del.gif\" alt=\"Удалить новость\" /></td>";
	}
										print'
							  		</tr></a>';
}

function topics_list($page, $num, $status)
{
	$query	= "SELECT * FROM news ORDER BY id DESC";
	$result	= mysql_query($query);
	$themes = mysql_num_rows($result);
	$total	= intval(($themes - 1) / $num) + 1;
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$result = mysql_query($query." LIMIT ".$start.", ".$num);

	while ($row = mysql_fetch_array($result))
	{
		show_topics($row['id'], $row['subject_en'], $row['msg_en'], $row['date'], $status);
	}

	if ($page) {
		if($page != 1) { $pervpage = "<a href=\"?page=". ($page - 1) ."\">««</a>"; }
		if($page != $total) { $nextpage = " <a href=\"?page=".$total."\">»»</a>"; }
		if($page - 2 > 0) { $page2left = " <a href=\"?page=". ($page - 2) ."\">". ($page - 2) ."</a> "; }
		if($page - 1 > 0) { $page1left = " <a href=\"?page=". ($page - 1) ."\">". ($page - 1) ."</a> "; }
		if($page + 2 <= $total) { $page2right = " | <a href=\"?page=". ($page + 2) ."\">". ($page + 2) ."</a> "; }
		if($page + 1 <= $total) { $page1right = " | <a href=\"?page=". ($page + 1) ."\">". ($page + 1) ."</a> "; }
	}
	print "<div class=\"pages\" style=\"background: rgba(0,0,0,0.25); margin-bottom: 1px; padding: 5px;\"><b>Pages:  </b>".$pervpage.$page2left.$page1left." <b style=\"font-size:19px;\">".$page."</b> ".$page1right.$page2right.$nextpage."</div>";
}

$page = intval($_GET['page']);
topics_list($page, $num, $status);
} else {

	print "<p align=\"justify\">".stripslashes($news_text)."</p>";
	print '<div class="hline"></div><script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"></script>
	<table width="100%"><tr><td><div class="yashare-auto-init" data-yashareType="button" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir,friendfeed,lj"></div></td><td align="right">Publication date: <b>'.$news_date.'</b></td></tr></table>';

}
?>
							  	</tbody>
								</table>
							</div>
						</article>
					</div>