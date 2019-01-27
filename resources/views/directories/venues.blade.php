@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <div class="card">
    <div class="card-header">
      Venues

      <div class="card-header-actions">
        <button data-target="#modal-add-venue" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
          <i class='fa fa-plus'></i> Add Venue
        </button>
        <button data-target="#modal-import-venues" data-toggle="modal" class="card-header-action btn btn-primary btn-sm">
          <i class='fa fa-upload'></i> Import Venues
        </button>
        <a href="/buildings/exportBuildings" class="card-header-action btn btn-danger btn-sm">
          <i class='fa fa-download'></i> Export Venues
        </a>
      </div>
    </div>
    <div class="card-body">
      <table id="datatable_tabletools_venues" class="table table-bordered">
        <thead>
          <th>Name</th>
          <th>Creator</th>
          <th class="text-center">Status</th>
          <th></th>
        </thead>
        <tbody>
          @foreach($buildings as $building)
            <tr>
              <td>
                <img src="{{ $building->logo }}" width="40" />
                &nbsp;
                {{ $building->name }}
              </td>
              <td>
                @if ($building->creator && $building->creator->person)
                  {{ $building->creator->person->first_name }} {{ $building->creator->person->last_name }}
                @endif
              </td>
              <td class="text-center">
                @if ($building->status == 'pending')
                  <span class="badge badge-warning">PENDING</span>
                @elseif ($building->status == 'in progress')
                  <span class="badge badge-primary">IN PROGRESS</span>
                @elseif ($building->status == 'live')
                  <span class="badge badge-success">LIVE</span>
                @endif
              </td>
              <td class="text-center">
                <div class="btn-group">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-cog"></i> 
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                    <a href="javascript:void(0)" data-target="#modal-edit-venue" class="dropdown-item venue-edit"  data-toggle="modal" data-id="{{ $building->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item venue-remove" data-id="{{ $building->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                    <a href="javascript:void(0)" data-target="#modal-upload-building-logo" class="dropdown-item building-upload-logo" data-toggle="modal" data-id="{{ $building->id }}" data-placement="top" data-original-title="Upload Logo"><i class="fa fa-upload"></i> Upload Logo</a>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modal-add-venue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Venue</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-venue" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-venue-name" type="text" class="form-control" name="name" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Address</label>
                  <input id="txt-add-venue-address" type="text" class="form-control" name="address" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Status</label>
                  <select id="ddl-add-venue-status" class="form-control" name="status" required>
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

  <div class="modal fade" id="modal-edit-venue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Venue</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-venue" method="POST" action="">
          <input type="hidden" id="hdn-edit-venue-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-venue-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Address</label>
                  <input id="txt-edit-venue-address" type="text" class="form-control" name="address" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Status</label>
                  <select id="ddl-edit-venue-status" class="form-control" name="status">
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

  <div class="modal fade" id="modal-upload-building-logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Annotation Logo</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-upload-building-logo" method="POST" action="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input name="_method" type="hidden" value="PATCH">
          <input id="hdn-upload-building-id" type="hidden">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div id="pnl-upload-container" class="photo-upload-container">
                  <br/>
                  <center>
                    <h3>Browse Photo...</h3>
                    <div class="row">
                      <div class="col-md-2"></div>
                        <div id="pnl-upload" class="croppie col-md-8">
                      </div>
                      <div class="col-md-2"></div>
                    </div>
                  </center>
                </div>
                <div class="buttons croppie">
                  <input id="file-photo-upload" name="file-photo-upload" accept="image/*" class="file-photo" type="file">
                  <center>
                    <button  type="button" class="btn btn-primary croppie-remove"><i class="fa fa-trash"></i> Remove</button>
                    <button  type="button" class="btn btn-primary croppie-rotate" data-deg="-90"><i class="fa fa-undo"></i> Rotate Left</button>
                    <button  type="button" class="btn btn-primary croppie-rotate" data-deg="90"><i class="fa fa-repeat"></i> Rotate Right</button>
                  </center>
                </div>
                <br/>
                <div class="upload-status">
                  <div id="bar" class="progress progress-striped active" role="progressbar">
                    <div class="progress-bar progress-bar-success" style="width: 0%;"> </div>
                  </div>
                  <center>
                    <span>Uploading...</span>
                  </center>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            <button id="btn-upload-building-logo" class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-import-venues" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Import Venues</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="frm-import-venues">
            <div class="row">
              <div class="col-sm-12">
                <center>
                  <a href="javascript:void(0);" id="lnk-upload-import-buildings-csv" class="btn btn-primary btn-lg"><i class="fa fa-folder-o"></i> Upload <i>CSV</i> File</a>
                  <input id="file-upload-import-buildings-csv" name="file-upload" accept=".csvx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="file-import" type="file">
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
          <button id="btn-upload-building-logo" class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection