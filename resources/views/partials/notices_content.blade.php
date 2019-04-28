@if (Session::get('success'))
	<div class="page-success">
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> {!! Session::get('success') !!}</div>
	</div>
@endif

@if (Session::get('warning'))
	<div class="page-warning">
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> {!! Session::get('warning') !!}</div>
	</div>
@endif

@if (Session::get('danger'))
	<div class="page-danger">
		<div class="alert alert-danger"><i class="fa fa-times-circle"></i> {!! Session::get('danger') !!}</div>
	</div>
@endif