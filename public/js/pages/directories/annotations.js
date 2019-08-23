var annotations = new Annotations();

function Annotations(){
    
}

Annotations.prototype.bindAnnotations = function () {
	if ($('#datatable_tabletools_annotations').length > 0) {
		$('#datatable_tabletools_annotations').DataTable({"paging":   false});
	}

	$('#ddl-building-id').unbind('change').on('change', function () {
		var id = $(this).val();

		$('#ddl-floor-id option').hide();
		$('#ddl-floor-id option[data-building-id=' + id + ']').show();

		var floor_building_id = $('#ddl-floor-id option:selected').data('building-id');

		$('#btn-add-annotation').removeAttr('disabled');

		if (floor_building_id != id) {
			if ($('#ddl-floor-id option[data-building-id=' + id + ']').length == 0) {
				$('#ddl-floor-id').val('');

				$('#btn-add-annotation').attr('disabled', 'disabled');
			}
			else {
				var id = $('#ddl-floor-id option[data-building-id=' + id + ']').eq(0).attr('value');
				$('#ddl-floor-id').val(id);	
			}
		}
	});

	$('#ddl-building-id').trigger('change');

	var $annotation_validator = $("#frm-main-filter").validate({
		rules: {
			'building_id': { required: true },
			'floor_id': { required: true },
		},
		messages: {
			'building_id': "Please select the building.",
			'floor_id': "Please select the floor."
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

	$('#frm-main-filter').unbind('submit').on('submit', function () {
		var $valid = $("#frm-main-filter").valid();

    	if (!$valid) {
		    $annotation_validator.focusInvalid();
		    return false;
		}

		$('#frm-main-filter').submit();
	});

	var $add_annotation_validator = $("#frm-add-annotation").validate({
		rules: {
			'name': { required: true },
			'sub_category': { required: true },
			'longitude': { required: true },
			'latitude': { required: true }
		},
		messages: {
			'name': "Please enter the name.",
			'sub_category': "Please select the category.",
			'longitude': "Please enter the longitude.",
			'latitude': "Please enter the latitude."
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

	$('#frm-add-annotation').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-annotation").valid();

    	if (!$valid) {
		    $add_annotation_validator.focusInvalid();
		    return false;
		}

		var name = $('#txt-add-annotation-name').val();
		var map_name = $('#txt-add-annotation-map-name').val();
		var longitude = $('#txt-add-annotation-longitude').val();
		var latitude = $('#txt-add-annotation-latitude').val();
		var min_zoom = $('#txt-add-annotation-min-zoom').val();
		var max_zoom = $('#txt-add-annotation-max-zoom').val();
		var sub_category_id = $('#ddl-add-annotation-sub-category-id').val();
		var floor_id = $('#ddl-floor-id').val();

		var annotation_obj = {
			'name' : name,
			'map_name' : map_name,
			'longitude' : longitude,
			'latitude' : latitude,
			'min_zoom' : min_zoom,
			'max_zoom' : max_zoom,
			'sub_category_id' : sub_category_id,
			'floor_id' : floor_id
		};

		$('#modal-add-annotation').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the annotation.', null);
		
		annotation.store(annotation_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Annotation successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_annotation_validator = $("#frm-edit-annotation").validate({
		rules: {
			'name': { required: true },
			'sub_category': { required: true },
			'longitude': { required: true },
			'latitude': { required: true }
		},
		messages: {
			'name': "Please enter the name.",
			'sub_category': "Please select the category.",
			'longitude': "Please enter the longitude.",
			'latitude': "Please enter the latitude."
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

	$('.annotation-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		annotation.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-annotation-id').val(data.annotation.id);
				$('#txt-edit-annotation-name').val(data.annotation.name);
				$('#txt-edit-annotation-map-name').val(data.annotation.map_name);
				$('#ddl-edit-annotation-sub-category-id').val(data.annotation.sub_category_id);
				$('#txt-edit-annotation-longitude').val(data.annotation.longitude);
				$('#txt-edit-annotation-latitude').val(data.annotation.latitude);
				$('#txt-edit-annotation-min-zoom').val(data.annotation.min_zoom);
				$('#txt-edit-annotation-max-zoom').val(data.annotation.max_zoom);
			}
		});
	});

	$('#frm-edit-annotation').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-annotation").valid();

    	if (!$valid) {
		    $edit_annotation_validator.focusInvalid();
		    return false;
		}

		var id = $('#hdn-edit-annotation-id').val();
		var name = $('#txt-edit-annotation-name').val();
		var map_name = $('#txt-edit-annotation-map-name').val();
		var longitude = $('#txt-edit-annotation-longitude').val();
		var latitude = $('#txt-edit-annotation-latitude').val();
		var min_zoom = $('#txt-edit-annotation-min-zoom').val();
		var max_zoom = $('#txt-edit-annotation-max-zoom').val();
		var sub_category_id = $('#ddl-edit-annotation-sub-category-id').val();
		var floor_id = $('#ddl-floor-id').val();

		var floor_obj = {
			'id' : id,
			'name' : name,
			'map_name' : map_name,
			'longitude' : longitude,
			'latitude' : latitude,
			'min_zoom' : min_zoom,
			'max_zoom' : max_zoom,
			'sub_category_id' : sub_category_id,
			'floor_id' : floor_id
		};

		$('#modal-edit-annotation').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the annotation.', null);
		
		annotation.update(floor_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Annotation successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.annotation-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This annotation will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the annotation.', null);

			annotation.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Annotation successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	$('.annotation-upload-logo').on('click',function(){
        var id = $(this).data('id');

        $('#hdn-upload-annotation-logo-id').val(id);
        
        $('.file-photo').hide();
        $('.croppie').hide();
        $('.upload-status').hide();

        $('.croppie-remove').click();
    })

	$('#pnl-upload-logo-container').on('click', function () {
        $('#file-photo-upload-logo').click();
    });

    var uploadCrop;
    var uploadProfileStatus = 'NO_FILE';

    $('#file-photo-upload-logo').on('change', function (e) {
        $('.croppie').show();
        $('#pnl-upload-logo-container h3').hide();
        $('#pnl-upload-logo-container').off('click');

        var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
                uploadCrop = new Croppie(document.getElementById('pnl-upload-logo'), {
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
                    $('#pnl-upload-logo-container h3').show();
                    $('#pnl-upload-logo-container').on('click', function () {
                        $('#file-photo-upload-logo').click();
                    });
                    $('#file-photo-upload-logo').val('');
                    $('.upload-status').hide();
                });

                uploadProfileStatus = 'FILE_CHOOSEN';
                $('#tab3 .alert-danger').hide();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    });

	$('#frm-upload-annotation-logo').unbind('submit').on('submit', function (event) {
        event.preventDefault();
        $('#btn-upload-annotation-logo').trigger('click');
    });

    $('#btn-upload-annotation-logo').unbind('click').on('click', function (event) {
        uploadCrop.result('canvas', 'viewport').then(function (src) {
           var id = $('#hdn-upload-annotation-logo-id').val();

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

            request.open('POST', '/annotations/' + id + '/ajaxUploadLogo');
            request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data); 
        });
    });

	$('#lnk-upload-import-annotations-csv').unbind('click').on('click', function () {
		$('#modal-import-annotations .file-import').click();
	});
	$('#modal-import-annotations .file-import').val('');

	$('#modal-import-annotations .file-import').unbind('change').on('change', function (e) {
		$('#lnk-upload-import-annotations-csv').hide();
		
		var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
            	var filename = $('#modal-import-annotations .file-import').val().substring($('#modal-import-annotations .file-import').val().lastIndexOf('\\') + 1);
			
            	$('#frm-import-annotations').submit();
			}

            reader.readAsDataURL(input.files[0]);
        }
	});

	$('#frm-import-annotations').unbind('submit').on('submit', function (event) {
		event.preventDefault();

		var input = document.getElementById('modal-import-annotations').getElementsByClassName('file-import')[0];

		if (input.files && input.files[0]) {
			var floor_id = $('#ddl-floor-id').val();

			var CSRF = $("meta[name='csrf-token']").attr('content');

			var request = new XMLHttpRequest();

      		var form_data = new FormData();
      		form_data.append("file-upload", input.files[0]);
      		form_data.append("floor_id", floor_id);

	        request.onreadystatechange = function() {
	            if (request.readyState == 4) {
	            	if (request.status == 200) {
		            	try {
		                    var data = JSON.parse(request.responseText);

		                    if (data.status == 'OK') {
			                    $('#modal-import-annotations .upload-status .progress').show();
			                    $('#modal-import-annotations .upload-status .progress-bar').css('width', '100%');
			                    $('#modal-import-annotations .upload-status span').html('Uploaded succesfully! 100%');
			                
			                    uploadProfileStatus = 'FILE_UPLOADED';

			                    $('#modal-import-annotations').modal('hide');
			                    modal.show('success', 'fa-check-circle', 'Success', 'Annotations successfully imported.', { ok : function () {
									window.location.reload();
								}});
			                }		                
			                else {
			                	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			                	$('#lnk-upload-import-annotations-csv').show();
			                	$('#modal-import-annotations .file-import').val('');
			                	$('#modal-import-annotations .upload-status .progress').hide();
			                    
			                    if (data && data.error)
			                    	$('#modal-import-annotations .upload-status span').html(data.error);
			                    else
			                    	$('#modal-import-annotations .upload-status span').html(request.responseText);
			                }
			            } catch(e) {
			            	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			            	$('#lnk-upload-import-annotations-csv').show();
			            	$('#modal-import-annotations .file-import').val('');			            	
			                $('#modal-import-annotations .upload-status .progress').hide();

					        $('#modal-import-annotations .upload-status span').html(request.responseText);
					    }
					}
					else {
						console.log(request.responseText);
					}
	            }
	        };

	        request.upload.onloadstart = function() {
	        	$('#modal-import-annotations .upload-status').show();
	            $('#modal-import-annotations .upload-status span').html('Uploaded started...');
	        };

	        request.upload.onprogress = function(event) {
	            $('#modal-import-annotations .upload-status .progress').show();
	            $('#modal-import-annotations .upload-status .progress-bar').css('width', Math.round(event.loaded / event.total * 100) + '%');
	            $('#modal-import-annotations .upload-status span').html('Upload Progress ' + Math.round(event.loaded / event.total * 100) + '%');
	        };

	        request.upload.onerror = function(error) {
	        	$('#lnk-upload-import-annotations-csv').show();
	        	$('#modal-import-annotations .file-import').val('');
	            $('#modal-import-annotations .upload-status span').html('Failed to upload to server: ' + error);
	        };

	        request.upload.onabort = function(error) {
	        	$('#lnk-upload-import-annotations-csv').show();
	        	$('#modal-import-annotations .file-import').val('');
	            $('#modal-import-annotations .upload-status span').html('XMLHttpRequest aborted: ' + error);
	        };

	        request.open('POST', '/annotations/ajaxImportAnnotations');
	        request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data);
		}
	});

	$('.annotation-upload-images').unbind('click').on('click', function () {
		var id = $(this).data('id');

		$('#hdn-upload-annotation-images-id').val(id);
		$('#frm-gallery').attr('action', '/annotations/' + id + '/ajaxUploadImages');
	});
}

$(document).ready(function(){
	annotations.bindAnnotations();
});