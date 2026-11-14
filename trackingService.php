<?php 
	if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
	include_once (__ROOT__.'/lib/curl.class.php');
		
	echo '<br>'.get('https://chungnhan.org/lib/tracking/stats.php');
?>