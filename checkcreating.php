<?php 
session_start();

include("mysql_connect.inc.php");

if($_REQUEST['method']=="checkAccount"){
if($_REQUEST["key1"]){
	$n = $_REQUEST["key1"];
	$result = mysql_query("SELECT * FROM `user` WHERE `username` = '$n'",$link)or die ("die2");
	if($rows=mysql_fetch_array($result,MYSQL_ASSOC)){
		print "此帳號有人使用";
		
	}else if($_REQUEST["key1"]){
		
		print "此帳號尚無人使用";
		}
}}
else{
	$account = $_GET["account"];
	$password = $_GET["password"];
	$nickname = $_GET["nickname"];
	$query1 = "INSERT INTO user (username ,password,nickname,rank,win,lose,prefer) VALUES ('$account', '$password','$nickname','D',0,0,'r')";
	mysql_query($query1, $link)or  die ("Error in query: $query1. " . mysql_error());
	echo "此帳號註冊成功,請再次登入";
	
	}
//	}
//}else{
// 	<form action="http://140.116.245.221/whlu/reg.php" method="post">
//	print '
//	<form action="http://localhost/reg.php" method="post">
//	Username: <input type="text" name="user"><br/>
//	password: <input type="password" name="pw">
//	<input type="submit" value="註冊">
//	</form>'; 

?>