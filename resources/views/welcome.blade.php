<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{ config('app.name', 'Mafindoor') }}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="css/pages/bootstrap.css" rel="stylesheet" media="screen">
	<link href="css/pages/owl.theme.css" rel="stylesheet" media="screen">
	<link href="css/pages/owl.carousel.css" rel="stylesheet" media="screen">
	<link href="css/pages/style-col1.css?version=1.4.8" rel="stylesheet" media="screen">
	<link href="css/pages/animate.css" rel="stylesheet" media="screen">
	<link href="css/pages/ionicons.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="css/pages/nivo-lightbox.css" type="text/css" />
	<link rel="stylesheet" href="css/pages/nivo-themes/default/default.css" type="text/css" />

	<link href="img/pages/favicon.png?version=1.2.0" rel="shortcut icon">
	
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Merriweather:300italic' rel='stylesheet' type='text/css'>
	
</head>
<body class='ios'>

	<!--HEADER-->
	<header id='header'>
		<div id='menu-bar' class='animated fadeIn'>
			<div class='container'>
				<div class='logo'>
					<a href="/"><img src="img/pages/logo.png?version=1.2.0" alt /></a>
				</div>
				<div class='search'>
					<input class="form-control main-autocomplete" placeholder="Search venues by name or address...">
				</div>
				<nav  id="nav" class='nav'>
					<ul class='nav-inner'>
						<li class='current'><a href="#header">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#features">Features</a></li>
						<li><a href="#screenshots">Venues</a></li>
						<li><a href="#download">Download</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class='row'>					
				<div class='col-sm-10 col-sm-offset-1 col-md-7 col-md-offset-0 col-md-push-5 col-lg-6 col-lg-push-6 animated fadeInRight'>
					<h1>Find Your Way Indoors With Mafindoor.</h1>
					<div id='header-btn'>
						<!-- <a class='btn btn-icon btn-secondary' href=""><span class='icon ion-social-apple'></span><strong>App Store</strong></a> -->
						<a class='btn btn-icon btn-primary' href="https://play.google.com/store/apps/details?id=com.mafindoor"><span class='icon ion-social-android'></span><strong>Play Store</strong></a>
					</div>
				</div>
				<div class='col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-5 col-md-offset-0 col-md-pull-7 col-lg-5 col-lg-pull-6 col-lg-offset-1'>
					<img src="img/pages/iPhone-header.png?version=1.2.0" id='header-img' class='img-responsive animated fadeInUp' alt>
				</div>
			</div>
		</div>
		<!-- <svg id='svg-header'>
			<defs>
				<linearGradient id="grad" x1="0%" y1="100%" x2="100%" y2="30%">
					<stop offset="8%" style="stop-color:rgb(9,184,237);stop-opacity:0.1" />
					<stop offset="50%" style="stop-color:rgb(9,184,237);stop-opacity:1" />
				</linearGradient>
			</defs>
			<rect id='svg-rect' width="3000" height="1050" fill="url(#grad)"/>
		</svg> -->
		<video autoplay muted loop id="vid_loop">
		  <source src="/videos/pages/loop.mp4" type="video/mp4">
		</video>
		<div id='menu-bar-fixed'>
			<div class='container'>
				<a class='logo scrollTo-header'><img src="img/pages/logo-sm.png?version=1.2.0" alt></a>
				<nav  id="nav-fixed" class='nav'>
					<a id="show-nav" title="Show navigation"><div></div></a>
    				<a id="hide-nav" title="Hide navigation"><div></div></a>
					<ul id='nav-ul' class='nav-inner'>
						<li class='current'><a href="#header">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#features">Features</a></li>
						<li><a href="#screenshots">Venues</a></li>
						<li><a href="#download">Download</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</header>

	<!--ABOUT-->
	<section id='about'>
		<div class="container">
			<div class='row wow fadeInUp'>	
				<div class='col-sm-12 col-md-6 col-lg-6'>
					<div class='circle-icon-lg'>
						<div class='icon ion-ios7-search'></div>
					</div>
					<div class='text'>
						<h3>INDOOR SEARCH</h3>
						<p>We let you SEE WHAT GOOGLE MAPS CAN'T SHOW.</p>
					</div>
				</div>
				<div class='col-sm-12 col-md-6 col-lg-6'>
					<div class='circle-icon-lg'>
						<div class='icon ion-ios7-navigate-outline'></div>
					</div>
					<div class='text'>
						<h3>INDOOR WAYFINDING</h3>
						<p>We let you GO WHERE WAZE CAN'T REACH.</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--FEATURES-->
	<section id='features'>
		<div class="container">
			<div class='wow fadeInDown'>
				<h2>How It Works</h2>
			</div>
			<div class='row'>	
				<div class='col-sm-4 col-md-4 col-lg-4 left wow fadeInLeft'>
					<div class='row'>
						<div class='col-sm-8 col-md-9 col-lg-9'>
							<h4>Zoom in to navigate</h4>
							<p>Zoom in to see the indoor floor plan of a building.</p>
						</div>
						<div class='hidden-xs col-sm-4 col-md-3 col-lg-3'>
							<div class='circle-icon-rspv'><div class='icon ion-ios7-plus-outline'></div></div>
						</div>
					</div>
					<div class='row hidden-xs'>
						<div class='col-sm-12'>
							<br/><br/><br/>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-8 col-md-9 col-lg-9'>
							<h4>View All Floors</h4>
							<p>Move from one floor to another in just a click of a button.</p>
						</div>
						<div class='hidden-xs col-sm-4 col-md-3 col-lg-3'>
							<div class='circle-icon-rspv'><div class='icon ion-android-stair-drawer'></div></div>
						</div>
					</div>
					<div class='row hidden-xs'>
						<div class='col-sm-12'>
							<br/><br/><br/>
						</div>
					</div>
				</div>
				<div class='col-sm-4 col-sm-push-4 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4 right wow fadeInRight'>
					<div class='row hidden-xs'>
						<div class='col-sm-12'>
							<br/><br/><br/>
						</div>
					</div>
					<div class='row'>
						<div class='hidden-xs col-sm-4 col-md-3 col-lg-3'>
							<div class='circle-icon-rspv'><div class='icon ion-ios7-search'></div></div>
						</div>
						<div class='col-sm-8 col-md-9 col-lg-9'>
							<h4>Search points of interest</h4>
							<p>Use search to find your favorite place inside a venue.</p>
						</div>						
					</div>
					<div class='row hidden-xs'>
						<div class='col-sm-12'>
							<br/><br/><br/>
						</div>
					</div>
					<div class='row'>
						<div class='hidden-xs col-sm-4 col-md-3 col-lg-3'>
							<div class='circle-icon-rspv'><div class='icon ion-ios7-bolt-outline'></div></div>
						</div>
						<div class='col-sm-8 col-md-9 col-lg-9'>
							<h4>Switch Venues</h4>
							<p>Easily switch you current venue by clicking an item on the sidebar.</p>
						</div>					
					</div>
				</div>
				<div class='col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-0 col-sm-pull-4 col-md-4 col-md-pull-4 col-lg-4 col-lg-pull-4'>
					<img src="img/pages/iPhone-5C-green.gif?version=1.2.0" class='img-responsive wow fadeInUp' style="width: 100%;" alt>
				</div>
			</div>
		</div>
	</section>

	<!--DETAILED INFO-->
	<section id='detailed'>
		<div class="container">
			<div class='row'>
				<div class='col-sm-12 col-md-7 col-lg-7 wow fadeInLeft'>
					<h2>Join Mafindoor Now</h2>
					<p class='subtitle'>Have an online directory of your favorite venues right on the palm of your hand</p>
				</div>
				<div class='col-sm-6 col-md-6 col-lg-6 wow fadeInLeft'>
					<div class='row'>
						<div class='col-sm-2 col-md-2 col-lg-2'>
							<div class='icon ion-ios7-cart'></div>
						</div>
						<div class='col-sm-10 col-md-10 col-lg-10'>
							<h4>Malls</h4>
							<p>Easily find your favorite store, spa, restaurant, and shop among others.</p>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-2 col-md-2 col-lg-2'>
							<div class='icon ion-plane'></div>
						</div>
						<div class='col-sm-10 col-md-10 col-lg-10'>
							<h4>Airports</h4>
							<p>Don't miss your flight! Quickly find your boarding section and get ahead of the crowd.</p>
						</div>
					</div>
				</div>
				<div class='col-sm-6 col-md-6 col-lg-6 img wow fadeInRight delay-sm'>
					<img src="img/pages/3iPhones.png?version=1.2.0" class='img-responsive' alt>
				</div>
			</div>
		<div></div>
	</section>
	
	<!--BANNER-DOWNLOAD-->
	<section id='download-banner'>
		<div class="container">
			<p>
				<span class='text wow fadeInLeft'>
					<span class='bold'>Download the app right now</span>
					<!-- <span class='bold'>Mafindoor app is coming soon...</span> -->
				</span>
				<a href="https://play.google.com/store/apps/details?id=com.mafindoor" class='btn btn-primary wow pulse delay-1s'>Download Now</a>
			</p>
		</div>
	</section>

	<!--SCREENSHOTS-->
	<section id='screenshots'>
		<div class="container">
			<div class='wow fadeInDown'>
				<h2>Our Venues</h2>
			</div>
			<div id="screens-carousel" class="owl-carousel wow fadeInUp text-center">
				@foreach($buildings as $building)
					<a href='/buildings/{{ $building->id }}-{{ $building->slug }}' class='item' data-lightbox-gallery="gallery1">
						<img src="{{ $building->image }}" class='img-responsive' alt>
					</a>
				@endforeach
			</div>
		</div>
	</section>

	<!--DOWNLOAD-->
	<section id='download'>
		<div class="container">
			<div class='wow fadeInDown'>
				<!-- <h2>Mafindoor App Is Coming Soon...</h2> -->
				<h2>Download Mafindoor Now!</h2>
				<p class='subtitle'>Avoid the hassle of finding and waiting for your turn to use the buildling's directory.</p>
			</div>
			<p class='download-buttons'>
				<!-- <a class='btn btn-icon btn-secondary' href=""><span class='icon ion-social-apple'></span>App Store</a> -->
				<a class='btn btn-icon btn-primary' href="https://play.google.com/store/apps/details?id=com.mafindoor"><span class='icon ion-social-android'></span>Play Store</a>
			</p>
			<div class='floating-phone wow fadeInRightBig'></div>
		</div>
	</section>

	<!--FOOTER-->
	<footer id='footer'>
		<div class="container">
			<div id='footer-content'>
				<h2><img src="img/pages/logo.png?version=1.2.0" alt></h2>
				<p id='social-links'>
					<a href="https://www.facebook.com/Mafindoor-507981219706005" target="_blank" class='icon ion-social-facebook'></a>
					<a href="https://twitter.com/mafindoor" class='icon ion-social-twitter' target="_blank"></a>
					<a href="https://plus.google.com/u/0/103858053761972961832" target="_blank" class='icon ion-social-googleplus-outline'></a>
					<!-- <a href="" class='icon ion-social-instagram'></a> -->
				</p>
				<p class='copyright'>
					<div class="row">
						<div class="col-md-6 text-right">
							Copyright © 2019 Mafindoor
						</div>
						<div class="col-md-6 text-left">
							<a href="/privacy">Privacy Policy</a>
						</div>
					</div>
				</p>
			</div>
		</div>
	</footer>
	<div id='static-footer'></div>

	<script src="js/pages/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="js/pages/retina.min.js"></script>
	<script src="js/pages/owl.carousel.min.js"></script>
	<script src="js/pages/wow.js"></script>
	<script src="js/pages/jquery.nav.js"></script>
	<script src="js/pages/jquery.scrollTo.min.js"></script>
	<script src="js/pages/nivo-lightbox.min.js"></script>

	<script src="{{ asset('/plugins/jquery-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>

	<script type="text/javascript">
		// general variables
		myWindow = $(window)
		windowHeight = myWindow.height()
		header = $('#header')
		// svgRect = $('#svg-rect')
		showNav = $('#show-nav')
		hideNav = $('#hide-nav')
		navUl = $('#nav-ul')

		// set header height
		if (windowHeight>=900) {
			header.css('height', windowHeight - 250)
		}
		else{
			header.css('height', windowHeight)
		}

		headerHeight = header.outerHeight()
		headerWidth = header.outerWidth()

		$(document).ready(function() {

			// header animation svg
			// var svgHeader = $('#svg-header')

			// svgRect.attr('height', 1.5*headerHeight)
			// svgRect.attr('width', 2*headerWidth)
			// svgHeader.css('transform', 'rotate(-55deg)')
			// svgHeader.css('-webkit-transform', 'rotate(-55deg)')
			// svgHeader.css('ms-transform', 'rotate(-55deg)')

			// wow.js initialization
			if (myWindow.width()>530) {
				new WOW().init();
			};

			// jquery.nav.js initialization
			$('.nav-inner', '#header').onePageNav();

			// Nivo Lightbox initialization
			$('#screenshots a').nivoLightbox({
				effect: 'fadeScale',
				keyboardNav: true,
			});

			// owl.carousel.js initialization
			$("#screens-carousel").owlCarousel({
				items : 4,
				itemsDesktop : [1199,4],
				itemsDesktopSmall : [980,3],
				itemsTablet: [768,2],
				itemsMobile : [480,1],
			});

			$("#reviews-carousel").owlCarousel({
				items : 1,
				itemsDesktop : [1199,1],
				itemsDesktopSmall : [980,1],
				itemsTablet: [768,1],
				itemsMobile : [480,1],
				autoPlay: 8000,
			});

			$('.main-autocomplete').autocomplete({
			    serviceUrl: '/buildings/ajaxSearch',
			    appendTo: $('body'),
				forceFixPosition: true,
				html: true,
				minChars: 0,
				showNoSuggestionNotice: true,
				noSuggestionNotice: '<center>No results found</center>',
			    onSelect: function (data) {
			    	if (data.type == 'building')
			        	window.location.href = '/buildings/' + data.id + '-' + data.slug;
			        else
			        	window.location.href = '/search/buildings/' + suggestion.floor.building_id + '/floors/' + suggestion.floor_id + '/annotations/' + suggestion.id;
			    },
				formatResult: function (suggestion, currentValue) {

					var s_link;

					if (suggestion.type == 'building')
						s_link = '/buildings/' + suggestion.id + '-' + suggestion.slug;
					else
						s_link = '/search/buildings/' + suggestion.floor.building_id + '/floors/' + suggestion.floor_id + '/annotations/' + suggestion.id;

					var pattern = '(' + currentValue.replace(/[|\\{}()[\]^$+*?.]/g, "\\$&") + ')';

					var photo = suggestion.image;

					return '<a class="place_link" href="' + s_link + '"><div class="row">'
						+		'<div class="col-xs-3 col-sm-2">'
						+			'<span class="image-holder image-holder-xs">'
						+				'<img class="thumbnail-sm" src="' + photo + '" />'
						+			'</span>'
						+		'</div>'
						+		'<div class="col-xs-9 col-sm-10">'
						+			'<div class="name"><b>' + suggestion.value.replace(new RegExp(pattern, 'gi'), '<span class="highlight">$1<\/span>') + '</b></div>'
						+			'<div class="desc"><span>' + suggestion.description.replace(new RegExp(pattern, 'gi'), '<span class="highlight">$1<\/span>') + '</span></div>'
						+		'</div>'
						+	'</div></a>';
				}
			});
		});
		// Responsive navigation show/hide
		function showNavig() {
			navUl.css('display','block')
			hideNav.css('display','block')
			showNav.css('display','none')
		}
		function hideNavig() {
			navUl.css('display','none')
			showNav.css('display','block')
			hideNav.css('display','none')
		}
		showNav.click(function() {
			showNavig();
		});
		hideNav.click(function() {
			hideNavig();
		});
		$( "#off-nav" ).click(function() {
			hideNavig();
		});
		$( "#nav-ul > li" ).click(function() {
			if (myWindow.width()<=767) {
				hideNavig();
			};
		});

		// Resize event handler
		myWindow.resize(function() {
			// show/hide responsive navigation
			if (myWindow.width()>767) {
				navUl.css('display','block')
			}
			else{
				navUl.css('display','none')
			}

			// resize SVG rectangle in header
			headerWidth = header.outerWidth()
			// svgRect.attr('height', 1.5*headerHeight)
			// svgRect.attr('width', 2*headerWidth)
		});

		// scrollTo buttons
		menuBarHeight = $('#menu-bar-fixed').outerHeight();

		$('.scrollTo-download').click(function(){
			$.scrollTo( $('#download').offset().top-menuBarHeight+'px' , 800 );
		});
		$('.scrollTo-about').click(function(){
			$.scrollTo( $('#about').offset().top-menuBarHeight+'px' , 800 );
		});
		$('.scrollTo-header').click(function(){
			$.scrollTo( header , 800 );
		});


		footerHeight = $('footer').outerHeight();
		// $('#static-footer').css('margin-top', footerHeight+'px');

		// scroll event
		window.onscroll = scroll;
		
		function scroll () {

			var wScrollTop = $(window).scrollTop();
			var wScrollBot = wScrollTop + $(window).height();
			var pageHeight = $(document).height();
			var footerContent = $("#footer-content")

			// fixed footer opacity change onscroll
			if(wScrollBot>pageHeight-(footerHeight/2)){
				var newOpacity = (0.99/(footerHeight/2)) * (wScrollBot-(pageHeight-(footerHeight/2)))
				footerContent.css('opacity', newOpacity);
			}
			else{
				footerContent.css('opacity','0');
			}
			
			// fixed navigation show/hide
			var menuBarFixed = $('#menu-bar-fixed')

			if (wScrollTop >= headerHeight - menuBarFixed.outerHeight()) {
				menuBarFixed.css('top','0');
				menuBarFixed.css('opacity','1');
			}
			else{
				menuBarFixed.css('top',-menuBarFixed.outerHeight()+'px');
				menuBarFixed.css('opacity','0');
			};
		}

	</script>

</body>
</html>