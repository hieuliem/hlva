<?php
    $tvNo = "117"; // số thánh vịnh hoặc tên bài hát
    $tacgia="";
    /*=================================================*/
    $SLASH = "/";
    $UNDERSCORE = "_";
    $THANHVINH = "Thánh Vịnh ";
    $IMGEXT = ".jpg";
    
    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $thisPath = dirname($_SERVER['PHP_SELF']);
    $onlyPath = str_replace($rootPath, '', $thisPath);
    //echo $rootPath."<br>".$thisPath."<br>".$onlyPath."<br>";
    
    $pArr = explode ($SLASH, ltrim($onlyPath, $SLASH));
    list($root, $context, $folder) = $pArr;
    //print_r($pArr);
    
	$tcpath = $context.$SLASH.$folder;
    $tv = "/Tv".$tvNo;
    $img=$tv.$UNDERSCORE.$folder;
	
    if(!is_numeric($tvNo)){
        $img=$folder;
        $THANHVINH = "";
    }
	
	$ogurl = "https://thanhca.chungnhan.org".$thisPath;
	$ogtitle=$THANHVINH.$tvNo;
	$ogdescription=$tacgia."Hoàng Tiếp - Séraphim Choir @ Church of the Vietnamese Martyrs Richmond VA";
	
	if(!file_exists($img))  $img=$img."-0";
	$ogimage = $thisPath.$img.$IMGEXT;
	//echo "<br>".$tcpath."<br>".$ogtitle."<br>".$ogdescription."<br>".$ogimage;
?>
<!DOCTYPE HTML>

<html>

	<head>

		<meta charset="UTF-8" />
        <meta property="og:url"           content="<?= $ogurl?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="<?= $ogtitle ?>" />
		<meta property="og:description"   content="<?= $ogdescription ?>" />
		<meta property="og:image"         content="<?= $ogimage ?>" />

<?php
	define('SONG_TITLE', $ogtitle);
    include $_SERVER['DOCUMENT_ROOT']."/taphat/_src/defaultTcTemplate.php"; 
?>