@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  <form id="frm-main-filter" method="GET" action="/routes">
    <div class="row main-filter">    
      <div class="col-md-5 col-4">
        <select id="ddl-building-id" name="building_id" class="form-control input-lg" required>
          @foreach($buildings as $build)
            <option value="{{ $build->id }}" {{ $building && $building->id == $build->id ? 'selected' : '' }} >{{ $build->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-5 col-4">
        <select id="ddl-floor-id" name="floor_id" class="form-control input-lg" required>
          <option value="" data-building-id="" ></option>
          @foreach($floors as $flo)
            <option value="{{ $flo->id }}" data-building-id="{{ $flo->building_id }}" {{ $floor && $floor->id == $flo->id ? 'selected' : '' }} >{{ $flo->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2 col-4">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i>&nbsp;Search</button>        
      </div>    
    </div>
  </form>
  <br/>
  <div class="card">
    <div class="card-header">
      <span>Routes</span>
      <div class="card-header-actions">
        <button id="btn-add-route" data-target="#modal-add-route" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
          <i class='fa fa-plus'></i> Add Route
        </button>
      </div>
    </div>
    <div class="card-body">
      <table id="datatable_tabletools_routes" class="table table-bordered">
        <thead>
          <th>Origin</th>
          <th>Destination</th>
          <th></th>
        </thead>
        <tbody>
          @foreach($routes as $route)
            <tr>
              <td>[{{ $route->origin_lat }}, {{ $route->origin_lng }}]</td>
              <td>[{{ $route->destination_lat }}, {{ $route->destination_lng }}]</td>
              <td class="text-center">
                <div class="btn-group">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-cog"></i> 
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                    <a href="javascript:void(0)" data-target="#modal-edit-route" class="dropdown-item route-edit"  data-toggle="modal" data-id="{{ $route->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item route-remove" data-id="{{ $route->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modal-add-route" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Route</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-route" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please manage the turns of this route.</div>
            <div class="row">
              <div class="col-md-12">
                <table  class="table table-bordered">
                  <thead>
                    <th>Step</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Direction</th>
                    <td></td>
                  </thead>
                  <tbody>
                    <tr class="extra-row hidden">
                      <td class="no-padding"><span class="step">0</span></td>
                      <td class="no-padding">
                        <div class="form-group">
                          <input class="form-control latitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control longitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <select class="form-control direction">
                            <option></option>
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                          </select>
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <tr class="extra-row visible">
                      <td class="no-padding"><span class="step">1</span></td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control latitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control longitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <select class="form-control direction">
                            <option></option>
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                          </select>
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-edit-route" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Route</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-route" method="POST" action="">
          <input type="hidden" id="hdn-edit-route-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please manage the turns of this route.</div>
            <div class="row">
              <div class="col-md-12">
                <table  class="table table-bordered">
                  <thead>
                    <th>Step</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Direction</th>
                    <td></td>
                  </thead>
                  <tbody>
                    <tr class="extra-row hidden">
                      <td class="no-padding"><span class="step">0</span></td>
                      <td class="no-padding">
                        <div class="form-group">
                          <input class="form-control latitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control longitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <select class="form-control direction">
                            <option></option>
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                          </select>
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <tr class="extra-row visible">
                      <td class="no-padding"><span class="step">1</span></td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control latitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <input class="form-control longitude" type="number" step="0.0000001" />
                        </div>
                      </td>
                      <td  class="no-padding">
                        <div class="form-group">
                          <select class="form-control direction">
                            <option></option>
                            <option value="right">Right</option>
                            <option value="left">Left</option>
                          </select>
                        </div>
                      </td>
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection