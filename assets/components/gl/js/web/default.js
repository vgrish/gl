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
		active: 1,
        default: 0,
        action: 'getlist'
	},
    initialize: function () {
        if (!!!gl.Init) {
            gl.initialize();
        }

        $(document).on('click touchend', '.gl-current-select', function (e) {
            gl.location.modal();
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
			gl.location.input.load('location');
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
            if (glConfig.modalShow) {
                gl.location.modal();
            }
		});

    },

    modal: function () {
        var html = $('.gl-modal').html();
        $.colorbox({
			html: html,
			//trapFocus: false
		});

    },

    select: function (select) {

    },

    request: function(action, data) {

        $.ajax({
            url: glConfig.actionUrl,
            dataType: 'json',
            delay: 200,
            type: 'POST',
            async: false,
            data: $.extend({}, {action: action}, data),
            success: function(response) {}
        });

        return true;
    },

    callbacks: {
        select2: function(evt) {
            var opts = "{}";

            if (!!evt) {
                opts = JSON.stringify(evt.params, function(key, value) {
                    if (value && value.nodeName) return "[DOM node]";
                    if (value instanceof $.Event) return "[$.Event]";
                    return value;
                });
            }
            opts = JSON.parse(opts);

            return gl.location.request('select', opts.data);
        }
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
        load: function(key) {
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
                                class: glConfig.locationClass,
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

			field.on("select2:select", function(e) {
				gl.location.callbacks.select2(e);
			});

		},
		getResult: function(el) {
			if (!el.id) {
				return '';
			}
			return $('<div>' + el.name_ru + '</div>');
		},
		getSelection: function(el) {
			if (!el.id) {
				return '';
			}
			return el.name_ru;
		}
	}

};


gl.location.initialize();
