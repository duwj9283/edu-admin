define(function(require, exports, module) {
    var model = require('models/baseModel');

    var facade = {
        getUserPageRows: function(data, result, fail) {
            var url = '/api/entrust/user-page-rows';
            return model.getData(url, data, result, fail);
        },
        importUser: function(data, result, fail){
            var url = '/api/entrust/user-import';
            return model.uploadFile(url, data, result, fail);
        },
        insertUser: function(data, result, fail){
            var url = '/api/entrust/user-insert';
            return model.postData(url, data, result, fail);
        },
        updateUser: function(data, result, fail){
            var url = '/api/entrust/user-update';
            return model.postData(url, data, result, fail);
        },
        deleteUser: function(data, result, fail){
            var url = '/api/entrust/user-delete';
            return model.postData(url, data, result, fail);
        },
        createRole: function(data, result, fail) {
            var url = '/api/entrust/role-insert';
            return model.postData(url, data, result, fail);
        },
        updateRole: function(data, result, fail) {
            var url = '/api/entrust/role-update';
            return model.postData(url, data, result, fail);
        },
        deleteRole: function(data, result, fail) {
            var url = '/api/entrust/role-delete';
            return model.postData(url, data, result, fail);
        },
        createPerm: function(data, result, fail) {
            var url = '/api/entrust/perm-insert';
            return model.postData(url, data, result, fail);
        },
        updatePerm: function(data, result, fail) {
            var url = '/api/entrust/perm-update';
            return model.postData(url, data, result, fail);
        },
        deletePerm: function(data, result, fail) {
            var url = '/api/entrust/perm-delete';
            return model.postData(url, data, result, fail);
        },
        getMembers: function(data, result, fail) {
            var url = '/api/entrust/member-rows';
            return model.getData(url, data, result, fail);
        },
        getOuterMembers: function(data, result, fail) {
            var url = '/api/entrust/outer-member-rows';
            return model.getData(url, data, result, fail);
        },
        getMemberInfo: function(data, result, fail) {
            var url = '/api/entrust/member-info';
            return model.getData(url, data, result, fail);
        },
        addMember: function(data, result, fail) {
            var url = '/api/entrust/add-member';
            return model.postData(url, data, result, fail);
        },
        removeMember: function(data, result, fail) {
            var url = '/api/entrust/remove-member';
            return model.postData(url, data, result, fail);
        },
        grantPerm: function(data, result, fail) {
            var url = '/api/entrust/grant-perm';
            return model.postData(url, data, result, fail);
        },
        removePerm: function(data, result, fail) {
            var url = '/api/entrust/remove-perm';
            return model.postData(url, data, result, fail);
        },
        updateUsersRole: function (data, result, fail) {
            var url = '/api/entrust/add-member-role';
            return model.postData(url, data, result, fail);
        }
    };
    module.exports = facade;
});