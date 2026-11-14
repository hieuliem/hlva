<?php
	//include('top-cache.php');
	include('../ipinfodb/ip2locationlite.class.php');
	
	// Secret key to encrypt/decrypt with 
	$key='seraphimhoanca'; // 8-32 characters without spaces 
	$api_key = '2f34fb88fac1557e947af063be35c929db048184505924f583f184dc0584880e';
	$greeting = '';
	$contact = '<br>Nếu có gì thắc mắc, bạn có thể liên lạc với <a href="mailto:hoang-tiep@hotmail.com">Ca Trưởng</a>,<br>hoặc gởi ý kiến đến chúng tôi ở <a href="mailto:seraphim@chungnhan.org">cđ. Séraphim</a><br><i>Church of the Vietnamese Martyrs of Richmond, VA</i><br><br>';
	$name = "unknown";
	$type = "non-member";
	$aDay = time()+60*60*24;
	
	if (isset($_COOKIE['sername'])) {
		$cookie = $_COOKIE['sername'];
		if($cookie==$name){ // tracking if that non-member come back
			$greeting = '<center><div style="color:black;display:block;width:350px;"><span style="color:red;font-weight:bold;">Chào Bạn đã trở lại với trang Tập Hát.</span></div></center>';
		}else{
			$type = "member"; 
			$name = convert($cookie,$key);
		}
	}else{ // unknown
		setcookie('sername', $name, time()+60*60*2*365,"/","");
		$greeting = '<center><div style="color:black;display:block;width:350px;"><span style="color:red;font-weight:bold;">Chào Bạn mới đến với trang Tập Hát.</span></div></center>';
	}
	
	if (isset($_GET['mem'])) {
		setcookie('sername', "", time()-3600);
		/* Set cookie to last 1 year */
        setcookie('sername', $_GET['mem'], time()+60*60*24365,"/","chungnhan.org");
		$type = "member";
		$name = convert($_GET['mem'],$key);
	}
	
	//Set geolocation cookie
	if(!isset($_COOKIE["geolocation"])||!$_COOKIE["geolocation"]){
		$ipLite = new ip2location_lite;
		$ipLite->setKey($api_key);
		
		//$visitorGeolocation = $ipLite->getCountry($_SERVER['REMOTE_ADDR']);
		$visitorGeolocation = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
		//$errors = $ipLite->getError();
		
		if ($visitorGeolocation['statusCode'] == 'OK') {
			$data = base64_encode(serialize($visitorGeolocation));
			setcookie("geolocation", $data, time()+60*60*2*7,"/",""); //set cookie for 1 week
		}
	}else{
	  	$visitorGeolocation = unserialize(base64_decode($_COOKIE["geolocation"]));
	}
	
	$city = $visitorGeolocation["cityName"];
	$region = $visitorGeolocation["regionName"];
	$country = $visitorGeolocation["countryName"];
	
	// String EnCrypt + DeCrypt function 
	// Author: halojoy, July 2006 
	function convert($str,$ky=''){ 
		if($ky=='')return $str; 
		$ky=str_replace(chr(32),'',$ky); 
		if(strlen($ky)<8)exit('key error'); 
		$kl=strlen($ky)<32?strlen($ky):32; 
		$k=array();for($i=0;$i<$kl;$i++){ 
		$k[$i]=ord($ky{$i})&0x1F;} 
		$j=0;for($i=0;$i<strlen($str);$i++){ 
		$e=ord($str{$i}); 
		$str{$i}=$e&0xE0?chr($e^$k[$j]):chr($e); 
		$j++;$j=$j==$kl?0:$j;} 
		return $str; 
	} 
	/////////////////////////////////// 
?>