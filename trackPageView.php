<?php
if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/lib/tracking/tracking_config.php');
include_once (__ROOT__.'/lib/crud.class.php');
include_once (__ROOT__.'/lib/tracking/utils.php');
$db = new Database();

$date = date("Y-m-d") ;
$time = date("H:i:s") ;
$ip = $_SERVER['REMOTE_ADDR'] ;
$query_string = $_SERVER['QUERY_STRING'] ;
$http_referer = isset( $_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "no referer" ;
$http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "no User-agent" ;
$web_page = isset($_GET['pageview']) ? $_GET['pageview'] : $_SERVER['SCRIPT_NAME'] ;
$isbot = is_Bot() ? '1' : '0' ;

if(is_localhost()){
	$country_code = 'us' ;
	$country = 'United States' ;
	$region = 'Virginia' ;
	$city = 'Richmond' ;
}

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

$db->insert("visitor_tracker",$track_data);
$db->Close();
?>
