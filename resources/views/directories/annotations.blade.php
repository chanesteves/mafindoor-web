@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  <form id="frm-main-filter" method="GET" action="/annotations">
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
      Annotations
      <div class="card-header-actions">
        <button id="btn-add-annotation" data-target="#modal-add-annotation" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
          <i class='fa fa-plus'></i> Add Annotation
        </button>
        <button data-target="#modal-import-annotations" data-toggle="modal" class="card-header-action btn btn-primary btn-sm">
          <i class='fa fa-upload'></i> Import Annotations
        </button>
        <a href="/annotations/exportAnnotations?floor_id={{ $floor->id }}" class="card-header-action btn btn-danger btn-sm">
          <i class='fa fa-download'></i> Export Annotations
        </a>
      </div>
    </div>
    <div class="card-body">
      <table id="datatable_tabletools_annotations" class="table table-bordered">
        <thead>
          <th>Name</th>
          <th>Coordinate</th>
          <th>Category</th>
          <th></th>
        </thead>
        <tbody>
          @foreach($annotations as $annotation)
            <tr>
              <td>
                <img src="{{ $annotation->logo }}" width="40" />
                &nbsp;
                {{ $annotation->name }}
              </td>
              <td>[{{ $annotation->longitude }}, {{ $annotation->latitude }}]</td>
              <td>{{ $annotation->sub_category ? $annotation->sub_category->name : '' }}</td>
              <td class="text-center">
                <div class="btn-group">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-cog"></i> 
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                    <a href="javascript:void(0)" data-target="#modal-edit-annotation" class="dropdown-item annotation-edit"  data-toggle="modal" data-id="{{ $annotation->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item annotation-remove" data-id="{{ $annotation->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                    <a href="javascript:void(0)" data-target="#modal-upload-annotation-logo" class="dropdown-item annotation-upload-logo" data-toggle="modal" data-id="{{ $annotation->id }}" data-placement="top" data-original-title="Upload Logo"><i class="fa fa-upload"></i> Upload Logo</a>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modal-add-annotation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Annotation</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-annotation" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-annotation-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Map Name</label>
                  <input id="txt-add-annotation-map-name" type="text" class="form-control" name="map_name" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Category</label>
                  <select id="ddl-add-annotation-sub-category-id" class="form-control" name="sub_category">
                    <option selected disabled></option>
                    @foreach($categories as $category)
                      @if ($category->sub_categories->count())
                        <optgroup label="{{ $category->name }}">
                          @foreach($category->sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                          @endforeach
                        </optgroup>                       
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Longitude</label>
                <input id="txt-add-annotation-longitude" type="number" class="form-control" name="longitude" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Latitude</label>
                <input id="txt-add-annotation-latitude" type="number" class="form-control" name="latitude" step="0.000001" autocomplete="off" />
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Min Zoom</label>
                <input id="txt-add-annotation-min-zoom" type="number" class="form-control" name="min_zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Max Zoom</label>
                <input id="txt-add-annotation-max-zoom" type="number" class="form-control" name="max_zoom" step="0.000001" autocomplete="off" />
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

  <div class="modal fade" id="modal-edit-annotation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Annotation</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-annotation" method="POST" action="">
          <input type="hidden" id="hdn-edit-annotation-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-annotation-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Map Name</label>
                  <input id="txt-edit-annotation-map-name" type="text" class="form-control" name="map_name" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Category</label>
                  <select id="ddl-edit-annotation-sub-category-id" class="form-control" name="sub_category">
                    <option selected disabled></option>
                    @foreach($categories as $category)
                      @if ($category->sub_categories->count())
                        <optgroup label="{{ $category->name }}">
                          @foreach($category->sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}">{{ $sub_category->name }}</option>
                          @endforeach
                        </optgroup>                       
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Longitude</label>
                <input id="txt-edit-annotation-longitude" type="number" class="form-control" name="longitude" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Latitude</label>
                <input id="txt-edit-annotation-latitude" type="number" class="form-control" name="latitude" step="0.000001" autocomplete="off" />
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label>* Min Zoom</label>
                <input id="txt-edit-annotation-min-zoom" type="number" class="form-control" name="min_zoom" step="0.000001" autocomplete="off" />
              </div>
              <div class="col-6">
                <label>* Max Zoom</label>
                <input id="txt-edit-annotation-max-zoom" type="number" class="form-control" name="max_zoom" step="0.000001" autocomplete="off" />
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

  <div class="modal fade" id="modal-upload-annotation-logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Annotation Logo</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-upload-annotation-logo" method="POST" action="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input name="_method" type="hidden" value="PATCH">
          <input id="hdn-upload-annotation-id" type="hidden">
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
            <button id="btn-upload-annotation-logo" class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-import-annotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Import Annotations</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="frm-import-annotations">
            <div class="row">
              <div class="col-sm-12">
                <center>
                  <a href="javascript:void(0);" id="lnk-upload-import-annotations-csv" class="btn btn-primary btn-lg"><i class="fa fa-folder-o"></i> Upload <i>CSV</i> File</a>
                  <input id="file-upload-import-annotations-csv" name="file-upload" accept=".csvx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="file-import" type="file">
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
          <button id="btn-upload-annotation-logo" class="btn btn-primary" type="submit">Save</button>
        </div>
      </div>
    </div>
  </div>
@endsection