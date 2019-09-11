var user = new User();

function User(){
    
}

User.prototype.store = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'first_name' : param.first_name,
				'last_name' : param.last_name,
				'email' : param.email,
				'gender' : param.gender,
				'username' : param.username,
				'password' : param.password,
				'roles' : param.roles
			};

	$.ajax({
		url: '/users/ajaxStore',
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

User.prototype.update = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'first_name' : param.first_name,
				'last_name' : param.last_name,
				'email' : param.email,
				'gender' : param.gender,
				'username' : param.username,
				'password' : param.password,
				'roles' : param.roles
			};

	$.ajax({
		url: '/users/' + param.id + '/ajaxUpdate',
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

User.prototype.destroy = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF
			};

	$.ajax({
		url: '/users/' + id + '/ajaxDestroy',
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

User.prototype.show = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	$.ajax({
		url: '/users/' + id + '/ajaxShow',
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

User.prototype.showActivities = function (id, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	$.ajax({
		url: '/users/' + id + '/ajaxShowActivities',
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