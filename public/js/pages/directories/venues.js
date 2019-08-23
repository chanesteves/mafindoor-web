var venues = new Venues();

var uploadCrop;
var dropzone;
var images;
var sharable_photo;

var site_url = $('#hdn-site-url').val()

function Venues(){
    
}

Venues.prototype.bindVenues = function () {
	if ($('#datatable_tabletools_venues').length > 0) {
		$('#datatable_tabletools_venues').DataTable({ "paging":   false });
	}

	$("#txt-add-venue-address").geocomplete({
		country: 'PH',
		type: ["postal_code", "neighborhood"]
	});

	$("#txt-edit-venue-address").geocomplete({
		country: 'PH',
		type: ["postal_code", "neighborhood"]
	});

	var $add_venue_validator = $("#frm-add-venue").validate({
		rules: {
			'name': { required: true },
			'address': { required: true },
			'status': { required: true }			
		},
		messages: {
			'name': "Please enter the name.",
			'address': "Please enter the address.",
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

	$('#frm-add-venue').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-venue").valid();

    	if (!$valid) {
		    $add_venue_validator.focusInvalid();
		    return false;
		}

		var name = $('#txt-add-venue-name').val();
		var address = $('#txt-add-venue-address').val();
		var status = $('#ddl-add-venue-status').val();

		var venue_obj = {
			'name' : name,
			'address' : address,
			'status' : status
		};

		$('#modal-add-venue').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the building.', null);
		
		building.store(venue_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Building successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_venue_validator = $("#frm-edit-venue").validate({
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

	$('.venue-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		building.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-venue-id').val(data.building.id);
				$('#txt-edit-venue-address').val(data.building.address);
				$('#txt-edit-venue-name').val(data.building.name);
				$('#ddl-edit-venue-status').val(data.building.status);
			}
		});
	});

	$('#frm-edit-venue').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-venue").valid();

    	if (!$valid) {
		    $edit_venue_validator.focusInvalid();
		    return false;
		}

		var id = $('#hdn-edit-venue-id').val();
		var name = $('#txt-edit-venue-name').val();
		var address = $('#txt-edit-venue-address').val();
		var status = $('#ddl-edit-venue-status').val();

		var venue_obj = {
			'id' : id,
			'name' : name,
			'address' : address,
			'status' : status
		};

		$('#modal-edit-venue').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the venue.', null);
		
		building.update(venue_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Venue successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.venue-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This venue will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the venue.', null);

			building.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Venue successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	$('.building-upload-logo').on('click',function(){
        var id = $(this).data('id');

        $('#hdn-upload-building-logo-id').val(id);
        
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

	$('#frm-upload-building-logo').unbind('submit').on('submit', function (event) {
        event.preventDefault();
        $('#btn-upload-building-logo').trigger('click');
    });

    $('#btn-upload-building-logo').unbind('click').on('click', function (event) {
        uploadCrop.result('canvas', 'viewport').then(function (src) {
           var id = $('#hdn-upload-building-logo-id').val();

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

            request.open('POST', '/buildings/' + id + '/ajaxUploadLogo');
            request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data); 
        });
    });

	$('#lnk-upload-import-buildings-csv').unbind('click').on('click', function () {
		$('#modal-import-venues .file-import').click();
	});
	$('#modal-import-venues .file-import').val('');

	$('#modal-import-venues .file-import').unbind('change').on('change', function (e) {
		$('#lnk-upload-import-buildings-csv').hide();
		
		var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
            	var filename = $('#modal-import-venues .file-import').val().substring($('#modal-import-venues .file-import').val().lastIndexOf('\\') + 1);
			
            	$('#frm-import-venues').submit();
			}

            reader.readAsDataURL(input.files[0]);
        }
	});

	$('#frm-import-venues').unbind('submit').on('submit', function (event) {
		event.preventDefault();

		var input = document.getElementById('modal-import-venues').getElementsByClassName('file-import')[0];

		if (input.files && input.files[0]) {
			var CSRF = $("meta[name='csrf-token']").attr('content');

			var request = new XMLHttpRequest();

      		var form_data = new FormData();
      		form_data.append("file-upload", input.files[0]);
      		
	        request.onreadystatechange = function() {
	            if (request.readyState == 4) {
	            	if (request.status == 200) {
		            	try {
		                    var data = JSON.parse(request.responseText);

		                    if (data.status == 'OK') {
			                    $('#modal-import-venues .upload-status .progress').show();
			                    $('#modal-import-venues .upload-status .progress-bar').css('width', '100%');
			                    $('#modal-import-venues .upload-status span').html('Uploaded succesfully! 100%');
			                
			                    uploadProfileStatus = 'FILE_UPLOADED';

			                    $('#modal-import-venues').modal('hide');
			                    modal.show('success', 'fa-check-circle', 'Success', 'Buildings successfully imported.', { ok : function () {
									window.location.reload();
								}});
			                }		                
			                else {
			                	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			                	$('#lnk-upload-import-buildings-csv').show();
			                	$('#modal-import-venues .file-import').val('');
			                	$('#modal-import-venues .upload-status .progress').hide();
			                    
			                    if (data && data.error)
			                    	$('#modal-import-venues .upload-status span').html(data.error);
			                    else
			                    	$('#modal-import-venues .upload-status span').html(request.responseText);
			                }
			            } catch(e) {
			            	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			            	$('#lnk-upload-import-buildings-csv').show();
			            	$('#modal-import-venues .file-import').val('');			            	
			                $('#modal-import-venues .upload-status .progress').hide();

					        $('#modal-import-venues .upload-status span').html(request.responseText);
					    }
					}
					else {
						console.log(request.responseText);
					}
	            }
	        };

	        request.upload.onloadstart = function() {
	        	$('#modal-import-venues .upload-status').show();
	            $('#modal-import-venues .upload-status span').html('Uploaded started...');
	        };

	        request.upload.onprogress = function(event) {
	            $('#modal-import-venues .upload-status .progress').show();
	            $('#modal-import-venues .upload-status .progress-bar').css('width', Math.round(event.loaded / event.total * 100) + '%');
	            $('#modal-import-venues .upload-status span').html('Upload Progress ' + Math.round(event.loaded / event.total * 100) + '%');
	        };

	        request.upload.onerror = function(error) {
	        	$('#lnk-upload-import-buildings-csv').show();
	        	$('#modal-import-venues .file-import').val('');
	            $('#modal-import-venues .upload-status span').html('Failed to upload to server: ' + error);
	        };

	        request.upload.onabort = function(error) {
	        	$('#lnk-upload-import-buildings-csv').show();
	        	$('#modal-import-venues .file-import').val('');
	            $('#modal-import-venues .upload-status span').html('XMLHttpRequest aborted: ' + error);
	        };

	        request.open('POST', '/buildings/ajaxImportBuildings');
	        request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data);
		}
	});

	$('.building-upload-images').unbind('click').on('click', function () {
		var id = $(this).data('id');

		$('#hdn-upload-building-images-id').val(id);
		$('#frm-gallery').attr('action', '/buildings/' + id + '/ajaxUploadImages');

		images = [];

		building.show(id, function (data) {
			if (data.status == 'OK') {
				var photos = data.building.images;

				$('#frm-gallery .dz-image-preview').remove();

				dropzone = Dropzone.forElement("#frm-gallery");

                for (var index = 0; index < photos.length; index++) {
                    filename = photos[index].url.substring(photos[index].url.lastIndexOf('/') + 1);
                    filename = filename.substring(filename.lastIndexOf('\\') + 1);
                    file = { id : photos[index].id, name : filename, size : 12345, serverId : filename, xhr : { responseText : photos[index].url }};

                    dropzone.emit("addedfile", file);
                    dropzone.options.thumbnail.call(dropzone, file, site_url + photos[index].url);
                }

                $('.dz-progress').hide();
			}
		});
	});

	$('#btn-upload-building-images').unbind('click').on('click', function () {
		var id = $('#hdn-upload-building-images-id').val();

		var building_obj = {
			id : id,
			images : images
		}

		$('#modal-upload-building-images').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are saving the building images.', null);

		building.storeImages(building_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Venue images successfully saved.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.building-upload-sharable-photo').unbind('click').on('click', function () {
	    var id = $(this).data('id');

	    $('#hdn-upload-building-sharable-photo-id').val(id);
	    $('#frm-sharable-photo').attr('action', '/buildings/' + id + '/ajaxUploadImage');

	    $('#modal-upload-building-sharable-photo .alert-danger').hide();

	    building.show(id, function (data) {
	      if (data.status == 'OK') {
	        var photo = data.building.image;

	        $('#frm-gallery .dz-image-preview').remove();

	        dropzone = Dropzone.forElement("#frm-sharable-photo");

	        if (photo) {
		        filename = photo.substring(photo.lastIndexOf('/') + 1);
		        filename = filename.substring(filename.lastIndexOf('\\') + 1);
		        file = { name : filename, size : 12345, serverId : filename, xhr : { responseText : photo }};

		        dropzone.emit("addedfile", file);
		        dropzone.options.thumbnail.call(dropzone, file, site_url + photo);
		    }

	        $('.dz-progress').hide();
	      }
	    });
	 });

	$('#btn-upload-building-sharable-photo').unbind('click').on('click', function () {
    	var id = $('#hdn-upload-building-sharable-photo-id').val();

    	if (!sharable_photo || sharable_photo == '') {
    		$('#modal-upload-building-sharable-photo .alert-danger').html('<i class="fa fa-times-circle"></i>&nbsp;Please upload a sharable photo').show();
    		return;
    	}

	    var building_obj = {
	      id : id,
	      image : sharable_photo
	    }

	    $('#modal-upload-building-sharable-photo').modal('hide');
	    	modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are saving the building sharable photo.', null);

		    building.storeImage(building_obj, function (data) {
		      if (data.status == 'OK')
		        modal.set('success', 'fa-check-circle', 'Success', 'Venue sharable photo successfully saved.', { ok : function () {
		          window.location.reload();
		        }});
		      else
		        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
    	});
  	});
}

Venues.prototype.reloadPageContent = function (data, message, callback) {
    venues.bindVenues();
}

$(document).ready(function(){
	venues.bindVenues();
});

Dropzone.options.frmGallery = {
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    addRemoveLinks: true,
    dictDefaultMessage: "Click Here to Upload Additional Images",
    init: function () {
    	this.on("processing", function(file) {
	      this.options.url = $('#frm-gallery').attr('action');
	    });

	    var _this = this;

    	this.on("addedfile", function (file) {                     
            if (file.id) {
            	var idName = file.id;
            	var removeButton = Dropzone.createElement('<a class="btn btn-block btn-danger" id="' + idName +'-remove" data-id="' + idName + '" style="color: #FFF; border-radius: 0;" data-original-title="Remove"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove</a>');
                        
	            removeButton.addEventListener("click", function (e) {
	                e.preventDefault();
	                e.stopPropagation();

	                _this.removeFile(file);

		            var id = $(e.target).data('id');

		            images = images.filter(function(value, index, arr){
					    return value != id;
					});
		        });

		        file.previewElement.appendChild(removeButton); 
	            images.push(idName);
            }
        });

        this.on("success", function(file, responseText) { 
            if (responseText.status == 'OK') {
            	var idName = responseText.image.id;
	            var removeButton = Dropzone.createElement('<a class="btn btn-block btn-danger" id="' + idName +'-remove" data-id="' + idName + '" style="color: #FFF; border-radius: 0; border: 1px solid #CCC;" data-original-title="Remove"><i class="fa fa-times"></i>&nbsp;&nbsp;Remove</a>');
                        
	            removeButton.addEventListener("click", function (e) {
	                e.preventDefault();
	                e.stopPropagation();

	                _this.removeFile(file);

		            var id = $(e.target).data('id');

		            images = images.filter(function(value, index, arr){
					    return value != id;
					});
		        });

		        file.previewElement.appendChild(removeButton); 
	            images.push(idName);
            }
        });

        this.on("removedfile", function(file) { 
            // insert code here      
        });
    }
};

Dropzone.options.frmSharablePhoto = {
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    maxFiles: 1,
    addRemoveLinks: true,
    dictDefaultMessage: "Click Here to Upload A Sharable Photo",
    init: function () {
      	this.on("processing", function(file) {
	      this.options.url = $('#frm-sharable-photo').attr('action');
	    });

	    var _this = this;

    	this.on("addedfile", function (file) {                     
            if (file.xhr && file.xhr.responseText) {
            	var idName = file.xhr.responseText;
            	var removeButton = Dropzone.createElement('<a class="btn btn-block btn-danger" id="sharable-photo-remove" data-path="' + idName + '" style="color: #FFF; border-radius: 0;" data-original-title="Remove"><i class="fa fa-trash"></i>&nbsp;&nbsp;Remove</a>');
                        
	            removeButton.addEventListener("click", function (e) {
	                e.preventDefault();
	                e.stopPropagation();

	                _this.removeFile(file);

		            sharable_photo = '';
		        });

		        file.previewElement.appendChild(removeButton); 
	            sharable_photo = idName;
            }
        });

        this.on("success", function(file, responseText) { 
            if (responseText.status == 'OK') {
            	var idName = responseText.path;
	            var removeButton = Dropzone.createElement('<a class="btn btn-block btn-danger" id="sharable-photo-remove" data-path="' + idName + '" style="color: #FFF; border-radius: 0; border: 1px solid #CCC;" data-original-title="Remove"><i class="fa fa-times"></i>&nbsp;&nbsp;Remove</a>');
                        
	            removeButton.addEventListener("click", function (e) {
	                e.preventDefault();
	                e.stopPropagation();

	                _this.removeFile(file);

		            var path = $(e.target).data('path');

		            sharable_photo = '';
		        });

		        file.previewElement.appendChild(removeButton); 
	            sharable_photo = idName;
            }
        });

        this.on("removedfile", function(file) { 
            // insert code here      
        });

        this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
     	});
    }
};