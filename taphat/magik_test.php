<?php
$PDF_ext = '.pdf';
$JPG_ext = '.jpg';
$SLASH = '/';
$tongphoiDir = 'tongphoi';

$rootPath = $_SERVER['DOCUMENT_ROOT'];
$thisPath = dirname($_SERVER['PHP_SELF']);
    
if(isset($_GET['view'])){
	displayImg($_GET['view'],$JPG_ext);
}else if(isset($_GET['dir'])){
	$overwrite = isset($_GET['over']);
	$d = $_GET['dir'];
	echo $d.'<br>';
	
	if(is_dir($d)){
		$pdfs = glob($d."*".$PDF_ext);
		foreach($pdfs as $pdf){
			echo $pdf.'<br>';
			$f = strstr($pdf, $PDF_ext, true);
			$img = $f.$JPG_ext;
			echo $img."<br>";
			if(strpos($f, "TongPhoi") !== false){
			    $newDir = $d.$tongphoiDir;
			    if(!is_dir($newDir))   mkdir($newDir, 0777, true);
			    $n = str_replace($d,"",$f); echo "$n=".$n."<br>";
			    $img = $newDir.$SLASH.$n.$JPG_ext;
			}echo "New: ".$img."<br>";
			
			
			if(sizeof(glob($f."*".$JPG_ext))>0&&!$overwrite){
				echo "pdf to img already there: $f<br>";				
			}else{
				echo "PDF to IMG proccessing .. ";
				pdf2jpg($pdf,$img);
				echo "DONE<br>";
				displayImg(str_replace($JPG_ext,"",$img), $JPG_ext);
			}
			
			//if(is_dir($newDir))    $f = $newDir.$SLASH.$n;
			
		}
	}
}else if(isset($_GET['pdf'])){
	$f = $_GET['pdf'];
	$pdf = $f.$PDF_ext;
	$img = $f.$JPG_ext;
	if (file_exists($img)){
		echo $img . ' existed!';
	}else{
		pdf2jpg($pdf,$img);		
	}
	// if want to?
	displayImg($f,$JPG_ext);
}else{
	testIM();
}

function displayImg($f,$ext){
	if (file_exists($f.$ext)){
		echo '<img src="'.$f.$ext.'">';
	}else{
		$n = 0;
		$file = $f.'-'.$n++.$ext;
		while(file_exists($file)){
			echo '<img src="'.$file.'">';
			$file = $f.'-'.$n++.$ext;
		}
	}
}

function pdf2jpg($pdf,$img){//$img = str_replace($JPG_ext, '-%02d'.$JPG_ext, $img);
	if(file_exists($pdf)){
		exec('convert -colorspace sRGB -density 300 -verbose '.$pdf.' -background white -alpha background -alpha off -quality 100 '.$img);
	}else{
		echo $pdf.' NOT found';
	}	
}
/*
// This file will run a test on your server to determine the location and versions of ImageMagick. 
//It will look in the most commonly found locations. The last two are where most popular hosts (including "Godaddy") install ImageMagick.
//
// Upload this script to your server and run it for a breakdown of where ImageMagick is.
//
*/echo "<pre>";
system("type convert"); 
echo "</pre>";

function testIM(){
echo '<h2>Test for versions and locations of ImageMagick</h2>';
echo '<b>Path: </b> convert<br>';
 
function alist ($array) {  //This function prints a text array as an html list.
    $alist = "<ul>";
    for ($i = 0; $i < sizeof($array); $i++) {
        $alist .= "<li>$array[$i]";
    }
    $alist .= "</ul>";
    return $alist;
}
 
exec("convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
echo alist($out); //Print the output of "convert -version"



echo '<br>';
echo '<b>This should test for ImageMagick version 5.x</b><br>';
echo '<b>Path: </b> /usr/bin/convert<br>'; 
exec("/usr/bin/convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
echo alist($out); //Print the output of "convert -version"
 
echo '<br>';
echo '<b>This should test for ImageMagick version 6.x</b><br>';
echo '<b>Path: </b> /usr/local/bin/convert<br>';
 
exec("/usr/local/bin/convert -version", $out, $rcode); //Try to get ImageMagick "convert" program version number.
echo "Version return code is $rcode <br>"; //Print the return code: 0 if OK, nonzero if error.
echo alist($out); //Print the output of "convert -version";
}
?>