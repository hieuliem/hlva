<?php
	$p = (isset($_GET['path']))?$_GET['path'].'/':"";
	$mp3Names = array(
		"TongPhoi.mp3" => "Tổng Phối",
		"Dan.mp3" => "Đàn Đệm",
		"Nu.mp3" => "Giọng Nữ",
		"Nam.mp3" => "Giọng Nam",
		"Soprano.mp3" => "Soprano",
		"Alto.mp3" => "Alto",
		"Tenor.mp3" => "Tenor",
		"Bass.mp3" => "Bass",
		"Organ.mp3" => "Organ",
		"Piano.mp3" => "Piano",
		"Violin.mp3" => "Violin",
		"KeyBoard.mp3" => "Keyboard",
		"Guitar.mp3" => "Guitar"
	);
	
	$n = 1;
	$mp3s = glob("*.mp3");
	//echo $mp3s[0].'<br>';
	$fName = strstr($mp3s[0], '.mp3', true);
	echo '&nbsp;<br>';
	$TV = str_replace("Tv","Thánh Vịnh ", strstr($mp3s[0], '_', true));
	buildPlayer($p,$mp3s[0],$TV, $n);
	unset($mp3s[0]);
	foreach($mp3Names as $k=>$v){
		foreach($mp3s as $mk=>$mv){
			if(endsWith($mv,$k)){
				//echo $mk.' => '.$mv.'<br>';
				buildPlayer($p,$mv,$v,++$n);
				unset($mp3s[$mk]);
				break;
			}
		}
	}
	
	function buildPlayer($p,$mp3,$label,$n){
		echo '<div class="col-md-1"></div><div class="col-sm-6 col-md-5">';
		echo '<div class="audio-player">';
		echo '<h1>'.$label.'</h1>';
		echo '<audio id="audio-player" src="'.$p.$mp3.'" type="audio/mp3" controls="controls"></audio>';
		echo '</div></div>';
		if($n % 2 == 0)	echo '<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';
	}
	
	//displayImg($p.$fName,".jpg");
    if(is_dir("tongphoi")){
		echo '<div class="toolContainer">';
			echo '<div class="lyrics"><button id="lyricsBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Main version!</button></div>';
			echo '<div class="hoaam"><button id="hoaamBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Hòa Âm - Tổng Phối version!</button></div>'; 
			echo '<div class="fb"><iframe src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fthanhca.chungnhan.org%2Ftaphat%2Fbaihatmoi%2FChuaSongTrongToi&layout=button_count&size=small&width=77&height=20&appId" width="77" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe></div>';
		echo '</div>';
		
		echo '<script>$("#hoaamBtn").click(function(){$(".hoaam").hide();$(".lyrics").show();$("#main").hide();$("#tongphoi").show();});</script>';
		echo '<script>$("#lyricsBtn").click(function(){$(".lyrics").hide();$(".hoaam").show();$("#tongphoi").hide();$("#main").show();});</script>';
		
		$tongphoiJPG = glob("tongphoi/*.jpg");
		echo '<div id="tongphoi" style="display: none;">';
		foreach($tongphoiJPG as $tp){

			//echo $p.$tp.'<br>';

			echo '<img src="'.$p.$tp.'" width="100%">';

		}echo '</div>';
	}
	
	$pdfs = glob("*.pdf");
	echo '<div class="col-md-1"></div><div id="pdf">';
	foreach($pdfs as $pdf){
	    if(preg_match('/'.$fName.'_(.*?).pdf/', $pdf, $match) == 1) {
            echo '<a style="margin-left: 20px;" href="'.$p.$pdf.'">'.$match[1].'</a>';
        }else	    echo '<a style="margin-left: 20px;" href="'.$p.$pdf.'">'.$pdf.'</a>';
	}echo '</div>';

	$jpgs = array_merge(glob($fName."-*.jpg"),glob($fName."_Page_*.jpg"));
	if(sizeof($jpgs)==0){
		$jpgs = glob($fName.".jpg");
	}
	
	foreach($jpgs as $jpg){
		//echo $p.$jpg.'<br>';
		echo '<img src="'.$p.$jpg.'" width="100%">';
	}

	
	function startsWith($haystack, $needle){
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
	function endsWith($haystack, $needle){
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
?>