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
    <meta name="description" content="CoreUI - Open Sousearchrce Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <title>{{ config('app.name', 'Mafindoor') }}</title>

    <link href="img/pages/favicon.png?version=1.4.0" rel="shortcut icon">

    <!-- Icons-->
    <link href="{{ asset('plugins/coreui-icons/css/coreui-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css?version=1.4.6') }}" rel="stylesheet">

    <link href="{{ asset('plugins/pace-progress/css/pace.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/datatables/datatables.min.css') }}" rel="stylesheet">

    @if (isset($page) && $page == 'Search')
      <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
      <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.52.0/mapbox-gl.css' rel='stylesheet' />

      <link href="{{ asset('css/pages/search.css?version=1.4.18') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Venues')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">
      <link href="{{ asset('plugins/dropzone/dist/dropzone.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Floors')
      <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.css' rel='stylesheet' />

      <link href="{{ asset('css/pages/directories/floors.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Annotations')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">           
    @elseif (isset($page) && $page == 'Categories')
      <link href="{{ asset('plugins/croppie/croppie.css') }}" rel="stylesheet">
    @elseif (isset($page) && $page == 'Users')
      <link href="{{ asset('css/pages/permissions/users.css') }}" rel="stylesheet">
    @endif

    <link href="{{ asset('css/override.css?version=1.4.6') }}" rel="stylesheet">

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
    <input type="hidden" id="hdn-site-url" value="{{ \URL::to("/") }}" />
    <header class="app-header navbar" style="display: {{ Session::get('header') == 'no' ? 'none' : 'flex' }}" >
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="/img/brand/logo-horizontal.png?version=1.4.0" width="89" height="25" alt="Mafindoor Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            @php $user = Auth::user(); @endphp

            @if ($user && $user->person)
              @if ($user->person->image && $user->person->image != '')
                <img class="img-avatar" src="{{ $user->person->image }}" alt="{{ $user->person->first_name }} {{ $user->person->last_name }}">
              @else
                <img class="img-avatar" src="/img/avatars/initials/{{ strtoupper($user->person->first_name[0]) }}.png" alt="{{ $user->person->first_name }} {{ $user->person->last_name }}">
              @endif
            @endif
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
    @if (isset($page) && $page != 'Search')
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
              <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="javascipt:void(0)">
                  <i class="nav-icon icon-people"></i> Permissions</a>
                <ul class="nav-dropdown-items">
                  <li class="nav-item">
                    <a class="nav-link" href="/users">
                      Users
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/privileges">
                      Privileges
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
    @else
      <div class="app-body">
        <div class="sidebar" style="display: {{ Session::get('sidebar') == 'no' ? 'none' : 'initial' }}">
          <nav class="sidebar-nav">
            <ul class="nav">
              @if (isset($search_building) && $search_building && $search_building->floors->count() > 0)
                <li class="nav-item nav-dropdown">
                  <a class="active nav-link building-link" href="/search/buildings/{{ $search_building->slug }}" data-floor-id="{{ $search_floor->id }}" data-sub-category-id="{{ isset($search_sub_category) && $search_sub_category ? $search_sub_category->id : '' }}" data-sub-category-name="{{ isset($search_sub_category) && $search_sub_category ? $search_sub_category->name : '' }}" data-annotation-id="{{ isset($search_annotation) && $search_annotation ? $search_annotation->id : '' }}" data-annotation-name="{{ isset($search_annotation) && $search_annotation ? $search_annotation->name : '' }}" data-annotation-map-name="{{ isset($search_annotation) && $search_annotation ? $search_annotation->map_name : '' }}" data-annotation-logo="{{ isset($search_annotation) && $search_annotation ? ($search_annotation->logo && $search_annotation->logo != '' ? $search_annotation->logo : ($search_annotation && $search_annotation->sub_category && $search_annotation->sub_category->icon && $search_annotation->sub_category->icon != '' ? $search_annotation->sub_category->icon : ($search_annotation && $search_annotation->sub_category && $search_annotation->sub_category->category && $search_annotation->sub_category->category->icon && $search_annotation->sub_category->category->icon != '' ? $search_annotation->sub_category->category->icon : '/images/buildings/shop.png'))) : '' }}" data-map-url="{{ $search_floor->map_url }}" data-longitude="{{ $search_annotation ? $search_annotation->longitude : $search_floor->longitude }}" data-latitude="{{ $search_annotation ? $search_annotation->latitude : $search_floor->latitude }}" data-min-zoom="{{ $search_floor->min_zoom }}" data-max-zoom="{{ $search_floor->max_zoom }}" data-zoom="{{ $search_annotation ? (($search_annotation->max_zoom + $search_annotation->min_zoom) / 2) : $search_floor->zoom }}" data-mode="{{ $search_annotation ? 'ANNOTATION' : 'FLOOR' }}">
                    <span class="nav-building-name">{{ $search_building->name }}</span> <i class="nav-icon icon-location-pin"></i>
                  </a>
                </li>
              @endif
              @foreach($buildings as $building)
                @if ((!$search_building || $search_building->id != $building->id) && $building->floors->count() > 0)
                  <li class="nav-item">
                    <a class="nav-link building-link" href="/search/buildings/{{ $building->slug }}" data-floor-id="{{ $building->floors->first()->id }}" data-map-url="{{ $building->floors->first()->map_url }}" data-longitude="{{ $building->floors->first()->longitude }}" data-latitude="{{ $building->floors->first()->latitude }}" data-min-zoom="{{ $building->floors->first()->min_zoom }}" data-max-zoom="{{ $building->floors->first()->max_zoom }}" data-zoom="{{ $building->floors->first()->zoom }}">
                      <span class="nav-building-name">{{ $building->name }}</span> <i class="nav-icon icon-arrow-right-circle"></i>
                    </a>
                  </li>
                @endif
              @endforeach
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
    @endif
    <footer class="app-footer">
      <div>
        <a href="http://mafindoor.com">Mafindoor</a>
        <span>&copy; 2019 CollabUX Web Solutions, Co.</span>
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

    <div class="browser-error">
      <div class="browser-error-wrapper">
        <div class="browser-error-content">
          <h4>You are using an outdated browser and will encounter some problems with our website. Please consider upgrading.</h4>
          <a href="http://outdatedbrowser.com/" class="btn btn-lg">Upgrade Now</a>
          <br/>
          <img src="/img/pages/logo.png?version=1.4.0" />
        </div>
      </div>
    </div>

    <!-- CoreUI and necessary plugins-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    
    <script>
      if (!window.jQuery) {
        document.write('<script src="{{ asset('/plugins/jquery/jquery-2.0.2.min.js?version=1.4.0') }}"><\/script>');
      }

      window.onerror=function(){
       $('.browser-error').show();
       return true
      }
    </script>


    <script src="{{ asset('plugins/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/pace-progress/pace.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/coreui/dist/js/coreui.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/dist/jquery.validate.js') }}"></script>

    <script src="{{ asset('js/controls/modal.js?version=1.4.0') }}"></script>

    @if (isset($page) && $page == 'Search')
      <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.52.0/mapbox-gl.js'></script>

      <script src="{{ asset('js/classes/building.js?version=1.4.3') }}"></script>
      <script src="{{ asset('js/classes/floor.js?version=1.4.0') }}"></script>
      <script src="{{ asset('js/pages/search.js?version=1.4.34') }}"></script>
    @elseif (isset($page) && $page == 'Venues')
      <script src="http://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyAY0WNFta5H_dKTZ_f260R39PhMDvu8ETQ"></script>
      <script src="{{ asset('plugins/geocomplete/jquery.geocomplete.js') }}"></script>

      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>
      <script src="{{ asset('plugins/dropzone/dist/dropzone.js') }}"></script>

    	<script src="{{ asset('js/classes/building.js?version=1.4.3') }}"></script>
      <script src="{{ asset('js/classes/image.js?version=1.4.0') }}"></script>
    	<script src="{{ asset('js/pages/directories/venues.js?version=1.4.8') }}"></script>      
    @elseif (isset($page) && $page == 'Floors')
      <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.49.0/mapbox-gl.js'></script>
      <script src="{{ asset('js/classes/floor.js?version=1.4.1') }}"></script>
      <script src="{{ asset('js/pages/directories/floors.js?version=1.4.3') }}"></script>
    @elseif (isset($page) && $page == 'Annotations')
      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>      

      <script src="{{ asset('js/classes/annotation.js?version=1.4.4') }}"></script>
      <script src="{{ asset('js/pages/directories/annotations.js?version=1.4.11') }}"></script>
    @elseif (isset($page) && $page == 'Categories')
      <script src="{{ asset('js/controls/tab.js?version=1.4.0') }}"></script>
      <script src="{{ asset('plugins/bootstrap-fileinput/js/plugins/canvas-to-blob.js') }}"></script>
      <script src="{{ asset('plugins/croppie/croppie.js') }}"></script>

      <script src="{{ asset('js/classes/category.js?version=1.4.1') }}"></script>
      <script src="{{ asset('js/classes/sub_category.js?version=1.4.0') }}"></script>
      <script src="{{ asset('js/pages/maintenance/categories.js?version=1.4.1') }}"></script>
    @elseif (isset($page) && $page == 'Users')
      <script src="{{ asset('js/classes/user.js?version=1.4.1') }}"></script>
      <script src="{{ asset('js/pages/permissions/users.js?version=1.4.1') }}"></script>
    @elseif (isset($page) && $page == 'Privileges')
      <script src="{{ asset('/plugins/jquery-nestable/jquery.nestable.js')}}"></script>      
      <script src="{{ asset('js/controls/tab.js?version=1.4.0') }}"></script>
      <script src="{{ asset('js/classes/role.js?version=1.4.1') }}"></script>
      <script src="{{ asset('js/classes/menu.js?version=1.4.0') }}"></script>
      <script src="{{ asset('js/pages/permissions/privileges.js?version=1.4.2') }}"></script>
    @endif
  </body>
</html>
