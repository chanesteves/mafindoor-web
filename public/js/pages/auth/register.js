$(document).ready(function(){
	$('#txt-email').unbind('change').on('change', function () {
		$('#txt-username').val($(this).val());
	});

	var $register_validator = $("#frm-register").validate({
		rules: {
			'first_name': { required: true },
			'last_name': { required: true },
			'gender': { required: true },
			'email': { required: true },
			'username': { required: true },
			'password': { required: true },
			'confirm_password': { required: true, equalTo: "#txt-password" }
		},
		messages: {
			'first_name': "Please enter your first name.",
			'last_name': "Please enter your last name.",
			'gender': "Please enter your gender.",
			'email': "Please enter your email.",
			'username': "Please enter your username.",
			'password': "Please enter your password.",
			'confirm_password': { required : "Please confirm your password.", equalTo : "Your passwords do not match." }
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

	$('#frm-register').unbind('submit').on('submit', function (event)
	{		
		event.preventDefault();

		var $valid = $("#frm-register").valid();

    	if (!$valid) {
		    $register_validator.focusInvalid();
		    return false;
		}

		var first_name = $('#txt-first-name').val();
		var last_name = $('#txt-last-name').val();
		var email = $('#txt-email').val();
		var gender = $('#ddl-gender').val();

		var username = $('#txt-username').val();
		var password = $('#txt-password').val();

		var register_obj = {
			'first_name' : first_name,
			'last_name' : last_name,
			'email' : email,
			'gender' : gender,
			'username' : username,
			'password' : password
		}

		var CSRF = $('#hdn-token').val();

		var data = { 
					_token: CSRF,
					first_name: register_obj.first_name,
					last_name: register_obj.last_name,
					email: register_obj.email,
					gender: register_obj.gender,
					username: register_obj.username,
					password: register_obj.password
				};

		$('#btn-register').html('<i class="fa fa-refresh fa-spin"></i> Create Account').attr('disabled', true);

		$.ajax({
			url: '/auth/ajaxRegister',
			type: 'POST',
			dataType: "json",
			data: data,
			success: function(data){
				$('#btn-register').html('Create Account').removeAttr('disabled');
				$('#pnl-register-form').hide();
				$('#pnl-register-message').show();

				if (data.status == 'OK') {
					$('#pnl-register-message h1').text('Email Verification Sent!');
					$('#pnl-register-message .message').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> We have sent an email to ' + data.person.email + '. Please click the verification link to confirm your email.</div>');
					$('.buttons-success').show();
					$('.buttons-error').hide();
				}
				else {
					$('#pnl-register-message h1').text('Oops!');
					$('#pnl-register-message .message').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> ' + data.error + '</div>');				
					$('.buttons-success').hide();
					$('.buttons-error').show();
				}

			}, error:function (xhr, error, ajaxOptions, thrownError){
				console.log(xhr.responseText);
				$('#lbl-error').html('<i class="fa fa-times"></i>' + xhr.responseText).show();
			}
		});

	});

	$('#btn-register-back').unbind('click').on('click', function () {
		$('#pnl-register-message').hide();
		$('#pnl-register-form').show();
	});
});

