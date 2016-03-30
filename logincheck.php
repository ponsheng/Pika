<?php session_start();
include("mysql_connect.inc.php");
echo "Authorizing........";
if($_SESSION['username'] != ""){
	
	header("Refresh:2;url=index.php");
	exit();
}
else if($_REQUEST['username']){ 
	//$r=md5($_REQUEST['password']); 
	$r=$_REQUEST['password']; 
	if($_REQUEST['username'] == "guest"||valid($_REQUEST['username'],$r)){
		//setcookie("username",$_REQUEST["username"]); 
		//setcookie("password",$r);
		
		$username =$_REQUEST['username'];
		$result= mysql_query("SELECT * FROM user  WHERE username='$username'") or die("die2");
		
		if($rows= mysql_fetch_array($result,MYSQL_ASSOC)){
		$_SESSION['username']=$_REQUEST['username'];
		$_SESSION['nickname'] =$rows['nickname'];
		$_SESSION['rank'] =$rows['rank'];
		$_SESSION['win'] =$rows['win'];	
		$_SESSION['lose'] =$rows['lose'];	
		$_SESSION['prefer'] =$rows['prefer'];
		
		header("Refresh:2;url=text.php");
		exit();}
	}
	else{
	header("Refresh:2;url=loginwrong.php");}
	
}


function valid($users,$pws){
    if($users && $pws){
    $result= mysql_query("SELECT * FROM user  WHERE username='$users'") or die("die2");
	if($rows= mysql_fetch_array($result,MYSQL_ASSOC)){
		if($rows['password'] == $pws){	
			return 1;}
		}
	}
	return 0;
}
 ?>