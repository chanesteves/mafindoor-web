<!DOCTYPE html>
<html lang="en">
<head>
	<meta property="og:type" content="profile">
  	<meta property="og:title" content="Mafindoor - {{ $building->name }}">
  	<meta property="og:image" content="{{ \URL::to("/") }}/{{ $building->image }}">
	<meta property="og:description" content="Thanks Mafindoor for helping me find my ways inside {{ $building->name }}">
  	<meta property="og:url" content="{{ \URL::to("/") }}/buildings/{{ $building->id }}-{{ $building->slug }}">
  	<meta property="fb:app_id" content="329159957633823">

	<title>{{ config('app.name', 'Mafindoor') }}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="/css/pages/bootstrap.css" rel="stylesheet" media="screen">
	<link href="/css/pages/owl.theme.css" rel="stylesheet" media="screen">
	<link href="/css/pages/owl.carousel.css" rel="stylesheet" media="screen">
	<link href="/css/pages/style-col1.css?version=1.4.8" rel="stylesheet" media="screen">
	<link href="/css/pages/animate.css" rel="stylesheet" media="screen">
	<link href="/css/pages/ionicons.css" rel="stylesheet" media="screen">

	<link href="/css/pages/buildings/show.css?version=1.4.4" rel="stylesheet" media="screen">

	<link rel="stylesheet" href="/css/pages/nivo-lightbox.css" type="text/css" />
	<link rel="stylesheet" href="/css/pages/nivo-themes/default/default.css" type="text/css" />

	<link href="/img/pages/favicon.png?version=1.2.0" rel="shortcut icon">
	
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Merriweather:300italic' rel='stylesheet' type='text/css'>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">	
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">	
</head>
<body class='ios'>
	<input id="hdn-main-url" type="hidden" value="{{ \URL::to("/") }}" />
	<!--HEADER-->
	<header id='header'>
		<div id='menu-bar' class='animated fadeIn'>
			<div class='container'>
				<div class='logo'>
					<a href="/"><img src="/img/pages/logo.png?version=1.2.0" alt /></a>
				</div>			
				<div class='search'>
					<input class="form-control main-autocomplete" placeholder="Search venues by name or address...">
				</div>	
				<nav  id="nav" class='nav'>
					<ul class='nav-inner'>
						<li class='current'><a href="#header">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#stores">Stores</a></li>
						<li><a href="#maps">Maps</a></li>
						<!-- <li><a href="#download">Download</a></li> -->
					</ul>
				</nav>
			</div>
		</div>
		<div class="container">
			<div class='row'>					
				<div class='col-sm-12 animated fadeInRight text-center no-padding'>
					<h2>Find Your Ways Inside</h2>
					<h1>{{ $building->name }}</h1>
					<h2>With Mafindoor</h2>
					<!-- <div id='header-btn'>
						<a class='btn btn-icon btn-secondary' href=""><span class='icon ion-social-apple'></span><strong>App Store</strong></a>
						<a class='btn btn-icon btn-primary' href=""><span class='icon ion-social-android'></span><strong>Play Store</strong></a>
					</div> -->
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
		@if ($building->images->count() > 0)
			<div id="images-carousel" class="owl-carousel wow fadeInUp text-center">
				@foreach($building->images as $image)
					<a href='javascript:void()' class='item' data-lightbox-gallery="gallery1">
						<img src="{{ $image->url }}" class='img-responsive' alt>
					</a>
				@endforeach
			</div>
		@else
			<video autoplay muted loop id="vid_loop">
			  <source src="/videos/pages/loop.mp4" type="video/mp4">
			</video>
		@endif
	</div>
		<div id='menu-bar-fixed'>
			<div class='container'>
				<a class='logo scrollTo-header'><img src="/img/pages/logo-sm.png?version=1.2.0" alt></a>
				<nav  id="nav-fixed" class='nav'>
					<a id="show-nav" title="Show navigation"><div></div></a>
    				<a id="hide-nav" title="Hide navigation"><div></div></a>
					<ul id='nav-ul' class='nav-inner'>
						<li class='current'><a href="#header">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#stores">Stores</a></li>
						<li><a href="#maps">Maps</a></li>
						<!-- <li><a href="#download">Download</a></li> -->
					</ul>
				</nav>
			</div>
		</div>
	</header>

	<section id="information" class="content">
		<div class="row">
			<div class="col-md-12">
				<h1 class="title">
					{{ $building->name }}
					<span class="address">{{ $building->address }}</span>
				</h1>
				<div class="row">
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-xs-5">
								<div class="circle-icon-rspv">
									<div class="icon ion-android-sort"></div>
								</div>
							</div>
							<div class="col-xs-7 building-info">
								<span class="label">Floors</span><br/>
								<span class="value">{{ $building->floors->count() }}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-xs-5">
								<div class="circle-icon-rspv">
									<div class="icon ion-ios7-pricetags"></div>
								</div>
							</div>
							<div class="col-xs-7 building-info">
								<span class="label">Spaces</span><br/>
								<span class="value">{{ $building->spaces()->count() }}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-xs-5">
								<div class="circle-icon-rspv">
									<div class="icon ion-android-stair-drawer"></div>
								</div>
							</div>
							<div class="col-xs-7 building-info">
								<span class="label">Escalators</span><br/>
								<span class="value">{{ $building->annotations()->where('name', 'LIKE', 'Escalator%')->count() }}</span>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-xs-5">
								<div class="circle-icon-rspv">
									<div class="icon ion-man"></div>
								</div>
							</div>
							<div class="col-xs-7 building-info">
								<span class="label">Elevators</span><br/>
								<span class="value">{{ $building->annotations()->where('name', 'LIKE', 'Elevator%')->count() }}</span>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<hr/>
				<br/>
				<div id="stores" class="row">
					<div class="col-md-12">
						<h2>Stores/Spaces Inside {{ $building->name }}</h2>
						<table id="datatable_tabletools_annotations" class="table table-bordered">
							<thead>
					          <th class="name">Name</th>
					          <th class="near">Location</th>
					          <th class="category text-center">Category</th>
					          <td class="action"></td>
					        </thead>
					        <tbody>
						        @foreach($building->spaces()->orderBy('name')->get() as $annotation)
						        	<tr>
						        		<td class="name">
						        			<img src="{{ $annotation->logo }}" width="40" />
						        			&nbsp;
	                						<strong>{{ $annotation->name }}</strong>
						        		</td>
						        		<td class="near">
						        			<strong>{{ $annotation->floor->name }}</strong>
						        			<div class="nowrap"><small>Near {{ $annotation->nears_str() }}</small></div>
						        		</td>
						        		<td class="category text-center">
						        			<label class="label label-primary">{{ strtoupper($annotation->sub_category->name) }}</label>
						        		</td>
						        		<td class="action">
						        			<a href="#maps" class="btn btn-sm btn-secondary show-in-map" data-annotation-slug="{{ $annotation->slug }}"  data-floor-slug="{{ $annotation->floor->slug }}" data-building-slug="{{ $annotation->floor->building->slug }}" >Show In Map</a>
						        		</td>
						        	</tr>
						        @endforeach
					        </tbody>
						</table>
					</div>
				</div>
				<br/>
				<hr/>
				<br/>
				<div id="maps" class="row">
					<div class="col-md-12">
						<h2>Indoor Maps of {{ $building->name }}</h2>
						<iframe src="{{ \URL::to("/") }}/search/buildings/{{ $building->id }}?header=no&sidebar=no" width="100%" height="500"></iframe>
					</div>
				</div>
				<br/>
				<br/>
				<br/>
			</div>
		</div>
	</section>

	<!--DOWNLOAD-->
	<section id='download'>
		<div class="container">
			<div class='wow fadeInDown'>
				<h2>Mafindoor App Is Coming Soon...</h2>
				<!-- <h2>Download Mafindoor Now!</h2> -->
				<p class='subtitle'>Avoid the hassle of finding and waiting for your turn to use <strong>{{ $building->name }}'s</strong> directory.</p>
			</div>
			<!-- <p class='download-buttons'>
				<a class='btn btn-icon btn-secondary' href=""><span class='icon ion-social-apple'></span>App Store</a>
				<a class='btn btn-icon btn-primary' href=""><span class='icon ion-social-android'></span>Play Store</a>
			</p> -->
			<div class='floating-phone wow fadeInRightBig'></div>
		</div>
	</section>

	<!--FOOTER-->
	<footer id='footer'>
		<div class="container">
			<div id='footer-content'>
				<h2><img src="/img/pages/logo.png?version=1.2.0" alt></h2>
				<p id='social-links'>
					<a href="https://www.facebook.com/Mafindoor-507981219706005" target="_blank" class='icon ion-social-facebook'></a>
					<a href="https://twitter.com/mafindoor" class='icon ion-social-twitter' target="_blank"></a>
					<a href="https://plus.google.com/u/0/103858053761972961832" target="_blank" class='icon ion-social-googleplus-outline'></a>
					<!-- <a href="" class='icon ion-social-instagram'></a> -->
				</p>
				<p class='copyright'>Copyright © 2019 Mafindoor</p>
			</div>
		</div>
	</footer>
	<div id='static-footer'></div>

	<script src="/js/pages/jquery-2.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/pages/retina.min.js"></script>
	<script src="/js/pages/owl.carousel.min.js"></script>
	<script src="/js/pages/wow.js"></script>
	<script src="/js/pages/jquery.nav.js"></script>
	<script src="/js/pages/jquery.scrollTo.min.js"></script>
	<script src="/js/pages/nivo-lightbox.min.js"></script>

	<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

	<script src="{{ asset('/plugins/jquery-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>

	<script type="text/javascript">
		// general variables
		myWindow = $(window)
		windowHeight = myWindow.height()
		windowWidth = myWindow.width()
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

			$("#images-carousel").owlCarousel({
				items : 1,
				itemsDesktop : [1199,1],
				itemsDesktopSmall : [980,1],
				itemsTablet: [768,1],
				itemsMobile : [480,1],
				autoPlay: 8000,
				dots: false,
				smartSpeed:450,
				navigation: true,
		      	loop: true,
		      	navigationText: ['<i class="gallery-icon left" aria-hidden="true"></i>','<i class="gallery-icon right" aria-hidden="true"></i>'],
			});

			$('#datatable_tabletools_annotations').DataTable({ language: { search: '' } });
			$('#datatable_tabletools_annotations_filter input').attr('placeholder', 'Search stores by name, floor, or category...');

			$('.show-in-map').unbind('click').on('click', function () {
				var building_slug = $(this).data('building-slug');
				var floor_slug = $(this).data('floor-slug');
				var annotation_slug = $(this).data('annotation-slug');

				var main_url = $('#hdn-main-url').val();

				$('iframe').attr('src', main_url + '/search/buildings/' + building_slug + '/floors/' + floor_slug + '/annotations/' + annotation_slug);
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

			windowHeight = myWindow.height()
			windowWidth = myWindow.width()

			if (windowWidth<=667) {
				header.css('height', 380)
				$('.owl-prev').css('top', 140)
				$('.owl-next').css('top', 140)
			}
			else if (windowWidth<=768) {
				header.css('height', 560)
				$('.owl-prev').css('top', 380)
				$('.owl-next').css('top', 380)
			}
			else {
				header.css('height', 768)
				$('.owl-prev').css('top', 384)
				$('.owl-next').css('top', 384)	
			}
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
			windowHeight = myWindow.height()
			windowWidth = myWindow.width()

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

			if (windowWidth<=667) {
				header.css('height', 380)
				$('.owl-prev').css('top', 140)
				$('.owl-next').css('top', 140)
			}
			else if (windowWidth<=768) {
				header.css('height', 560)
				$('.owl-prev').css('top', 380)
				$('.owl-next').css('top', 380)
			}
			else {
				header.css('height', 768)
				$('.owl-prev').css('top', 384)
				$('.owl-next').css('top', 384)	
			}

			headerHeight = header.outerHeight()
			headerWidth = header.outerWidth()
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