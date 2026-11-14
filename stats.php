<?php
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
$sessID = session_id();
if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
include_once (__ROOT__.'/lib/tracking.class.php');
include_once (__ROOT__.'/lib/tracking/utils.php');

$db = new Database();

$date = date("Y-m-d") ;
$time = date("H:i:s") ;
$ip = $_SERVER['REMOTE_ADDR'] ;
$query_string = $_SERVER['QUERY_STRING'] ;
$http_referer = isset( $_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "no referer" ;
$http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "no User-agent" ;
$web_page = $_SERVER['SCRIPT_NAME'] ;
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

$db->insert("visitor_country",$country_data, " ON DUPLICATE KEY UPDATE counts=counts+1");
$db->insert("visitor_live",$live_data, " ON DUPLICATE KEY UPDATE ts=NOW()");
$db->delete("visitor_live","ts < (NOW() - INTERVAL 5 MINUTE)");
$db->insert("visitor_tracker",$track_data);

$total = 37758;
$pages = 218390;
$cntries = '';
if ($result=$db->select("visitor_country","counts>0","counts DESC, country_name")){
	$total = 0;
	/* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        //printf ("%s (%s) %s<br>", $row["counts"], $row["country_code"], $row["country_name"]);
		$country = $row["country_name"];
		$countryCode = $row["country_code"];
		$hits = $row["counts"];
		
		$img = 'blank.gif';
		if($countryCode=='xx'){
			$img = 'q.gif';
		}
		@$total = $total + $hits;
		@$pages = $pages + $row['nb_actions'];
		@$cntries = $cntries.'<p><img src="images/'.$img.'" class="flag flag-'.$countryCode.'" alt="'.$country.'" /> '.$hits.' '.$country.' &nbsp;&nbsp;&nbsp;</p>';
    }
	// Free result set
	mysqli_free_result($result);
}

$last24_hits = 0;
$cities = '';
$last24_views = 0;
$rArr = array();
//if($result=$db->select("visitor_tracker","TIMESTAMP(`date`,`time`) > DATE_SUB(NOW(), INTERVAL 24 HOUR)","ip")){
if($result=$db->myquery("SELECT ip, city, region, country_code, date, time, CONCAT(date,' ',time) AS dt, CONCAT(city,'-',region,'-',country_code) AS loc FROM `visitor_tracker` WHERE TIMESTAMP(`date`,`time`) > DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY city, region, ip, time")){
	$last24_views = mysqli_num_rows($result);
	$_ip = '';
	$_dt = '';
	
	/* fetch associative array */
    while ($row = $result->fetch_assoc()) {
		$date_time = new DateTime($row["dt"]);	
		if($_ip!=$row["ip"]){// distinct user
			$_ip = $row["ip"];
			$last24_hits = $last24_hits+1;
			setVal($row["loc"]);
		}else{			
			$interval = $_dt->diff($date_time);
			$diff = $interval->format("%i%");

			if($diff > 5){
				$last24_hits = $last24_hits+1;
				setVal($row["loc"]);
			}
		}
		$_dt = $date_time;
		//printf("<br>%s, %s (%s). %s||%s |||| %s || %s",$row["ip"], $row["city"],$row["region"],$row["country_code"],$row["date"],$row["time"],$diff);
	}

	// Free result set
	mysqli_free_result($result);
}
arsort($rArr);
foreach ($rArr as $region => $value){
	$row = explode('-', $region);
	$city = $row[0];
	$region = $row[1];
	$countryCode = $row[2];
	
	$img = 'blank.gif';
	if($countryCode=='xx'){
		$img = 'q.gif';
	}

	if($city!='')	$cities = $cities.'<p> &nbsp;&nbsp;'.$value.' '.$city.', '.$region.' <img src="images/'.$img.'" class="flag flag-'.$countryCode.'" alt="'.$countryCode.'" /></p>';
}

function setVal($k){
	global $rArr;
	if(empty($rArr)){
		$rArr[$k] = 1;
	}else if(array_key_exists($k, $rArr)){
		$rArr[$k] = $rArr[$k]+1;
	}else{
		$rArr[$k] = 1;
	}
}


$db->disconnect();
?>
<div id="live"></div>

<span class="label zeroPadding">Last 24 Hrs:</span>
<span class="count"><?php echo $last24_hits ?></span> <span class="description">Visits</span>
<span class="count"><?php echo $last24_views ?></span> <span class="description">Pageviews</span>
<div id="left2right"><?php echo $cities ?></div>

<span class="label zeroPadding">Since 10/21/2012:</span> 
<span class="count"><?php echo $total ?></span> <span class="description">Visits</span>
<span class="count"><?php echo $pages ?></span> <span class="description">Pageviews</span>
<!-- countries -->
<div style="clear: both;margin-bottom:15px;"><div id="right2left"><?php echo $cntries ?></div> </div>
<link rel="Stylesheet" type="text/css" href="css/flags.css" />
<link rel="Stylesheet" type="text/css" href="css/smoothDivScroll.css" />
<!-- jQuery UI Widget and Effects Core (custom download)
	 You can make your own at: http://jqueryui.com/download -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>

<!-- Latest version of jQuery Mouse Wheel by Brandon Aaron
	 You will find it here: http://brandonaaron.net/code/mousewheel/demos -->
<script src="js/jquery.mousewheel.min.js" type="text/javascript"></script>

<!-- jQuery Kinetic - for touch -->
<script src="js/jquery.kinetic.min.js" type="text/javascript"></script>
<script src="js/jquery.smoothdivscroll-1.3-min.js"></script>


<script type="text/javascript">

$(document).ready(function(){
	$( "#live" ).load('live.php');
	var handler= window.setInterval(function(){
		$('#live').fadeOut("slow").load('live.php').fadeIn('slow'); 
	},30000);
	
	$("#right2left").smoothDivScroll({
		autoScrollingMode: "always", 
		autoScrollingDirection: "endlessLoopRight", 
		autoScrollingStep: 1, 
		autoScrollingInterval: 8 /*, 
		getContentOnLoad: { 
			method: "getAjaxContent",
			content: "ajaxContent.html",
			manipulationMethod: "replace"
		}*/
	});
	
	$("#left2right").smoothDivScroll({
		autoScrollingMode: "always",
		autoScrollingDirection: "endlessLoopLeft",
		autoScrollingStep: 1,
		autoScrollingInterval: 15 
	});

	$("#right2left").bind("mouseover", function () {
		$("#right2left").smoothDivScroll("stopAutoScrolling");
	}).bind("mouseout", function () {
		$("#right2left").smoothDivScroll("startAutoScrolling");
	});
	
	$("#left2right").bind("mouseover", function () {
		$("#left2right").smoothDivScroll("stopAutoScrolling");
	}).bind("mouseout", function () {
		$("#left2right").smoothDivScroll("startAutoScrolling");
	});
});
</script>