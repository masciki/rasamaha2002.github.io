<div align="center">
<p class="warn" style="margin-top:5px; position:relative; font-size: 23px;">����</p>
<?php
if($login) {
	print'<meta http-equiv="refresh" content="0;/lk/">';
	exit();
}
if($er) {
	print '	<div class="alert alert-fixed alert-danger alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-close-circle"></i>
								  </div>
								  <p class="alert__text"> <strong>�� ������� �����.</strong><br />
	����������, ��������� ������������ ��������� <b>������</b> � <b>������</b>.</p>
								</div>';
} else {
if($_GET['mes'] == 'nopage') {
	print '<div class="alert alert-fixed alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-attention"></i>
								  </div>
								  <p class="alert__text"> ������� ��� ����� � ������ � ������ �����!</p>
								</div>';
								} elseif($_GET['mes'] == 'rsuc') {
								print '<div class="alert alert-fixed alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true"><i class="pe-7s-close"></i></span><span class="sr-only">Close</span></button>
								  <div class="alert__icon pull-left">
								  	<i class="pe-7s-check"></i>
								  </div>
								  <p class="alert__text">�����������! �� ������������������. ��������������� ����������.</p>
								</div>';
								}
}
?>
					<form method="post" action="/login/">
					<div class="col-md-4  col-md-offset-4">
						<article class="widget widget__login">
							<header class="widget__header one-btn">
							<a href="/registration/" style="text-decoration:none; color:#ffffff;" onclick="window.location.href = /registration/">
								<div class="widget__title">
									 �����������
								</div>
								</a>
								<div class="widget__config">
									<a href="/reminder/" onclick="window.location.href = /reminder/"><i class="pe-7s-help1"></i></a>
								</div>
							</header>

							<div class="widget__content">
								<input name="user" type="text" onblur="if (value == '') {value='�����'}" onfocus="if (value == '�����') {value =''}" placeholder="�����">
								<input name="pass" type="password" onblur="if (value == '') {value='������'}" onfocus="if (value == '������') {value =''}" placeholder="������">
								<button type="submit">�����</button>
							</div>
						</article><!-- /widget -->
					</div>
					</form>
				</div>