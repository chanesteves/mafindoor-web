@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <ul id="nvs-pathways" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#lines" role="tab">Lines</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#routes" role="tab">Routes</a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="lines" role="tabpanel">
      <form id="frm-main-filter" method="GET" action="/floors">
        <div class="row main-filter">    
          <div class="col-md-10 col-6">
            <select id="ddl-building-id" name="building_id" class="form-control input-lg">
              @foreach($buildings as $build)
                <option value="{{ $build->id }}" {{ $building && $building->id == $build->id ? 'selected' : '' }} >{{ $build->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-6">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i>&nbsp;Search</button>
          </div>    
        </div>
      </form>
      <br/>

      @if (count($floors) > 0)

        <div class="row">
          <div class="col-md-6">
            <ul id="nvs-lines-floors" class="nav nav-tabs" role="tablist">
              @php $count = 0; @endphp
              @foreach($floors as $floor)
                <li class="nav-item">
                  <a class="nav-link {{ $count == 0 ? 'active' : '' }}" data-id="{{ $floor->id }}" data-toggle="tab" href="#tab-line-{{ $floor->id }}" role="tab">{{ $floor->label }}</a>
                </li>
                @php $count++; @endphp
              @endforeach
            </ul>
            <div class="tab-content">
              @php $count = 0; @endphp
              @foreach($floors as $floor)
                <div class="tab-pane {{ $count == 0 ? 'active' : '' }}" id="tab-line-{{ $floor->id }}" role="tabpanel">
                  <div id="pnl-pathway-map-{{ $floor->id }}" class="lines-map" data-id="{{ $floor->id }}" data-url="{{ $floor->map_url }}" data-longitude="{{ $floor->longitude }}" data-latitude="{{ $floor->latitude }}" data-zoom="{{ ($floor->max_zoom + $floor->min_zoom) / 2 }}" ></div>
                </div>
                @php $count++; @endphp
              @endforeach
            </div>
          </div>
          <div class="col-md-6">
            <form id="frm-pathways">
              <div id="pnl-pathways">
                <table id="tbl-pathways" class="table table-bordered">
                  <thead>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Two-way</th>
                    <td></td>
                  </thead>
                  <tbody>
                    <tr class="extra-row hidden">
                      <td class="no-padding">
                        <div class="form-group">
                          <input class="form-control text-center origin" type="text" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control text-center destination" type="text" />
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <input type="checkbox" checked class="two-way" />
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>                  
                    </tr>
                    <tr class="extra-row visible">
                      <td class="no-padding">
                        <div class="form-group">
                          <input class="form-control text-center origin" type="text" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control text-center destination" type="text" />
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <input type="checkbox" checked class="two-way" />
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <button id="btn-save-building-lines" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save</button>
              </div>
            </form>
          </div>
        </div>
      @endif
    </div>
    <div class="tab-pane" id="routes" role="tabpanel">
      @if (count($floors) > 0)

        <div class="row">
          <div class="col-md-12">
            <ul id="nvs-routes-floors" class="nav nav-tabs" role="tablist">
              @php $count = 0; @endphp
              @foreach($floors as $floor)
                <li class="nav-item">
                  <a class="nav-link {{ $count == 0 ? 'active' : '' }}" data-id="{{ $floor->id }}" data-toggle="tab" href="#tab-route-{{ $floor->id }}" role="tab">{{ $floor->label }}</a>
                </li>
                @php $count++; @endphp
              @endforeach
            </ul>
            <div class="tab-content">
              @php $count = 0; @endphp
              @foreach($floors as $floor)
                <div class="tab-pane {{ $count == 0 ? 'active' : '' }}" id="tab-route-{{ $floor->id }}" role="tabpanel">
                  <div id="pnl-route-map-{{ $floor->id }}" class="routes-map" data-id="{{ $floor->id }}" data-url="{{ $floor->map_url }}" data-longitude="{{ $floor->longitude }}" data-latitude="{{ $floor->latitude }}" data-zoom="{{ $floor->max_zoom }}" ></div>
                </div>
                @php $count++; @endphp
              @endforeach
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection