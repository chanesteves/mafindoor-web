@extends('layouts.app')

@section('content')
<div class="container">
  <br/>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card mx-4">
        <div class="card-body p-4">
            <div id="pnl-verify-email-message">
                <center>
                    <h1>
                      @if ($status == 'OK')
                        Email Verified!
                      @else
                        Email Verification Failed!
                      @endif
                    </h1>            
                </center>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <div class="message">
                            @if ($status == 'OK')
                              <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> {!! $message !!}
                              </div>
                            @else
                              <div class="alert alert-danger">
                                <i class="fa fa-times-circle"></i> {!! $error !!}
                              </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                  @if ($status == 'OK')
                    <div class="col-12 buttons-success text-center">
                        <a href="/auth/login" class="btn btn-success"><i class="fa fa-sign-in"></i> Login</a>
                    </div>
                  @endif
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
