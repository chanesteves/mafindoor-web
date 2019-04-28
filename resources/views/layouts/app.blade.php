<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mafindoor') }}</title>

    <!-- Icons-->
    <link href="{{ asset('plugins/coreui-icons/css/coreui-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/simple-line-icons/css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css?version=1.1.2') }}" rel="stylesheet">

    <link href="{{ asset('plugins/pace-progress/css/pace.min.css') }}" rel="stylesheet">

    @if(Request::path() == 'auth/register' || Request::path() == 'register')
        <link href="{{ asset('css/pages/auth/register.css') }}" rel="stylesheet">
    @endif
</head>
<body class="app header-fixed">
    <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="/img/brand/logo-horizontal.png" width="89" height="25" alt="Maze Logo">
      </a>
        <ul class="nav navbar-nav d-md-down-none">
            <li class="nav-item px-3">
                <a class="nav-link" href="/auth/login">Login</a>
            </li>
            <li class="nav-item px-3">
                <a class="nav-link" href="/auth/register">Register</a>
            </li>
        </ul>
    </header>
    <div class="app-body">
        <main class="main">
            <div class="container-fluid">
              <div class="animated fadeIn">
                @yield("content")
              </div>
            </div>
        </main>
    </div>
    <div id="contact" class="footer">
        <div class="contact">
            <section class="row">
                <div class="col-md-4 logo">
                    <center>
                        <img class="navbar-brand-minimized" src="{{ asset('img/brand/logo-vertical.png') }}" width="200" alt="Mafindoor Logo">
                        <br/>
                        <h4>Find Your Way Indoors</h4>
                        <p>
                            Join thousands of mafindoor users who find it easy to see what <i>Google Maps</i> can't show and reach where <i>Waze</i> can't go
                        </p>
                    </center>
                </div>
                <div class="col-md-4">
                    For any questions: <br>
                    <strong>Email us at</strong> <a href="mailto:support@mafindoor.com">support@mafindoor.com</a> <br>

                    <strong>Call us at</strong> <br>
                    (052) 740-6214 | (+63) 917-143-5116 <br>
                    (+63) 998-591-4749 | (+63) 998-591-4751 <br><br>
                    <strong>Visit our office</strong> <br>
                    Unit 203 Pineda Bldg, Rizal St., Brgy. 25, <br>
                    Legazpi City, Philippines, 4500 <br><br>
                </div>
                <div class="col-md-4">
                    <br/>
                    <a href="#">About Us</a>
                    <br/>
                    <a href="#">Help Center</a>
                    <br/>
                    <a href="#">Privacy Policy</a>
                    <br/>
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </section>
        </div>
        <section class="row breath">
            <div class="col-md-4 text-center">
                <p style="font-size: 14px;">Powered by <a href="https://collabux.com" target="_blank"><b>CollabUX Web Solutions, Co.</b></a></p>
            </div>
            <div class="col-md-4 text-center">
                <p style="font-size: 14px;">© 2018 Mafindoor. All Rights Reserved</p>
            </div>
            <div class="col-md-4 text-center">
                <a href="#" target="_blank" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
                <a href="#" target="_blank" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
                <a href="#" target="_blank" class="btn btn-google-plus"><i class="fa fa-google-plus"></i></a>
                <a href="#" target="_blank" class="btn btn-linkedin"><i class="fa fa-linkedin"></i></a>
            </div>
            <br/><br/>
        </section>

    </div>
    <!-- Scripts -->
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

    @if(Request::path() == 'auth/register' || Request::path() == 'register')
        <script type="text/javascript" src="{{ asset('/js/pages/auth/register.js?version=1.1.3') }}"></script>
    @elseif(Request::path() == 'auth/login' || Request::path() == 'login')
        <script type="text/javascript" src="{{ asset('/js/pages/auth/login.js?version=1.1.1') }}"></script>
    @endif
</body>
</html>
