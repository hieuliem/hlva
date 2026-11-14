<?php
////////////////////////////////////////////////////////////////////////////////////SETTINGS/////////////////////////////////////////////////////////////////////////////////
//fill in below info from your piwik install

$token_auth = 'ee845ebc84e523a3f777ade3e34700c3';
$piwikpath = 'http://piwik.chungnhan.org/';
$siteid = '1';
$minutes = isset($_GET['min']) ? $_GET['min'] : '5';
$last24hrs = 1440;
/////////////////////////////////////////////////////////////////////////////END OF SETTINGS///////////////////////////////////////////////////////////////////////////////////////

function buildArrayRequest($token_auth, $siteid, $piwikpath, $minutes){
	$url = $piwikpath;
	$url .= "?module=API&method=Live.getCounters&format=PHP";
	$url .= "&idSite=$siteid";
	$url .= "&token_auth=$token_auth"; 
	$url .= "&lastMinutes=$minutes";
		
	return unserialize(file_get_contents($url));
}
$last5 = buildArrayRequest($token_auth, $siteid, $piwikpath, $minutes);
$last24 = buildArrayRequest($token_auth, $siteid, $piwikpath, $last24hrs);

$html = '<span class="label">Last 24 hours:</span> <span id="last24hr" class="count">'.$last24[0]["visits"].'</span> Visits <span class="count">'.$last24[0]["actions"].'</span> Pageviews<br>';
$user = $last5[0]["visits"];
if($user>0){
	$html = '<br><br><br><span class="label">Online:</span> &nbsp;&nbsp;<span id="realstats" class="count">'.$user.'</span><br>' . $html;
}else{
	$html = '<br><br><br><span class="label">&nbsp;</span><br>' . $html;
}

echo $html;

/*
foreach($last30 as $row){	
	$hits = $row['visits'];
	echo $hits;

echo "<div class='liveWidget'>
      <div class='count'><b>$hits<br></div>
      <div class='onlinelabel'>online</div></div>";
}
*/
?>