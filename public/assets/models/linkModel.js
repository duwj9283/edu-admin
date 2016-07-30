define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        insert: function(data, result, fail) {
            var url = '/api/link/insert';
            return model.postData(url, data, result, fail);
        },
        update: function(data, result, fail) {
            var url = '/api/link/update';
            return model.postData(url, data, result, fail);
        },
        uploadPic1: function(data, result, fail) {
            var url = '/api/link/upload-pic1';
            return model.uploadFile(url, data, result, fail);
        },
        deletePic1: function(data, result, fail) {
            var url = '/api/link/delete-pic1';
            return model.postData(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/api/link/delete';
            return model.postData(url, data, result, fail);
        },
        addTag: function(data, result, fail) {
            var url = '/api/link/add-tag';
            return model.postData(url, data, result, fail);
        },
        editTag: function(data, result, fail) {
            var url = '/api/link/edit-tag';
            return model.postData(url, data, result, fail);
        },
        delTag: function(data, result, fail) {
            var url = '/api/link/del-tag';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});