<?php session_start();
		$_SESSION['username']="";
		$_SESSION['nickname'] ="";
		$_SESSION['rank'] ="";
		$_SESSION['win'] ="";	
		$_SESSION['lose'] ="";
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=big5">
<link rel="stylesheet" href="style.css"/>
<script>
function creat(){
	document.location.href="creating.php";
}

</script>
<style>
body {
    margin:0;
    padding:0;
    background: #000 url(bgimg.PNG) center center fixed no-repeat;
    -moz-background-size: cover;
    background-size: cover;
  }
.leftside{
  margin-top: 8%;
  margin-left: 25%;
  width: 400px;
  float: left;
}
.main{
	margin-top: 8%;
	margin-left: 60%;
	margin-right: 15%;
	background-color: rgba(100%, 100%, 100%, 0.6);
	border-radius: 20px;
	font-family: cursive;
	font-size: 22px;
}
.btn {
  -webkit-border-radius: 15;
  -moz-border-radius: 15;
  padding: 5px 20px 10px 20px;
  text-decoration: none;
  border-radius: 15px;
  font-family: cursive;
  color: #ffffff;
  font-size: 20px;
  background: #3498db;
  height: 40px;
  width: 100px;
}
  
.btn:hover {
  background: #3cb0fd;
  text-decoration: none;
  cursor:pointer;
}
label {margin-right:20px;}
</style>
</head>
<body>
<div class="leftside" >
  <h1>Welcome</h1>

</div>

<div class = "main">
<br>
<form method="get" action="logincheck.php">
<p align="center"><font color="green" >Username:</font></p>
<p align="center"><input type="text" name="username"  class="css-input" ></p>
<p align="center"><font color="green" >Password:</font></p>
<p align="center"><input type="password" name="password" class="css-input" ></p>
<p align="center">
<input class="btn" type="submit" value="Send">
<input class="btn" onclick="creat()" type="button" value="Creat">
</form>

<br>
</div>
 </body>
 </HTML>