<?php	
	require_once('../litCalendar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CPK5M5031M"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-CPK5M5031M');
    </script>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title></title>
	<meta  name="keywords" content="lịch Phụng Vụ, thánh ca, thánh vịnh, đáp ca, tập hát, Kim Long, ca đoàn, seraphim, TV, DC, ĐC, Thánh Vịnh Đáp Ca, tap hat, ca doan, thanh ca" />
	<meta  name="description" content="Lịch Thánh Ca Phụng Vụ Chúa Nhật và Lễ" />
	<meta name="author" content="Hiếu Liêm">
	<link rel="icon" type="image/png" href="/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link type="text/css" rel="stylesheet" href="../style.css?v=1" />
</head>
<?php
	$dataPath = "../data/";
	$tcpv = new thanhca();
	$tcpv->setLichPV($dataPath);
	$tcpv->printLichPV();
	
	trait lich{
	    public $current_setting = [
    		'Year'      	=> 2025,
			'Tet'			=> '01-29',
    		'Epiphany' 		=> "SUNDAY_JAN2_JAN8", 	/*JAN6*/
    		'Ascension'		=> "SUNDAY",			/*THURSDAY*/
    		'CorpusChristi'	=> "SUNDAY",			/*THURSDAY*/
    		'Locale'		=> 	"EN",
    		'ReturnType'	=>	"JSON"				/*XML, ICS*/
    	];
    }
?>