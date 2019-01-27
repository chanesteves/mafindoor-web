@extends('layouts.app')

@section('content')
<div class="container">
  <br/>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card mx-4">
        <div class="card-body p-4">
            <div id="pnl-register-message">
                <center>
                    <h1>Email Verification Sent!</h1>            
                </center>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <div class="message">
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 buttons-success text-center">
                        <a href="/auth/login" class="btn btn-success"><i class="fa fa-sign-in"></i> Login</a>
                    </div>
                    <div class="col-12 buttons-error text-center">
                        <button id="btn-register-back" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
                    </div>
                </div>
            </div>
            <div id="pnl-register-form">
              <center>
                <h1>Join Mafindoor</h1>            
              </center>
              <br/>
              <form id="frm-register" method="POST" action="">
                <input id="hdn-token" type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="row">
                    <div class="col-12">
                      <a href="{{ url('auth/facebook') }}" class="btn btn-brand btn-block btn-facebook">
                        <i class="fa fa-facebook"></i>
                        <span>Facebook</span>                
                      </a>
                    </div>
                  </div>
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
                  <center>
                    <p class="text-muted">Create your account</p>
                  </center>
                  <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <input id="txt-first-name" class="form-control" name="first_name" type="text" placeholder="First Name" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <input id="txt-last-name" class="form-control" name="last_name" type="text" placeholder="Last Name"  required>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <select id="ddl-gender" class="form-control" name="gender" required>
                                <option selected disabled>Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">@</span>
                    </div>
                    <input id="txt-email" class="form-control" type="text" name="email" placeholder="Email"  required>
                  </div>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-user"></i>
                      </span>
                    </div>
                    <input id="txt-username" class="form-control" type="text" name="username" placeholder="Username"  required>
                  </div>          
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="icon-lock"></i>
                      </span>
                    </div>
                    <input id="txt-password" class="form-control" type="password" name="password" placeholder="Password"  required>
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend">
                      <span id="txt-password" class="input-group-text">
                        <i class="icon-lock"></i>
                      </span>
                    </div>
                    <input id="txt-confirm-password" class="form-control" type="password" name="confirm_password" placeholder="Repeat password"  required>
                  </div>
                  <button id="btn-register" type="submit" class="btn btn-block btn-success" type="button">Create Account</button>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
