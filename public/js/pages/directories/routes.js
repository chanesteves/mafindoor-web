var routes = new Routes();

function Routes(){
    
}

Routes.prototype.bindRoutes = function () {
	if ($('#datatable_tabletools_routes').length > 0) {
		$('#datatable_tabletools_routes').DataTable({"paging":   false});
	}

	$('#ddl-building-id').unbind('change').on('change', function () {
		var id = $(this).val();

		$('#ddl-floor-id option').hide();
		$('#ddl-floor-id option[data-building-id=' + id + ']').show();

		var floor_building_id = $('#ddl-floor-id option:selected').data('building-id');

		$('#btn-add-route').removeAttr('disabled');

		if (floor_building_id != id) {
			if ($('#ddl-floor-id option[data-building-id=' + id + ']').length == 0) {
				$('#ddl-floor-id').val('');

				$('#btn-add-route').attr('disabled', 'disabled');
			}
			else {
				var id = $('#ddl-floor-id option[data-building-id=' + id + ']').eq(0).attr('value');
				$('#ddl-floor-id').val(id);	
			}
		}
	});

	$('#ddl-building-id').trigger('change');

	var $route_validator = $("#frm-main-filter").validate({
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
		    $route_validator.focusInvalid();
		    return false;
		}

		$('#frm-main-filter').submit();
	});

	$('#frm-add-route').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var floor_id = $('#ddl-floor-id').val();
		var turns = [];

		$('#frm-add-route tr.visible').each(function () {
			var turn_id = $(this).attr('data-id');
			var step = $(this).find('.step').text();
			var lat = $(this).find('.latitude').val();
			var lng = $(this).find('.longitude').val();			
			var direction = $(this).find('.direction').val();			

			if (lat.trim() != '' && lng.trim() != '')
				turns.push({ 'id' : turn_id, 'step' : step, 'latitude' : lat, 'longitude' : lng, 'direction' : direction });
		});

		var route_obj = {
			'turns' : turns,
			'floor_id' : floor_id
		}

		$('#modal-add-route').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are adding the route.', null);
		
		route.store(route_obj, function (data) {
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Route successfully added.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	var $edit_route_validator = $("#frm-edit-route").validate({
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

	$('.route-edit').unbind('click').on('click', function () {
		var id = $(this).data('id');

		$('#hdn-edit-route-id').val(id);

		$('#frm-edit-route tr.visible').remove();

		route.show(id, function (data) {
			if (data.status == 'OK') {
				checkTurnsEmptyRow('edit');				
				data.route.turns.forEach(function (turn) {
					var row = $('#frm-edit-route .extra-row.visible');

					$(row).attr('data-id', turn.id);
					$(row).find('.latitude').val(turn.latitude).trigger('change');
					$(row).find('.longitude').val(turn.longitude).trigger('change');
					$(row).find('.direction').val(turn.direction).trigger('change');
				});
			}
		});
	});

	$('#frm-edit-route').unbind('submit').on('submit', function (e) {
		e.preventDefault();

		var floor_id = $('#ddl-floor-id').val();
		var id = $('#hdn-edit-route-id').val();
		var turns = [];

		$('#frm-edit-route tr.visible').each(function () {
			var turn_id = $(this).attr('data-id');
			var step = $(this).find('.step').text();
			var lat = $(this).find('.latitude').val();
			var lng = $(this).find('.longitude').val();			
			var dir = $(this).find('.direction').val();	

			if (lat.trim() != '' && lng.trim() != '')
				turns.push({ 'id' : turn_id, 'step' : step, 'latitude' : lat, 'longitude' : lng, 'direction' : dir });
		});

		var route_obj = {
			'id' : id,
			'turns' : turns,
			'floor_id' : floor_id
		}

		$('#modal-edit-route').modal('hide');
		modal.show('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are updating the route.', null);

		route.update(route_obj, function (data) {				
			if (data.status == 'OK')
				modal.set('success', 'fa-check-circle', 'Success', 'Route turns successfully updated.', { ok : function () {
					window.location.reload();
				}});
			else
				modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
		});
	});

	$('.route-remove').unbind('click').on('click', function () {
		var id = $(this).data('id');

		modal.show('warning', 'fa-question', 'Are You Sure?', 'This route will no longer be available.', { 'no' : function () {
			modal.hide();
		}, 'yes' : function () {
			modal.set('info', 'fa-refresh fa-spin', 'Saving...', 'Please wait while we are deleting the route.', null);

			route.destroy(id, function (data) {
				if (data.status == 'OK')
					modal.set('success', 'fa-check-circle', 'Success', 'Route successfully deleted.', { ok : function () {
						window.location.reload();
					}});
				else
					modal.set('danger', 'fa-times-circle', 'Oops', data.error, { ok : true });
			});
		}});
	});

	function bindTurns (flag) {
		$('#frm-' + flag + '-route .latitude, #frm-' + flag + '-route .longitude').unbind('change').on('change', function () {
			var lat = $(this).val();

			if (lat.trim() != '') {
				$(this).closest('tr').removeClass('extra-row');
				checkTurnsEmptyRow(flag);
			}
		});

		$('#frm-' + flag + '-route .btn-danger').unbind('click').on('click', function () {
			$(this).closest('tr').remove();
			checkTurnsEmptyRow(flag);
		});
	}

	function checkTurnsEmptyRow (flag) {
		if ($('#frm-' + flag + '-route .extra-row.visible').length == 0) {
			var row = $('#frm-' + flag + '-route .extra-row.hidden').clone();

			$(row).removeClass('hidden').addClass('visible');
			$(row).find('.step').text($('#frm-' + flag + '-route .visible').length + 1);
			$('#frm-' + flag + '-route tbody').append(row);

			bindTurns(flag);
		}

		var count = 0;
		$('#frm-' + flag + '-route .visible').each(function () {
			$(this).find('.step').text(++count);
		});
	}

	$('#frm-add-route tr.visible').remove();
	checkTurnsEmptyRow('add');
	bindTurns('add');
	bindTurns('edit');
}

$(document).ready(function(){
	routes.bindRoutes();
});