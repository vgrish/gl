/** v 1.0.5 */

if (typeof(gl) == 'undefined') {
    gl = {
        Init: false
    };
}

gl = {
    initialize: function() {
        if (!jQuery().colorbox) {
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/colorbox/jquery.colorbox-min.js"><\/script>');
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/colorbox/i18n/jquery.colorbox-ru.js"><\/script>');
        }
        if (!jQuery().select2) {
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/select2/js/select2.min.js"><\/script>');
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/select2/js/i18n/ru.js"><\/script>');
        }
        $(document).ready(function() {

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
    initialize: function() {
        if (!!!gl.Init) {
            gl.initialize();
        }

        $(document).on('click touchend', '.gl-current-select', function(e) {
            gl.location.modal();
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend', '.btn-change', function(e) {
            $('.gl-default').hide();
            $('.gl-change-select').show();
            $.colorbox.resize();
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend', '.btn-yes', function(e) {
            var data = {
                id: 0,
                class: 'default'
            };

            var def = $('.gl-default-list').find('.gl-list-location').slice(0, 1);
            if (def.length > 0) {
                data = {
                    id: def.data('id'),
                    class: def.data('class')
                };
            }

            gl.location.select(data);
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend', '.gl-change-list .gl-list-location', function(e) {
            var data = {
                id: $(this).data('id'),
                class: $(this).data('class')
            };

            gl.location.select(data);

            e.preventDefault();
            return false;
        });

        $(document).bind('cbox_complete', function() {
            $('#colorbox').removeAttr('tabindex');
            $('.gl-default').show();
            $('.gl-change-select').hide();
            $.colorbox.resize();
            gl.location.input.load('location');
        });

        $(document).bind('cbox_cleanup', function() {
            gl.location.input.close('location');
        });

        $(document).bind('cbox_closed', function() {
            gl.location.input.destroy('location');
        });

        $(document).bind('gl_select', function(e, data, response) {
            $.colorbox.close();

            var resourceUrl = response.object.current.data['resource_url'];
            if (!!resourceUrl) {
                document.location.href = resourceUrl;
            }
            else {
                location.reload();
            }
        });

        $(document).ready(function() {
            if (glConfig.modalShow) {
                gl.location.modal();
            }
        });

    },

    modal: function() {
        var html = $('.gl-modal').html();

        $.colorbox({
            html: html
        });
    },

    request: function(action, data) {

        $.ajax({
            url: glConfig.actionUrl,
            dataType: 'json',
            delay: 200,
            type: 'POST',
            async: false,
            cache: false,
            data: $.extend({}, {
                action: action
            }, data),
            success: function(response) {
                $(document).trigger('gl_select', [data, response]);
            }
        });

        return true;
    },

    select: function(data) {
        return gl.location.request('select', data);
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

            return gl.location.select(opts.data);
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
