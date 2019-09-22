@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <form id="frm-main-filter" method="GET" action="/floors">
    <div class="row main-filter">    
      <div class="col-md-10 col-6">
        <select id="ddl-floor-id" name="building_id" class="form-control input-lg">
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
  <div class="row">
    @foreach($floors as $floor)
      <div class="col-sm-6 col-md-4">
        <div class="card">
          <div class="card-body">
            <div id="pnl-map-{{ $floor->id }}" class="floor-map" data-url="{{ $floor->map_url }}" data-longitude="{{ $floor->longitude }}" data-latitude="{{ $floor->latitude }}" data-zoom="{{ $floor->zoom }}"></div>
            <div class="map-cover">
              @if ($floor->status == 'pending')
                <span class="badge badge-warning">PENDING</span>
              @elseif ($floor->status == 'in progress')
                <span class="badge badge-primary">IN PROGRESS</span>
              @elseif ($floor->status == 'live')
                <span class="badge badge-success">LIVE</span>
              @endif
              <h2>{{ $floor->name }} ({{ $floor->label }})</h2>
            </div>
            <div class="actions">
              <div class="row">
                <div class="col-4 text-center">
                  <a href="javascript:void(0);" class="floor-edit" data-id="{{ $floor->id }}" data-target="#modal-edit-floor" data-toggle="modal"><i class="fa fa-pencil"></i> Edit</a>
                </div>
                <div class="col-4 text-center">
                  <a href="javascript:void(0);" class="floor-remove" data-id="{{ $floor->id }}"><i class="fa fa-trash"></i> Delete</a>
                </div>
                <div class="col-4 text-center">
                  <a href="javascript:void(0);" class="floor-manage-points" data-id="{{ $floor->id }}" data-target="#modal-manage-points-floor" data-toggle="modal"><i class="fa fa-circle"></i> Points</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <div class="col-sm-6 col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="blank">
            <br/><br/><br/><br/>
            <center>
              <a href="javascript:void(0);" data-target="#modal-add-floor" data-toggle="modal"><i class="fa fa-plus fa-2x"></i></a>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="pull-right">
        <button data-target="#modal-import-floors" data-toggle="modal" class="card-header-action btn btn-primary btn-lg">
          <i class='fa fa-upload'></i> Import Floors
        </button>
        <a href="/floors/exportFloors?building_id={{ $building->id }}" class="btn btn-danger btn-lg">
          <i class='fa fa-download'></i> Export Floors
        </a>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-add-floor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Floor</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-floor" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-9 col-sm-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-floor-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label>* Label</label>
                  <input id="txt-add-floor-label" type="text" class="form-control" name="label" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Map URL</label>
                  <input id="txt-add-floor-map-url" type="text" class="form-control" name="map_url" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Longitude</label>
                <input id="txt-add-floor-longitude" type="number" class="form-control" name="longitude" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Latitude</label>
                <input id="txt-add-floor-latitude" type="number" class="form-control" name="latitude" step="0.000001" autocomplete="off" />
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <label>* Initial Zoom</label>
                <input id="txt-add-floor-zoom" type="number" class="form-control" name="zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-4">
                <label>* Min Zoom</label>
                <input id="txt-add-floor-min-zoom" type="number" class="form-control" name="min_zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-4">
                <label>* Max Zoom</label>
                <input id="txt-add-floor-max-zoom" type="number" class="form-control" name="max_zoom" step="0.000001" autocomplete="off" />
              </div>              
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Status</label>
                  <select id="ddl-add-floor-status" class="form-control" name="status">
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="live">Live</option>
                  </select>
                </div>
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

  <div class="modal fade" id="modal-edit-floor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Floor</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-floor" method="POST" action="">
          <input type="hidden" id="hdn-edit-floor-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-9 col-sm-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-floor-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="form-group">
                  <label>* Label</label>
                  <input id="txt-edit-floor-label" type="text" class="form-control" name="label" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Map URL</label>
                  <input id="txt-edit-floor-map-url" type="text" class="form-control" name="map_url" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Longitude</label>
                <input id="txt-edit-floor-longitude" type="number" class="form-control" name="longitude" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Latitude</label>
                <input id="txt-edit-floor-latitude" type="number" class="form-control" name="latitude" step="0.000001" autocomplete="off" />
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <label>* Initial Zoom</label>
                <input id="txt-edit-floor-zoom" type="number" class="form-control" name="zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-4">
                <label>* Min Zoom</label>
                <input id="txt-edit-floor-min-zoom" type="number" class="form-control" name="min_zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-4">
                <label>* Max Zoom</label>
                <input id="txt-edit-floor-max-zoom" type="number" class="form-control" name="max_zoom" step="0.000001" autocomplete="off" />
              </div>              
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Status</label>
                  <select id="ddl-edit-floor-status" class="form-control" name="status">
                    <option value="pending">Pending</option>
                    <option value="in progress">In Progress</option>
                    <option value="live">Live</option>
                  </select>
                </div>
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

  <div class="modal fade" id="modal-import-floors" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Import Floors</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="frm-import-floors">
            <div class="row">
              <div class="col-sm-12">
                <center>
                  <a href="javascript:void(0);" id="lnk-upload-import-floors-csv" class="btn btn-primary btn-lg"><i class="fa fa-folder-o"></i> Upload <i>CSV</i> File</a>
                  <input id="file-upload-import-floors-csv" name="file-upload" accept=".csvx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="file-import" type="file">
                  <br/>
                  <div class="upload-status">
                      <div id="bar" class="progress progress-striped active" role="progressbar">
                        <div class="progress-bar progress-bar-success" style="width: 0%;"> </div>
                      </div>
                      <center>
                        <span>Uploading...</span>
                      </center>
                    </div>
                </center>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
          <button id="btn-upload-floor-logo" class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-manage-points-floor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Manage Points</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-manage-points-floor" method="POST" action="">
          <input type="hidden" id="hdn-manage-points-floor-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please click on the map to add points for this floor.</div>
            <div class="row">
              <div class="col-md-12">
                <div id="pnl-map" class="map"><div class="marker">+</div></div>
              </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-md-12">
                <table  class="table table-bordered">
                  <thead>
                    <th>ID</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <td></td>
                  </thead>
                  <tbody>
                    <tr class="extra-row hidden">
                      <td class="no-padding"><span class="label">0</span></td>
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
                      <td  class="no-padding text-center">
                        <a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <tr class="extra-row visible">
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
            <button id="btn-manage-points-floor" class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection