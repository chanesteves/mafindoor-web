@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')

  <div>
    <center>
      <img src="{{ $building->image }}" width="250" />
    </center>
  </div>
@endsection