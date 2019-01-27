@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')

  <div id="map">
  </div>

	<div id="pnl-search" style="display: {{ $search_floor ? '' : 'none' }}">
		<form action="#" class="header-search pull-right">
			<input type="text" placeholder="Search" id="search-fld" class="annotation-main-autocomplete" autocomplete="off">
			<a href="javascript:void(0)">
				<i class="fa fa-search"></i>
			</a>
		</form>
		<div id="pnl-results"></div>
		<div id="pnl-near">
			@if ($search_annotation)
				@foreach($search_annotation->near as $item)
					<input type="hidden" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-logo="{{ $item->logo && $item->logo != '' ? $item->logo : ($item->sub_category && $item->sub_category->icon && $item->sub_category->icon != '' ? $item->sub_category->icon : ($item->sub_category && $item->sub_category->category && $item->sub_category->category->icon && $item->sub_category->category->icon != '' ? $item->sub_category->category->icon : '/images/buildings/shop.png')) }}" data-floor-name="{{ $search_floor ? $search_floor->name : '' }}" class="near">
				@endforeach
			@endif
		</div>
	</div>

	<div id="pnl-floors" style="display: {{ $search_floor ? '' : 'none' }}">
		@if ($search_building)
		  	@foreach($search_building->floors as $floor)
		  		<span>
				  	<a href="/search/buildings/{{ $search_building->slug }}/floors/{{ $floor->slug }}" class="btn {{ $floor->id == $search_floor->id ? 'active' : '' }}">
				  		{{ $floor->label }}
				  	</a>
				</span>
		  	@endforeach
		@endif
	</div>

  <div class="modal fade" id="modal-select-venue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Venue</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

        	<div class="alert alert-info"><i class="fa fa-info-circle"></i> Let's start by selecting the venue you want to navigate.</div>
        	<div class="building-list">
	        	@foreach($buildings as $building)
	        		@if ($building->floors->count() > 0)
		        		<div class="row">
		        			<div class="col-md-12">
					        	<a href="/search/buildings/{{ $building->slug }}" data-floor-id="{{ $building->floors->first()->id }}" data-map-url="{{ $building->floors->first()->map_url }}" data-longitude="{{ $building->floors->first()->longitude }}" data-latitude="{{ $building->floors->first()->latitude }}" data-min-zoom="{{ $building->floors->first()->min_zoom }}" data-max-zoom="{{ $building->floors->first()->max_zoom }}" data-zoom="{{ $building->floors->first()->zoom }}" class="btn btn-block btn-secondary btn-lg building-link">
					        		<div class="row">
					        			<div class="col-xs-3">
					        				@if ($building->logo)
						                      <img src="{{ $building->logo }}" class="building-logo">
						                    @else
						                      <img src="/images/buildings/shop.png" class="building-logo">
						                    @endif
					        			</div>
					        			<div class="col-xs-9 building-details">
					        				<div class="building-name"><h5>{{ $building->name }}</h5></div>
					        				<div class="building-address">{{ $building->address }}</div>
					        			</div>
					        		</div>				        		
					        	</a>
					    	</div>    
			        	</div>
			        @endif
		        @endforeach
		    </div>
        </div>
      </div>
    </div>
  </div>
@endsection