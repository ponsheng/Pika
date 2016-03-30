<?php session_start(); ?>
<?php
  echo "<script>var username='".$_SESSION['username']."';var rank='".$_SESSION['rank']."';var nickname='".$_SESSION['nickname']."';var win ='".$_SESSION['win']."';var lose ='".$_SESSION['lose']."';var prefer ='".$_SESSION['prefer']."';</script>";

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body{padding:0;margin:0;}

#mwt_mwt_slider_scroll{
  top:0;
  left:-240px;
  width:240px;
  position:fixed;
  z-index:9999;}
  #mwt_mwt_slider_scroll2{
  top:0;
  left:-240px;
  width:240px;
  position:fixed;
  z-index:9999;}

#mwt_slider_content{
  background:#fff6a4;
  text-align:center;
  padding-top:20px;
  margin-top: 20px;
  font-size: 20px;
  color:#623108;
  font-family:cursive,Microsoft JhengHei;
  }

#mwt_slider_content2{
  background:#A9F5F2;
  text-align:left;
  padding-top:20px;
  margin-top: 20px;
  font-size: 20px;
  color:#623108;
  font-family:cursive,Microsoft JhengHei;
  }
#mwt_slider_content2
p{
  margin-left: 5px;
}
#mwt_slider_content
img{
  width: 160px;
  height: 150px;
  margin-top: 20px;
}
#mwt_slider_content2
img{
  width: 160px;
  height: 150px;
  margin-top: 20px;
}
#mwt_fb_tab{
  position:absolute;
  top:20px;
  right:-30px;
  width:30px;
  background:#fff6a4;
  color:#623108;
  font-family:Arial,Helvetica,sans-serif,Microsoft JhengHei;
  font-size: 20;
  text-align:center;
  padding:20px 0;
  -moz-border-radius-topright:10px;
  -moz-border-radius-bottomright:10px;
  -webkit-border-top-right-radius:10px;
  -webkit-border-bottom-right-radius:10px;}
#mwt_fb_tab 
span{
  display:block;
  height:20px;
  padding:1px 0;
  line-height:12px;
  text-transform:uppercase;
  font-size:20px;}
#mwt_fb_tab2{
  position:absolute;
  top:150px;
  right:-30px;
  width:30px;
  background:#A9F5F2;
  color:#623108;
  font-family:Arial,Helvetica,sans-serif,Microsoft JhengHei;
  font-size: 20;
  text-align:center;
  padding:20px 0;
  -moz-border-radius-topright:10px;
  -moz-border-radius-bottomright:10px;
  -webkit-border-top-right-radius:10px;
  -webkit-border-bottom-right-radius:10px;}
#mwt_fb_tab2 
span{
  display:block;
  height:20px;
  padding:1px 0;
  line-height:12px;
  text-transform:uppercase;
  font-size:20px;}
#main_choose{
  float: left;
  width:700px;
  height:550px;
  margin-left: 2%;
  margin-top: 5%;
}
#child_choose{
  float: right;
  margin-right: 30%;
  margin-top: 5%;
  font-size: 30px;
}
#child_choose
ul{
  list-style: none;
}
</style>
<script type='text/javascript' src='http://code.jquery.com/jquery-1.9.1.min.js'></script>
<script type="text/javascript" src="jquery-2.2.0.js"></script>
<script type="text/javascript" src="jquery.backgroundPosition.js"></script>
<script>

$(function(){

  var w = $("#mwt_slider_content").width();
  $('#mwt_slider_content').css('height', ($(window).height() - 200) + 'px' ); 
  
  $("#mwt_fb_tab").mouseover(function(){
    if ($("#mwt_mwt_slider_scroll").css('left') == '-'+w+'px')
    { 
      $("#mwt_mwt_slider_scroll").animate({ left:'0px' }, 400 ,'swing');
    }
  });
  $("#mwt_slider_content").mouseleave(function(){
    $("#mwt_mwt_slider_scroll").animate( { left:'-'+w+'px' }, 400 ,'swing');  
  });  

  $("#mwt_fb_tab2").mouseover(function(){
    if ($("#mwt_mwt_slider_scroll2").css('left') == '-'+w+'px')
    {
      $("#mwt_mwt_slider_scroll2").animate({ left:'0px' }, 400 ,'swing');
    }
    $("#mwt_slider_content2").css('z-index', '10');
  });
  $("#mwt_slider_content2").mouseleave(function(){
    $("#mwt_mwt_slider_scroll2").animate( { left:'-'+w+'px' }, 400 ,'swing');  
  }); 


});
$(document).ready(function(){
  $(".myCanvas").click(function(){
  $("#myCanvas").animate({left:"-1000px"});
  });
});


</script>
</head>
<body>

<div id="mwt_mwt_slider_scroll">
<div id="mwt_fb_tab">
<span>個</span>
<span>人</span>
<span>資</span>
<span>料</span>
</div>

<div id="mwt_slider_content">
<img src="http://a0.att.hudong.com/61/36/01200000194917136323362702423.jpg" id="user_img" style="border:6px double #ff9900;">
<p>帳號：<span id="username"></span></p>
<p>暱稱：<span id="nickname"></span></p>
<p>慣用手：<span id="prefer"></span></p>
<p>排名：<span id="rank"></span></p>
<p>戰績：<span id="win"></span>勝 <span id="lose"></span>敗</p>
<p><button onclick="window.location.href = 'index.php';">log out</button>
<button onclick="window.location.href = 'set.php';">修改資料</button></p>
</div>
</div>

<div id="mwt_mwt_slider_scroll2">
<div id="mwt_fb_tab2">
<span>規</span>
<span>則</span>
<span>講</span>
<span>解</span>
</div>
<div id="mwt_slider_content2">
<p>規則講解</p>
<p>按鍵說明：</p>
<p>利用上下左右操作皮卡丘的動作</p>
<p>按enter進行殺球</p>
<p>2p操作：</p>
<p>上鍵：T 左鍵：F 右鍵：H</p>
<p>按Z進行殺球</p>
</br>
</div>
</div>


<div id="main_choose">

<canvas id="myCanvas" width="700" height="550"  ></canvas>
</div>
<div id="child_choose">
<ul >
<li><canvas id="myCanvas1" width="170" height="170" ></canvas></li>
<li><canvas id="myCanvas2" width="170" height="170" ></canvas></li>
<li><canvas id="myCanvas3" width="170" height="170" ></canvas></li>
</ul>
</div>
<script>
document.getElementById("username").innerHTML=username;
document.getElementById("nickname").innerHTML=nickname;
document.getElementById("rank").innerHTML=rank;
document.getElementById("win").innerHTML=win;
document.getElementById("lose").innerHTML=lose;
document.getElementById("prefer").innerHTML=prefer;
var c = document.getElementById("myCanvas");
var ctx = c.getContext("2d");



var img = new Image();
img.onload = function(){
    var pattern = ctx1.createPattern(img, "no-repeat");
    ctx.beginPath();
    ctx.arc(490,275,200,0,2*Math.PI);
    ctx.fillStyle = pattern;
    ctx.fill();
};
img.src = "ball.jpg";

var c1 = document.getElementById("myCanvas1");
var c2 = document.getElementById("myCanvas2");
var c3 = document.getElementById("myCanvas3");
var ctx1 = c1.getContext("2d");
var ctx2 = c2.getContext("2d");
var ctx3 = c3.getContext("2d");

ctx1.beginPath();
ctx1.arc(80,80,80,0,2*Math.PI);
ctx1.fillStyle="#62d5b4";
ctx1.fill();
ctx1.font = "30px Microsoft JhengHei";
ctx1.strokeText("連線對戰",25,95);
ctx1.stroke();

ctx2.beginPath();
ctx2.arc(80,80,80,0,2*Math.PI);
ctx2.fillStyle="#c52018";
ctx2.fill();
ctx2.font = "75px Arial";
ctx2.fillStyle="black";
ctx2.fillText("2P",30,110);

ctx3.beginPath();
ctx3.arc(80,80,80,0,2*Math.PI);
ctx3.fillStyle="#f6e652";
ctx3.fill();
ctx3.font = "75px Arial";
ctx3.fillStyle="black";
ctx3.fillText("1P",30,110);


c1.addEventListener('click', function() { }, false);
c1.addEventListener('click', function(event) {
  window.location.href="client.php";

});

c2.addEventListener('click', function() { }, false);
c2.addEventListener('click', function(event) {
window.location.href="http://web.htps.tn.edu.tw/chhoko/95510/%E9%81%8A%E6%88%B2/%E7%9A%AE%E5%8D%A1%E4%B8%98%E6%89%93%E6%8E%92%E7%90%83.exe";}
);

c3.addEventListener('click', function() { }, false);
c3.addEventListener('click', function(event) {
document.location.href="http://web.htps.tn.edu.tw/chhoko/95510/%E9%81%8A%E6%88%B2/%E7%9A%AE%E5%8D%A1%E4%B8%98%E6%89%93%E6%8E%92%E7%90%83.exe";});
</script> 
</div>
</body>
</html>