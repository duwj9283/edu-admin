define(function(require, exports, module) {
    var model = require('models/baseModel');

    var facade = {
        setProfile: function(data, result, fail) {
            var url = '/api/user/set-profile';
            return model.postData(url, data, result, fail);
        },
        changePwd: function(data, result, fail) {
            var url = '/api/user/changepwd';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});