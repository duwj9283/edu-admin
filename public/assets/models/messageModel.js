define(function(require, exports, module) {
    var model = require('models/baseModel');
    var facade = {
        getDetails: function(data, result, fail) {
            var url = '/admin/message/details';
            return model.getData(url, data, result, fail);
        },
        saveDrafts: function(data, result, fail) {
            var url = '/admin/message/save-drafts';
            return model.uploadFile(url, data, result, fail);
        },
        sendMsg: function(data, result, fail) {
            var url = '/admin/message/send';
            return model.uploadFile(url, data, result, fail);
        },
        inboxToTrash: function(data, result, fail) {
            var url = '/admin/message/inbox-to-trash';
            return model.postData(url, data, result, fail);
        },
        outboxToTrash: function(data, result, fail) {
            var url = '/admin/message/outbox-to-trash';
            return model.postData(url, data, result, fail);
        },
        deleteDrafts: function(data, result, fail) {
            var url = '/admin/message/delete-drafts';
            return model.postData(url, data, result, fail);
        },
        deleteFile1: function(data, result, fail) {
            var url = '/admin/message/delete-file1';
            return model.postData(url, data, result, fail);
        },
        delete: function(data, result, fail) {
            var url = '/admin/message/delete';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});