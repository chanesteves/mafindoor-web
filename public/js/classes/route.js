var route = new Route();

function Route(){
    
}

Route.prototype.store = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'turns' : param.turns,
				'floor_id' : param.floor_id
			};

	$.ajax({
		url: '/routes/ajaxStore',
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

Route.prototype.show = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	$.ajax({
		url: '/routes/' + id + '/ajaxShow',
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

Route.prototype.update = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'turns' : param.turns,
				'floor_id' : param.floor_id
			};

	$.ajax({
		url: '/routes/' + param.id + '/ajaxUpdate',
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

Route.prototype.destroy = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF
			};

	$.ajax({
		url: '/routes/' + id + '/ajaxDestroy',
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