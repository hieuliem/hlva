<?php
if(!defined('__ROOT__')) define('__ROOT__', dirname(dirname(__FILE__)));
include_once (__ROOT__.'/lib/tracking.class.php');
$db = new Database();

$live = '';
if ($result=$db->myquery("SELECT country_code, count(*) AS hits FROM visitor_live GROUP BY country_code")){
	/* fetch associative array */
    while ($row = $result->fetch_assoc()) {
		$countryCode = $row["country_code"];
		$img = 'blank.gif';
		if($countryCode=='xx'){
			$img = 'q.gif';
		}
        $live = $live.$row["hits"].' <img src="images/'.$img.'" class="flag flag-'.$countryCode.'" alt="'.$countryCode.'" /> &nbsp;&nbsp;';
    }
	// Free result set
	mysqli_free_result($result);
}

$db->disconnect();
?>
<span class="label zeroPadding">Online:</span> <span class="count"><?php echo $live; ?></span><br>
