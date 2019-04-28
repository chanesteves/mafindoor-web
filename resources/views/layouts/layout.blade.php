<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.1.5
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>{{ config('app.name', 'Mafindoor') }}</title>
    <!-- Icons-->
    <link href="{{ asset('plugins/coreui-icons/css/coreui-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css?version=1.1.4') }}" rel="stylesheet">

    <link href="{{ asset('plugins/pace-progress/css/pace.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet">

    @if (isset($page) && $page == 'Venues')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Floors')
      <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />

      <link href="{{ asset('css/pages/directories/floors.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Annotations')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Categories')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">
    @endif

    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
  </head>
  <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="/img/brand/logo-horizontal.png" width="89" height="25" alt="Mafindoor Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
          <a class="nav-link" href="#">Dashboard</a>
        </li>
      </ul>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <img class="img-avatar" src="img/avatars/6.jpg" alt="admin@bootstrapmaster.com">
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
              <strong>Settings</strong>
            </div>
            <a class="dropdown-item" href="#">
              <i class="fa fa-user"></i> Profile</a>
            <a class="dropdown-item" href="#">
              <i class="fa fa-wrench"></i> Settings</a>
            <a class="dropdown-item" href="/auth/logout">
              <i class="fa fa-lock"></i> Logout</a>
          </div>
        </li>
      </ul>
    </header>
    <div class="app-body">
      <div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav">
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="javascipt:void(0)">
                <i class="nav-icon icon-map"></i> Directories</a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="/venues">
                    Venues
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/floors">
                    Floors
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/annotations">
                    Annotations
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="javascipt:void(0)">
                <i class="nav-icon icon-list"></i> Maintenance</a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="/categories">
                    Categories
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>
      <main class="main">
        <div class="container-fluid">
          <div class="animated fadeIn">
            @yield("content")
          </div>
        </div>
      </main>
    </div>
    <footer class="app-footer">
      <div>
        <a href="https://coreui.io">Mafindoor</a>
        <span>&copy; 2018 CollabUX Web Solutions, Co.</span>
      </div>
    </footer>

    <div class="modal fade" id="modal-main" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
	    <div class="modal-dialog modal-info" role="document">
	      <div class="modal-content">
	        <div class="modal-header">
	          <h4 class="modal-title"></h4>
	        </div>
	          <div class="modal-body">
	            <div class="row">
	              <div class="col-md-12">
	              	<br/>
	              	<div id="lbl-main"></div>
	              	<br/>
	              </div>
	            </div>
	          </div>
	      </div>
	    </div>
	  </div>

    <!-- CoreUI and necessary plugins-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    
    <script>
      if (!window.jQuery) {
        document.write('<script src="{{ asset('/plugins/jquery/jquery-2.0.2.min.js?version=1.1.0') }}"><\/script>');
      }
    </script>


    <script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/coreui/dist/js/coreui.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/dist/jquery.validate.js') }}"></script>

    <script src="{{ asset('js/controls/modal.js?version=1.1.0') }}"></script>

    @if (isset($page) && $page == 'Venues')
      <script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA59CRxMrLwSr33LgnuZ3sbX-fJMhovtHU"></script>
      <script src="{{ asset('plugins/geocomplete/jquery.geocomplete.js') }}"></script>

      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>

    	<script src="{{ asset('js/classes/building.js?version=1.1.1') }}"></script>
    	<script src="{{ asset('js/pages/directories/venues.js?version=1.1.5') }}"></script>
    @elseif (isset($page) && $page == 'Floors')
      <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
      <script src="{{ asset('js/classes/floor.js?version=1.1.1') }}"></script>
      <script src="{{ asset('js/pages/directories/floors.js?version=1.1.3') }}"></script>
    @elseif (isset($page) && $page == 'Annotations')
      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>

      <script src="{{ asset('js/classes/annotation.js?version=1.1.1') }}"></script>
      <script src="{{ asset('js/pages/directories/annotations.js?version=1.1.5') }}"></script>
    @elseif (isset($page) && $page == 'Categories')
      <script src="{{ asset('js/controls/tab.js?version=1.1.0') }}"></script>
      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>

      <script src="{{ asset('js/classes/category.js?version=1.1.1') }}"></script>
      <script src="{{ asset('js/classes/sub_category.js?version=1.1.0') }}"></script>
      <script src="{{ asset('js/pages/maintenance/categories.js?version=1.1.1') }}"></script>
    @endif
  </body>
</html>
