#!/php -q
<?php  /*  >php -q server.php  */
include("mysql_connect.inc.php");
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

//$master  = WebSocket("140.116.20.54",12345);
$master  = WebSocket("0.0.0.0",12345);
$sockets = array($master);
$users   = array();
$debug   = true;

while(true){
  $changed = $sockets;
  $write=NULL;
  $except=NULL;
  socket_select($changed,$write,$except,NULL);
  foreach($changed as $socket){
    if($socket==$master){
      $client=socket_accept($master);
      if($client<0){ console("socket_accept() failed"); continue; }
      else{ connect($client); }
    }
    else{
      $bytes = @socket_recv($socket,$buffer,2048,0);
	  //$buf = socket_read($socket, 2048, PHP_NORMAL_READ);
	  //say($buf.$buf);
      if($bytes==0){ disconnect($socket); }
      else{
        $user = getuserbysocket($socket);
        if(!$user->handshake){ dohandshake($user,$buffer); peermatch($user);}
        else{ 
		process($user,$buffer); 
		}
      }
    }
  }
}

//---------------------------------------------------------------
function process($user,$msg){
  $action = unwrap($msg);
  $obj = json_decode($action);
  
  if($obj->data->method=="server"){
	  //say("<".$user->socket." & ".$user->peer.": ".$action);
	  $user->state = $obj->data->state;
	  $user->prefer = $obj->data->prefer;
	  $user->username = $obj->data->username;
	  $enemy = getuserbysocket($user->peer);
	  if($user->state==1&&$enemy->state==1){
		  
		  global $link;
		  $myName = $user->username;
		  $enemyName = $enemy->username;
		  
		$result= mysql_query("SELECT * FROM user  WHERE username='$myName'", $link)or  die ("Error in query:" . mysql_error());
		if($rows= mysql_fetch_array($result,MYSQL_ASSOC)){
		$nickname=$rows['nickname'];}
		$result1= mysql_query("SELECT * FROM user  WHERE username='$enemyName'", $link)or  die ("Error in query:" . mysql_error());
		if($rows= mysql_fetch_array($result1,MYSQL_ASSOC)){
		$nickname1=$rows['nickname'];}
		$jsonData = '{"data":{"state" : 1,"nickname": "'.$nickname1.'","prefer" : "'.$enemy->prefer.'","method":"server"}}';
		$action = '{"data":{"state" : 1,"nickname": "'.$nickname.'","prefer" : "'.$user->prefer.'","method":"server"}}';
		
		//
		
		
		//$jsonData = '{"data":{"state" : 1,"prefer" : "'.$enemy->prefer.'","method":"server"}}';
		send($user->socket,$jsonData);
		send($user->peer,$action);
		
		
	  }
  }
  else {switch ($obj->data->method){
	  
	  case "ball":		send($user->peer,$action); 
						$obj->data->fromMe = 1;
						$actionNew = json_encode($obj);
						send($user->socket,$actionNew);
						break;
	  case "ballin": 	getuserbysocket($user->peer)->score ++;
						$scoreSelf = $user->score;
						$enemy = getuserbysocket($user->peer);
						$scoreEnemy = $enemy->score;
						
						send($user->socket,jsonEncode($scoreSelf,$scoreEnemy,0));
						send($user->peer,jsonEncode($scoreEnemy,$scoreSelf,1));
						if($scoreSelf==3||$scoreEnemy==3){
							$jsonDataW = '{"data":{"state" :4,"systemMsg" : "Win","method":"system"}}';
							$jsonDataL = '{"data":{"state" : 4,"systemMsg" : "Lose","method":"system"}}';
							if($scoreSelf==3){
								send($user->socket,$jsonDataW);
								send($user->peer,$jsonDataL);
								gameSet($user,$enemy,1,-1);
							}
							if($scoreEnemy==3){
								send($user->socket,$jsonDataL);
								send($user->peer,$jsonDataW);
								gameSet($user,$enemy,-1,1);
								
							}
							$user->score = 0;
							getuserbysocket($user->peer)->score =0;
						}
						
						break;
	  default: 	say("<".$user->socket." 2 ".$user->peer.": \n   ".$action);
				sendPeer($user->socket,$action);
	  
	  
  }
	  
  }
 
}
function gameSet($user,$enemy,$u,$e){
		global $link;
		$myName = $user->username;
		$enemyName = $enemy->username;
		if($u>0){
			$result1= mysql_query("SELECT * FROM user  WHERE username='$myName'", $link)or  die ("Error in query:" . mysql_error());
			if($rows= mysql_fetch_array($result1,MYSQL_ASSOC)){
			$userwin=$rows['win'];}
			$result2= mysql_query("SELECT * FROM user  WHERE username='$enemyName'", $link)or  die ("Error in query:" . mysql_error());
			if($rows= mysql_fetch_array($result2,MYSQL_ASSOC)){
			$enemylose=$rows['lose'];}
			
			$sql = "UPDATE user SET win=".($userwin+1)." WHERE username='".$myName."'";
			mysql_query($sql, $link)or  die ("Error in query: $sql. " . mysql_error());
			$sql = "UPDATE user SET lose=".($enemylose+1)." WHERE username='".$enemyName."'";
			mysql_query($sql, $link)or  die ("Error in query: $sql. " . mysql_error());
		}
		else{
			$result1= mysql_query("SELECT * FROM user  WHERE username='$myName'", $link)or  die ("Error in query:" . mysql_error());
			if($rows= mysql_fetch_array($result1,MYSQL_ASSOC)){
			$userlose=$rows['lose'];}
			$result2= mysql_query("SELECT * FROM user  WHERE username='$enemyName'", $link)or  die ("Error in query:" . mysql_error());
			if($rows= mysql_fetch_array($result2,MYSQL_ASSOC)){
			$enemywin=$rows['win']; }
			
			$sql = "UPDATE user SET win=".($enemywin+1)." WHERE username='".$enemyName."'";
			mysql_query($sql, $link)or  die ("Error in query: $sql. " . mysql_error());
			$sql = "UPDATE user SET lose=".($userlose+1)." WHERE username='".$myName."'";
			mysql_query($sql, $link)or  die ("Error in query: $sql. " . mysql_error());
		}
}

function sendPeer($client,$msg){
  say("> ".$client." \n   ".$msg);
  $msg = wrap($msg);
  $client = getuserbysocket($client);
  //$sent = 
  socket_write($client->peer,$msg);
}
function send($client,$msg){
  say("> ".$client.": \n   ".$msg);
  $msg = wrap($msg);
  $client = getuserbysocket($client);
  //$sent = 
  socket_write($client->socket,$msg);
}

function WebSocket($address,$port){
  $master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");
  socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");
  socket_bind($master, $address, $port)                    or die("socket_bind() failed");
  socket_listen($master,20)                                or die("socket_listen() failed");
  echo "Server Started : ".date('Y-m-d H:i:s')."\n";
  echo "Master socket  : ".$master."\n";
  echo "Listening on   : ".$address." port ".$port."\n\n";
  
  return $master;
}
//store socket & id
function connect($socket){
  global $sockets,$users;
  $user = new User();
  $user->id = uniqid();
  $user->socket = $socket;
  $user->peer = NULL;
  $user->state = 0;
  $user->score = 0;
  array_push($users,$user);
  array_push($sockets,$socket);
  console($socket." CONNECTED!");
}

function disconnect($socket){
  global $sockets,$users;
  $found=null;
  $n=count($users);
  for($i=0;$i<$n;$i++){
    if($users[$i]->socket==$socket){ $found=$i; break; }
  }
  if(!is_null($found)){ array_splice($users,$found,1); }
  $index = array_search($socket,$sockets);
  socket_close($socket);
  console($socket." DISCONNECTED!");
  if($index>=0){ array_splice($sockets,$index,1); }
}

function dohandshake($user,$buffer){
  console("\nRequesting handshake...");
  //console($buffer);
  list($resource,$host,$origin,$key) = getheaders($buffer);
  console("Handshaking...");

$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
            "Upgrade: WebSocket\r\n" .
            "Connection: Upgrade\r\n" .
            "Sec-WebSocket-Accept: ".base64_encode(sha1($key."258EAFA5-E914-47DA-95CA-C5AB0DC85B11",true))."\r\n".
            "\r\n";
  socket_write($user->socket,$upgrade);

  $user->handshake=true;
  //console($upgrade);
  console("Done handshaking...");
  return true;
}
function peermatch($user){
	global $sockets,$users;
	foreach($users as $us){
	  if($us->handshake&&$us->peer==NULL&&$us->id!=$user->id){
		  $us->peer = $user->socket;
		  $user->peer = $us->socket;
		  console("\nPeer find\n");
		  
		  $jsonData = '{"data":{"systemMsg" : "matched","method":"system"}}';
		  sendPeer($user->socket,$jsonData);
		  sendPeer($user->peer,$jsonData);
		  
		  break;
	  }
	}
}
function getheaders($req){
  $r=$h=$o=null;
  if(preg_match("/GET (.*) HTTP/"   ,$req,$match)){ $r=$match[1]; }
  if(preg_match("/Host: (.*)\r\n/"  ,$req,$match)){ $h=$match[1]; }
  if(preg_match("/Origin: (.*)\r\n/",$req,$match)){ $o=$match[1]; }
  if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/",$req,$match)){ $key1=$match[1]; }
  //if(preg_match("/\r\n(.*?)\$/",$req,$match)){ $data=$match[1]; }
  return array($r,$h,$o,$key1);
}

function getuserbysocket($socket){
  global $users;
  $found=null;
  foreach($users as $user){
    if($user->socket==$socket){ $found=$user; break; }
  }
  return $found;
}

function     say($msg=""){ echo $msg."\n"; }
function    wrap($msg=""){
$length=strlen($msg);
$header=chr(0x81).chr($length);
$msg=$header.$msg;
return $msg;
}
function jsonEncode($scoreSelf,$scoreEnemy,$win){
	$json = '{"data":{"scoreSelf" : '.$scoreSelf.',"scoreEnemy" : '.$scoreEnemy.',"win" : '.$win.',"method":"score"}}';
	return $json;
}


function  unwrap($msg=""){
{
$firstMask=     bindec("10000000");
$secondMask=    bindec("01000000");//im not doing anything with the rsvs since we arent negotiating extensions...
$thirdMask=     bindec("00100000");
$fourthMask=    bindec("00010000");
$firstHalfMask= bindec("11110000");
$secondHalfMask=bindec("00001111");
$payload="";
$firstHeader=ord(($msg[0]));
$secondHeader=ord($msg[1]);
$key=Array();
$fin=(($firstHeader & $firstMask)?1:0);
$rsv1=$rsv2=$rsv3=0;
$opcode=$firstHeader & (~$firstHalfMask);//TODO: make the opcode do something. it extracts it but the program just assumes text;
$masked=(($secondHeader & $firstMask) !=0);
$length=$secondHeader & (~$firstMask);
$index=2;
if($length==126)
{
$length=ord($msg[$index])+ord($msg[$index+1]);
$index+=2;
}
if($length==127)
{
$length=ord($msg[$index])+ord($msg[$index+1])+ord($msg[$index+2])+ord($msg[$index+3])+ord($msg[$index+4])+ord($msg[$index+5])+ord($msg[$index+6])+ord($msg[$index+7]);
$index+=8;
}
if($masked)
{
for($x=0;$x<4;$x++)
{
$key[$x]=ord($msg[$index]);
$index++;
}
}
//echo $length."\n";
for($x=0;$x<$length;$x++)
{
$msgnum=ord($msg[$index]);
$keynum=$key[$x % 4];
$unmaskedKeynum=$msgnum ^ $keynum;
$payload.=chr($unmaskedKeynum);
$index++;
}

if($fin!=1)
{
return $payload.processMsg(substr($msg,$index));
}
return $payload;
}
}
function console($msg=""){ global $debug; if($debug){ echo $msg."\n"; } }

class User{
  var $id;
  var $socket;
  var $handshake;
  var $peer;
  var $state; //state 1: ready to start
  var $score; //match score
  var $prefer;
  var $username;
}

?>

