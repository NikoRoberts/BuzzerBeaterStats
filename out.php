<?php
session_start();

if(strpos($_SERVER['HTTP_REFERER'],'stats')>-1) header('Location:'.$_GET['url']);
else die($_SERVER['HTTP_REFERER']."Bad Url");

include('functions.php');

$IPaddress=addslashes($_SERVER['REMOTE_ADDR']);
$cc=iptocountry($IPaddress);
$url = addslashes($_GET['url']);

?>
