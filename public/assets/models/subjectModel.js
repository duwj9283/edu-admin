define(function(require, exports, module) {
    var model = require('models/baseModel');

    var facade = {
        importSubject: function(data, result, fail){
            var url = '/api/subject/subject-import';
            return model.uploadFile(url, data, result, fail);
        }
    };
    module.exports = facade;
});