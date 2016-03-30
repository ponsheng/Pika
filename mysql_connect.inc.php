<?php
$host="140.116.245.148";
$user="e94026107";
$upwd="z1z2z3z4"; 
$db="e94026107";
$link=mysql_connect($host,$user,$upwd) or die ("Unable to connect!");
mysql_select_db($db,$link) or die ("Unable to select database!");

?>