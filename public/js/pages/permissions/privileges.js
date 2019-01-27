var privileges = new Privileges();

function Privileges(){
    
}

Privileges.prototype.bindPrivileges = function () {
	tab.init();

	if ($('#datatable_tabletools_roles').length > 0) {
		$('#datatable_tabletools_roles').DataTable({ "paging":   false });
	}

    if ($('#datatable_tabletools_menus').length > 0) {
        $('#datatable_tabletools_menus').DataTable({ "paging":   false });
    }

    $('.menu-error').hide();

	var $add_role_validator = $("#frm-add-role").validate({
		rules: {
			'name': { required: true },
            'code': { required: true }          
		},
		messages: {
			'name': "Please enter the name.",
            'code': "Please enter the code."
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

    $('#modal-add-role .tree input[type=checkbox]').on('click', function () {
        if ($(this).prop('checked') == true)
            $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
        else
            $(this).parent().parent().find('input[type=hidden]').val(0);

    });

	$('#frm-add-role').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-role").valid();

    	if (!$valid) {
		    $add_role_validator.focusInvalid();
		    return false;
		}

        var menus = [];
        var check = [];
       
        $("#modal-add-role input:checkbox").each(function () {
            if ($(this).parent().parent().find('input[type=hidden]').length > 0 && $(this).parent().parent().find('input[type=hidden]').val() != '' && $(this).parent().parent().find('input[type=hidden]').val() != 0) {
                if ($(this).data('id')) {
                    $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
                    menus.push($(this).data('id'));
                }
            }
        });

       if(menus.length === 0){
            $(".menu-error").show();
            $('#modal-add-role').animate({ scrollTop: 0 }, 'slow');
            return false;
       }else{
            $(".menu-error").hide();
       }

		var name = $('#txt-add-role-name').val();
        var code = $('#txt-add-role-code').val();

		var privilege_obj = {
			'name' : name,
            'code' : code,
            'menus' : menus
		};

		$('#modal-add-role').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the role.', null);
		
		role.store(privilege_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Role successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_role_validator = $("#frm-edit-role").validate({
		rules: {
			'name': { required: true },
            'code': { required: true }
		},
		messages: {
			'name': "Please enter the name.",
            'code': "Please enter the code."
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

	$('.role-edit').unbind('click').on('click', function () {
        collapseAll();
		var id = $(this).data('id');

        $('#modal-edit-role').find('.alert-danger').hide();
        $(".menu-error").hide();

        $('#hdn-edit-role-id').val(id);
        $edit_role_validator.resetForm();
        $('#modal-edit-role .form-group').removeClass('has-error').removeClass('has-success');

		role.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-role-id').val(data.role.id);
				$('#txt-edit-role-name').val(data.role.name);
                $('#txt-edit-role-code').val(data.role.code);

                $('#modal-edit-role .tree input[type=checkbox]').prop('checked', false);

                if (data.role.menus && data.role.menus.length > 0) {
                    data.role.menus.forEach(function (menu) {
                        $('#chk-edit-menu-' + menu.id).prop('checked', true);
                        $('#chk-edit-menu-' + menu.id).parent().parent().find('input[type=hidden]').val($('#chk-edit-menu-' + menu.id).data('id'));

                        $('.tree input[type=checkbox]').on('click', function () {
                            if ($(this).prop('checked') == true)
                                $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
                            else
                                $(this).parent().parent().find('input[type=hidden]').val(0);
                        });
                 
                        var all = true;
                                
                        $('#chk-edit-menu-' + menu.id).parent().parent().parent().parent().find('li input[type=checkbox]').each(function () {
                            if ($(this).prop('checked') == false && !$(this).hasClass('select-all'))
                                all = false;
                        });

                        if (all) {
                            var level = $('#chk-edit-menu-' + menu.id).data('level');
                            var parent = $('#chk-edit-menu-' + menu.id).data('parent');

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
                            var level = $('#chk-edit-menu-' + menu.id).data('level');
                            var parent = $('#chk-edit-menu-' + menu.id).data('parent');

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

    $('#modal-edit-role .tree input[type=checkbox]').on('click', function () {
        if ($(this).prop('checked') == true)
            $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
        else
            $(this).parent().parent().find('input[type=hidden]').val(0);

    });

	$('#frm-edit-role').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-role").valid();

    	if (!$valid) {
		    $edit_role_validator.focusInvalid();
		    return false;
		}

        var menus = [];
        var check = [];
       
       $("#modal-edit-role input:checkbox").each(function () {
            if ($(this).parent().parent().find('input[type=hidden]').length > 0 && $(this).parent().parent().find('input[type=hidden]').val() != '' && $(this).parent().parent().find('input[type=hidden]').val() != 0) {
                if ($(this).data('id')) {
                    $(this).parent().parent().find('input[type=hidden]').val($(this).data('id'));
                    menus.push($(this).data('id'));
                }
            }
        });

       if(menus.length === 0){
            $(".menu-error").show();
            $('#modal-edit-role').animate({ scrollTop: 0 }, 'slow');
            return false;
       }else{
            $(".menu-error").hide();
       }

		var id = $('#hdn-edit-role-id').val();
		var name = $('#txt-edit-role-name').val();
        var code = $('#txt-edit-role-code').val();

		var privilege_obj = {
			'id' : id,
			'name' : name,
            'code' : code,
            'menus' : menus
		};

		$('#modal-edit-role').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the role.', null);
		
		role.update(privilege_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Role successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.role-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This role will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the role.', null);

			role.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Role successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

    var $add_menu_validator = $("#frm-add-menu").validate({
    rules: {
        'short_name': { required: true },
        'long_name': { required: true },
        'sequence': { required: true }
    },
    messages: {
        'short_name': "Please enter the short name.",
        'long_name': "Please enter the long name.",
        'sequence': "Please enter the sequence."
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

  $('#frm-add-menu').unbind('submit').on('submit', function (e) {
    e.preventDefault();

    var $valid = $("#frm-add-menu").valid();

      if (!$valid) {
        $add_menu_validator.focusInvalid();
        return false;
    }

    var short_name = $('#txt-add-menu-short-name').val();
    var long_name = $('#txt-add-menu-long-name').val();
    var link = $('#txt-add-menu-link').val();
    var parent = $('#ddl-add-menu-parent').val();
    var sequence = $('#txt-add-menu-sequence').val();

    var menu_obj = {
        'short_name' : short_name,
        'long_name' : long_name,
        'link' : link,
        'parent' : parent,
        'sequence' : sequence
    };

    $('#modal-add-menu').modal('hide');
    modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the menu.', null);
    
    menu.store(menu_obj, function (data) {
      if (data.status == 'OK')
        modal.set('success', 'fa-check-circle', 'Success', 'Menu successfully added.', { ok : function () {
          window.location.reload();
        }});
      else
        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
    });
  });

    $('.menu-edit').unbind('click').on('click', function () {
        var id = $(this).data('id');

        menu.show(id, function (data) {
            if (data.status == 'OK') {
                $('#hdn-edit-menu-id').val(data.menu.id);
                $('#txt-edit-menu-short-name').val(data.menu.short_name);
                $('#txt-edit-menu-long-name').val(data.menu.long_name);
                $('#txt-edit-menu-link').val(data.menu.link);
                $('#ddl-edit-menu-parent').val(data.menu.parent_id);
                $('#txt-edit-menu-sequence').val(data.menu.sequence);
            }
        });
    });

    var $edit_menu_validator = $("#frm-edit-menu").validate({
        rules: {
            'short_name': { required: true },
            'long_name': { required: true },
            'sequence': { required: true }
        },
        messages: {
            'short_name': "Please enter the short name.",
            'long_name': "Please enter the long name.",
            'sequence': "Please enter the sequence."
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

    $('#frm-edit-menu').unbind('submit').on('submit', function (e) {
        e.preventDefault();

        var $valid = $("#frm-edit-menu").valid();

        if (!$valid) {
            $edit_menu_validator.focusInvalid();
            return false;
        }

        var id = $('#hdn-edit-menu-id').val();
        var short_name = $('#txt-edit-menu-short-name').val();
        var long_name = $('#txt-edit-menu-long-name').val();
        var link = $('#txt-edit-menu-link').val();
        var parent = $('#ddl-edit-menu-parent').val();
        var sequence = $('#txt-edit-menu-sequence').val();

        var privilege_obj = {
            'id' : id,
            'short_name' : short_name,
            'long_name' : long_name,
            'link' : link,
            'parent' : parent,
            'sequence' : sequence
        };

        $('#modal-edit-menu').modal('hide');
        modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the menu.', null);
        
        menu.update(privilege_obj, function (data) {
            if (data.status == 'OK')
                modal.set('success', 'fa-check-circle', 'Success', 'Menu successfully updated.', { ok : function () {
                    window.location.reload();
                }});
            else
                modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
        });
    });

    $('.menu-remove').unbind('click').on('click', function () {
        var id = $(this).data('id');

        modal.show('warning', 'fa-question', 'Are You Sure?', 'This menu will no longer be available.', { 'no' : function () {
          modal.hide();
        }, 'yes' : function () {
          modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the menu.', null);

          menu.destroy(id, function (data) {
            if (data.status == 'OK')
              modal.set('success', 'fa-check-circle', 'Success', 'Menu successfully deleted.', { ok : function () {
                window.location.reload();
              }});
            else
              modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
          });
        }});
    });

    $('.tree').nestable();
    function collapseAll () {
        $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').each(function() {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-plus-circle');
        });
    }
    $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
    $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').unbind('click').on('click', function(e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(':visible')) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-plus-circle');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-minus-circle');
        }
        e.stopPropagation();
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

Privileges.prototype.reloadPageContent = function (data, message, callback) {
    privileges.bindPrivileges();
}

$(document).ready(function(){
	privileges.bindPrivileges();
});