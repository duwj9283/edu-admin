@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>用户列表</h5>
        </div>
        <div class="ibox-content">
            <div class="m-b">
                <a href="{{url('admin/entrust/user-add')}}" class="btn btn-warning"> <i class="fa fa-plus"></i>
                    新建用户
                </a>
                <a href="javascript:;" id="btnImport" class="btn btn-info m-l"> <i class="fa fa-file-excel-o"></i>
                    导入用户
                </a>
            </div>
            <div id="tblDataList">
                <p class="ibox-loading-31"></p>
            </div>
        </div>
    </div>
</div>
<script id="tplDataList" type="text/html">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>用户名</th>
                <th>姓名</th>
                <th>邮箱</th>
                <th>手机</th>
                <th>状态</th>
                <th>创建时间</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <%each data as row i%>
            <tr data-id="<%row.id%>">
                <td><%row.id%></td>
                <td><%row.username%></td>
                <td><%row.realname%></td>
                <td><%row.email%></td>
                <td><%row.mobile%></td>
                <td>
                    <%if row.status==1 %>
                        <i class="fa fa-check-circle fa-lg text-success"></i>
                    <%else%>
                        <i class="fa fa-times-circle fa-lg text-danger"></i>
                    <%/if%>
                </td>
                <td><%row.created_at%></td>
                <td>
                    <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            操作
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="{{url('admin/entrust/user-edit')}}?id=<%row.id%>">
                                    <i class="fa fa-pencil-square fa-lg"></i>
                                    编辑信息
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;" class="js-resetpwd">
                                    <i class="fa fa-key fa-lg"></i>
                                    重置密码
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:;" class="js-del">
                                    <i class="fa fa-times-circle fa-lg"></i>
                                    删除
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <%/each%>
        </tbody>
    </table>
    <div class="text-center"><%#page_str%></div>
</script>

<script id="tplUploadFile" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">导入用户</h4>
            </div>
            <div class="modal-body">
                <form id="formUpfile" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">模板文件</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">
                                <a href="{{asset('upload/users_template.xls')}}" target="_blank">[点击下载]</a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">选择文件</label>
                        <div class="col-sm-9">
                            <input name="file1" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <button type="submit" class="btn btn-primary btn-w-m"> <i class="fa fa-upload"></i>
                                立即导入
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>

<script id="tplResetPassword" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">重置密码</h4>
            </div>
            <div class="modal-body">
                <form id="formResetPassword" method="post">
                    <div class="form-group">
                        <label class="control-label">新密码</label>
                        <input type="password" name="password" class="form-control" required="" autofocus="" />
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <input type="hidden" name="id" value="<%id%>" />
                        <button class="btn btn-primary btn-w-m" type="submit">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>

@endsection

@section('pagescript')
<script src="{{asset('assets/swfobject/swfobject.js')}}"></script>
<script>
    $("#side-menu li[rel='entrust']").addClass("active")
        .find("ul").addClass("in")
        .find("li[rel='user']").addClass("active");

    seajs.use('models/entrustModel', function(entrustModel) {
        var artDialog;
        var filter = {limit: 12, page: 1, keyword: ''};
        var renderList = function() {
            entrustModel.getUserPageRows(filter, function(data) {
                data.page_str = page(data.last_page, data.total, data.current_page);
                $("#tblDataList").html(template('tplDataList', data));
            }, failure);
        };
        renderList();

        $("#tblDataList").delegate(".pagination a", "click", function() {
            filter.page = $(this).attr("rel");
            renderList();
        });

        $("#btnImport").on("click", function(){
            $("#modalDialog").html(template("tplUploadFile", null)).modal("show");
        });

        $("#modalDialog").delegate("#formUpfile", 'submit', function(){
            var file = this.file1.value;
            if (file == ""){
                alert("请选择上传文件！");
                return false;
            }
            if (file.lastIndexOf(".") == -1){
                alert("文件类型不正确！");
                return false;
            }
            var ext = file.substr(file.lastIndexOf(".") + 1).toLowerCase();
            if (ext != 'xls' && ext != 'xlsx'){
                alert('无效的文件类型！');
                return false;
            }
            var data = new FormData();
            data.append('file1', this.file1.files[0]);
            entrustModel.importUser(data, function(){
                $("#modalDialog").html("").modal('hide');
                renderList();
            }, failure);
            return false;
        });

        $("#tblDataList").delegate(".js-resetpwd", "click", function(){
            var id = $(this).parents('tr').eq(0).data('id');
            $("#modalDialog").html(template("tplResetPassword", {"id":id})).modal("show");
        });

        $("#modalDialog").delegate("#formResetPassword", "submit", function(){
            entrustModel.updateUser($(this).serialize(), function(data){
                $("#modalDialog").html("").modal('hide');
                artInfo('<i class="fa fa-check-circle"></i> 密码修改成功');
            }, failure);
            return false;
        });

        $("#tblDataList").delegate('.js-del', 'click', function(e) {
            var id = $(this).parents('tr').eq(0).data('id');
            dialog({
                content: '<i class="fa fa-info-circle"></i> 确定要删除此用户吗？',
                ok: function() {
                    entrustModel.deleteUser({'id': id}, function() {
                        $(e.target).parents("tr").remove();
                    }, failure);
                },
                cancel: true
            }).showModal();
        });
    });
</script>
@endsection
