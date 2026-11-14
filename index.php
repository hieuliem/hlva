<?php
ob_start();
session_start();

require_once('../lib/Page.php');
require_once '../lib/Mobile-Detect-2.8.3/Mobile_Detect.php';
$detect = new Mobile_Detect;

//include('inc/seasons.php');
$page = new Page;

$page->setTitle('Thánh Ca Online');
$page->startBody();
$page->addCSS('http://fonts.googleapis.com/css?family=Reenie+Beanie|Lobster');

echo $page->render('main/tvdc.php');
//echo $page->render('cmt/cmt.php');
//$page->render('lich/index.php');
if($detect->isMobile())	echo $page->render('trackingService.php');
	
$page->endBody();
echo $page->render('inc/template_jtab.php');
?>