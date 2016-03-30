<?php 
session_start();

include("mysql_connect.inc.php");

	$account = $_GET["account"];
	$password = $_GET["password"];
	$nickname = $_GET["nickname"];
	$prefer = $_GET["prefer"];
	$sql = "UPDATE user set password='$password',prefer='$prefer', nickname='$nickname' where username='$account'";
	
	mysql_query($sql, $link)or  die ("Error in query: $sql. " . mysql_error());
	echo "此帳號修改成功";
	
	$_SESSION['nickname'] =$nickname;	
	$_SESSION['prefer'] =$prefer;
	

	
?>