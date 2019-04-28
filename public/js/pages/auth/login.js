$(document).ready(function(){
	var $login_validator = $("#frm-login").validate({
		rules: {
			'username': { required: true },
			'password': { required: true }
		},
		messages: {
			'username': "Please enter your username.",
			'password': "Please enter your password."
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

	$('#frm-login').unbind('submit').on('submit', function (event)
	{		
		event.preventDefault();

		var $valid = $("#frm-login").valid();

    	if (!$valid) {
		    $login_validator.focusInvalid();
		    return false;
		}

		$('#frm-login').unbind('submit').submit();
	});
});

