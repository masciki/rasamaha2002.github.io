<?php
/*
������ ������ ���������� ���������� �������� ������������, ����� �����.
����� ������������� ������� �������, ��������� ������ � ����������� �������� ������.
������ ������� �������: http://adminstation.ru/images/docs/doc1.jpg
���� ����������: 4.11.2008 �.

-> ���� �������� ������ ���-��������
*/

include '../../cfg.php';
include '../../ini.php';

if($status == 1) {
$id = intval($_GET['id']);
	if (mysql_num_rows(mysql_query('SELECT `id` FROM paysystems WHERE id = '.$id))) {
		if (mysql_query("DELETE FROM paysystems WHERE id = ".$id." LIMIT 1")) {
			print "<script>alert('��������� ������� �������!'); location.href='../adminstation.php?a=paysystems'</script>";
		} else {
			print "<script>alert('�� ������ ������� ��������� �������!'); location.href='../adminstation.php?a=paysystems'</script>";
		}
	} else {
		print "<script>alert('��� ����� ��������� �������!'); location.href='../adminstation.php?a=paysystems'</script>";
	}
}
?>