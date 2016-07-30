define(function(require, exports, module) {
    var model = require('models/baseModel');

    var facade = {
        login: function(data, result, fail) {
            var url = '/api/account/login';
            return model.postData(url, data, result, fail);
        },
        logout: function(data, result, fail) {
            var url = '/api/account/logout';
            return model.postData(url, data, result, fail);
        },
        sendCode: function(data, result, fail) {
            var url = '/api/account/reg-step1';
            return model.postData(url, data, result, fail);
        },
        register: function(data, result, fail) {
            var url = '/api/account/register';
            return model.postData(url, data, result, fail);
        },
        forget: function(data, result, fail) {
            var url = '/api/account/forget';
            return model.postData(url, data, result, fail);
        },
        findpwd: function(data, result, fail) {
            var url = '/api/account/findpwd';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});