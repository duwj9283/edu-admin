define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getList: function(data, result, fail) {
            var url = '/api/newsclass/list';
            return model.getData(url, data, result, fail);
        },
        getInfo: function(data, result, fail) {
            var url = '/api/newsclass/info';
            return model.getData(url, data, result, fail);
        },
        insert: function(data, result, fail) {
            var url = '/api/newsclass/insert';
            return model.postData(url, data, result, fail);
        },
        update: function(data, result, fail) {
            var url = '/api/newsclass/update';
            return model.postData(url, data, result, fail);
        },
        uploadPic1: function(data, result, fail) {
            var url = '/api/newsclass/upload-pic1';
            return model.uploadFile(url, data, result, fail);
        },
        removePic1: function(data, result, fail) {
            var url = '/api/newsclass/remove-pic1';
            return model.postData(url, data, result, fail);
        },
        addColumn: function(data, result, fail) {
            var url = '/api/newsclass/add-column';
            return model.postData(url, data, result, fail);
        },
        editColumn: function(data, result, fail) {
            var url = '/api/newsclass/edit-column';
            return model.postData(url, data, result, fail);
        },
        addPerm: function(data, result, fail) {
            var url = '/api/newsclass/add-popedom';
            return model.postData(url, data, result, fail);
        },
        removePerm: function(data, result, fail) {
            var url = '/api/newsclass/remove-popedom';
            return model.postData(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/api/newsclass/delete';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});