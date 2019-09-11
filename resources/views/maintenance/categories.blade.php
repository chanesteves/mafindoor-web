@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="#categories" data-toggle="tab">Categories</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#sub_categories" data-toggle="tab">Sub-Categories</a>
    </li>
  </ul>

  <div class="tab-content">
    <div id="categories" class="tab-pane active">
      <div class="card">
        <div class="card-header">
          Categories

          <div class="card-header-actions">
            <button data-target="#modal-add-category" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
              <i class='fa fa-plus'></i> Add Category
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="datatable_tabletools_categories" class="table table-bordered">
            <thead>
              <th>Name</th>
              <th>Searches</th>
              <th></th>
            </thead>
            <tbody>
              @foreach($categories as $cat)
                <tr>
                  <td>
                    @if ($cat->icon && file_exists(public_path() . $cat->icon))
                      <img src="{{ $cat->icon }}" width="40" />
                      &nbsp;
                    @endif
                    {{ $cat->name }}
                  </td>
                  <td class="text-center">
                    {{ $cat->searches->count() }}
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-cog"></i> 
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                        <a href="javascript:void(0)" data-target="#modal-edit-category" class="dropdown-item category-edit"  data-toggle="modal" data-id="{{ $cat->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item category-remove" data-id="{{ $cat->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                        <a href="javascript:void(0)" data-target="#modal-upload-category-logo" class="dropdown-item category-upload-logo" data-toggle="modal" data-id="{{ $cat->id }}" data-placement="top" data-original-title="Upload Icon"><i class="fa fa-upload"></i> Upload Icon</a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div id="sub_categories" class="tab-pane">
      <form id="frm-main-filter" method="GET" action="/categories">
        <div class="row main-filter">    
          <div class="col-md-10 col-6">
            <select id="ddl-category-id" name="category_id" class="form-control input-lg">
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $category && $category->id == $cat->id ? 'selected' : '' }} >{{ $cat->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-6">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-search"></i>&nbsp;Search</button>
          </div>    
        </div>
      </form>
      <br/>

      <div class="card">
        <div class="card-header">
          Sub-Categories

          <div class="card-header-actions">
            <button data-target="#modal-add-sub-category" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
              <i class='fa fa-plus'></i> Add Sub-Category
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="datatable_tabletools_sub_categories" class="table table-bordered">
            <thead>
              <th>Name</th>
              <th>Searches</th>
              <th></th>
            </thead>
            <tbody>
              @foreach($sub_categories as $sub_category)
                <tr>
                  <td>
                    @if ($sub_category->icon && file_exists(public_path() . $sub_category->icon))
                      <img src="{{ $sub_category->icon }}" width="40" />
                      &nbsp;
                    @endif
                    {{ $sub_category->name }}
                  </td>
                  <td class="text-center">
                    {{ $sub_category->searches->count() }}
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-cog"></i> 
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                        <a href="javascript:void(0)" data-target="#modal-edit-sub-category" class="dropdown-item sub-category-edit"  data-toggle="modal" data-id="{{ $sub_category->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item sub-category-remove" data-id="{{ $sub_category->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                        <a href="javascript:void(0)" data-target="#modal-upload-sub-category-logo" class="dropdown-item sub-category-upload-logo" data-toggle="modal" data-id="{{ $sub_category->id }}" data-placement="top" data-original-title="Upload Icon"><i class="fa fa-upload"></i> Upload Icon</a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal-add-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Category</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-category" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-category-name" type="text" class="form-control" name="name" autocomplete="off" required />
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

  <div class="modal fade" id="modal-edit-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Category</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-category" method="POST" action="">
          <input type="hidden" id="hdn-edit-category-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-category-name" type="text" class="form-control" name="name" autocomplete="off" />
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

  <div class="modal fade" id="modal-add-sub-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Sub-Category</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-sub-category" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-sub-category-name" type="text" class="form-control" name="name" autocomplete="off" required />
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

  <div class="modal fade" id="modal-edit-sub-category" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Sub-Category</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-sub-category" method="POST" action="">
          <input type="hidden" id="hdn-edit-sub-category-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-sub-category-name" type="text" class="form-control" name="name" autocomplete="off" />
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

  <div class="modal fade" id="modal-upload-category-logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Category Logo</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-upload-category-logo" method="POST" action="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input name="_method" type="hidden" value="PATCH">
          <input id="hdn-upload-category-id" type="hidden">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div id="pnl-upload-container" class="photo-upload-container">
                  <br/>
                  <center>
                    <h3>Browse Photo...</h3>
                    <div class="row">
                      <div class="col-md-2"></div>
                        <div id="pnl-upload-category" class="croppie col-md-8">
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
            <button id="btn-upload-category-logo" class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal-upload-sub-category-logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Sub-Category Logo</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-upload-sub-category-logo" method="POST" action="">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input name="_method" type="hidden" value="PATCH">
          <input id="hdn-upload-sub-category-id" type="hidden">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div id="pnl-upload-container" class="photo-upload-container">
                  <br/>
                  <center>
                    <h3>Browse Photo...</h3>
                    <div class="row">
                      <div class="col-md-2"></div>
                        <div id="pnl-upload-sub-category" class="croppie col-md-8">
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
            <button id="btn-upload-sub-category-logo" class="btn btn-primary" type="submit">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection