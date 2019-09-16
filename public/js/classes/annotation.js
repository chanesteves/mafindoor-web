var annotation = new Annotation();

function Annotation(){
    
}

Annotation.prototype.store = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'name' : param.name,
				'map_name' : param.map_name,
				'longitude' : param.longitude,
				'latitude' : param.latitude,
				'min_zoom' : param.min_zoom,
				'max_zoom' : param.max_zoom,
				'sub_category_id' : param.sub_category_id,
				'floor_id' : param.floor_id
			};

	$.ajax({
		url: '/annotations/ajaxStore',
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

Annotation.prototype.update = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'name' : param.name,
				'map_name' : param.map_name,
				'longitude' : param.longitude,
				'latitude' : param.latitude,
				'min_zoom' : param.min_zoom,
				'max_zoom' : param.max_zoom,
				'sub_category_id' : param.sub_category_id,
				'floor_id' : param.floor_id
			};

	$.ajax({
		url: '/annotations/' + param.id + '/ajaxUpdate',
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

Annotation.prototype.destroy = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF
			};

	$.ajax({
		url: '/annotations/' + id + '/ajaxDestroy',
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

Annotation.prototype.show = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	$.ajax({
		url: '/annotations/' + id + '/ajaxShow',
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

Annotation.prototype.updateEntries = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'entries' : param.entries
			};

	$.ajax({
		url: '/annotations/' + param.id + '/ajaxUpdateEntries',
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