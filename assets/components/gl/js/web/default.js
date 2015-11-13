if (typeof(gl) == 'undefined') {
    gl = {
        Init: false
    };
}

gl = {
    initialize: function () {
        $(document).ready(function () {


        });
        gl.Init = true;
    }
};


gl.location = {
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
            $.colorbox.resize({innerWidth: '30%'});
            e.preventDefault();
            return false;
        });

        $(document).bind('cbox_complete', function(){
            $('.gl-default').show();
            $('.gl-change-select').hide();
            $.colorbox.resize();
        });

        $(document).bind('cbox_closed', function(){

        });

        //$(document).on('onClosed', '.btn-change', function(e) {
        //
        //    $('.gl-default').hide();
        //    $('.gl-change-select').show();
        //    $.colorbox.resize({ innerWidth:'30%' });
        //    e.preventDefault();
        //    return false;
        //});
        //


        $(document).ready(function () {


        });

        $(document).on('cbox_open', function () {
            $('body').css({overflow: 'hidden'});
        }).on('cbox_closed', function () {
            $('body').css({overflow: ''});
        });

    },

    modal: function (select) {
        var html = $('.gl-modal').html();
        $.colorbox({html: html});
    },

    select: function (select) {

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
