<?php
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$sessID = session_id();
if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
include_once (__ROOT__.'/lib/tracking.class.php');
include_once (__ROOT__.'/lib/tracking/trackingUtils.php');

$db = new Database();

//printf("get Page view %s", $_GET['p']);

$date = date("Y-m-d") ;
$time = date("H:i:s") ;
$ip = $_SERVER['REMOTE_ADDR'] ;
$query_string = $_SERVER['QUERY_STRING'] ;
$http_referer = isset( $_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "no referer" ;
$http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "no User-agent" ;
$web_page = isset($_GET['p'])?$_GET['p']:$_SERVER['SCRIPT_NAME'];
$isbot = is_Bot() ? '1' : '0' ;

if(is_localhost()){
	$country_code = 'us' ;
	$country = 'United States' ;
	$region = 'Virginia' ;
	$city = 'Richmond' ;
}

$country_data = array(
    "country_code"=>$country_code,
	"country_name"=>$country
);

$live_data = array(
    "country_code"=>$country_code,
	"region"=>$region,
	"city"=>$city,
	"sessID"=>$sessID,
	"ip"=>$ip
);

$track_data = array(
    "country_code"=>$country_code,
	"region"=>$region,
	"city"=>$city,
	"date"=>$date,
	"time"=>$time,
	"ip"=>$ip,
	"web_page"=>$web_page,
	"query_string"=>$query_string,
	"http_referer"=>$http_referer,
	"http_user_agent"=>$http_user_agent,
	"is_bot" =>$isbot
);

if($web_page=="home"){
	$db->insert("visitor_country",$country_data, " ON DUPLICATE KEY UPDATE counts=counts+1");
	$db->insert("visitor_live",$live_data, " ON DUPLICATE KEY UPDATE ts=NOW()");
	$db->delete("visitor_live","ts < (NOW() - INTERVAL 5 MINUTE)");
}
$db->insert("visitor_tracker",$track_data);

$db->disconnect();
?>