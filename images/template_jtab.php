<?php
	include_once('inc/cookie.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $this->title; ?></title>
	<!--link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/base.css">
	<link rel="stylesheet" href="css/grid.css">
	<link rel="stylesheet" href="css/demo.css"-->
	<link href="css/bootstrap.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="css/responsive-tabs.css" />
    <link type="text/css" rel="stylesheet" href="css/responsive-tabs-style.css" />	
	<link rel="stylesheet" href="../mediaelement/johndyer/build/mediaelementplayer.css" />

	<style>
		html {
			height: 100%;
		}
		body {
			font: 12px Arial, sans-serif;
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

		.d{color: silver;}
		.r{color: red;}
		.o{color: orange;}
		.b{color: blue;}
		.link{color:blue; cursor:pointer}
		.topBaiHat, .topList{
			float: right;
		}
		.additional{
			padding-top: 20px;
		}
		.le_tvdc{
			font-weight: bold;
		}
		
		.footer{
			padding: 10px;
			color: silver;
			font: 8px Arial, sans-serif;
		}
		
		.r-tabs-state-active{
			background-color: white;
			over-flow:auto;
		}
		
		.back-to-top, .back-to {
			position: fixed;
			right: 0px;
			text-decoration: none;
			color: #000;
			background: url("./images/upwards.gif") right top no-repeat; 
			font-size: 12px;
			padding: 1em;
			padding-top: 2em;
			padding-right: 25px;
			display: none;
		}
		.back-to-top{
			bottom: 2em;
		}
		.back-to{
			bottom: 6em;
		}
		
		.back-to-top:hover, .back-to:hover {
			color: red;
			text-decoration: none;
		}
		
		.zeroPadding{padding:0;}
		.tacgia{
			font-weight: bold;
			color: #000;
		}
		.italic{
			font-size: 10px;
			font-style: italic;
		}
	</style>
	<!-- Piwik --> 
	<script type="text/javascript">
		var _paq = _paq || [];
		(function(){ var u=(("https:" == document.location.protocol) ? "https://piwik.chungnhan.org/" : "http://piwik.chungnhan.org/");
		_paq.push(['setSiteId', 1]);
		_paq.push(['setTrackerUrl', u+'piwik.php']);
		
		var visitor_id;
		_paq.push([ function() { visitor_id = this.getVisitorId(); }]);
		_paq.push(['setCustomVariable','1','Seraphim','<?php echo $type; ?>']);
		_paq.push([ function() { var customVariable = this.getCustomVariable(1); }]);
		_paq.push(['setCustomVariable','2','Name','<?php echo $name; ?>']);
		_paq.push([ function() { var customVariable = this.getCustomVariable(2); }]);
		_paq.push(['setCustomVariable','3','City', '<?php echo $city; ?>' ]);
		_paq.push([ function() { var customVariable = this.getCustomVariable(3); }]);
		_paq.push(['setCustomVariable','4','Region', '<?php echo $region; ?>' ]);
		_paq.push([ function() { var customVariable = this.getCustomVariable(4); }]);
		_paq.push(['setCustomVariable','5','Country', '<?php echo $country; ?>' ]);
		_paq.push([ function() { var customVariable = this.getCustomVariable(5); }]);
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript'; g.defer=true; g.async=true; g.src=u+'piwik.js';
		s.parentNode.insertBefore(g,s); })();
		
		function track($bp){
			_gaq.push(['_trackPageview', $bp]);
			piwikTracker.setCustomVariable(1, "Seraphim", '<?php echo $type;?>', "visit" );
			piwikTracker.setCustomVariable(2, "Name", '<?php echo $name;?>', "visit" );					
			piwikTracker.setCustomVariable(3, "City", '<?php echo $city;?>', "visit" );
			piwikTracker.setCustomVariable(4, "Region", '<?php echo $region;?>', "visit" );
			piwikTracker.setCustomVariable(5, "Country", '<?php echo $country; ?>', "visit" );
			piwikTracker.trackPageView($bp);
		}
		
	 </script>
	<!-- End Piwik Code -->
	<!-- jQuery with fallback to the 1.* for old IE -->
    <!--[if lt IE 9]>
        <script src="js/jquery-1.11.0.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="js/jquery-2.1.0.min.js"></script>
    <!--<![endif]-->
	<script src="../mediaelement/johndyer/build/mediaelement-and-player.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
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
	<iframe id="piwikfr" src="view.php" width="100%" height="120" frameborder="0" scrolling="no" ></iframe>
	<div class="footer">© 2009-<span id="currentYear">2014</span> Séraphim Choir - Church of the Vietnamese Martyrs Richmond, VA</div>
    <!-- Responsive Tabs JS -->
    <script src="js/jquery.responsiveTabs.js" type="text/javascript"></script>

    <script type="text/javascript">
		$.ajaxSetup ({  
			// Disable caching of AJAX responses  
			cache: false  
		});

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
				activate: function(e, tab) {
                    //alert('Tab ' + tab.id + ' activated!');
					track('Tab ' + tab.id + ' activated!');
                }
            });
			
			//loadPanel($('ul li:first a'));
			var listItems = $("ul li a");
			listItems.each(function(idx, li) {
				loadPanel($(li));
			});
			
			$('#tvdcTab').responsiveTabs('activate', 0);
			
			var currYear = (new Date).getFullYear();
			$('#currentYear').html(currYear);
			
			
        });
		
		$(document).on('click', 'a.tvdc', function(){
			$wrapper = $(this).closest('#row');
			//localStorage.setItem('href', $(this).attr("href"));
			
			if($wrapper.find('#add').length){
				$('#add').unload(function(){
					Cleanup();
				}).remove();
				//$('#add').remove();
			}else{//alert("Are you OK?");
				setTimeout('window.alert("This helps loading files.  Please patient.  Thank you!")', 500);				
				track($(this).attr("href"));
				if ($('#add').length){
					$('#add').unload(function(){
						Cleanup();
					}).remove();
					//$('#add').remove();
				}
				$path = "?t="+encodeURIComponent($(this).text())+"&d="+$(this).attr("href");
				$url = "main/baihat.php"+$path;
				$wrapper.append('<div id="add" class="additional"></div>');	
				$('#add').load($url).fadeIn('slow');
			}
			return false;
		});
			
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
	<script type="text/javascript">
	//<![CDATA[
	var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-21693458-1"]);_gaq.push(["_setDomainName",".chungnhan.org"]);_gaq.push(["_trackPageview"]);(function(){var a=document.createElement("script");a.type="text/javascript";a.async=true;a.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();
	//]]>
	</script>
	<script type="text/javascript">
		var pkBaseURL = (("https:" == document.location.protocol) ? "https://piwik.chungnhan.org/" : "http://piwik.chungnhan.org/");
		document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
		var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
		piwikTracker.setDocumentTitle("Tập Hát Thánh Ca");
		tracker.setCookieDomain('*.chungnhan.org'); 
		tracker.setDomains('*.chungnhan.org'); 
		piwikTracker.trackPageView();
		piwikTracker.enableLinkTracking();
	} catch( err ) {}
	</script><noscript><p><img src="http://piwik.chungnhan.org/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript>	
	
</body>
</html>
