@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link active" href="#roles" data-toggle="tab">Roles</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#menus" data-toggle="tab">Menus</a>
    </li>
  </ul>

  <div class="tab-content">
    <div id="roles" class="tab-pane active">
      <div class="card">
        <div class="card-header">
          Roles

          <div class="card-header-actions">
            <button data-target="#modal-add-role" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
              <i class='fa fa-plus'></i> Add Role
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="datatable_tabletools_roles" class="table table-bordered">
            <thead>
              <th>Name</th>
              <th></th>
            </thead>
            <tbody>
              @foreach($roles as $role)
                <tr>
                  <td>{{ $role->name }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-cog"></i> 
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                        <a href="javascript:void(0)" data-target="#modal-edit-role" class="dropdown-item role-edit"  data-toggle="modal" data-id="{{ $role->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item role-remove" data-id="{{ $role->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
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
    <div id="menus" class="tab-pane">

      <div class="card">
        <div class="card-header">
          Menus

          <div class="card-header-actions">
            <button data-target="#modal-add-menu" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
              <i class='fa fa-plus'></i> Add Menu
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="datatable_tabletools_menus" class="table table-bordered">
            <thead>
              <th>Name</th>
              <th>Parent</th>
              <th></th>
            </thead>
            <tbody>
              @foreach($menus as $menu)
                <tr>
                  <td>{{ $menu->long_name }}</td>
                  <td>{{ $menu->parent ? $menu->parent->long_name : '' }}</td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-cog"></i> 
                        <span class="caret"></span>
                      </button>
                      <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                        <a href="javascript:void(0)" data-target="#modal-edit-menu" class="dropdown-item menu-edit"  data-toggle="modal" data-id="{{ $menu->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="javascript:void(0)" class="dropdown-item menu-remove" data-id="{{ $menu->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
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

  <div class="modal fade" id="modal-add-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Role</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-role" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-add-role-name" type="text" class="form-control" name="name" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Code</label>
                  <input id="txt-add-role-code" type="text" class="form-control" name="code" autocomplete="off" required />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Pages
                  </div>
                  <div class="card-body">
                    <div class="menu-error alert alert-danger"><i class="fa fa-times-circle"></i> Please select at least one page for this group.</div>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please select the page(s) you want to be accessible to this group.</div>
                    <div class="tree">
                      @if( $menus->count() > 0 )
                        <ul>
                          <li>
                            <span  style="width: 100%">
                              <label class="checkbox inline-block" >
                                <input class="select-all" type="checkbox" name="checkbox-inline" data-level="1" data-parent="0">
                                <i></i>Select All
                              </label>
                            </span>
                          </li>
                      @foreach( $menus->sortby('sequence') as $menu )
                        @if( $menu->parent_id == null )
                          <li class="parent_li">
                            <span  style="width: 100%; margin-bottom: 4px;">
                              @if( $menu->children()->count() > 0 )
                              <i class="fa fa-plus-circle"></i> {{ $menu->long_name }}
                              @else
                                <input type="hidden" name="menus[]" value="0">
                                  <label class="checkbox inline-block">
                                    <input id="chk-add-menu-{{ $menu->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $menu->id }}"  data-level="1" data-parent="0">
                                    <i></i>{{ $menu->long_name }}
                                  </label>
                              @endif
                            </span>
                              @if( $menu->children()->count() > 0 )
                                <ul>
                                  <li style="display: none;">
                                    <span  style="width: 100%">
                                      <label class="checkbox inline-block">
                                        <input class="select-all" type="checkbox" name="checkbox-inline" data-level="2" data-parent="{{ $menu->id }}">
                                        <i></i>Select All
                                      </label>
                                    </span> 
                                  </li>
                                @foreach( $menu->children as $child )
                                  <li style="display: none;">
                                    <span  style="width: 100%">
                                      <input type="hidden" name="menus[]" value="0">
                                      <label class="checkbox inline-block" name="checkbox">
                                        <input id="chk-add-menu-{{ $child->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $child->id }}"  data-level="2" data-parent="{{ $menu->id }}" >
                                        <i></i>{{ $child->long_name }}
                                      </label>
                                    </span>
                                  </li>
                                  @endforeach
                                </ul>
                              @endif
                            </li>
                          @endif
                      @endforeach
                      </ul>
                      @endif
                    </div>
                  </div>
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

  <div class="modal fade" id="modal-edit-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Role</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-role" method="POST" action="">
          <input type="hidden" id="hdn-edit-role-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Name</label>
                  <input id="txt-edit-role-name" type="text" class="form-control" name="name" autocomplete="off" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Code</label>
                  <input id="txt-edit-role-code" type="text" class="form-control" name="code" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Pages
                  </div>
                  <div class="card-body">
                    <div class="menu-error alert alert-danger"><i class="fa fa-times-circle"></i> Please select at least one page for this group.</div>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please select the page(s) you want to be accessible to this group.</div>
                    <div class="tree">
                      @if( $menus->count() > 0 )
                        <ul>
                          <li>
                            <span  style="width: 100%">
                              <label class="checkbox inline-block" >
                                <input class="select-all" type="checkbox" name="checkbox-inline" data-level="1" data-parent="0">
                                <i></i>Select All
                              </label>
                            </span>
                          </li>
                      @foreach( $menus->sortby('sequence') as $menu )
                        @if( $menu->parent_id == null )
                          <li class="parent_li">
                            <span  style="width: 100%; margin-bottom: 4px;">
                              @if( $menu->children()->count() > 0 )
                              <i class="fa fa-plus-circle"></i> {{ $menu->long_name }}
                              @else
                                <input type="hidden" name="menus[]" value="0">
                                  <label class="checkbox inline-block">
                                    <input id="chk-edit-menu-{{ $menu->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $menu->id }}"  data-level="1" data-parent="0">
                                    <i></i>{{ $menu->long_name }}
                                  </label>
                              @endif
                            </span>
                              @if( $menu->children()->count() > 0 )
                                <ul>
                                  <li style="display: none;">
                                    <span  style="width: 100%">
                                      <label class="checkbox inline-block">
                                        <input class="select-all" type="checkbox" name="checkbox-inline" data-level="2" data-parent="{{ $menu->id }}">
                                        <i></i>Select All
                                      </label>
                                    </span> 
                                  </li>
                                @foreach( $menu->children as $child )
                                  <li style="display: none;">
                                    <span  style="width: 100%">
                                      <input type="hidden" name="menus[]" value="0">
                                      <label class="checkbox inline-block" name="checkbox">
                                        <input id="chk-edit-menu-{{ $child->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $child->id }}"  data-level="2" data-parent="{{ $menu->id }}" >
                                        <i></i>{{ $child->long_name }}
                                      </label>
                                    </span>
                                  </li>
                                  @endforeach
                                </ul>
                              @endif
                            </li>
                          @endif
                      @endforeach
                      </ul>
                      @endif
                    </div>
                  </div>
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

  <div class="modal fade" id="modal-add-menu" tabindex="-1" menu="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" menu="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Menu</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-menu" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Short Name</label>
                  <input id="txt-add-menu-short-name" type="text" class="form-control" name="short_name" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Long Name</label>
                  <input id="txt-add-menu-long-name" type="text" class="form-control" name="long_name" autocomplete="off" required />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label> Link</label>
                  <input id="txt-add-menu-link" type="text" class="form-control" name="link" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label> Parent</label>
                  <select id="ddl-add-menu-parent" class="form-control" name="parent">
                    <option value="" selected>Parent</option>
                    @foreach($menus as $menu)
                      @if (!$menu->parent_id)
                        <option value="{{ $menu->id }}">{{ $menu->long_name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Sequence</label>
                  <input id="txt-add-menu-sequence" type="number" class="form-control" name="sequence" autocomplete="off" required />
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

  <div class="modal fade" id="modal-edit-menu" tabindex="-1" menu="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" menu="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Menu</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-menu" method="POST" action="">
          <input type="hidden" id="hdn-edit-menu-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Short Name</label>
                  <input id="txt-edit-menu-short-name" type="text" class="form-control" name="short_name" autocomplete="off" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Long Name</label>
                  <input id="txt-edit-menu-long-name" type="text" class="form-control" name="long_name" autocomplete="off" required />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label> Link</label>
                  <input id="txt-edit-menu-link" type="text" class="form-control" name="link" autocomplete="off" />
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label> Parent</label>
                  <select id="ddl-edit-menu-parent" class="form-control" name="parent">
                    <option value="" selected>Parent</option>
                    @foreach($menus as $menu)
                      @if (!$menu->parent_id)
                        <option value="{{ $menu->id }}">{{ $menu->long_name }}</option>
                      @endif
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>* Sequence</label>
                  <input id="txt-edit-menu-sequence" type="number" class="form-control" name="sequence" autocomplete="off" required />
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
@endsection