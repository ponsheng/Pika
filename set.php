<?php session_start(); 
include("mysql_connect.inc.php");
echo "<script>var username='".$_SESSION['username']."'; var prefer ='".$_SESSION['prefer']."';</script>";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<script>

function check(){
        if(document.getElementById("new_password").value!=document.getElementById("checkpassword").value) 
                document.getElementById("check").innerHTML="密碼確認失敗";
else
        document.getElementById("check").innerHTML="";
}

function checksubmit(){
        if(document.getElementById("new_password").value=="" || document.getElementById("new_nickname").value=="" ){
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
		console.log(document.getElementById("left").checked);
        if(checksubmit()){
                change();
        } 
}
function change(){
        
    var oXHR = new XMLHttpRequest();
        var passwd = document.getElementById("new_password").value;
        var account = document.getElementById("username").innerHTML;
        var nickname = document.getElementById("new_nickname").value;
        if(document.getElementById("right").checked){
			prefer = "r";
		}
		else prefer = "l";
        var para= "account="+account+"&password="+passwd+"&nickname="+nickname+"&prefer="+prefer;
        oXHR.open("GET","setcreating.php?"+para,true);
        console.log(123);
        
        oXHR.onreadystatechange= function(){
                if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304)){
                        console.log(oXHR.responseText);
                        if(oXHR.responseText=="此帳號修改成功"){
                                alert("change successfully");
                                window.location.href = "text.php";
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


<p align="center"><font color="green" >帳號：<span id="username"></span></font></p>

<p align="center"><font color="green" >新暱稱：</font></p>
<p align="center"><input type="text" class="css-input" id="new_nickname" name="new_nickname"></p>

<p align="center"><font color="green" >慣用手:</font>

 <input type="radio" name="side" id="left" value="Left"> Left
 <input type="radio" name="side" id="right" value="Right" checked> Right</p>


<p align="center"><font color="green" >新密碼：</font></p>
<p align="center"><input type="password" class="css-input" id="new_password" name="new_password"></p>

<p align="center"><font color="green" >再確認密碼：</font></p>
<p align="center"><input type="password"  onblur="check()" class="css-input" id="checkpassword"></p>
<b><div align="center" id="check"></div></b>
<p align="center"><input class="btn" type="button" onclick="submit()" value="check"></p>

<br>
</div>
</div>
<script>
document.getElementById("username").innerHTML=username;
if(prefer == "r"){
	document.getElementById("right").checked =true;
}
else if(prefer == "l"){
	document.getElementById("left").checked = true;
}
</script>
 </body>
</html>