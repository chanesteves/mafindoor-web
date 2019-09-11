@extends("layouts.layout")

@section('content')
  @include('partials.notices_content')
  
  <div class="card">
    <div class="card-header">
      Users

      <div class="card-header-actions">
        <button data-target="#modal-add-user" data-toggle="modal" class="card-header-action btn btn-success btn-sm">
          <i class='fa fa-plus'></i> Add User
        </button>
      </div>
    </div>
    <div class="card-body">
      <table id="datatable_tabletools_users" class="table table-bordered">
        <thead>
          <th>Name</th>
          <th>Member Since</th>
          <th>Last Login</th>
          <th>Activities</th>
          <th class="text-center">Status</th>
          <th></th>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>
                @if ($user->person->image && $user->person->image != '')
                  <img src="{{ $user->person->image }}" class="img-round pull-left user-photo" width="50" height="50" />
                @else
                  <img src="/img/avatars/initials/{{ strtoupper($user->person->first_name[0]) }}.png" class="img-round pull-left user-photo" width="50" height="50" />
                @endif
                &nbsp;
                {{ $user->person->first_name }} {{ $user->person->last_name }}
                <br/>
                @foreach($user->roles as $role)
                  <span class="badge badge-primary">{{ $role->name }}</span>
                @endforeach
              </td>
              <td>
                {{ Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
              </td>
              <td>
                {{ Carbon\Carbon::parse($user->last_logged_at)->diffForHumans() }}
              </td>
              <td class="text-center">
                <a href="javascript:void(0)" data-target="#modal-show-activities-user" class="user-show-activities btn btn-primary"  data-toggle="modal" data-id="{{ $user->id }}">{{ $user->activities->count() }}</a>
              </td>
              <td class="text-center">
                @if ($user->email_verification_sent_at && !$user->email_verified_at )
                  <span class="badge badge-warning">Pending Email Verification</span>
                @else
                  <span class="badge badge-success">Active</span>
                @endif
              </td>
              <td class="text-center">
                <div class="btn-group">
                  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-cog"></i> 
                    <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -188px, 0px);">
                    <a href="javascript:void(0)" data-target="#modal-edit-user" class="dropdown-item user-edit"  data-toggle="modal" data-id="{{ $user->id }}" data-placement="top" data-original-title="Edit"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="javascript:void(0)" class="dropdown-item user-remove" data-id="{{ $user->id }}" data-placement="top" data-original-title="Remove"><i class="fa fa-trash-o"></i> Remove</a>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="modal fade" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add User</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-add-user" method="POST" action="">
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-6">
                  <div class="form-group">
                      <input id="txt-add-user-first-name" class="form-control" name="first_name" type="text" placeholder="First Name" required>
                  </div>
              </div>
              <div class="col-6">
                  <div class="form-group">
                      <input id="txt-add-user-last-name" class="form-control" name="last_name" type="text" placeholder="Last Name"  required>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <div class="form-group">
                      <select id="ddl-add-user-gender" class="form-control" name="gender" required>
                          <option selected disabled>Gender</option>
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                      </select>
                  </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">@</span>
              </div>
              <input id="txt-add-user-email" class="form-control" type="text" name="email" placeholder="Email"  required>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-user"></i>
                </span>
              </div>
              <input id="txt-add-user-username" class="form-control" type="text" name="username" placeholder="Username"  required>
            </div>          
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <input id="txt-add-user-password" class="form-control" type="password" name="password" placeholder="Password"  required>
            </div>
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <input id="txt-add-user-confirm-password" class="form-control" type="password" name="confirm_password" placeholder="Repeat password"  required>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Roles
                  </div>
                  <div class="card-body">
                    <div class="role-error alert alert-danger"><i class="fa fa-times-circle"></i> Please select at least one role for this user.</div>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please select the role(s) you want to be applicable to this user.</div>
                    <div class="tree">
                      @if( $roles->count() > 0 )
                        <ul>
                          <li>
                            <span  style="width: 100%">
                              <label class="checkbox inline-block" >
                                <input class="select-all" type="checkbox" name="checkbox-inline" data-level="1" data-parent="0">
                                <i></i>Select All
                              </label>
                            </span>
                          </li>
                          @foreach( $roles as $role )
                            <li>
                              <span  style="width: 100%; margin-bottom: 4px;">
                                <input type="hidden" name="roles[]" value="0">
                                <label class="checkbox inline-block">
                                  <input id="chk-add-role-{{ $role->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $role->id }}"   data-level="1" data-parent="0">
                                  <i></i>{{ $role->name }}
                                </label>
                              </span>
                            </li>
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

  <div class="modal fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit User</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form id="frm-edit-user" method="POST" action="">
          <input type="hidden" id="hdn-edit-user-id" value="" />
          <div class="modal-body">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Fields with asterisks (*) are required</div>
            <div class="row">
              <div class="col-6">
                  <div class="form-group">
                      <input id="txt-edit-user-first-name" class="form-control" name="first_name" type="text" placeholder="First Name" required>
                  </div>
              </div>
              <div class="col-6">
                  <div class="form-group">
                      <input id="txt-edit-user-last-name" class="form-control" name="last_name" type="text" placeholder="Last Name"  required>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <div class="form-group">
                      <select id="ddl-edit-user-gender" class="form-control" name="gender" required>
                          <option selected disabled>Gender</option>
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                      </select>
                  </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">@</span>
              </div>
              <input id="txt-edit-user-email" class="form-control" type="text" name="email" placeholder="Email"  required>
            </div>
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-user"></i>
                </span>
              </div>
              <input id="txt-edit-user-username" class="form-control" type="text" name="username" placeholder="Username"  required>
            </div>          
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <input id="txt-edit-user-password" class="form-control" type="password" name="password" placeholder="Password"  required>
            </div>
            <div class="input-group mb-4">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <input id="txt-edit-user-confirm-password" class="form-control" type="password" name="confirm_password" placeholder="Repeat password"  required>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Roles
                  </div>
                  <div class="card-body">
                    <div class="role-error alert alert-danger"><i class="fa fa-times-circle"></i> Please select at least one role for this user.</div>
                    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Please select the role(s) you want to be applicable to this user.</div>
                    <div class="tree">
                      @if( $roles->count() > 0 )
                        <ul>
                          <li>
                            <span  style="width: 100%">
                              <label class="checkbox inline-block" >
                                <input class="select-all" type="checkbox" name="checkbox-inline" data-level="1" data-parent="0">
                                <i></i>Select All
                              </label>
                            </span>
                          </li>
                          @foreach( $roles as $role )
                            <li>
                              <span  style="width: 100%; margin-bottom: 4px;">
                                <input type="hidden" name="roles[]" value="0">
                                <label class="checkbox inline-block">
                                  <input id="chk-edit-role-{{ $role->id }}" type="checkbox" name="checkbox-inline" data-id="{{ $role->id }}"   data-level="1" data-parent="0">
                                  <i></i>{{ $role->name }}
                                </label>
                              </span>
                            </li>
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

  <div class="modal fade" id="modal-show-activities-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-primary modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">User Activities</h4>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div id="pnl-activities">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection