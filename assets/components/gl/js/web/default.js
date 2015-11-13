if (typeof(gl) == 'undefined') {
    gl = {
        Init: false
    };
}

gl = {
    initialize: function () {
		if (!jQuery().colorbox) {
			document.write('<script src="' + glConfig.assetsUrl + 'vendor/colorbox/jquery.colorbox-min.js"><\/script>');
			document.write('<script src="' + glConfig.assetsUrl + 'vendor/colorbox/i18n/jquery.colorbox-ru.js"><\/script>');
		}
		if (!jQuery().select2) {
			document.write('<script src="' + glConfig.assetsUrl + 'vendor/select2/js/select2.min.js"><\/script>');
			document.write('<script src="' + glConfig.assetsUrl + 'vendor/select2/js/i18n/ru.js"><\/script>');
		}
        $(document).ready(function () {

        });
        gl.Init = true;
    }
};


gl.location = {
	config: {},
	placeholder: {},
	baseParams: {
		limit: 0,
		active: 1
	},
    initialize: function () {
        if (!!!gl.Init) {
            gl.initialize();
        }

        $(document).on('click touchend', '.gl-current-select', function (e) {
            var $this = $(this);
            gl.location.modal($this);
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend', '.btn-yes', function (e) {
            $.colorbox.close();
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend', '.btn-change', function (e) {
            $('.gl-default').hide();
            $('.gl-change-select').show();
			$.colorbox.resize();
            e.preventDefault();
            return false;
        });

		$(document).on('click touchend', '.gl-change-list .gl-list-location', function (e) {

			console.log(this);

			e.preventDefault();
			return false;
		});

        $(document).bind('cbox_complete', function(){
			$('#colorbox').removeAttr('tabindex');
            $('.gl-default').show();
            $('.gl-change-select').hide();
            $.colorbox.resize();
			gl.location.input.activate('location');
        });

		$(document).bind('cbox_cleanup', function(){
			gl.location.input.close('location');
		});
		$(document).bind('cbox_closed', function(){
			gl.location.input.destroy('location');
		});

        $(document).on('cbox_open', function () {
            $('body').css({overflow: 'hidden'});
        }).on('cbox_closed', function () {
            $('body').css({overflow: ''});
        });

		$(document).ready(function () {

		});

    },

    modal: function (select) {
        var html = $('.gl-modal').html();
        $.colorbox({
			html: html,
			//trapFocus: false
		});

    },

    select: function (select) {

    },

	input: {
		close: function(key) {
			var field = $('[name="' + key + '"]');
			if (!field) {
				return false;
			}
			field.select2('close');
		},
		destroy: function(key) {
			var field = $('[name="' + key + '"]');
			if (!field) {
				return false;
			}
			field.select2('destroy');
		},
		activate: function(key, action) {

			action = 'rrr';

			var field = $('[name="' + key + '"]');
			if (!field) {
				return false;
			}

			field.select2({
				templateResult: gl.location.input.getResult,
				templateSelection: gl.location.input.getSelection,
				maximumSelectionLength: 1,
				language: "ru",
				ajax: {
					url: glConfig.actionUrl,
					dataType: 'json',
					delay: 200,
					type: 'POST',
					data: function(params) {
						return $.extend({},
							gl.location.baseParams, {
								action: action,
								query: params.term
							});
					},
					processResults: function(data, page) {
						return {
							results: data.results
						};
					},
					cache: false
				}
			});

			//field.on("select2:select", function(e) {
			//	mspointsissue.callbacks.handle("select2:select", e);
			//});
			//field.on("select2:unselect", function(e) {
			//	mspointsissue.callbacks.handle("select2:unselect", e);
			//});

			/*field.on("select2:open", function(e) {
			 mspointsissue.callbacks.handle("select2:open", e);
			 });
			 field.on("select2:close", function(e) {
			 mspointsissue.callbacks.handle("select2:close", e);
			 });*/
		},
		getResult: function(el) {
			if (!el.id) {
				return '';
			}
			return $('<div>' + el.name + ' <sup>' + el.company_name + '</sup></div><div>' + el.phone + '</div>');
		},
		getSelection: function(el) {
			if (!el.id) {
				return '';
			}
			return el.name;
		}
	},


};


gl.location.initialize();

/*

 action: function(form, button, confirm) {
 if (confirm) {
 paymentsystem.Operation.Сonfirm(form, button);
 return false;
 }
 var action = $(button).prop('name').split('/');

 $(form).ajaxSubmit({
 data: {
 action: action
 },
 url: paymentsystemConfig.actionUrl,
 form: form,
 button: button,
 dataType: 'json',
 beforeSubmit: function() {
 $(button).attr('disabled', true);
 return true;
 },
 success: function(response) {
 if (response.success) {
 paymentsystem.Message.success('', response.message);

 if ($.inArray('paymentopen',action) > 0  && response.object['payment_url']) {
 document.location.href = response.object['payment_url'];
 }

 if (response.object && response.object['process']) {
 var process = response.object['process'];
 if (process.id && process.type && process.output != '') {
 var view = $(paymentsystemConfig.defaults.selector.view).parent().find('[data-type="' + process.type + '"][data-id="' + process.id + '"]');
 if (view.length) {
 view.html(process.output);
 }
 }
 }

 } else {
 if (response.data && response.data.length > 0) {
 var errors = [];
 var i, field;
 for (i in response.data) {
 field = response.data[i];
 var elem = $(form).find('[name="' + field.id + '"]').parent().find('.error');
 if (elem.length > 0) {
 elem.text(field.msg)
 }
 else if (field.id && field.msg) {
 errors.push(field.id + ': ' + field.msg);
 }
 }
 if (errors.length > 0) {
 paymentsystem.Message.error('', errors.join('<br/>'));
 }
 }
 else {
 paymentsystem.Message.error('', response.message);
 }
 }
 $(button).attr('disabled', false);
 }
 });
 }*/
