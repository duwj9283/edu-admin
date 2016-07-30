define(function(require, exports, module) {
    var ajax = function(action, data, result, fail, method) {
        $.ajax(action, {
            type: method,
            data: data,
            dataType: 'json',
            async: true,
            beforeSend: function() {
                $("#loadingMask").fadeIn();
            },
            complete: function() {
                $("#loadingMask").fadeOut();
            },
            success: result,
            error: fail
        });
    };
    var facade = {
        getData: function(action) {
            var formdata = arguments[1] || null,
                result = arguments[2] || null,
                fail = arguments[3] || null;
            $.get(action, formdata, result).fail(fail);
        },
        postData: function(action, formdata) {
            var result = arguments[2] || null,
                fail = arguments[3] || null;
            ajax(action, formdata, result, fail, 'post');
        },
        uploadFile: function(action, formdata, result, fail) {
            result = result || null;
            fail = fail || null;
            $.ajax(action, {
                type: "post",
                data: formdata,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#loadingMask").fadeIn();
                },
                complete: function() {
                    $("#loadingMask").fadeOut();
                },
                success: result,
                error: fail
            });
        }
    };
    module.exports = facade;
});