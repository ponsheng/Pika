var socket;
var action_up = false;
var action_backward = false;
var action_forward = false;
var action_down = false;
function init(){
  var host = "ws://localhost:12345/server1.php";
  //var host = "ws://140.116.20.54:12345/server1.php";
  try{
    socket = new WebSocket(host);
    console.log('WebSocket - status '+socket.readyState);
    socket.onopen    = function(msg){ console.log("Welcome - status "+this.readyState); };
    socket.onmessage = function(msg){  process(msg.data);};
    socket.onclose   = function(msg){ console.log("Disconnected - status "+this.readyState); };
  }
  catch(ex){ console.log(ex); }
}
var jsonObj;
function process(msg){
	//Log(msg);
	jsonObj = JSON.parse(msg);
	//var jsonData = '{"data":{"up" : true}}';
	//var jsonObj = JSON.parse(jsonData);
	//console.log(jsonObj);
	switch (jsonObj.data.method) {
		case "dir": getcontrol();break;
		case "ball": changeBall();break;
		case "system": {
							if(jsonObj.data.systemMsg=="matched"){clearInterval(myGameArea.interval); startGame();}
							else if(jsonObj.data.systemMsg=="Win"){alert("Win"); window.location.href="text.php";}
							else if(jsonObj.data.systemMsg=="Lose"){alert("Lose");window.location.href="text.php";}
							}break;
		case "server": if(jsonObj.data.state==1){myGameArea.enemyPrefer= jsonObj.data.prefer;console.log(jsonObj.data.nickname);myGameArea.enemyNickname= jsonObj.data.nickname; myGameArea.state = 1;ball.gravity=0.4;} break;
		case "score":  	state2(); break;
	}
	
	
}
function state2(){
	myGameArea.state = 2;
	clearInterval(myGameArea.interval);
	myGameArea.interval = setInterval(updateGameArea, 50);
	myGameArea.win = jsonObj.data.win;
	leftScore.innerText = (prefer=="l")?jsonObj.data.scoreSelf:jsonObj.data.scoreEnemy;
	rightScore.innerText = (prefer=="l")?jsonObj.data.scoreEnemy:jsonObj.data.scoreSelf;
	
}
function state3(){
	myGameArea.state=3;
	clearInterval(myGameArea.interval);
	myGameArea.interval = setInterval(updateGameArea, 10);
	setTimeout(ready,500);
}
/*function send(){
  var txt,msg;
  txt = $("msg");
  msg = txt.value;
  if(!msg){ alert("Message can not be empty"); return; }
  txt.value="";
  txt.focus();
  try{ socket.send(msg); log('Sent: '+msg); } catch(ex){ log(ex); }
} */
function quit(){
  console.log("Goodbye!");
  socket.close();
  socket=null;
}

function getcontrol(){
	
	if(typeof  jsonObj.data.forward !== "undefined"){
		action_forward = jsonObj.data.forward;
		//console.log(action_forward);
	}
	if(typeof  jsonObj.data.backward !== "undefined"){
		//console.log(jsonObj.data.backward);
		action_backward = jsonObj.data.backward;
	}
	if(typeof  jsonObj.data.up !== "undefined"){
		//console.log(jsonObj.data.up);
		action_up = jsonObj.data.up;
	}
	if(typeof  jsonObj.data.down !== "undefined"){
		//console.log(jsonObj.data.down);
		action_down = jsonObj.data.down;
	}
}

function changeBall(){
	if(myGameArea.enemyPrefer!=prefer){
		//Log("change");
		ball.speedX=jsonObj.data.speedX;
		ball.speedY=jsonObj.data.speedY;
		ball.x = jsonObj.data.X;
		ball.y = jsonObj.data.Y;
	}
	else {    //same prefer
		
		if(jsonObj.data.fromMe==1){
			ball.speedX=jsonObj.data.speedX;
			ball.x = jsonObj.data.X;
		}
		else {
			ball.speedX=-1*jsonObj.data.speedX;
			ball.x = myGameArea.canvas.width-jsonObj.data.X;
		}
		
		ball.speedY=jsonObj.data.speedY;
		ball.y = jsonObj.data.Y;
	}
	
	//Log(jsonObj.data.speedX);
	//Log(jsonObj.data.speedY);
}

// Utilities
function $(id){ return document.getElementById(id); }
