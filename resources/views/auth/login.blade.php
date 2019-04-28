@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-group">
        <div class="card p-4">
          <div class="card-body">
            <h1 class="text-center">Login</h1>
            <a href="{{ url('auth/facebook') }}" class="btn btn-brand btn-block btn-facebook">
                <i class="fa fa-facebook"></i>
                <span>Facebook</span>                
            </a>
            <br/>
            <div class="row">
              <div class="col-5">
                <hr/>
              </div>
              <div class="col-2 text-center">
                <strong>OR</strong>
              </div>
              <div class="col-5">
                <hr/>
              </div>
            </div>
            <form id="frm-login" method="POST" action="/auth/login">
                <input id="hdn-token" type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <p class="text-muted text-center">Sign In to your account</p>

                @if (Session::get('error'))
                    <div class="row">
                        <div class="col-12">
                            <div class="message">
                                <div class="alert alert-danger">
                                    <i class="fa fa-times-circle"></i>
                                    {{ Session::get('error') }}                     
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-user"></i>
                    </span>
                  </div>
                  <input id="txt-username" class="form-control" type="text" name="username" placeholder="Username">
                </div>
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-lock"></i>
                    </span>
                  </div>
                  <input id="txt-password" class="form-control" type="password" name="password" placeholder="Password">
                </div>
                <div class="row">
                  <div class="col-6">
                    <button class="btn btn-primary px-4" type="submit">Login</button>
                  </div>
                  <div class="col-6 text-right">
                    <button class="btn btn-link px-0" type="button">Forgot password?</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
        <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <div class="card-body text-center">
            <div>
              <h2>Sign up</h2>
              <p>Join thousands of mazers who find it easy to see what <i>Google Maps</i> can't show and reach where <i>Waze</i> can't go.</p>
              <a class="btn btn-primary active mt-3" href="/auth/register">Register Now!</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
