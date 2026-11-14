<?php
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;
$isMobile = "false";
if($detect->isMobile())	$isMobile = "true";
$path = (isset($_GET['d']))?$_GET['d']:"NOT PASSED!";
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=1">		
		<link rel="stylesheet" href="http://thanhca.chungnhan.org/taphat/css/style.css" media="screen">
		<!--script src="http://code.jquery.com/jquery-latest.js"></script-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script src="http://chungnhan.org/mediaelement/johndyer/build/mediaelement-and-player.min.js"></script>
		<script>
			function loadBaiHat(p, isMobile){
				$.get( p+'/media.php', { path: p } )
					.done(function( data ) {
					$('.loading').html(data);
					if(isMobile){
						$('audio#audio-player,video').mediaelementplayer();
					}else{
						var c = 0;
						$('audio#audio-player').each(function(){
							$(this).mediaelementplayer({
								alwaysShowControls: true,
								features: ['playpause','volume','progress'],
								audioVolume: 'horizontal',
								audioWidth: 400,
								audioHeight: 120,
								success: function(mediaElement, domObject) {
									if(c==0){
										mediaElement.play();
										mediaElement.addEventListener('ended', function (e) {
												alert("finished");
										}, true);
									}
								},
								error: function() {
									alert('Error setting media!');
								}
							});
							c ++;
						});
					}
				});
			}
			
			loadBaiHat('<?php echo $path ?>', <?php echo $isMobile ?>);
		</script>
	</head>
	<body>
		<div class="loading">
			<?php echo $path ?>
		</div>
		<br><br>
	</body>
</html>