var users = new Users();

function Users(){
    
}

Users.prototype.bindUsers = function () {
	if ($('#datatable_tabletools_users').length > 0) {
		$('#datatable_tabletools_users').DataTable({"paging":   false});
	}

	$('#txt-add-user-email').unbind('change').on('change', function () {
		$('#txt-add-user-username').val($(this).val());
	});

	$('.role-error').hide();

	var $add_user_validator = $("#frm-add-user").validate({
		rules: {
			'first_name': { required: true },
			'last_name': { required: true },
			'gender': { required: true },
			'email': { required: true },
			'username': { required: true },
			'password': { required: true },
			'confirm_password': { required: true, equalTo: "#txt-add-user-password" }
		},
		messages: {
			'first_name': "Please enter the first name.",
			'last_name': "Please enter the last name.",
			'gender': "Please enter the gender.",
			'email': "Please enter the email.",
			'username': "Please enter the username.",
			'password': "Please enter the password.",
			'confirm_password': { required : "Please confirm the password.", equalTo : "The passwords do not match." }
		},
		highlight: function (element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		errorElement: 'em',
		errorPlacement: function errorPlacement(error, element) {
		    error.addClass('invalid-feedback');

		    if (element.prop('type') === 'checkbox') {
		      error.insertAfter(element.parent('label'));
		    } else {
		      error.insertAfter(element);
		    }
		},
		highlight: function highlight(element) {
		    $(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function unhighlight(element) {
		    $(element).addClass('is-valid').removeClass('is-invalid');
		}
	});

	$('#frm-add-user').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-user").valid();

    	if (!$valid) {
		    $add_user_validator.focusInvalid();
		    return false;
		}

		var roles = [];
        var check = [];
       
       	$("#modal-add-user input:checkbox").each(function () {
            if ($(this).data('id') && $(this).prop('checked'))
                roles.push($(this).data('id'));
        });

       	if(roles.length === 0){
            $(".role-error").show();
            $('#modal-add-user').animate({ scrollTop: 0 }, 'slow');
            return false;
       	}else{
            $(".role-error").hide();
       	}

		var first_name = $('#txt-add-user-first-name').val();
		var last_name = $('#txt-add-user-last-name').val();
		var email = $('#txt-add-user-email').val();
		var gender = $('#ddl-add-user-gender').val();

		var username = $('#txt-add-user-username').val();
		var password = $('#txt-add-user-password').val();

		var user_obj = {
			'first_name' : first_name,
			'last_name' : last_name,
			'email' : email,
			'gender' : gender,
			'username' : username,
			'password' : password,
			'roles' : roles
		}

		$('#modal-add-user').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the user.', null);
		
		user.store(user_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'User successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_user_validator = $("#frm-edit-user").validate({
		rules: {
			'first_name': { required: true },
			'last_name': { required: true },
			'gender': { required: true },
			'email': { required: true },
			'username': { required: true },
			'password': { required: true },
			'confirm_password': { required: true, equalTo: "#txt-edit-user-password" }
		},
		messages: {
			'first_name': "Please enter the first name.",
			'last_name': "Please enter the last name.",
			'gender': "Please enter the gender.",
			'email': "Please enter the email.",
			'username': "Please enter the username.",
			'password': "Please enter the password.",
			'confirm_password': { required : "Please confirm the password.", equalTo : "The passwords do not match." }
		},
		highlight: function (element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		errorElement: 'em',
		errorPlacement: function errorPlacement(error, element) {
		    error.addClass('invalid-feedback');

		    if (element.prop('type') === 'checkbox') {
		      error.insertAfter(element.parent('label'));
		    } else {
		      error.insertAfter(element);
		    }
		},
		highlight: function highlight(element) {
		    $(element).addClass('is-invalid').removeClass('is-valid');
		},
		unhighlight: function unhighlight(element) {
		    $(element).addClass('is-valid').removeClass('is-invalid');
		}
	});

	$('.user-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		$('#modal-edit-user').find('.alert-danger').hide();
        $(".role-error").hide();

        $('#hdn-edit-user-id').val(id);
        $edit_user_validator.resetForm();
        $('#modal-edit-user .form-group').removeClass('has-error').removeClass('has-success');

		user.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-user-id').val(data.user.id);
				$('#txt-edit-user-first-name').val(data.user.person.first_name);
				$('#txt-edit-user-last-name').val(data.user.person.last_name);
				$('#ddl-edit-user-gender').val(data.user.person.gender);
				$('#txt-edit-user-email').val(data.user.person.email);
				$('#txt-edit-user-username').val(data.user.username);
				$('#txt-edit-user-password').val('');
				$('#txt-edit-user-confirm-password').val('');

				$('#modal-edit-user .tree input[type=checkbox]').prop('checked', false);

                if (data.user.roles && data.user.roles.length > 0) {
                    data.user.roles.forEach(function (role) {
                        $('#chk-edit-role-' + role.id).prop('checked', true);
                        $('#chk-edit-role-' + role.id).parent().parent().find('input[type=hidden]').val($('#chk-edit-role-' + role.id).data('id'));

                        $('.tree input[type=checkbox]').on('click', function () {
                            if ($(this).prop('checked') == true)
                                $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
                            else
                                $(this).parent().parent().find('input[type=hidden]').val(0);
                        });
                 
                        var all = true;
                                
                        $('#chk-edit-role-' + role.id).parent().parent().parent().parent().find('li input[type=checkbox]').each(function () {
                            if ($(this).prop('checked') == false && !$(this).hasClass('select-all'))
                                all = false;
                        });

                        if (all) {
                            var level = $('#chk-edit-role-' + role.id).data('level');
                            var parent = $('#chk-edit-role-' + role.id).data('parent');

                            $('.tree .select-all').each(function () {
                                if ($(this).data('level') < level || $(this).data('parent') == parent) {
                                    var all = true;

                                    $(this).parent().parent().parent().parent().find('li input[type=checkbox]').each(function () {
                                        if ($(this).prop('checked') == false && !$(this).hasClass('select-all'))
                                            all = false;
                                    });

                                    if (all)
                                        $(this).prop('checked', true);
                                    else
                                        $(this).prop('checked', false);
                                }
                            });
                        }
                        else {
                            var level = $('#chk-edit-role-' + role.id).data('level');
                            var parent = $('#chk-edit-role-' + role.id).data('parent');

                            $('.tree .select-all').each(function () {
                                if ($(this).data('level') < level || $(this).data('parent') == parent)
                                    $(this).prop('checked', false);
                            });
                        }
                    }); 
                }
			}
		});
	});

	$('#modal-edit-user .tree input[type=checkbox]').on('click', function () {
        if ($(this).prop('checked') == true)
            $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
        else
            $(this).parent().parent().find('input[type=hidden]').val(0);
    });

	$('#frm-edit-user').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-user").valid();

    	if (!$valid) {
		    $edit_user_validator.focusInvalid();
		    return false;
		}

        var roles = [];
        var check = [];
       
       	$("#modal-edit-user input:checkbox").each(function () {
            if ($(this).data('id') && $(this).prop('checked'))
                roles.push($(this).data('id'));
        });

       	if(roles.length === 0){
            $(".role-error").show();
            $('#modal-edit-user').animate({ scrollTop: 0 }, 'slow');
            return false;
       	}else{
            $(".role-error").hide();
       	}

		var id = $('#hdn-edit-user-id').val();
		var first_name = $('#txt-edit-user-first-name').val();
		var last_name = $('#txt-edit-user-last-name').val();
		var email = $('#txt-edit-user-email').val();
		var gender = $('#ddl-edit-user-gender').val();

		var username = $('#txt-edit-user-username').val();
		var password = $('#txt-edit-user-password').val();

		var user_obj = {
			'id' : id,
			'first_name' : first_name,
			'last_name' : last_name,
			'email' : email,
			'gender' : gender,
			'username' : username,
			'password' : password,
			'roles' : roles
		};

		$('#modal-edit-user').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the user.', null);
		
		user.update(user_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Annotation successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.user-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This user will no longer be able to access Mafindoor.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the user.', null);

			user.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'User successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	$('.tree .select-all').on('click', function () {

        if ($(this).prop('checked') == true) {
            $(this).parent().parent().parent().parent().find('input[type=checkbox]').prop('checked', true);

            $(this).parent().parent().parent().parent().find('input[type=checkbox]').each(function () {
                if ($(this).data('id'))
                    $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
            });
        }
        else {
            $(this).parent().parent().parent().parent().find('input[type=checkbox]').prop('checked', false);
            $(this).parent().parent().parent().parent().find('input[type=hidden]').val(0);
        }
    });

    $('.tree input[type=checkbox]').on('click', function () {
        if ($(this).prop('checked') == true)
            $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
        else
            $(this).parent().parent().find('input[type=hidden]').val(0);

        var all = true;

        $(this).parent().parent().parent().parent().find('li input[type=checkbox]').each(function () {
            if ($(this).prop('checked') == false && !$(this).hasClass('select-all'))
                all = false;
        });

        if (all) {
            var level = $(this).data('level');
            var parent = $(this).data('parent');

            $('.tree .select-all').each(function () {
                if ($(this).data('level') < level || $(this).data('parent') == parent) {
                    var all = true;

                    $(this).parent().parent().parent().parent().find('li input[type=checkbox]').each(function () {
                        if ($(this).prop('checked') == false && !$(this).hasClass('select-all'))
                            all = false;
                    });

                    if (all)
                        $(this).prop('checked', true);
                    else
                        $(this).prop('checked', false);
                }
            });
        }
        else {
            var level = $(this).data('level');
            var parent = $(this).data('parent');

            $('.tree .select-all').each(function () {
                if ($(this).data('level') < level || $(this).data('parent') == parent)
                    $(this).prop('checked', false);
            });
        }
            
    });
}

Users.prototype.reloadPageContent = function (data, message, callback) {
    users.bindUsers();
}

$(document).ready(function(){
	users.bindUsers();
});