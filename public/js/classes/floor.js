var floor = new Floor();

function Floor(){
    
}

Floor.prototype.store = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'name' : param.name,
				'label' : param.label,
				'map_url' : param.map_url,
				'longitude' : param.longitude,
				'latitude' : param.latitude,
				'zoom' : param.zoom,
				'min_zoom' : param.min_zoom,
				'max_zoom' : param.max_zoom,
				'status' : param.status,
				'building_id' : param.building_id
			};

	$.ajax({
		url: '/floors/ajaxStore',
		type: 'POST',
		dataType: "json",
		data: data,
		success: function(data){
			if (callback)
				callback(data);
		}, error:function (xhr, error, ajaxOptions, thrownError){
			console.log(xhr.responseText);
		}
	});
}

Floor.prototype.update = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'name' : param.name,
				'label' : param.label,
				'map_url' : param.map_url,
				'longitude' : param.longitude,
				'latitude' : param.latitude,
				'zoom' : param.zoom,
				'min_zoom' : param.min_zoom,
				'max_zoom' : param.max_zoom,
				'status' : param.status,
				'building_id' : param.building_id
			};

	$.ajax({
		url: '/floors/' + param.id + '/ajaxUpdate',
		type: 'POST',
		dataType: "json",
		data: data,
		success: function(data){
			if (callback)
				callback(data);
		}, error:function (xhr, error, ajaxOptions, thrownError){
			console.log(xhr.responseText);
		}
	});
}

Floor.prototype.destroy = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF
			};

	$.ajax({
		url: '/floors/' + id + '/ajaxDestroy',
		type: 'POST',
		dataType: "json",
		data: data,
		success: function(data){
			if (callback)
				callback(data);
		}, error:function (xhr, error, ajaxOptions, thrownError){
			console.log(xhr.responseText);
		}
	});
}

Floor.prototype.show = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	$.ajax({
		url: '/floors/' + id + '/ajaxShow',
		type: 'POST',
		dataType: "json",
		data: {_token: CSRF},
		success: function(data){
			if (data.status == 'OK')
				callback(data);
		}, error:function (xhr, error, ajaxOptions, thrownError){
			console.log(xhr.responseText);
		}
	});
}

Floor.prototype.updatePoints = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'points' : param.points
			};

	$.ajax({
		url: '/floors/' + param.id + '/ajaxUpdatePoints',
		type: 'POST',
		dataType: "json",
		data: data,
		success: function(data){
			if (callback)
				callback(data);
		}, error:function (xhr, error, ajaxOptions, thrownError){
			console.log(xhr.responseText);
		}
	});
}