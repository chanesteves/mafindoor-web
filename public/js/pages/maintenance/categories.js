var categories = new Categories();

function Categories(){
    
}

Categories.prototype.bindCategories = function () {
	tab.init();

	if ($('#datatable_tabletools_categories').length > 0) {
		$('#datatable_tabletools_categories').DataTable({ "paging":   false });
	}

	var $add_category_validator = $("#frm-add-category").validate({
		rules: {
			'name': { required: true }			
		},
		messages: {
			'name': "Please enter the name."
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

	$('#frm-add-category').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-category").valid();

    	if (!$valid) {
		    $add_category_validator.focusInvalid();
		    return false;
		}

		var name = $('#txt-add-category-name').val();

		var category_obj = {
			'name' : name
		};

		$('#modal-add-category').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the category.', null);
		
		category.store(category_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Category successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_category_validator = $("#frm-edit-category").validate({
		rules: {
			'name': { required: true },
			'status': { required: true }			
		},
		messages: {
			'name': "Please enter the name.",
			'status': "Please select a status."
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

	$('.category-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		category.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-category-id').val(data.category.id);
				$('#txt-edit-category-name').val(data.category.name);
			}
		});
	});

	$('#frm-edit-category').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-category").valid();

    	if (!$valid) {
		    $edit_category_validator.focusInvalid();
		    return false;
		}

		var id = $('#hdn-edit-category-id').val();
		var name = $('#txt-edit-category-name').val();

		var category_obj = {
			'id' : id,
			'name' : name
		};

		$('#modal-edit-category').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the category.', null);
		
		category.update(category_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Category successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.category-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This category will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the category.', null);

			category.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Category successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	var $add_sub_category_validator = $("#frm-add-sub-category").validate({
    rules: {
      'name': { required: true }
    },
    messages: {
      'name': "Please enter the name."
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
          error.insertAfter(element.parent('icon'));
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

  $('#frm-add-sub-category').unbind('submit').on('submit', function (e) {
    e.preventDefault();

    var $valid = $("#frm-add-sub-category").valid();

      if (!$valid) {
        $add_sub_category_validator.focusInvalid();
        return false;
    }

    var name = $('#txt-add-sub-category-name').val();
    var category_id = $('#ddl-category-id').val();

    var sub_category_obj = {
      'name' : name,
      'category_id' : category_id
    };

    $('#modal-add-sub-category').modal('hide');
    modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the sub-category.', null);
    
    sub_category.store(sub_category_obj, function (data) {
      if (data.status == 'OK')
        modal.set('success', 'fa-check-circle', 'Success', 'Sub-Category successfully added.', { ok : function () {
          window.location.reload();
        }});
      else
        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
    });
  });

  var $edit_sub_category_validator = $("#frm-edit-sub-category").validate({
    rules: {
      'name': { required: true }     
    },
    messages: {
      'name': "Please enter the name."
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
          error.insertAfter(element.parent('icon'));
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

  $('.sub-category-edit').unbind('click').on('click', function () {
    var id = $(this).data('id');

    sub_category.show(id, function (data) {
      if (data.status == 'OK') {
        $('#hdn-edit-sub-category-id').val(data.sub_category.id);
        $('#txt-edit-sub-category-name').val(data.sub_category.name);
      }
    });
  });

  $('#frm-edit-sub-category').unbind('submit').on('submit', function (e) {
    e.preventDefault();

    var $valid = $("#frm-edit-sub-category").valid();

      if (!$valid) {
        $edit_sub_category_validator.focusInvalid();
        return false;
    }

    var id = $('#hdn-edit-sub-category-id').val();
    var name = $('#txt-edit-sub-category-name').val();
    var category_id = $('#ddl-category-id').val();

    var sub_category_obj = {
      'id' : id,
      'name' : name,
      'category_id' : category_id
    };

    $('#modal-edit-sub-category').modal('hide');
    modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the sub-category.', null);
    
    sub_category.update(sub_category_obj, function (data) {
      if (data.status == 'OK')
        modal.set('success', 'fa-check-circle', 'Success', 'Sub-Category successfully updated.', { ok : function () {
          window.location.reload();
        }});
      else
        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
    });
  });

  $('.sub-category-remove').unbind('click').on('click', function () {
    var id = $(this).data('id');

    modal.show('warning', 'fa-question', 'Are You Sure?', 'This sub-category will no longer be available.', { 'no' : function () {
      modal.hide();
    }, 'yes' : function () {
      modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the sub-category.', null);

      sub_category.destroy(id, function (data) {
        if (data.status == 'OK')
          modal.set('success', 'fa-check-circle', 'Success', 'Sub-Category successfully deleted.', { ok : function () {
            window.location.reload();
          }});
        else
          modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
      });
    }});
  });

  $('.category-upload-logo').on('click',function(){
        var id = $(this).data('id');

        $('#hdn-upload-category-id').val(id);
        
        $('.file-photo').hide();
        $('.croppie').hide();
        $('.upload-status').hide();

        $('.croppie-remove').click();
    })

	$('#frm-upload-category-logo .photo-upload-container').on('click', function () {
        $('#frm-upload-category-logo input.file-photo').click();
    });

    var uploadCrop;
    var uploadProfileStatus = 'NO_FILE';

    $('#frm-upload-category-logo input.file-photo').on('change', function (e) {
        $('.croppie').show();
        $('#frm-upload-category-logo .photo-upload-container h3').hide();
        $('#frm-upload-category-logo .photo-upload-container').off('click');

        var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
                uploadCrop = new Croppie(document.getElementById('pnl-upload-category'), {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    },
                    showZoomer: true,
                    enableOrientation: true,
                    url: e.target.result,
                    orientation: 4
                });

                setTimeout(function(){
                    uploadCrop.setZoom(1);
                },1000);

                $('.croppie-rotate').on('click', function(ev) {
                    uploadCrop.rotate(parseInt($(this).data('deg')));
                });

                $('.croppie-remove').on('click', function(ev) {
                    uploadCrop.destroy();

                    $('.croppie').hide();
                    $('#frm-upload-category-logo .photo-upload-container h3').show();
                    $('#frm-upload-category-logo .photo-upload-container').on('click', function () {
                        $('#frm-upload-category-logo input.file-photo').click();
                    });
                    $('#frm-upload-category-logo input.file-photo').val('');
                    $('.upload-status').hide();
                });

                uploadProfileStatus = 'FILE_CHOOSEN';
                $('#tab3 .alert-danger').hide();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    });

	$('#frm-upload-category-logo').unbind('submit').on('submit', function (event) {
        event.preventDefault();
        $('#btn-upload-category-logo').trigger('click');
    });

    $('#btn-upload-category-logo').unbind('click').on('click', function (event) {
        uploadCrop.result('canvas', 'viewport').then(function (src) {
           var id = $('#hdn-upload-category-id').val();

           var CSRF = $("meta[name='csrf-token']").attr('content');

            $('.upload-status').show();

            var file_data = src;
            var form_data = new FormData();
            var blob = dataURLtoBlob(file_data);
            
            form_data.append("image", blob);

            var request = new XMLHttpRequest();

            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    try {
                        var data = JSON.parse(request.responseText);

                        if (data.status == 'OK') {
                            $('.upload-status .progress').show();
                            $('.upload-status .progress-bar').css('width', '100%');
                            $('.upload-status span').html('Uploaded successfully! 100%');
                        
                            uploadProfileStatus = 'FILE_UPLOADED';

                            window.location.reload(true);
                        }
                        else {
                            uploadProfileStatus = 'FILE_UPLOAD_ERROR';

                            $('.upload-status .progress').hide();
                            
                            if (data && data.error)
                                $('.upload-status span').html(data.error);
                            else
                                $('.upload-status span').html(request.responseText);
                        }
                    } catch(e) {
                        uploadProfileStatus = 'FILE_UPLOAD_ERROR';

                        $('.upload-status .progress').hide();

                        $('.upload-status span').html(request.responseText);
                    }
                }
            };

            request.upload.onloadstart = function() {
                $('.upload-status span').html('Uploaded started...');
            };

            request.upload.onprogress = function(event) {
                $('.upload-status .progress').show();
                $('.upload-status .progress-bar').css('width', Math.round(event.loaded / event.total * 100) + '%');
                $('.upload-status span').html('Upload Progress ' + Math.round(event.loaded / event.total * 100) + '%');
            };

            request.upload.onerror = function(error) {
                $('.upload-status span').html('Failed to upload to server: ' + error);
            };

            request.upload.onabort = function(error) {
                $('.upload-status span').html('XMLHttpRequest aborted: ' + error);
            };

            request.open('POST', '/categories/' + id + '/ajaxUploadLogo');
            request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data); 
        });
    });

	$('.sub-category-upload-logo').on('click',function(){
        var id = $(this).data('id');

        $('#hdn-upload-sub-category-id').val(id);
        
        $('.file-photo').hide();
        $('.croppie').hide();
        $('.upload-status').hide();

        $('.croppie-remove').click();
    })

  	$('#frm-upload-sub-category-logo .photo-upload-container').on('click', function () {
        $('#frm-upload-sub-category-logo input.file-photo').click();
    });

    var uploadCrop;
    var uploadProfileStatus = 'NO_FILE';

    $('#frm-upload-sub-category-logo input.file-photo').on('change', function (e) {
        $('.croppie').show();
        $('#frm-upload-sub-category-logo .photo-upload-container h3').hide();
        $('#frm-upload-sub-category-logo .photo-upload-container').off('click');

        var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
                uploadCrop = new Croppie(document.getElementById('pnl-upload-sub-category'), {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    },
                    showZoomer: true,
                    enableOrientation: true,
                    url: e.target.result,
                    orientation: 4
                });

                setTimeout(function(){
                    uploadCrop.setZoom(1);
                },1000);

                $('.croppie-rotate').on('click', function(ev) {
                    uploadCrop.rotate(parseInt($(this).data('deg')));
                });

                $('.croppie-remove').on('click', function(ev) {
                    uploadCrop.destroy();

                    $('.croppie').hide();
                    $('#frm-upload-sub-category-logo .photo-upload-container h3').show();
                    $('#frm-upload-sub-category-logo .photo-upload-container').on('click', function () {
                        $('#frm-upload-sub-category-logo input.file-photo').click();
                    });
                    $('#frm-upload-sub-category-logo input.file-photo').val('');
                    $('.upload-status').hide();
                });

                uploadProfileStatus = 'FILE_CHOOSEN';
                $('#tab3 .alert-danger').hide();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    });

  $('#frm-upload-sub-category-logo').unbind('submit').on('submit', function (event) {
        event.preventDefault();
        $('#btn-upload-sub-category-logo').trigger('click');
    });

    $('#btn-upload-sub-category-logo').unbind('click').on('click', function (event) {
        uploadCrop.result('canvas', 'viewport').then(function (src) {
           var id = $('#hdn-upload-sub-category-id').val();

           var CSRF = $("meta[name='csrf-token']").attr('content');

            $('.upload-status').show();

            var file_data = src;
            var form_data = new FormData();
            var blob = dataURLtoBlob(file_data);
            
            form_data.append("image", blob);

            var request = new XMLHttpRequest();

            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    try {
                        var data = JSON.parse(request.responseText);

                        if (data.status == 'OK') {
                            $('.upload-status .progress').show();
                            $('.upload-status .progress-bar').css('width', '100%');
                            $('.upload-status span').html('Uploaded successfully! 100%');
                        
                            uploadProfileStatus = 'FILE_UPLOADED';

                            window.location.reload(true);
                        }
                        else {
                            uploadProfileStatus = 'FILE_UPLOAD_ERROR';

                            $('.upload-status .progress').hide();
                            
                            if (data && data.error)
                                $('.upload-status span').html(data.error);
                            else
                                $('.upload-status span').html(request.responseText);
                        }
                    } catch(e) {
                        uploadProfileStatus = 'FILE_UPLOAD_ERROR';

                        $('.upload-status .progress').hide();

                        $('.upload-status span').html(request.responseText);
                    }
                }
            };

            request.upload.onloadstart = function() {
                $('.upload-status span').html('Uploaded started...');
            };

            request.upload.onprogress = function(event) {
                $('.upload-status .progress').show();
                $('.upload-status .progress-bar').css('width', Math.round(event.loaded / event.total * 100) + '%');
                $('.upload-status span').html('Upload Progress ' + Math.round(event.loaded / event.total * 100) + '%');
            };

            request.upload.onerror = function(error) {
                $('.upload-status span').html('Failed to upload to server: ' + error);
            };

            request.upload.onabort = function(error) {
                $('.upload-status span').html('XMLHttpRequest aborted: ' + error);
            };

            request.open('POST', '/subCategories/' + id + '/ajaxUploadLogo');
            request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data); 
        });
    });
}

Categories.prototype.reloadPageContent = function (data, message, callback) {
    categories.bindCategories();
}

$(document).ready(function(){
	categories.bindCategories();
});