<?php
define("incPath", "../inc/");
define("jsonPath", incPath."json/");
require_once '../../lib/Mobile-Detect-2.8.3/Mobile_Detect.php';
require_once(incPath.'pv.class.php');

$detect = new Mobile_Detect;
$pv = new phungvu(jsonPath."ABC.json");


$name = $pv->currYear();
if(isset($_GET['name'])){
	$name = $_GET['name'];
	if(in_array($name,$pv->getNamPvArr())){
		$pv->nam($name, jsonPath);
		echo $pv->tvdc->printList($detect->isMobile());
	}else{
		$pv->thanhca(jsonPath."$name.json");
		echo $pv->tc->printList($detect->isMobile());
	}
}

?>