define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getPageList: function(data, result, fail) {
            var url = '/api/newsinfo/page-list';
            return model.getData(url, data, result, fail);
        },
        getInfo: function(data, result, fail) {
            var url = '/api/newsinfo/info';
            return model.getData(url, data, result, fail);
        },
        create: function(data, result, fail) {
            var url = '/api/newsinfo/insert';
            return model.postData(url, data, result, fail);
        },
        update: function(data, result, fail) {
            var url = '/api/newsinfo/update';
            return model.postData(url, data, result, fail);
        },
        move: function(data, result, fail) {
            var url = '/api/newsinfo/move';
            return model.postData(url, data, result, fail);
        },
        uploadPic1: function(data, result, fail) {
            var url = '/api/newsinfo/upload-pic1';
            return model.uploadFile(url, data, result, fail);
        },
        removePic1: function(data, result, fail) {
            var url = '/api/newsinfo/remove-pic1';
            return model.postData(url, data, result, fail);
        },
        uploadPic2: function(data, result, fail) {
            var url = '/api/newsinfo/upload-pic2';
            return model.uploadFile(url, data, result, fail);
        },
        removePic2: function(data, result, fail) {
            var url = '/api/newsinfo/remove-pic2';
            return model.postData(url, data, result, fail);
        },
        uploadFile1: function(data, result, fail) {
            var url = '/api/newsinfo/upload-file1';
            return model.uploadFile(url, data, result, fail);
        },
        removeFile1: function(data, result, fail) {
            var url = '/api/newsinfo/remove-file1';
            return model.postData(url, data, result, fail);
        },
        uploadPics: function(data, result, fail) {
            var url = '/api/newsinfo/pics-insert';
            return model.uploadFile(url, data, result, fail);
        },
        picsUpdate: function(data, result, fail) {
            var url = '/api/newsinfo/pics-update';
            return model.postData(url, data, result, fail);
        },
        picsDelete: function(data, result, fail) {
            var url = '/api/newsinfo/pics-delete';
            return model.postData(url, data, result, fail);
        },
        getPicsInfo: function(data, result, fail){
            var url = '/api/newsinfo/pics-info';
            return model.getData(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/api/newsinfo/delete';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});