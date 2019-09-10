var activity = new Activity();

function Activity(){
    
}

Activity.prototype.store = function (param, callback) {
	CSRF = $("meta[name='csrf-token']").attr('content');

	data = { 
				_token: CSRF,
				'object_id' : param.object_id,
				'object_type' : param.object_type,
				'request_type' : param.request_type
			};

	$.ajax({
		url: '/activities/ajaxStore',
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