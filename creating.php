<?php session_start(); ?>
<?php include("mysql_connect.inc.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="style.css"/>
<script>
function checking(){	
	if(document.getElementById("new_username").value != ""){
    var oXHR = new XMLHttpRequest();
	para= "key1="+document.getElementById("new_username").value+"&method=checkAccount";
	oXHR.open("GET","checkcreating.php?"+para,true);
	oXHR.onreadystatechange= function(){
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304)){
			document.getElementById("check").innerHTML=oXHR.responseText;
			if(oXHR.responseText=="此帳號尚無人使用") return true;
			else return false;
		}
	}
  oXHR.send();
    }
    else
		document.getElementById("check").innerHTML="";
}
function check(){
	if(document.getElementById("new_password").value!=document.getElementById("checkpassword").value) 
		document.getElementById("check").innerHTML="密碼確認失敗";
else
        document.getElementById("check").innerHTML="";
}

function checksubmit(){
	if(document.getElementById("new_password").value=="" || document.getElementById("new_username").value=="" || document.getElementById("checkpassword").value=="" ){
		document.getElementById("check").innerHTML="有資料沒填到喔!";
		return false;
	}
	else if(document.getElementById("new_password").value!=document.getElementById("checkpassword").value){
		document.getElementById("check").innerHTML="密碼確認失敗!";
		return false;
	}
	else
    return true; 
}
function submit(){
	if(checksubmit()){
		if(document.getElementById("new_username").value != ""){
		var oXHR = new XMLHttpRequest();
		para= "key1="+document.getElementById("new_username").value+"&method=checkAccount";
		oXHR.open("GET","checkcreating.php?"+para,true);
		oXHR.onreadystatechange= function(){
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304)){
			
			if(oXHR.responseText=="此帳號尚無人使用") {
				console.log(oXHR.responseText);signup();
			}
			else {document.getElementById("check").innerHTML=oXHR.responseText;}
			
		}
	}
	oXHR.send();
	
    }
	}
	
}
function signup(){
	
    var oXHR = new XMLHttpRequest();
	var passwd = document.getElementById("new_password").value;
	var account = document.getElementById("new_username").value;
	var nickname = document.getElementById("new_nickname").value;
		
	var para= "account="+account+"&password="+passwd+"&nickname="+nickname+"&method=signup";
	oXHR.open("GET","checkcreating.php?"+para,true);
	console.log(123);
	
	oXHR.onreadystatechange= function(){
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304)){
			console.log(oXHR.responseText);
			if(oXHR.responseText=="此帳號註冊成功,請再次登入"){
				alert("sign up successfully");
				window.location.href = "index.php";
			}
		}
	}
	
  oXHR.send();
  
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
.main{
	margin-top: 3%;
	margin-left: 35%;
	margin-right: 35%;
	background-color: rgba(100%, 100%, 100%, 0.6);
	border-radius: 20px;
	font-family: cursive;
	font-size: 20px;
}
.btn {
  -webkit-border-radius: 15;
  -moz-border-radius: 15;

  border-radius: 15px;
  font-family: cursive;
  color: #ffffff;
  font-size: 20px;
  background: #3498db;
  padding: 10px 20px 10px 20px;
  text-decoration: none;
}

.btn:hover {
  background: #3cb0fd;
  text-decoration: none;
}
label {margin-right:20px;}
</style>
</head>

<body>
<div class = "main">
<div id="show">
<br>


<p align="center"><font color="green" >帳號:</font></p>
<p align="center"><input type="text" onblur="checking()" class="css-input" id="new_username" name="new_username"></p>

<p align="center"><font color="green" >暱稱:</font></p>
<p align="center"><input type="text" class="css-input" id="new_nickname" name="new_nickname"></p>

<p align="center"><font color="green" >密碼:</font></p>
<p align="center"><input type="password" class="css-input" id="new_password" name="new_password"></p>

<p align="center"><font color="green" >再確認密碼:</font></p>
<p align="center"><input type="password"  onblur="check()" class="css-input" id="checkpassword"></p>
<b><div align="center" id="check"></div></b>
<p align="center"><input class="btn" type="button" onclick="submit()" value="check"></p>

<br>
</div>
</div>
 </body>
</html>
 