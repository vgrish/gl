if (typeof(gl) == 'undefined') {
    gl = {
        Init: false
    };
}

gl = {
    initialize: function() {
        /*if (!jQuery().ajaxForm) {
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/ajaxform/jquery.form.min.js"><\/script>');
        }
        if (!jQuery().pnotify) {
            document.write('<script src="' + glConfig.assetsUrl + 'vendor/pnotify/custom/pnotify.custom.min.js"><\/script>');
        }*/

        $(document).ready(function() {


        });
        gl.Init = true;
    }
};


gl.location = {
    initialize: function() {
        if (!!!gl.Init) {
            gl.initialize();
        }

        $(document).ready(function () {

        });

    }
};


gl.location.initialize();