<?php 
	if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
	include_once (__ROOT__.'/lib/curl.class.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	$geo = get('http://chungnhan.org/lib/tracking/locService.php', array('ip' => $ip));
	// $output contains the output string 
	$visitorGeolocation = json_decode($geo,true); 
	//var_dump($visitorGeolocation);
	if ($visitorGeolocation['statusCode'] == 'OK') {
		$ip = $visitorGeolocation["ipAddress"];
		$city = ucwords(strtolower($visitorGeolocation["cityName"]));
		$region = ucwords(strtolower($visitorGeolocation["regionName"]));
		$country = ucwords(strtolower($visitorGeolocation["countryName"]));
		$country_code = strtolower($visitorGeolocation["countryCode"]);
		printf("[%s]:  %s, %s (%s) %s<br>", $ip, $city, $region, $country_code, $country);
	}
	
	get('http://chungnhan.org/lib/tracking/trackPageLoad.php', array('p' => 'Liem.html'));
	echo '<br>'.get('http://chungnhan.org/lib/tracking/tracking.php');
?>