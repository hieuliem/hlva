<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://thanhca.chungnhan.org/taphat/css/style.css" media="screen">

		<style>
			.toolContainer{
				/*clear: both;*/
			  display: flex;
			  padding-top: 20px;
			  padding-left: 7.3rem;
			  padding-right: 1.8rem;
			}

			.lyrics,
			.hoaam,
			.fb{
			  display: inline-block;
			}

			.left-wrapper,
			.right-wrapper {
			  display: inline-block;
			}

			.lyrics,
			.hoaam {
			  flex: 1;
			  margin-right: auto;
			}
			
			.lyrics{
				display: none;
			}
		</style>

		<!--script src="http://code.jquery.com/jquery-latest.js"></script-->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

		<script src="https://chungnhan.org/lib/bootstrap/bootstrap.min.js"></script>

		<script src="https://chungnhan.org/lib/players/mediaelement-and-player.min.js"></script>

		<script>

			$(function() {

				var c = 0;

				$('audio#audio-player').each(function(){

					$(this).mediaelementplayer({

						alwaysShowControls: true,

						features: ['playpause','volume','progress'],

						audioVolume: 'horizontal',

						success: function(mediaElement, domObject) {

							if(c==0){

								mediaElement.play();

								mediaElement.addEventListener('ended', function (e) {

									//alert("finished");

								}, true);

							}

						},

						error: function() {

							alert('Error setting media!');

						}

					});

					c ++;

				});

			});

		</script>

	</head>

<body>

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
	if(empty($TV) && defined('SONG_TITLE'))  $TV = SONG_TITLE;  // passed from INDEX.
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
	
	function startsWith($haystack, $needle){
		return $needle === "" || strpos($haystack, $needle) === 0;
	}
	function endsWith($haystack, $needle){
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
?>
<?php
	//echo $folder;
	//displayImg($p.$fName,".jpg");
    
	echo '<div class="toolContainer">';
		echo '<div class="lyrics"><button id="lyricsBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Main version!</button></div>';
		echo '<div class="hoaam"><button id="hoaamBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Hòa Âm - Tổng Phối version!</button></div>'; 
		echo '<div class="fb"><iframe src="https://www.facebook.com/plugins/share_button.php?href=https%3A%2F%2Fthanhca.chungnhan.org%2Ftaphat%2F'.$tcpath.'&layout=button_count&size=small&width=77&height=20&appId" width="77" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe></div>';
	echo '</div>';
	if(is_dir("tongphoi")){	
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
?>

<!-- Histats.com  (div with counter) --><div id="histats_counter" style="text-align:center;"></div>
    <!-- Histats.com  START  (aync)-->
    <script type="text/javascript">var _Hasync= _Hasync|| [];
    _Hasync.push(['Histats.start', '1,4723141,4,244,241,20,00011111']);
    _Hasync.push(['Histats.fasi', '1']);
    _Hasync.push(['Histats.track_hits', '']);
    (function() {
    var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
    hs.src = ('//s10.histats.com/js15_as.js');
    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
    })();</script>
    <noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?4723141&101" alt="free hit counters" border="0"></a></noscript>
    <!-- Histats.com  END  --><!-- Responsive Tabs JS -->
	
	<script type="text/javascript" src="//rf.revolvermaps.com/0/0/6.js?i=5qngijvwwmz&amp;m=7&amp;c=e63100&amp;cr1=ffffff&amp;f=arial&amp;l=0&amp;bv=90&amp;lx=-420&amp;ly=420&amp;hi=20&amp;he=7&amp;hc=a8ddff&amp;rs=80" async="async"></script>
	
	</body>

</html>