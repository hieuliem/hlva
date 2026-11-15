<?php
require_once('inc/pv.class.php');
require_once '../lib/Mobile-Detect-2.8.3/Mobile_Detect.php';
$pv = new phungvu("inc/json/ABC.json");

$new_order = array("tc","gs","tt","ps","st");
$tcTab = $pv->getOrderTab($new_order);

$detect = new Mobile_Detect;

// Automatically determine current and next Liturgical Calendar years from active cycle in ABC.json
$currLetter = $pv->currYear();
$currRange  = $pv->getYearRange($currLetter); // e.g. "(2025-2026)"
$calYear1   = 2026;
if (preg_match('/-(\d{4})\)/', $currRange, $m)) {
    $calYear1 = intval($m[1]);
}
$calYear2 = $calYear1 + 1;
?>
<div id="tvdcTab">
	<ul>
	<?php
		foreach($pv->getNamPvArr() as $n){
			echo '<li><a href="#'.$n.'" data="main/pvUtils.php?name='.$n.'">Phụng Vụ Đáp Ca Năm '.$n.' '.$pv->getYearRange($n).'</a></li> ';
		}
	?><li><a href="#3" target="_blank">Lịch Phụng Vụ <?php echo $calYear1; ?></a></li>
	<li><a href="#4" target="_blank">
	<?php
	    if($detect->isMobile())	echo " Lịch Phụng Vụ";
	?> <?php echo $calYear2; ?>
	</a></li>
	</ul>

	<?php
		foreach($pv->getNamPvArr() as $n){
			echo '<div id="'.$n.'"></div>';
		}
	?><div id="3"></div><div id="4"></div>
</div>
<div id="tcTab">
	<ul>
	<?php
		foreach($tcTab as $k => $v){
			echo '<li><a href="#'.$k.'" data="main/pvUtils.php?name='.$k.'">'.$v.'</a></li> ';
		}
	?>
	</ul>
	<?php
		foreach($tcTab as $k => $v){
			echo '<div id="'.$k.'"></div>';
		}
	?>
</div>
<div>&nbsp;</div>
<script>
$(document).on('click', 'a[href="#3"]', function() {
   window.location.href = "lich/<?php echo $calYear1; ?>";
});
$(document).on('click', 'a[href="#4"]', function() {
   window.location.href = "lich/<?php echo $calYear2; ?>";
});
</script>
