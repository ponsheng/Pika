<?php
session_start();
include("mysql_connect.inc.php");
echo "<script>var username='".$_SESSION['username']."';var rank='".$_SESSION['rank']."';var nickname='".$_SESSION['nickname']."';var win ='".$_SESSION['win']."';var lose ='".$_SESSION['lose']."';var prefer ='".$_SESSION['prefer']."';</script>";

//echo "<script>var username ='".$_SESSION['username']."';console.log(username);</script>"
?>

<html>
<head>
<title>PiKa PiKa</title>
<link href="img/title.ico" rel="SHORTCUT ICON">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
canvas{
				border:1px solid #d3d3d3;
				background-color: #dfdfdf;
			}
			#myGameArea:-webkit-full-screen {
			height: 100%;
			}
			#main_window:-webkit-full-screen {
			height: 100%;
			}
</style>
<style>
 html,body{font:normal 0.9em arial,helvetica;}
</style>

<script type="text/javascript" src="socket.js"></script>


</head>
<body  onunload="quit()">
<!--<body onload="init(); startGame();">-->
 <!--<button onclick="quit()">Quit</button>-->



</body>

<script>

		var startBall;
		var startText;
		var loading;
		var myGameArea;
		
	var myGameArea = {
		canvas : document.createElement("canvas"),
		prestart : function(){
        this.canvas.width = 1350;
        this.canvas.height = 720;
		this.canvas.id = "myGameArea";
		this.canvas.state = 0;
		//this.canvas.id="myGameArea";
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.interval = setInterval(updateStartArea, 10);
		this.canvas.onclick = function(){this.state = 1;
					var elem = document.getElementById("myGameArea");
					if (elem.requestFullscreen) {
						elem.requestFullscreen();
					} else if (elem.msRequestFullscreen) {
						elem.msRequestFullscreen();
					} else if (elem.mozRequestFullScreen) {
						elem.mozRequestFullScreen();
					} else if (elem.webkitRequestFullscreen) {
						elem.webkitRequestFullscreen();
					}}
		}, 
		start : function() {
		this.canvas.onclick = "";
		this.canvas.id="main_window";
		this.state = 0;
		this.stateCounter = 70;
		this.win = 0;
		this.enemyPrefer="";
		this.enemyNickname="Pika";
		if(prefer=="r") {updateGameArea = updateGameArea1;checkCollision = checkCollisionR;}
			else if (prefer=="l") {updateGameArea = updateGameArea2;checkCollision = checkCollisionL;}
        this.interval = setInterval(updateGameArea, 10);
        window.addEventListener('keydown', function (e) {
            myGameArea.keys = (myGameArea.keys || []);
            myGameArea.keys[e.keyCode] = (e.type == "keydown");
			
			if(prefer=="r") RightKeyDown(e.keyCode);
				else if (prefer=="l") LeftKeyDown(e.keyCode);
			
        })
        window.addEventListener('keyup', function (e) {
            myGameArea.keys[e.keyCode] = (e.type == "keydown");
			if(prefer=="r") RightKeyUp(e.keyCode);
				else if (prefer=="l") LeftKeyUp(e.keyCode);
        })
    }, 
    clear : function(){
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }
}
	
	function preStartGame() {
    
	myGameArea.prestart();
    
	startBall = new startball(90, myGameArea.canvas.width/2, 300);
	startText = new startText(540,450,"Start Game","black",70);
	loading = new loading(540,380,"Loading......","black",70);
	
}
	function updateStartArea(){
		myGameArea.clear();
		if(myGameArea.canvas.state <3){
			startBall.update();
			startText.update();
		}
		else 
			loading.update();
	}
	function startball(radius, x, y) {
    this.gamearea = myGameArea;
    this.radius = radius;   
    this.x = x;
    this.y = y;
	this.Image = new Image();
	this.Image.src = "img/ball.png";
	this.countdown = 300;
	this.xLeft = radius;
	this.xRight = myGameArea.canvas.width-radius;
	this.yInitial = y;
    this.update = function() {
        ctx = myGameArea.context;
		if(myGameArea.canvas.state ==2 && this.countdown>0){
			this.countdown --;
			var scale = 900 - 3*this.countdown;
			this.radius = this.radius+0.1*scale;
			ctx.drawImage(this.Image,this.x-this.radius,this.y-this.radius,this.radius*2,this.radius*2);
		if(this.countdown<2) {myGameArea.canvas.state =3;init();}
		}
		else
		ctx.drawImage(this.Image,this.x-this.radius,this.y-this.radius,this.radius*2,this.radius*2);
    }
    this.newPos = function() {	
    }
}
	function startText(x, y,innerText,color,size) {
    this.gamearea = myGameArea;  
    this.x = x;
    this.y = y;
	this.countdown = 70;
	this.size = size;
	this.innerText = innerText;
	this.update = function() {
		ctx = myGameArea.context;
		ctx.fillStyle = color;
		if(myGameArea.canvas.state ==1 && this.countdown>0){
			this.countdown --;
			var scale = 70 - this.countdown;
			this.size = this.size+ scale;
			this.x = this.x -scale;
			if(this.countdown<2){myGameArea.canvas.state =2;this.countdown=140;}
		}
		if(myGameArea.canvas.state ==2 && this.countdown>0){
			this.countdown --;
			
			var scale = 140 - this.countdown;
			this.size = this.size - scale;
			
		
		}
		ctx.font = ""+this.size+"px serif";
		ctx.fillText(this.innerText,this.x,this.y);
	}
    
	
}
function loading(x, y,innerText,color,size) {
    this.gamearea = myGameArea;  
    this.x = x;
    this.y = y;
	this.size = size;
	this.innerText = innerText;
	this.update = function() {
		ctx = myGameArea.context;
		ctx.fillStyle = color;
		ctx.font = ""+this.size+"px serif";
		ctx.fillText(this.innerText,this.x,this.y);
	}
    
	
}
preStartGame();




















var leftPika;
var rightPika;
var wall;
var ball;
var wallTop;
var leftScore;
var rightScore;
var leftName;
var rightName;
function startGame() {
    myGameArea.start();
    leftPika = new component1(100, 130, "red", 0, h(130),"l","img/lwalk.png");
	
	rightPika = new component1(100, 130, "red", 1200, h(130),"r","img/walk.png");
	
	wall = new staticComponent(20, 260, "black", 665, h(250),"rec");
	wallTop = new staticComponent(13,0,"brown",675,h(250),"round");
	
	ball = new Ball(60, "pink", myGameArea.canvas.width/2, h(580));
	
	leftScore = new text(50,100,0,"black",100);
	rightScore = new text(1200,100,0,"black",100);
	if(prefer =="l"){
		leftName = new text(30,170,nickname,"black",70);
		rightName = new text(1180,170,myGameArea.enemyNickname,"black",70);
	}
	else {
		leftName = new text(30,170,myGameArea.enemyNickname,"black",70);
		rightName = new text(1180,170,nickname,"black",70);
	}
	
	ready();
	
}
function ballIn(){
	if(prefer=="r"){
		if(ball.x>myGameArea.canvas.width/2){
			BALLIN_send();
		}
	}
	else if(prefer=="l"){
		if(ball.x<myGameArea.canvas.width/2){
			BALLIN_send();
		}
	}
	
}
function nextRound(winner){
	
	leftPika.x = leftPika.xInitial;
	leftPika.y = leftPika.yInitial;
	leftPika.speedX = 0;
	leftPika.speedY = 0;
	
	rightPika.x = rightPika.xInitial;
	rightPika.y = rightPika.yInitial;
	rightPika.speedX = 0;
	rightPika.speedY = 0;
	
	if(winner=="left")
		ball.x = ball.xLeft;
	else if(winner=="right")
		ball.x = ball.xRight;
	ball.y = ball.yInitial;
	ball.speedX = 0;
	ball.speedY = 0;
	ball.gravity = 0;
}
function h(height){
	return myGameArea.canvas.height-height;
}
function rh(rheight){
	return myGameArea.canvas.height-rheight;
}









//key up down
function LeftKeyDown(keyCode){
	//console.log(keyCode);
			if(keyCode ==71){if(forward==false)JSON_send("forward",true);forward=true;}
			if(keyCode ==68){if(backward==false)JSON_send("backward",true);backward=true;}
			if(keyCode ==82){if(up==false)JSON_send("up",true);up=true;}
			if(keyCode ==70){if(down==false)JSON_send("down",true);down=true;}
}
function LeftKeyUp(keyCode){
			if(keyCode ==71){JSON_send("forward",false);forward=false;}
			if(keyCode ==68){JSON_send("backward",false);backward=false;}
			if(keyCode ==82){JSON_send("up",false);up=false;}
			if(keyCode ==70){JSON_send("down",false);down=false;}
}

function RightKeyDown(keyCode){
			if(keyCode ==37){if(forward==false)JSON_send("forward",true);forward=true;}
			if(keyCode ==39){if(backward==false)JSON_send("backward",true);backward=true;}
			if(keyCode ==38){if(up==false)JSON_send("up",true);up=true;}
			if(keyCode ==40){if(down==false)JSON_send("down",true);down=true;}
}
function RightKeyUp(keyCode){
			if(keyCode ==37){JSON_send("forward",false);forward=false;}
			if(keyCode ==39){JSON_send("backward",false);backward=false;}
			if(keyCode ==38){JSON_send("up",false);up=false;}
			if(keyCode ==40){JSON_send("down",false);down=false;}
}



function component1(width, height, color, x, y,side,src) {
    this.gamearea = myGameArea;
    this.width = width;
    this.height = height;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;
	this.xInitial = x;
	this.yInitial = y;
	this.side=side;
	this.gravity = -0.3;
	this.Image = new Image();
	this.Image.src = src;
	this.air = false;
    this.update = function() {
        ctx = myGameArea.context;
		ctx.drawImage(this.Image,this.x,this.y,this.width,this.height);
    }
    this.newPos = function() {
        
		if(side=="l"){
			if(this.x + this.speedX<0){this.x = 0;}
			else if(this.x+ this.speedX+this.width>wall.x)this.x=wall.x-this.width;
			else this.x += this.speedX;
		}
		else if(side=="r"){
			if(this.x + this.speedX+this.width> this.gamearea.canvas.width)
				{this.x = this.gamearea.canvas.width-this.width;}
			if(this.x+ this.speedX<wall.x+wall.width)this.x=wall.x+wall.width;
			else this.x += this.speedX;
		}
	if(h(this.y + this.speedY)<this.height){
			this.y = rh(this.height);
			this.air = false;
			this.speedY = 0;
		}
		else {
			if(this.air){this.speedY = this.speedY-this.gravity;}
			this.y += this.speedY;}
    }
	this.jump=function(){
		this.air = true;
		this.speedY = -12;
	}
}
function staticComponent(width, height, color, x, y,shape) {
    this.gamearea = myGameArea;
    this.width = width;
    this.height = height;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;
	if(shape=="rec"){
    this.update = function() {
        ctx = myGameArea.context;
        ctx.fillStyle = color;
        ctx.fillRect(this.x, this.y, this.width, this.height);
    }}
	else if(shape=="round"){
		this.update = function() {
		ctx = myGameArea.context;
        ctx.fillStyle = color;
		ctx.beginPath();
		ctx.arc(this.x,this.y,this.width,0,Math.PI*2,true);
		ctx.closePath();
		ctx.fill();
	}}
    this.newPos = function() {
        this.x += this.speedX;
		this.y += this.speedY;
    }
	
}

function Ball(radius, color, x, y) {
    this.gamearea = myGameArea;
    this.radius = radius;
    this.speedX = 0;
    this.speedY = 0;    
    this.x = x;
    this.y = y;
	this.Image = new Image();
	this.Image.src = "img/ball.png";
	this.xLeft = radius;
	this.xRight = myGameArea.canvas.width-radius;
	this.yInitial = y;
	this.gravity=0;
	this.collisionCounter=0;
    this.update = function() {
        ctx = myGameArea.context;
		// ctx.save();
		// ctx.setTransform(1,0,0,1,0,0);
		// ctx.translate(x+this.radius*2,y+this.radius*2);
		//ctx.rotate(10);
		ctx.drawImage(this.Image,this.x-this.radius,this.y-this.radius,this.radius*2,this.radius*2);
		//ctx.restore();
        
    }
    this.newPos = function() {
		if(this.x+this.radius + this.speedX>myGameArea.canvas.width){
			this.speedX= -1*this.speedX;
			this.x = myGameArea.canvas.width - this.radius;
		}
		else if(this.x-this.radius + this.speedX<0){
			//console.log(this.x+this.radius - this.speedX);
			this.speedX= -1*this.speedX;
			this.x = this.radius;
			//console.log(this.speedX);
		}
		else {if(this.y > wall.y){
			if(this.x-this.radius>wall.x+wall.width&&this.x-radius+this.speedX<=wall.x+wall.width)
				this.speedX= -1*this.speedX;
			if(this.x+this.radius<wall.x&&this.x+radius+this.speedX>=wall.x)
				this.speedX= -1*this.speedX;
		}
        this.x += this.speedX;}
		
		this.speedY += this.gravity;
		
		if(this.y+this.radius + this.speedY>myGameArea.canvas.height){
			this.speedY= -1*(this.speedY+0.5*this.gravity);
			this.y = myGameArea.canvas.height - this.radius;
			ballIn();  //ballin
		}
		/*else if(this.y-this.radius - this.speedY<-3){
			this.speedY= -1*this.speedY;
		}*/
		else this.y += this.speedY;
		
		/*if(getDistance(this.x,this.y,wallTop.x,wallTop.y)<wallTop.width*5){
			Log("collison");
			this.speedY = -1*(this.speedY+this.gravity);
		}*/
		
			checkCollision(this.x,this.y);
		
    }
	
}
function text(x, y,innerText,color,size) {
    this.gamearea = myGameArea;  
    this.x = x;
    this.y = y;
	this.innerText = innerText;
	this.update = function() {
		ctx = myGameArea.context;
		ctx.fillStyle = color;
		ctx.font = ""+size+"px serif";
		ctx.fillText(this.innerText,x,y);
	}
    
	
}
var up = false;
var down = false;
var forward = false;
var backward = false;

//right
function updateGameArea1() {
    myGameArea.clear();
    leftPika.speedX = 0;
    rightPika.speedX =0;    
    if (myGameArea.keys && myGameArea.keys[39]) {rightPika.speedX = 4; }
    if (myGameArea.keys && myGameArea.keys[37]) {rightPika.speedX = -4; }
    if (myGameArea.keys && myGameArea.keys[38]) {if(!rightPika.air)rightPika.jump(); }
    if (myGameArea.keys && myGameArea.keys[40]) { }
	if(action_forward)leftPika.speedX = 4; 
	if(action_backward)leftPika.speedX=-4;
	if(action_up)if(!leftPika.air)leftPika.jump(); 
	if(action_down){};
	
    NewPosUpdate();
	stateCheck();
	
}
//left
function updateGameArea2() {
    myGameArea.clear();
    leftPika.speedX = 0;
    rightPika.speedX =0;    
    if (myGameArea.keys && myGameArea.keys[68]) {leftPika.speedX = -4; }
    if (myGameArea.keys && myGameArea.keys[71]) {leftPika.speedX = 4; }
    if (myGameArea.keys && myGameArea.keys[82]) {if(!leftPika.air)leftPika.jump(); }
    if (myGameArea.keys && myGameArea.keys[70]) { }
	if(action_forward)rightPika.speedX = -4; 
	if(action_backward)rightPika.speedX=4;
	if(action_up)if(!rightPika.air)rightPika.jump(); 
	if(action_down){}; 
    
	NewPosUpdate();
	
	stateCheck();
	
}
function NewPosUpdate(){
	leftPika.newPos();    
    leftPika.update();
	rightPika.newPos();    
    rightPika.update();
	wall.update();
	wallTop.update();
	ball.newPos();
	ball.update();
	leftName.update();
	leftScore.update();
	rightScore.update();
	rightName.update();
}

function stateCheck(){
	if(myGameArea.state ==2){
		myGameArea.stateCounter-=2;
		
		if(myGameArea.stateCounter<=30){
		ctx = myGameArea.context;
        ctx.fillStyle = "black";
		ctx.globalAlpha = 1-Math.abs(myGameArea.stateCounter)/30;
		//ctx.globalAlpha = 0.1;
        ctx.fillRect(0, 0, myGameArea.canvas.width, myGameArea.canvas.height);
		ctx.globalAlpha = 1;
		}
		
		if(myGameArea.stateCounter==0){
			
			if((myGameArea.win==1&&prefer=="r")||(!myGameArea.win==1&&prefer=="l")){
			nextRound("right");
			}
			else if((myGameArea.win==1&&prefer=="l")||(!myGameArea.win==1&&prefer=="r")){
			nextRound("left");
			}
		}
		if(myGameArea.stateCounter<-28){
			myGameArea.stateCounter=70;
			state3();
			
		}
			
	}
}
function checkCollisionR(ballX,ballY){
	if(ball.collisionCounter==0){
		if(ballX+ball.radius>rightPika.x&&ballX-ball.radius<rightPika.x+rightPika.width){
			if(ballY+ball.radius>rightPika.y&&ballY-ball.radius<rightPika.y+rightPika.height) //collision
			{
				var centerBall = ballX;
				var centerPika = rightPika.x+rightPika.width/2;
				var width = rightPika.width/2;
				var distance = Math.abs(centerBall-centerPika);
				
				 //attack
				if(myGameArea.keys && myGameArea.keys[13]){
					if(myGameArea.keys && myGameArea.keys[37] && myGameArea.keys[38]){
						BALL_send(-15,-15,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[39] && myGameArea.keys[38]){
						BALL_send(-15,-15,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[37] && myGameArea.keys[40]){
						BALL_send(-15,20,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[37]){
						BALL_send(-20,0,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[38]){
						BALL_send(-10,-20,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[39]){
						BALL_send(-20,0,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[40]){
						BALL_send(-10,20,ball.x,ball.y);
					}
				}
				
				else if(distance<0.2*width){
					BALL_send(0,-1*Math.abs(Math.round(10*(ball.speedY+1.4*ball.gravity)))/10,ball.x,ball.y);
				}
				else if(distance<0.6*width){
					if(centerBall>centerPika){
						BALL_send(2,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
					if(centerBall<centerPika){
						BALL_send(-2,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
				}
				else {
					if(centerBall>centerPika){
						BALL_send(6,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
					if(centerBall<centerPika){
						BALL_send(-6,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
				}
				
			
			ball.collisionCounter=12;
			}
		}
	}
	else ball.collisionCounter-=1;
}
//checkCollisionL
function checkCollisionL(ballX,ballY){
	if(ball.collisionCounter==0){
		if(ballX+ball.radius>leftPika.x&&ballX-ball.radius<leftPika.x+leftPika.width){
			if(ballY+ball.radius>leftPika.y&&ballY-ball.radius<leftPika.y+leftPika.height) //collision
			{
				var centerBall = ballX;
				var centerPika = leftPika.x+leftPika.width/2;
				var width = leftPika.width/2;
				var distance = Math.abs(centerBall-centerPika);
				 //attack
				if(myGameArea.keys && myGameArea.keys[90]){
					if(myGameArea.keys && myGameArea.keys[71] && myGameArea.keys[82]){
						BALL_send(15,-15,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[68] && myGameArea.keys[82]){
						BALL_send(15,-15,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[71] && myGameArea.keys[70]){
						BALL_send(15,20,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[71]){
						BALL_send(20,0,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[82]){
						BALL_send(10,-20,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[68]){
						BALL_send(20,0,ball.x,ball.y);
					}
					else if(myGameArea.keys && myGameArea.keys[70]){
						BALL_send(10,20,ball.x,ball.y);
					}
				}
				
				else if(distance<0.2*width){
					BALL_send(0,-1*Math.abs(Math.round(10*(ball.speedY+1.4*ball.gravity)))/10,ball.x,ball.y);
				}
				else if(distance<0.6*width){
					if(centerBall>centerPika){
						BALL_send(2,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
					if(centerBall<centerPika){
						BALL_send(-2,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
				}
				else {
					if(centerBall>centerPika){
						BALL_send(6,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
					if(centerBall<centerPika){
						BALL_send(-6,-1*Math.abs(Math.round(10*(ball.speedY+1.3*ball.gravity)))/10,ball.x,ball.y);
					}
				}
				
			
			ball.collisionCounter=12;
			}
		}
	}
	else ball.collisionCounter-=1;
}

function ready(){
	//var jsonData = '{"data":{"state" : 1,"method":"server"}}';
	
	// php
	var jsonData2 = '{"data":{"state" : 1,"prefer" : "'+prefer+'","username": "'+username+'","method":"server"}}';
	//
	//socket.send(jsonData);
	socket.send(jsonData2);
}
function getDistance(x1,y1,x2,y2){
	var X = x1-x2;
	var Y = y1-y2;
	var D = X*X+Y*Y;
	return Math.sqrt(D);
}
// send socket
function JSON_send(item,value){
	var jsonData = '{"data":{"'+item+'" : '+value+',"method":"dir"}}';
	socket.send(jsonData);
}
function BALL_send(speedx,speedy,x,y){
	var jsonData = '{"data":{"speedX" : '+speedx+',"speedY" : '+speedy+',"X" : '+x+',"Y" : '+y+',"fromMe": 0,"method":"ball"}}';
	socket.send(jsonData);
}
function BALLIN_send(){
	var jsonData = '{"data":{"method":"ballin"}}';
	socket.send(jsonData);
}
function Log(message){
	console.log(message);
}
</script>

</html>