var floors = new Floors();

var map;
var geojson = {
	"type": "FeatureCollection",
	"features": [{
		"type": "Feature",
		"geometry": {
			"type": "Point",
			"coordinates": [0, 0]
		},
		"properties": {
			"title": ""
		}
	}]
};
var pointCount = 0;

function Floors(){
    
}

Floors.prototype.bindFloors = function () {
	mapboxgl.accessToken = 'pk.eyJ1IjoiY2hhbmVzdGV2ZXMiLCJhIjoiY2ptc2Zjc3NrMDFhczNxbXJwZ2RqajVnbyJ9.Xuv8n5uFzywa-ZRcppeAKA';
	
	$('.floor-map').each(function () {
		var id = $(this).attr('id');
		var url = $(this).data('url');
		var longitude = $(this).data('longitude');
		var latitude = $(this).data('latitude');
		var zoom = $(this).data('zoom');

		var map = new mapboxgl.Map({
			container: id,
			style: url,
			center: [longitude, latitude],
			zoom: zoom
		});
	});

	var $add_floor_validator = $("#frm-add-floor").validate({
		rules: {
			'name': { required: true },
			'label': { required: true },
			'map_url': { required: true },
			'longitude': { required: true },
			'latitude': { required: true },
			'zoom': { required: true },
			'min_zoom': { required: true },
			'max_zoom': { required: true },
			'status': { required: true }			
		},
		messages: {
			'name': "Please enter the name.",
			'label': "Please enter the label.",
			'map_url': "Please enter the map URL.",
			'longitude': "Please enter the longitude.",
			'latitude': "Please enter the latitude.",
			'zoom': "Please enter the zoom.",
			'min_zoom': "Please enter the minimum zoom level.",
			'max_zoom': "Please enter the maximum zoom level.",
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

	$('#frm-add-floor').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-add-floor").valid();

    	if (!$valid) {
		    $add_floor_validator.focusInvalid();
		    return false;
		}

		var name = $('#txt-add-floor-name').val();
		var label = $('#txt-add-floor-label').val();
		var map_url = $('#txt-add-floor-map-url').val();
		var longitude = $('#txt-add-floor-longitude').val();
		var latitude = $('#txt-add-floor-latitude').val();
		var zoom = $('#txt-add-floor-zoom').val();
		var min_zoom = $('#txt-add-floor-min-zoom').val();
		var max_zoom = $('#txt-add-floor-max-zoom').val();
		var status = $('#ddl-add-floor-status').val();
		var building_id = $('#ddl-floor-id').val();

		var floor_obj = {
			'name' : name,
			'label' : label,
			'map_url' : map_url,
			'longitude' : longitude,
			'latitude' : latitude,
			'zoom' : zoom,
			'min_zoom' : min_zoom,
			'max_zoom' : max_zoom,
			'status' : status,
			'building_id' : building_id
		};

		$('#modal-add-floor').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the floor.', null);
		
		floor.store(floor_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Floor successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_floor_validator = $("#frm-edit-floor").validate({
		rules: {
			'name': { required: true },
			'label': { required: true },
			'map_url': { required: true },
			'longitude': { required: true },
			'latitude': { required: true },
			'zoom': { required: true },
			'min_zoom': { required: true },
			'max_zoom': { required: true },
			'status': { required: true }			
		},
		messages: {
			'name': "Please enter the name.",
			'label': "Please enter the label.",
			'map_url': "Please enter the map URL.",
			'longitude': "Please enter the longitude.",
			'latitude': "Please enter the latitude.",
			'zoom': "Please enter the zoom.",
			'min_zoom': "Please enter the minimum zoom level.",
			'max_zoom': "Please enter the maximum zoom level.",
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

	$('.floor-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		floor.show(id, function (data) {
			if (data.status == 'OK') {
				$('#hdn-edit-floor-id').val(data.floor.id);
				$('#txt-edit-floor-name').val(data.floor.name);
				$('#txt-edit-floor-label').val(data.floor.label);
				$('#txt-edit-floor-map-url').val(data.floor.map_url);
				$('#txt-edit-floor-longitude').val(data.floor.longitude);
				$('#txt-edit-floor-latitude').val(data.floor.latitude);
				$('#txt-edit-floor-zoom').val(data.floor.zoom);
				$('#txt-edit-floor-min-zoom').val(data.floor.min_zoom);
				$('#txt-edit-floor-max-zoom').val(data.floor.max_zoom);
				$('#ddl-edit-floor-status').val(data.floor.status);
			}
		});
	});

	$('#frm-edit-floor').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var $valid = $("#frm-edit-floor").valid();

    	if (!$valid) {
		    $edit_floor_validator.focusInvalid();
		    return false;
		}

		var id = $('#hdn-edit-floor-id').val();
		var name = $('#txt-edit-floor-name').val();
		var label = $('#txt-edit-floor-label').val();
		var map_url = $('#txt-edit-floor-map-url').val();
		var longitude = $('#txt-edit-floor-longitude').val();
		var latitude = $('#txt-edit-floor-latitude').val();
		var zoom = $('#txt-edit-floor-zoom').val();
		var min_zoom = $('#txt-edit-floor-min-zoom').val();
		var max_zoom = $('#txt-edit-floor-max-zoom').val();
		var status = $('#ddl-edit-floor-status').val();
		var building_id = $('#ddl-floor-id').val();

		var floor_obj = {
			'id' : id,
			'name' : name,
			'label' : label,
			'map_url' : map_url,
			'longitude' : longitude,
			'latitude' : latitude,
			'zoom' : zoom,
			'min_zoom' : min_zoom,
			'max_zoom' : max_zoom,
			'status' : status,
			'building_id' : building_id
		};

		$('#modal-edit-floor').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the floor.', null);
		
		floor.update(floor_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Floor successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.floor-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This floor will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the floor.', null);

			floor.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Floor successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	$('#lnk-upload-import-floors-csv').unbind('click').on('click', function () {
		$('#modal-import-floors .file-import').click();
	});
	$('#modal-import-floors .file-import').val('');

	$('#modal-import-floors .file-import').unbind('change').on('change', function (e) {
		$('#lnk-upload-import-floors-csv').hide();
		
		var input = e.target;
        var reader;

        if (input.files && input.files[0]) {
            reader = new FileReader();

            reader.onload = function (e) {
            	var filename = $('#modal-import-floors .file-import').val().substring($('#modal-import-floors .file-import').val().lastIndexOf('\\') + 1);
			
            	$('#frm-import-floors').submit();
			}

            reader.readAsDataURL(input.files[0]);
        }
	});

	$('#frm-import-floors').unbind('submit').on('submit', function (event) {
		event.preventDefault();

		var input = document.getElementById('modal-import-floors').getElementsByClassName('file-import')[0];

		if (input.files && input.files[0]) {
			var building_id = $('#ddl-floor-id').val();

			var CSRF = $("meta[name='csrf-token']").attr('content');

			var request = new XMLHttpRequest();

      		var form_data = new FormData();
      		form_data.append("file-upload", input.files[0]);
      		form_data.append("building_id", building_id);

	        request.onreadystatechange = function() {
	            if (request.readyState == 4) {
	            	if (request.status == 200) {
		            	try {
		                    var data = JSON.parse(request.responseText);

		                    if (data.status == 'OK') {
			                    $('#modal-import-floors .upload-status .progress').show();
			                    $('#modal-import-floors .upload-status .progress-bar').css('width', '100%');
			                    $('#modal-import-floors .upload-status span').html('Uploaded succesfully! 100%');
			                
			                    uploadProfileStatus = 'FILE_UPLOADED';

			                    $('#modal-import-floors').modal('hide');
			                    modal.show('success', 'fa-check-circle', 'Success', 'Floors successfully imported.', { ok : function () {
									window.location.reload();
								}});
			                }		                
			                else {
			                	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			                	$('#lnk-upload-import-floors-csv').show();
			                	$('#modal-import-floors .file-import').val('');
			                	$('#modal-import-floors .upload-status .progress').hide();
			                    
			                    if (data && data.error)
			                    	$('#modal-import-floors .upload-status span').html(data.error);
			                    else
			                    	$('#modal-import-floors .upload-status span').html(request.responseText);
			                }
			            } catch(e) {
			            	uploadProfileStatus = 'FILE_UPLOAD_ERROR';

			            	$('#lnk-upload-import-floors-csv').show();
			            	$('#modal-import-floors .file-import').val('');			            	
			                $('#modal-import-floors .upload-status .progress').hide();

					        $('#modal-import-floors .upload-status span').html(request.responseText);
					    }
					}
					else {
						console.log(request.responseText);
					}
	            }
	        };

	        request.upload.onloadstart = function() {
	        	$('#modal-import-floors .upload-status').show();
	            $('#modal-import-floors .upload-status span').html('Uploaded started...');
	        };

	        request.upload.onprogress = function(event) {
	            $('#modal-import-floors .upload-status .progress').show();
	            $('#modal-import-floors .upload-status .progress-bar').css('width', Math.round(event.loaded / event.total * 100) + '%');
	            $('#modal-import-floors .upload-status span').html('Upload Progress ' + Math.round(event.loaded / event.total * 100) + '%');
	        };

	        request.upload.onerror = function(error) {
	        	$('#lnk-upload-import-floors-csv').show();
	        	$('#modal-import-floors .file-import').val('');
	            $('#modal-import-floors .upload-status span').html('Failed to upload to server: ' + error);
	        };

	        request.upload.onabort = function(error) {
	        	$('#lnk-upload-import-floors-csv').show();
	        	$('#modal-import-floors .file-import').val('');
	            $('#modal-import-floors .upload-status span').html('XMLHttpRequest aborted: ' + error);
	        };

	        request.open('POST', '/floors/ajaxImportFloors');
	        request.setRequestHeader('X-CSRF-TOKEN', CSRF);
            request.send(form_data);
		}
	});

	$('.floor-manage-points').unbind('click').on('click', function () {
	    var id = $(this).data('id');

	    $('#hdn-manage-points-floor-id').val(id);

	    $('#frm-manage-points-floor tr.visible').remove();

	    floor.show(id, function (data) {
	      if (data.status == 'OK') {
	        checkPointsEmptyRow(); 

	        map = new mapboxgl.Map({
	          container: 'pnl-map',
	          style: data.floor.map_url,
	          center: [data.floor.longitude, data.floor.latitude],
	          zoom: (data.floor.max_zoom + data.floor.min_zoom) / 2
	        });

	        map.on('load', function (e) {
	          map.on('click', function(e) {
	              var latitude = e.lngLat.lat.toFixed(6);
	              var longitude = e.lngLat.lng.toFixed(6);

	              var row = $('#frm-manage-points-floor .extra-row.visible');

	            $(row).find('.latitude').val(latitude).trigger('change');
	            $(row).find('.longitude').val(longitude).trigger('change');
	          });

	          data.floor.points.forEach(function (point) {
	            var row = $('#frm-manage-points-floor .extra-row.visible');

	            $(row).attr('data-id', point.id);
	            $(row).find('.latitude').val(point.latitude).trigger('change');
	            $(row).find('.longitude').val(point.longitude).trigger('change');
	          });
	        });
	      }
	    });
	});

	function bindPoints () {
	    $('#frm-manage-points-floor .latitude, #frm-manage-points-floor .longitude').unbind('change').on('change', function () {
	      var val = $(this).val();

	      if (val.trim() != '') {
	        $(this).closest('tr').removeClass('extra-row');
	        checkPointsEmptyRow();

	        var latitude = $(this).closest('tr').find('.latitude').val();
	        var longitude = $(this).closest('tr').find('.longitude').val();

	        if (latitude.trim() != '' && longitude.trim() != '') {
	          pointCount++;

	          $(this).closest('tr').find('.label').text(pointCount);
	          geojson.features[0].geometry.coordinates = [longitude, latitude];
	          geojson.features[0].properties.title = pointCount.toString();         
	          
	          map.addLayer({
	            "id": "circle" + pointCount.toString(),
	            "type": "circle",
	            "source": {
	              "type": "geojson",
	              "data": geojson
	            },
	              "paint": {
	                  "circle-radius": 10,
	                  "circle-color": "#5b94c6"
	              }
	          });

	          map.addLayer({
	            "id": "symbol" + pointCount.toString(),
	            "type": "symbol",
	            "source": {
	              "type": "geojson",
	              "data": geojson
	            },
	            "paint": {
	                  "text-color": "#FFF"              
	              },
	              "layout": {       
	                "text-size": 8,     
	              "text-field": "{title}"
	            }
	          });

	          $(this).closest('tr').attr('data-point-id', pointCount.toString());
	        }
	      }
	    });

	    $('#frm-manage-points-floor .btn-danger').unbind('click').on('click', function () {
	      var point_id = $(this).closest('tr').attr('data-point-id');

	      $(this).closest('tr').remove();
	      map.removeLayer("circle" + point_id);
	      map.removeLayer("symbol" + point_id);
	      checkPointsEmptyRow();
	    });
	}

	function checkPointsEmptyRow () {
	    if ($('#frm-manage-points-floor .extra-row.visible').length == 0) {
	      var row = $('#frm-manage-points-floor .extra-row.hidden').clone();

	      $(row).removeClass('hidden').addClass('visible');
	      $('#frm-manage-points-floor tbody').append(row);

	      bindPoints();
	    }
	}

	  $('#frm-manage-points-floor tr.visible').remove();
	  checkPointsEmptyRow();
	  bindPoints();

	  $('#frm-manage-points-floor').unbind('submit').on('submit', function (event) {
	    event.preventDefault();

	    var id = $('#hdn-manage-points-floor-id').val();
	    var points = [];

	    $('#frm-manage-points-floor tr.visible').each(function () {
	      var point_id = $(this).attr('data-id');
	      var lat = $(this).find('.latitude').val();
	      var lng = $(this).find('.longitude').val();     

	      if (lat.trim() != '' && lng.trim() != '')
	        points.push({ 'id' : point_id, 'latitude' : lat, 'longitude' : lng });
	    });

	    var floor_obj = {
	      'id' : id,
	      'points' : points
	    }

	    $('#modal-manage-points-floor').modal('hide');
	    modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the points.', null);

	    floor.updatePoints(floor_obj, function (data) {        
	      if (data.status == 'OK')
	        modal.set('success', 'fa-check-circle', 'Success', 'Floor points successfully updated.', { ok : function () {
	          window.location.reload();
	        }});
	      else
	        modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
	    });
	  });
}

Floors.prototype.reloadPageContent = function (data, message, callback) {
    floors.bindFloors();
}

$(document).ready(function(){
	floors.bindFloors();
});