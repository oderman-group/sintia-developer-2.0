<?php
/*
function getip() //Averigua el numero Ip del usuario
{
if (getenv('HTTP_X_FORWARDED_FOR')){
$ip=getenv('HTTP_X_FORWARDED_FOR');
} else {
$ip=getenv('remote_addr');
}
if (strpos($ip,",")>0)
{
$ip=substr($ip,0,strpos($ip,",")-1);
}
return $ip;
}
$ipp=getip();
*/
$ipp = $_SERVER['REMOTE_ADDR'] ;
?>