define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getPageRows: function(data, result, fail) {
            var url = '/admin/userfiles/page-rows';
            return model.getData(url, data, result, fail);
        },
        addFolder: function(data, result, fail) {
            var url = '/admin/userfiles/add-folder';
            return model.postData(url, data, result, fail);
        },
        renFolder: function(data, result, fail) {
            var url = '/admin/userfiles/ren-folder';
            return model.postData(url, data, result, fail);
        },
        removeFolder: function(data, result, fail) {
            var url = '/admin/userfiles/remove-folder';
            return model.postData(url, data, result, fail);
        },
        getFolderZtree: function(data, result, fail) {
            var url = '/admin/userfiles/folder-ztree';
            return model.getData(url, data, result, fail);
        },
        changeSortByZtree: function(data, result, fail) {
            var url = '/admin/userfiles/change-sort-by-ztree';
            return model.postData(url, data, result, fail);
        },
        uploadFile: function(data, result, fail) {
            var url = '/admin/userfiles/upload-file';
            return model.uploadFile(url, data, result, fail);
        },
        getFileInfo: function(data, result, fail) {
            var url = '/admin/userfiles/file-info';
            return model.getData(url, data, result, fail);
        },
        renFile: function(data, result, fail) {
            var url = '/admin/userfiles/ren-file';
            return model.postData(url, data, result, fail);
        },
        moveFiles: function(data, result, fail) {
            var url = '/admin/userfiles/move-files';
            return model.postData(url, data, result, fail);
        },
        removeFiles: function(data, result, fail) {
            var url = '/admin/userfiles/remove-files';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});