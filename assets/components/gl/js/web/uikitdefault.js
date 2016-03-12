/**
 *  v 1.1.7
 *
 * with uikit
 *
 * */

if (typeof(gl) == 'undefined') {
    gl = {
        Init: false
    };
}

gl = {
    initialize: function() {
        if (!jQuery().UIkit) {
            document.writeln('<style data-compiled-css>@import url('+glConfig.assetsUrl + 'css/web/uikit/modal.css); </style>');
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/uikit/src/js/core/core.js"><\/script>');
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/uikit/src/js/core/modal.js"><\/script>');
        }
        if (!jQuery().select2) {
            document.writeln('<style data-compiled-css>@import url('+glConfig.assetsUrl + 'vendor/select2/dist/css/select2.min.css); </style>');
            document.writeln('<script src="' + glConfig.assetsUrl + 'vendor/select2/dist/js/select2.min.js"><\/script>');
            document.writeln('<script src="' + glConfig.assetsUrl + 'vendor/select2/dist/js/i18n/ru.js"><\/script>');
        }
        $(document).ready(function() {

        });
        gl.Init = true;
    }
};


gl.location = {
    modal: null,
    config: {},
    placeholder: {},
    baseParams: {
        limit: 0,
        active: 1,
        default: 0,
        action: 'getlist'
    },
    selectors: {
        modal: '.gl-modal',
        listChange: '.gl-change-list',
        listDefault: '.gl-default-list',
        selectCurrent: '.gl-current-select',
        location: '.gl-list-location',
        select2Container: '.gl-select2-container',

        btnYes: '.btn-yes',
        btnChange: '.btn-change',

    },
    initialize: function() {
        if (!!!gl.Init) {
            gl.initialize();
        }

        $(document).on('click touchend', gl.location.selectors.selectCurrent, function(e) {
            e.preventDefault();
            gl.location.modal.show();
            gl.location.input.load('location');
            return false;
        });

        $(document).on('click touchend', gl.location.selectors.btnChange, function(e) {
            $('.gl-default').hide();
            $('.gl-change-select').show();
            e.preventDefault();
            return false;
        });

        $(document).on('click touchend',  gl.location.selectors.btnYes, function(e) {
            var data = {
                id: 0,
                class: 'default'
            };

            var def = $(gl.location.selectors.listDefault).find(gl.location.selectors.location).slice(0, 1);
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

        $(document).on('click touchend', gl.location.selectors.listChange + ' ' + gl.location.selectors.location, function(e) {
            var data = {
                id: $(this).data('id'),
                class: $(this).data('class')
            };

            gl.location.select(data);

            e.preventDefault();
            return false;
        });

        $(document).on('gl_select', function(e, data, response) {
            gl.location.modal.hide();

            if(response.object.current && response.object.current.data && response.object.current.data.resource_url)
            {
                document.location.href = response.object.current.data.resource_url;
            }
            else {
                location.reload();
            }
        });

        $(document).ready(function() {
            gl.location.modal = UIkit.modal(gl.location.selectors.modal, { center:true });
            $(gl.location.selectors.modal).parent().show();

            if (glConfig.modalShow) {
                gl.location.modal.show();
            }
            gl.location.input.load('location');

        });

    },

    request: function(action, data) {

        $.ajax({
            url: glConfig.actionUrl,
            dataType: 'json',
            delay: 200,
            type: 'POST',
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

            var select2Container = $(gl.location.selectors.select2Container);
            if (!select2Container.length) {
                select2Container = field.parent();
            }

            field.select2({
                templateResult: gl.location.input.getResult,
                templateSelection: gl.location.input.getSelection,
                dropdownParent: select2Container,
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

            var name = !!!el.name_alt ? el.name_ru :el.name_alt;

            return $('<div>' + name + '</div>');
        },
        getSelection: function(el) {
            if (!el.id) {
                return '';
            }

            var name = !!!el.name_alt ? el.name_ru :el.name_alt;

            return name
        }
    }

};


gl.location.initialize();
