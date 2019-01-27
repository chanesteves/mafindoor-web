var modal = new Modal();

function Modal(){
    
}

Modal.prototype.init = function() {

};

Modal.prototype.show = function(status, icon, title, message, buttons) {
	$('#modal-main').modal('show');
	$('#modal-main .modal-dialog').removeClass('modal-info').removeClass('modal-success').removeClass('modal-warning').removeClass('modal-danger').addClass('modal-' + status)
	$('#modal-main .modal-title').html('<i class="fa ' + icon + '"></i> ' + title);
	$('#lbl-main').html(message);

	$('#modal-main .modal-content').find('.modal-footer').remove();

	if (buttons) {		
		$('#modal-main .modal-content').append('<div class="modal-footer">'
														+	'</div>');

		if (buttons.ok) {
			if ({}.toString.call(buttons.ok) === '[object Function]') {
	            $('#modal-main .modal-footer').html('<button id="btn-main-ok" class="btn btn-primary" type="button">OK</button>');

				$('#btn-main-ok').unbind('click').on('click', function () {
					buttons.ok();
				});           
	        }
			else {
				$('#modal-main .modal-footer').html('<button id="btn-main-ok" class="btn btn-primary" type="button" data-dismiss="modal">OK</button>');

				$('#btn-main-ok').unbind('click').on('click', function () {
					modal.hide();
				});
			}
		}

		if (buttons.no && {}.toString.call(buttons.no) === '[object Function]') {
			$('#modal-main .modal-footer').append('<button id="btn-main-no" class="btn btn-secondary" type="button">NO</button>');

			$('#btn-main-no').unbind('click').on('click', function () {
				buttons.no();
			});
        }

        if (buttons.yes) {
        	$('#modal-main .modal-footer').append('<button id="btn-main-yes" class="btn btn-primary" type="button">YES</button>');

			$('#btn-main-yes').unbind('click').on('click', function () {
				buttons.yes();
			});
        }
	}
};

Modal.prototype.set = function(status, icon, title, message, buttons) {
    $('#modal-main .modal-dialog').removeClass('modal-info').removeClass('modal-success').removeClass('modal-warning').removeClass('modal-danger').addClass('modal-' + status)
    $('#modal-main .modal-title').html('<i class="fa ' + icon + '"></i> ' + title);
	$('#lbl-main').html(message);

    if (buttons) {
		$('#modal-main .modal-content').find('.modal-footer').remove();
		$('#modal-main .modal-content').append('<div class="modal-footer">'
														+	'</div>');

		if (buttons.ok) {
			if ({}.toString.call(buttons.ok) === '[object Function]') {
	            $('#modal-main .modal-footer').html('<button id="btn-main-ok" class="btn btn-primary" type="button">OK</button>');

				$('#btn-main-ok').unbind('click').on('click', function () {
					buttons.ok();
				});           
	        }
			else {
				$('#modal-main .modal-footer').html('<button id="btn-main-ok" class="btn btn-primary" type="button" data-dismiss="modal">OK</button>');

				$('#btn-main-ok').unbind('click').on('click', function () {
					modal.hide();
				});
			}
		}
	}
};

Modal.prototype.hide = function() {
    $('.modal').modal('hide');
    $('.modal-backdrop').hide();
    $('body').removeClass('modal-open');   
}