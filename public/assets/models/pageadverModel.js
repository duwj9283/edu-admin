define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getPageRows: function(data, result, fail) {
            var url = '/api/pageadver/page-rows';
            return model.getData(url, data, result, fail);
        },
        insert: function(data, result, fail) {
            var url = '/api/pageadver/insert';
            return model.postData(url, data, result, fail);
        },
        update: function(data, result, fail) {
            var url = '/api/pageadver/update';
            return model.postData(url, data, result, fail);
        },
        uploadPic1: function(data, result, fail) {
            var url = '/api/pageadver/upload-pic1';
            return model.uploadFile(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/api/pageadver/delete';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});