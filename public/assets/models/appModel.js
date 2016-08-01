define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getList: function(data, result, fail) {
            var url = '/admin/mkapp/all-rows';
            return model.getData(url, data, result, fail);
        },
        getAppInfo: function(data, result, fail) {
            var url = '/admin/mkapp/app-info';
            return model.getData(url, data, result, fail);
        },
        insert: function(data, result, fail){
            var url = '/admin/mkapp/insert';
            return model.uploadFile(url, data, result, fail);
        },
        update: function(data, result, fail){
            var url = '/admin/mkapp/update';
            return model.uploadFile(url, data, result, fail);
        },
        getReleases: function(data, result, fail) {
            var url = '/admin/mkapp/all-releases';
            return model.getData(url, data, result, fail);
        },
        getRelease: function(data, result, fail) {
            var url = '/admin/mkapp/release-info';
            return model.getData(url, data, result, fail);
        },
        addRelease: function(data, result, fail) {
            var url = '/admin/mkapp/add-release';
            return model.postData(url, data, result, fail);
        },
        editRelease: function(data, result, fail) {
            var url = '/admin/mkapp/edit-release';
            return model.postData(url, data, result, fail);
        },
        removeRelease: function(data, result, fail) {
            var url = '/admin/mkapp/remove-release';
            return model.postData(url, data, result, fail);
        },
        uploadPic1: function(data, result, fail) {
            var url = '/admin/mkapp/upload-pic1';
            return model.uploadFile(url, data, result, fail);
        },
        uploadFile1: function(data, result, fail) {
            var url = '/admin/mkapp/upload-file1';
            return model.uploadFile(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/admin/mkapp/delete';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});