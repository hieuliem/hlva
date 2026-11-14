<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
</head>
<?php
// this token is used to authenticate your API request. 
// You can get the token on the API page inside your Piwik interface
$token_auth = 'ee845ebc84e523a3f777ade3e34700c3';
$breakline = "<br>=====================================<br>";

$result4 = file_get_contents("http://piwik.chungnhan.org/?module=API&method=UserCountry.getCountry&idSite=1&date=2012-10-21,today&period=range&format=php&token_auth=$token_auth");
$out4 = unserialize($result4);

$total = 0;
$pages = 0;
$mess = '<div id="right2left">';
foreach ($out4 as $row) {
	//$keyword = htmlspecialchars(html_entity_decode(urldecode($row['label']), ENT_QUOTES), ENT_QUOTES);
	$country = $row['label'];
	$countryCode = $row['code'];
	$hits = $row['nb_visits'];
	$flag = $row['logo'];
	
	$img = 'blank.gif';
	if($countryCode=='xx'){
		$img = 'q.gif';
	}
	@$total = $total + $hits;
	@$pages = $pages + $row['nb_actions'];
	@$mess = $mess.'<p><img src="images/'.$img.'" class="flag flag-'.$countryCode.'" alt="'.$country.'" /> '.$hits.' '.$country.' &nbsp;&nbsp;&nbsp;</p>';
	//@$mess = $mess.'<p><img src="http://piwik.chungnhan.org/'.$flag.'" /> '.$hits.' '.$country.' &nbsp;&nbsp;&nbsp;</p>';
}
$mess = $mess.'</div>';

//echo "Since 10/21/2012: &nbsp;&nbsp;<b>$total</b> Visitors &nbsp;&nbsp;&nbsp;<b>$pages</b> Pageviews<br>$mess";

$result5 = file_get_contents("http://piwik.chungnhan.org/index.php?module=API&method=Live.getLastVisitsDetails&idSite=1&period=day&date=today&format=php&token_auth=$token_auth");
$out5 = unserialize($result5);
$simple = array();
foreach ($out5 as $row) {
	$city = ucwords(strtolower($row["customVariables"][3]["customVariableValue3"]));
	$st = ucwords(strtolower($row["customVariables"][4]["customVariableValue4"]));
	$countryCode = $row['countryCode'];	
	
	if($city=="Ha Noi")	$st = "Ha Noi";
	if($st=="Ho Chi Minh"){
		$st = $city = "Saigon";
	}
	if($st=="District Of Columbia"){
		$st = "DC";
	}
	if($city==""){
		if($st!="")	$city = $st;
		else	continue;
	}
	
	$simple[] = array($city,$st,$countryCode);
}
asort($simple);
$r = array();
$prev_value = array('value' => null, 'amount' => null);

foreach ($simple as $val) {
    if ($prev_value['value'] != $val) {
        unset($prev_value);
        $prev_value = array('value' => $val, 'amount' => 0);
        $r[] =& $prev_value;
    }

    $prev_value['amount']++;
}

//var_dump($r);
$count = 0;
$mess2 = '<div id="left2right">';
foreach ($r as $item){	
	list($city,$st,$countryCode) = $item['value'];
	$img = 'blank.gif';
	if($countryCode=='xx'){
		$img = 'q.gif';
	}

	$loc =  '<p>'.$item['amount'] .' ' .$city.', '.$st.' <img src="../images/'.$img.'" class="flag flag-'.$countryCode.'" alt="'.$countryCode.'" /> &nbsp;&nbsp;</p>';

	$mess2 .= $loc;
	$count += $item['amount'];
}
$mess2 .= '</div>';

$stats = '<span class="count">'.$count.'</span> <span class="description"> Visits in </span><span class="count">'.sizeof($r).' <span class="description">Regions</span>';
?>
<!-- cities -->
<br>
<div id="cityscrolling"><span class="label zeroPadding">Last 24 hrs:</span> <?php echo $stats ?><?php echo $mess2 ?></div>

<span class="label zeroPadding">Since 10/21/2012:</span> 
<span class="count"><?php echo $total ?></span> <span class="description">Visits</span>
<span class="count"><?php echo $pages ?></span> <span class="description">Pageviews</span>
<!-- countries -->
<div style="clear: both;margin-bottom:15px;"><?php echo $mess ?> </div>
<link rel="Stylesheet" type="text/css" href="css/flags.css" />
<link rel="Stylesheet" type="text/css" href="css/smoothDivScroll.css" />
<!-- jQuery UI Widget and Effects Core (custom download)
	 You can make your own at: http://jqueryui.com/download -->
<script src="js/jquery-2.1.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js" type="text/javascript"></script>

<!-- Latest version of jQuery Mouse Wheel by Brandon Aaron
	 You will find it here: http://brandonaaron.net/code/mousewheel/demos -->
<script src="js/jquery.mousewheel.min.js" type="text/javascript"></script>

<!-- jQuery Kinetic - for touch -->
<script src="js/jquery.kinetic.js" type="text/javascript"></script>

<script src="js/jquery.smoothdivscroll-1.3-min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
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