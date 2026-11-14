<?php	require_once('inc/pv.class.php');	$pv = new phungvu("inc/json/ABC.json");	$key = array_search ($pv->currYear(), $pv->getNamPvArr());	
	require_once '../lib/Mobile-Detect-2.8.3/Mobile_Detect.php';
	$detect = new Mobile_Detect;
	$isMobile = "false";
	if($detect->isMobile())	$isMobile = "true";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CPK5M5031M"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-CPK5M5031M');
    </script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $this->title; ?></title>
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	<meta  name="keywords" content="Christmas, Easter, Psalm, thánh ca, thánh vịnh, đáp ca, tập hát, Kim Long, ca đoàn, seraphim, TV, DC, ĐC, Thánh Vịnh Đáp Ca, tap hat, ca doan, thanh ca" />
	<meta  name="description" content="Trang Tập Hát Online phục vụ Thánh Vịnh Đáp Ca hàng tuần.  Dựa trên bộ Đáp Ca Lm. Kim Long (mới), thực hiện bởi ca đoàn Séraphim Giáo xứ Các Thánh Tử Đạo Việt Nam Richmond, VA." />
	<meta name="author" content="Hiếu Liêm">
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
	<link href="css/bootstrap.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="css/responsive-tabs-style.css" />
    <link type="text/css" rel="stylesheet" href="css/responsive-tabs.css" />
    
	<link type="text/css" rel="stylesheet" href="css/jquery.lazyloadxt.spinner.css">
	<!--link rel="stylesheet" href="http://chungnhan.org/mediaelement/johndyer/build/mediaelementplayer.css" /-->
	<link rel="stylesheet" href="taphat/css/style.css" media="screen">
	<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
	<style>
		/* Demo Player Options for Chúa Nhật 33 Thường Niên (cn33A) */
		.demo-switcher-bar {
			clear: both;
			background: #1e293b;
			color: #fff;
			padding: 12px 16px;
			border-radius: 8px;
			margin: 12px 15px 16px 15px;
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			gap: 8px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
		}
		.demo-switcher-bar span.demo-title {
			font-weight: bold;
			margin-right: 8px;
			color: #38bdf8;
		}
		.demo-btn {
			background: #334155;
			color: #e2e8f0;
			border: 1px solid #475569;
			padding: 6px 14px;
			border-radius: 6px;
			cursor: pointer;
			font-size: 13px;
			font-weight: 600;
			transition: all 0.15s ease;
		}
		.demo-btn:hover {
			background: #475569;
			color: #fff;
		}
		.demo-btn.active {
			background: #0284c7;
			color: #fff;
			border-color: #38bdf8;
		}
		/* Option 1: Plyr Grid */
		.demo-plyr-grid, .demo-native-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 12px;
			margin: 12px 15px 18px 15px;
		}
		.demo-track-card {
			background: rgba(255,255,255,0.92);
			border: 1px solid #cbd5e1;
			border-radius: 8px;
			padding: 10px 14px;
			box-shadow: 0 2px 5px rgba(0,0,0,0.06);
		}
		.demo-track-card.playing {
			border-color: #0284c7;
			box-shadow: 0 0 0 2px rgba(2,132,199,0.25);
		}
		.demo-track-title {
			font-size: 14px;
			font-weight: 700;
			color: #0f172a;
			margin: 0 0 6px 0;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		/* Option 2: Sticky Choir Voice Switcher */
		.choir-sticky-player {
			clear: both;
			position: sticky;
			top: 0;
			z-index: 999;
			background: rgba(15, 23, 42, 0.96);
			backdrop-filter: blur(8px);
			color: #f8fafc;
			padding: 12px 16px;
			border-radius: 10px;
			margin: 12px 15px 16px 15px;
			box-shadow: 0 6px 20px rgba(0,0,0,0.25);
		}
		.choir-part-pills {
			display: flex;
			flex-wrap: wrap;
			gap: 6px;
			margin-bottom: 10px;
			align-items: center;
		}
		.choir-pill {
			background: #334155;
			color: #e2e8f0;
			border: 1px solid #475569;
			padding: 5px 12px;
			border-radius: 999px;
			font-size: 13px;
			font-weight: 600;
			cursor: pointer;
		}
		.choir-pill.active {
			background: #10b981;
			color: #052e16;
			border-color: #34d399;
		}
		.choir-controls-row {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			gap: 10px;
		}
		.choir-Controls-audio {
			flex: 1;
			min-width: 220px;
			height: 36px;
		}
		.choir-opt-select, .choir-loop-btn {
			background: #1e293b;
			color: #f8fafc;
			border: 1px solid #475569;
			border-radius: 6px;
			padding: 5px 10px;
			font-size: 12px;
			cursor: pointer;
		}
		.choir-loop-btn.active {
			background: #f59e0b;
			color: #fff;
			border-color: #fbbf24;
		}
		html {
			height: 100%;
		}
		body {
			font: 1.2rem Arial, sans-serif !important;
			height: 100%;
			margin: 0;
			background-repeat: no-repeat;
			background-attachment: fixed;
			/* IE10 Consumer Preview */ 
			background-image: -ms-linear-gradient(top, #FFFFFF 0%, #00A3EF 100%);
			
			/* Mozilla Firefox */ 
			background-image: -moz-linear-gradient(top, #FFFFFF 0%, #00A3EF 100%);
			
			/* Opera */ 
			background-image: -o-linear-gradient(top, #FFFFFF 0%, #00A3EF 100%);
			
			/* Webkit (Safari/Chrome 10) */ 
			background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #FFFFFF), color-stop(1, #00A3EF));
			
			/* Webkit (Chrome 11+) */ 
			background-image: -webkit-linear-gradient(top, #FFFFFF 0%, #00A3EF 100%);
			
			/* W3C Markup, IE10 Release Preview */ 
			background-image: linear-gradient(to bottom, #FFFFFF 0%, #00A3EF 100%);
		}
		
		a{
			text-decoration: none !important;
		}
		
		a.tvdc{
			color: #0373FC;
		}
		
		a.tvdc:hover{
			color: 	#F00;
			text-decoration: none;
		}

		.d{color: silver;}
		.r{color: red;}
		.o{color: orange;}
		.b{color: blue;}
		.link{color:blue; cursor:pointer}
		.topBaiHat, .topList{
			float: right;
		}
		.additional{
			padding-bottom: 20px;
		}
		.le_tvdc{
			font-weight: bold;
			text-shadow: 1px 1px #ccc;
		}		
		
		.back-to-top, .back-to {
			position: fixed;
			right: 0px;
			text-decoration: none;
			color: silver;
			background: url("./images/upwards.gif") right top no-repeat; 
			font-size: 12px;
			padding: 1em;
			padding-top: 2em;
			padding-right: 25px;
			display: none;
		}
		.back-to-top{
			bottom: 5px;
		}
		.back-to{
			bottom: 5em;
		}
		
		.back-to-top:hover, .back-to:hover {
			color: red;
			text-decoration: none;
		}
		
		.zeroPadding{
			padding:0;
			text-shadow: 1px 1px #000;
		}
		
		.italic{
			font-size: 10px;
			font-style: italic;
			color: #CCC;
			text-shadow: 1px 1px #fff;
			margin-bottom: 15px;
		}
		.pldc, .plcnl, .plchl, .tacgia{
			/* The tvdc label */
			font-family: san-serif;
			font-size:8px;
			font-weight: bold;
			text-transform:uppercase;
			color: #000;
		}
		.tvdc{
			text-shadow: 1px 1px #ccc;
		}
		
		[class*="audio-player"] {
			padding-top: 2px;
			padding-bottom: 2px;
			background-color: #eee;
			background-color: rgba(86,61,124,.15);
			border: 1px solid #ddd;
			border: 1px solid rgba(86,61,124,.2);
		}
		
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
		
		[class^="col-"]{
			padding-right: 0;
		}
	</style>
	
	<!-- End Piwik Code -->
	<!-- jQuery with fallback to the 1.* for old IE -->
    <!--[if lt IE 9]>
        <script src="js/jquery-1.11.0.min.js"></script>
    <![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="http://chungnhan.org/lib/players/mediaelement-and-player.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.lazyloadxt.extra.min.js" type="text/javascript"></script>
	<script>
			function track($p){
			    dataLayer.push({
                    'event': 'page_view',
                    'pagePath': 'https://thanhca.chungnhan.org',
                    'pageTitle': 'Thánh Ca Online - Track',
                    'visitorType': 'Séraphim Choir'
                });
			}
			//track("home");
			gtag('pageTitle', 'Home');
	</script>
</head>
<body>
	<div class="container clearfix">		
		<div id="bodycontent" class="">
			<a href="http://seraphim.chungnhan.org">
				<img onmouseover="this.src='images/site_logo_on.gif';" 
					onmouseout="this.src='images/site_logo.gif';" 
					src="images/site_logo.gif" alt="Tập Hát - Ca Đoàn Séraphim"  /></a>
			<?php echo $this->body; ?>
		</div>
		<a href="#" class="back-to-top">TOP PAGE</a>
	</div>
    
	<div class="footer" style="text-align:center;">© 2009-<span id="currentYear">2014</span> Séraphim Choir - Church of the Vietnamese Martyrs Richmond, VA</div>
    
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
    
    <!--script type="text/javascript" src="//rf.revolvermaps.com/0/0/8.js?i=5qngijvwwmz&amp;m=0&amp;c=ff0000&amp;cr1=ffffff&amp;f=arial&amp;l=33" async="async"></script-->
	<script type="text/javascript" src="//rf.revolvermaps.com/0/0/6.js?i=5qngijvwwmz&amp;m=0&amp;c=ff0000&amp;cr1=ffffff&amp;f=arial&amp;l=0" async="async"></script>
	<script src="js/jquery.responsiveTabs.js" type="text/javascript"></script>
	<script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
	
    <script type="text/javascript">
		$.ajaxSetup ({  
			// Disable caching of AJAX responses  
			cache: false  
		});
		
		//$( "#mobileStats" ).load('stats.php');
		$(window).ready(function() {
			$(window).resize(function() {
				doAdjust(768);
			});
		});
		
		function doAdjust(w){
			var wi = $(window).width();
			if (wi < w){
				$(".plcnl").html("CNL:&nbsp");
				$(".pldc").html("ĐC:&nbsp;&nbsp;&nbsp");
			}else{
				$(".plcnl").html("");
				$(".pldc").html("");
			}
		}
		
        $(document).ready(function(){
			var offset = 220;
			var duration = 500;
			jQuery(window).scroll(function() {
				if (jQuery(this).scrollTop() > offset) {
					jQuery('.back-to-top').fadeIn(duration);
				} else {
					jQuery('.back-to-top').fadeOut(duration);
				}
			});
			
			jQuery('.back-to-top').click(function(event) {
				event.preventDefault();
				jQuery('html, body').animate({scrollTop: 0}, duration);
				return false;
			});
	
			$('#tvdcTab, #tcTab').responsiveTabs({
                rotate: false,
                startCollapsed: true,
                collapsible: true,
                setHash: false,
				activate: function(e, tab){
        			gtag('event', $(this).attr("id") + ' Tab ' + tab.id + ' activated!');
					//track('Tab ' + tab.id + ' activated!');
                }
            });
			
			//loadPanel($('ul li:first a'));
			var listItems = $("ul li a");
			listItems.each(function(idx, li) {
				loadPanel($(li));
			});
			// set the tab ordered (A=0;B=1;C=2)			// use ABC.json
			$('#tvdcTab, #tcTab').responsiveTabs('activate', <?php echo $key; ?>);
			
			var currYear = (new Date).getFullYear();
			$('#currentYear').html(currYear);
			
        });
		
		$(document).on('click', 'a.tvdc', function(event){			event.preventDefault();
			$wrapper = $(this).closest('#row');	
			document.title = "Thánh Ca Online";
			if($wrapper.find('#add').length){
				$('#add').unload(function(){
					Cleanup();
				}).remove();
			}else{
				//setTimeout('window.alert("We are loading media files.  Please patient.  Thank you!")', 1000);
				//localStorage["pageview"] = $(this).attr("href");	
				
				$HREF = $(this).attr("href").replace(/\/$/, '');
				document.title = "Thánh Ca Online - " + $HREF;
				track($HREF);
				
    			dataLayer.push({
                    'event': 'Pageview',
                    'pageTitle': document.title,
                    'pagePath': $HREF
                });
				//gtag('event', 'pageView');
				gtag('pagePath', $HREF);
				
				if ($('#add').length){
					$('#add').unload(function(){
						Cleanup();
					}).remove();
					//$('#add').remove();
				}
				$path = "?t="+encodeURIComponent($(this).text())+"&d="+$HREF;
				$url = "main/baihat.php"+$path+'&_=' + (new Date()).getTime();
				$wrapper.append('<div id="add" class="additional"></div>');	
				//$('#add').load($url).fadeIn('slow');
				loadBaiHat($HREF, <?php echo $isMobile ?>, $.trim($(this).text()));
			}
			return false;
		});
		
		function loadBaiHat(p, isMobile, songTitle){
			$.get( 'main/songMedia.php', { dir: p, title: songTitle || '' } )
				.done(function( data ) {
				renderDemoOptionsForSunday(data, p);
			});
		}

		function renderDemoOptionsForSunday(rawHtml, p) {
			// Parse tracks and sheet music content from media.php output using DOMParser (compatible with jQuery 1.7.2)
			var doc = (new DOMParser()).parseFromString(rawHtml, 'text/html');
			var tracks = [];
			var playerDivs = doc.querySelectorAll('.audio-player');
			for (var d = 0; d < playerDivs.length; d++) {
				var h1 = playerDivs[d].querySelector('h1');
				var aud = playerDivs[d].querySelector('audio');
				var label = h1 ? $.trim(h1.textContent) : 'Track ' + (d + 1);
				var src = aud ? aud.getAttribute('src') : '';
				if (src) tracks.push({ label: label, src: src });
			}

			// Extract optional toolContainer (Hòa Âm / Tổng Phối toggle + FB), PDF links, tongphoi images, and main score images
			var toolContainerEl = doc.querySelector('.toolContainer');
			var pdfEl = doc.querySelector('#pdf');
			var tongphoiEl = doc.querySelector('#tongphoi');
			var hasTongPhoi = !!(tongphoiEl && tongphoiEl.querySelectorAll('img').length > 0);

			// Main sheet music images are <img> tags not inside #tongphoi or <noscript>
			var images = [];
			var imgEls = doc.querySelectorAll('img');
			for (var m = 0; m < imgEls.length; m++) {
				var imgNode = imgEls[m];
				if (imgNode.closest && (imgNode.closest('#tongphoi') || imgNode.closest('noscript'))) continue;
				var imgSrc = imgNode.getAttribute('src');
				if (imgSrc && imgSrc.indexOf('histats') === -1) images.push(imgSrc);
			}

			var switcherHtml =
				'<div class="demo-switcher-bar" style="display:none;">' +
					'<span class="demo-title">🎵 Demo Player:</span>' +
					'<button type="button" class="demo-btn" data-opt="1">Option 1: Plyr.io (Speed + Loop)</button>' +
					'<button type="button" class="demo-btn active" data-opt="2">Option 2: Sticky Choir Voice Switcher</button>' +
					'<button type="button" class="demo-btn" data-opt="3">Option 3: Native HTML5 Audio</button>' +
				'</div>' +
				'<div id="demo-player-stage"></div>' +
				'<div id="demo-tools-stage"></div>' +
				'<div id="demo-score-stage"></div>';

			$('.additional').html(switcherHtml);

			var toolsHtml = '';
			if (toolContainerEl) {
				// Only show Hòa Âm button if tongphoi images actually exist
				if (!hasTongPhoi) {
					var hoaamDiv = toolContainerEl.querySelector('.hoaam');
					if (hoaamDiv) hoaamDiv.style.display = 'none';
				}
				toolsHtml += toolContainerEl.outerHTML;
			}
			if (pdfEl && $.trim(pdfEl.textContent || pdfEl.innerHTML)) {
				toolsHtml += '<div style="margin: 8px 0;">' + pdfEl.outerHTML + '</div>';
			}
			$('#demo-tools-stage').html(toolsHtml);

			var scoreHtml = '<div id="main">';
			for (var i = 0; i < images.length; i++) {
				scoreHtml += '<img src="' + images[i] + '" width="100%" style="margin-bottom:8px;border-radius:4px;" />';
			}
			scoreHtml += '</div>';
			if (hasTongPhoi) {
				scoreHtml += tongphoiEl.outerHTML;
			}
			$('#demo-score-stage').html(scoreHtml);

			if (hasTongPhoi) {
				$('#hoaamBtn').on('click', function() {
					$('.hoaam').hide();
					$('.lyrics').show();
					$('#demo-score-stage #main').hide();
					$('#demo-score-stage #tongphoi').show();
				});
				$('#lyricsBtn').on('click', function() {
					$('.lyrics').hide();
					$('.hoaam').show();
					$('#demo-score-stage #tongphoi').hide();
					$('#demo-score-stage #main').show();
				});
			}

			function mountOption(opt) {
				var $stage = $('#demo-player-stage');
				$stage.empty();

				if (opt === 1) {
					// Option 1: Plyr.io cards with speed and loop controls
					var html = '<div class="demo-plyr-grid">';
					for (var i = 0; i < tracks.length; i++) {
						html +=
							'<div class="demo-track-card">' +
								'<div class="demo-track-title"><span>' + tracks[i].label + '</span></div>' +
								'<audio class="js-plyr-audio" controls preload="metadata">' +
									'<source src="' + tracks[i].src + '" type="audio/mp3" />' +
								'</audio>' +
							'</div>';
					}
					html += '</div>';
					$stage.html(html);

					if (window.Plyr) {
						var players = Plyr.setup('.js-plyr-audio', {
							controls: ['play', 'progress', 'current-time', 'duration', 'mute', 'volume', 'settings'],
							settings: ['speed', 'loop'],
							speed: { selected: 1, options: [0.5, 0.75, 1, 1.25, 1.5] }
						});
						if (players && players.length) {
							players.forEach(function(pl) {
								pl.on('play', function() {
									players.forEach(function(other) {
										if (other !== pl) other.pause();
									});
								});
							});
						}
					}
				} else if (opt === 2) {
					// Option 2: Sticky Choir Voice Switcher Bar (stays visible while scrolling sheet music)
					var pills = '';
					for (var j = 0; j < tracks.length; j++) {
						pills += '<button type="button" class="choir-pill' + (j === 0 ? ' active' : '') + '" data-idx="' + j + '">' + tracks[j].label + '</button>';
					}
					var stickyHtml =
						'<div class="choir-sticky-player">' +
							'<div class="choir-part-pills">' +
								'<strong style="margin-right:6px;font-size:13px;color:#94a3b8;">Chọn bè / nhạc:</strong>' +
								pills +
							'</div>' +
							'<div class="choir-controls-row">' +
								'<audio id="choir-master-audio" class="choir-Controls-audio" controls preload="metadata" src="' + (tracks[0] ? tracks[0].src : '') + '"></audio>' +
								'<label style="font-size:12px;margin:0;">Tốc độ: ' +
									'<select id="choir-speed-select" class="choir-opt-select">' +
										'<option value="0.75">0.75x (Tập chậm)</option>' +
										'<option value="0.9">0.9x</option>' +
										'<option value="1" selected>1.0x (Chuẩn)</option>' +
										'<option value="1.25">1.25x</option>' +
									'</select>' +
								'</label>' +
								'<button type="button" id="choir-loop-toggle" class="choir-loop-btn">🔁 Lặp lại: Tắt</button>' +
							'</div>' +
						'</div>';
					// Mount inside .additional above score so position:sticky works while scrolling the song
					$stage.html(stickyHtml);

					var audioEl = document.getElementById('choir-master-audio');
					if (audioEl) {
						audioEl.addEventListener('play', function() {
							var srcAttr = audioEl.getAttribute('src') || audioEl.currentSrc;
							document.title = "Thánh Ca Online - " + srcAttr;
							if (typeof gtag === 'function') gtag('play', srcAttr);
							if (window.dataLayer) {
								dataLayer.push({
									'event': 'Pageview',
									'pageTitle': document.title,
									'pagePath': srcAttr
								});
							}
						});
						var playPromise = audioEl.play();
						if (playPromise && typeof playPromise.catch === 'function') {
							playPromise.catch(function() {});
						}
					}
					$stage.find('.choir-pill').on('click', function() {
						var idx = parseInt($(this).attr('data-idx'), 10);
						$stage.find('.choir-pill').removeClass('active');
						$(this).addClass('active');
						var rate = parseFloat($('#choir-speed-select').val()) || 1;
						audioEl.src = tracks[idx].src;
						audioEl.playbackRate = rate;
						audioEl.play();
					});
					$('#choir-speed-select').on('change', function() {
						audioEl.playbackRate = parseFloat($(this).val()) || 1;
					});
					$('#choir-loop-toggle').on('click', function() {
						audioEl.loop = !audioEl.loop;
						$(this).toggleClass('active', audioEl.loop);
						$(this).text(audioEl.loop ? '🔁 Lặp lại: Bật' : '🔁 Lặp lại: Tắt');
					});
				} else if (opt === 3) {
					// Option 3: Clean Native HTML5 Audio with auto-pause-others
					var nativeHtml = '<div class="demo-native-grid">';
					for (var k = 0; k < tracks.length; k++) {
						nativeHtml +=
							'<div class="demo-track-card">' +
								'<div class="demo-track-title">' +
									'<span>' + tracks[k].label + '</span>' +
									'<button type="button" class="choir-loop-btn js-native-loop" style="padding:2px 8px;font-size:11px;">🔁 Loop</button>' +
								'</div>' +
								'<audio class="js-native-audio" controls preload="metadata" src="' + tracks[k].src + '" style="width:100%;height:38px;"></audio>' +
							'</div>';
					}
					nativeHtml += '</div>';
					$stage.html(nativeHtml);

					var $audios = $stage.find('.js-native-audio');
					$audios.on('play', function() {
						var current = this;
						$('.demo-track-card').removeClass('playing');
						$(current).closest('.demo-track-card').addClass('playing');
						$audios.each(function() {
							if (this !== current) this.pause();
						});
					});
					$stage.find('.js-native-loop').on('click', function() {
						var aud = $(this).closest('.demo-track-card').find('audio')[0];
						aud.loop = !aud.loop;
						$(this).toggleClass('active', aud.loop);
					});
				}
			}

			$('.demo-switcher-bar .demo-btn').on('click', function() {
				$('.demo-switcher-bar .demo-btn').removeClass('active');
				$(this).addClass('active');
				mountOption(parseInt($(this).attr('data-opt'), 10));
			});

			mountOption(2);
		}
			
			
		function loadPanel(ele){
			//$('body').addClass("bodyGradient");
			var panel = ele.attr("href");
			var url = ele.attr("data");
			$(panel).load(url);
		}
		
		var textcolours = Array("#FF0000","#FF7F00","#FFFF00","#00FF00","#0000FF","#4B0082 ","#8F00FF");
		var textcolours2 = Array("#00FF00","#0000FF","#4B0082 ","#8F00FF","#FF0000","#FF7F00","#FFFF00");
		function flashtext() {
			var colour = Math.round( textcolours.length * Math.random() );
			if (document.getElementById("flashingtext1")) {
				document.getElementById( "flashingtext1" ).style.color = textcolours[ colour ];
			}
			if (document.getElementById("flashingtext2")) {
				document.getElementById("flashingtext2").style.color = textcolours2[ colour ];
			}
		} 
		setInterval( "flashtext()", 100 );
		
    </script> 
</body>
</html>
