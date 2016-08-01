@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>应用列表</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="m-b">
                            <a href="javascript:;" id="btnCreateApp" class="btn btn-warning"> <i class="fa fa-plus"></i>
                                添加新应用
                            </a>
                        </div>
                        <div id="tblAppDataList">
                            <p class="ibox-loading-31"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tblAppReleaseList" class="col-lg-6"></div>
        </div>
    </div>

    <script id="tplAppDataList" type="text/html">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>名称</th>
                    <th>简介</th>
                    <th width="150">更新时间</th>
                </tr>
            </thead>
            <tbody>
                <%each rows as row i%>
                    <tr data-id="<%row.id%>">
                        <td><%row.id%></td>
                        <td><strong><%row.name%></strong></td>
                        <td><%row.intro%></td>
                        <td><%row.updated_at%></td>
                    </tr>
                <%/each%>
            </tbody>
        </table>
    </script>

    <script id="tplAppReleaseList" type="text/html">
        <div class="ibox">
            <div class="ibox-title">
                <h5>应用信息</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-2">
                        <p class="text-center"><img src="<%app.pic1%>" class="img-thumbnail img-responsive js-pic1 hand" title="点击更换图片" width="80" /></p>
                    </div>
                    <div class="col-lg-10">
                        <h2><%app.name%></h2>
                        <div><%#app.intro%></div>
                        <p class="m-t">
                            <a href="javascript:;" class="btn btn-primary btn-sm js-modify">
                                <i class="fa fa-pencil fa-lg"></i> 编辑
                            </a>
                            <a href="javascript:;" class="btn btn-danger btn-sm m-l js-delete">
                                <i class="fa fa-times fa-lg"></i> 删除
                            </a>
                        </p>
                    </div>
                </div>
                <hr />
                <div class="m-b">
                    <a id="btnAddRelease" href="javascript:;" class="btn btn-info"> <i class="fa fa-plus"></i>
                        新增版本
                    </a>
                </div>
                <div id="tblReleaseDataList"></div>
            </div>
        </div>
    </script>

    <script id="tplReleaseDataList" type="text/html">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>版本</th>
                    <th>新</th>
                    <th>源</th>
                    <th width="150">创建时间</th>
                    <th width="150">操作</th>
                </tr>
            </thead>
            <tbody>
                <%each rows as row i%>
                <tr data-id="<%row.id%>">
                    <td><%i+1%></td>
                    <td><%row.version%></td>
                    <td>
                        <%if row.is_top%>
                            <i class="fa fa-check text-navy"></i>
                        <%/if%>
                    </td>
                    <td>
                        <a href="javascript:;" class="js-file1" data-src="<%row.file1%>">
                            <%if row.file1%>
                            <i class="fa fa-file-archive-o fa-lg"></i>
                            <%else%>
                            <i class="fa fa-cloud-upload fa-lg"></i>
                            <%/if%>
                        </a>
                    </td>
                    <td><%row.created_at%></td>
                    <td>
                        <a href="javascript:;" class="m-r-xs js-edit">
                            <i class="fa fa-pencil-square fa-lg"></i>编辑
                        </a>
                        <a href="javascript:;" class="js-remove">
                            <i class="fa fa-times-circle fa-lg"></i>删除
                        </a>
                    </td>
                </tr>
                <%/each%>
            </tbody>
        </table>
    </script>

    <script id="tplCreateAppPanel" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">添加新应用</h4>
                </div>
                <div class="modal-body">
                    <form id="formCreate" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" required="" maxlength="50" autofocus />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用简介</label>
                            <div class="col-sm-10">
                                <div id="ueditor_intro" class="summernote"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本号</label>
                            <div class="col-sm-10">
                                <input type="text" name="version" class="form-control" required="" maxlength="30" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-primary btn-w-m" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script id="tplModifyAppPanel" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">编辑应用</h4>
                </div>
                <div class="modal-body">
                    <form id="formUpdate" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" value="<%app.name%>" class="form-control" required="" maxlength="50" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用简介</label>
                            <div class="col-sm-10">
                                <div id="ueditor_intro" class="summernote"><%#app.intro%></div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="id" value="<%app.id%>" />
                                <button class="btn btn-primary btn-w-m" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script id="tplAddReleasePanel" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">新增版本</h4>
                </div>
                <div class="modal-body">
                    <form id="formAdd" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本号</label>
                            <div class="col-sm-10">
                                <input type="text" name="version" value="" class="form-control" required="" autofocus maxlength="30" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">下载地址</label>
                            <div class="col-sm-10">
                                <input type="text" name="file1" value="" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox checkbox-success">
                                    <input type="checkbox" id="awesomeCheckbox01" name="is_top" value="1" checked="checked" />
                                    <label for="awesomeCheckbox01">
                                        最新版本
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="app_id" value="<%app_id%>" />
                                <button class="btn btn-primary btn-w-m" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script id="tplEditReleasePanel" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">编辑版本</h4>
                </div>
                <div class="modal-body">
                    <form id="formEdit" method="post" class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本号</label>
                            <div class="col-sm-10">
                                <input type="text" name="version" value="<%version%>" class="form-control" required="" maxlength="30" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">版本描述</label>
                            <div class="col-sm-10">
                                <textarea name="description" rows="5" class="form-control"><%#description%></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">下载地址</label>
                            <div class="col-sm-10">
                                <input type="text" name="file1" value="<%file1%>" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <div class="checkbox checkbox-success">
                                    <%if is_top%>
                                    <input type="checkbox" id="awesomeCheckbox02" name="is_top" value="1" checked="checked" />
                                    <%else%>
                                    <input type="checkbox" id="awesomeCheckbox02" name="is_top" value="1" />
                                    <%/if%>
                                    <label for="awesomeCheckbox02">
                                        最新版本
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="id" value="<%id%>" />
                                <button class="btn btn-primary btn-w-m" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script id="tplUploadPic1" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title">图片上传</h4>
                </div>
                <div class="modal-body">
                    <form id="formUppic1" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择图片</label>
                            <div class="col-sm-5">
                                <input name="pic1" type="file" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="hidden" name="id"  value="<%id%>">
                                <button class="btn btn-primary btn-w-m" type="submit"><i class="fa fa-cloud-upload fa-lg"></i> 上传图片</button>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">预览</label>
                            <div id="pictureViewer" class="col-sm-10">
                                <img src="<%src%>" class="img-thumbnail" style="min-width: 80px;" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>

    <script id="tplUploadFile1" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">程序上传</h4>
                </div>
                <div class="modal-body">
                    <form id="formUpfile1" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">选择文件</label>
                            <div class="col-sm-10">
                                <input name="file1" type="file" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-10" id="txtFile1"><%src%></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <input type="hidden" name="id"  value="<%id%>" />
                                <button type="submit" class="btn btn-primary btn-w-m"><i class="fa fa-cloud-upload"></i> 上传附件</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </script>
@endsection

@section('pageheader')
    <link href="{{cdn1('assets/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/bootstrap/css/awesome-bootstrap-checkbox.css')}}" rel="stylesheet">
@endsection

@section('pagescript')
    <script src="{{cdn1('assets/summernote/summernote.min.js')}}"></script>
    <script>
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("li[rel='apps']").addClass("active");

        var sendFile = function (file, editor, welEditable) {
            var data = new FormData();
            data.append("upfile", file, file.name || ('blob.' + file.type.substr('image/'.length)));
            $.ajax({
                data: data,
                type: "POST",
                dataType: "json",
                url: "{{url('ueditor?action=uploadimage')}}",
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    editor.insertImage(welEditable, data.url);
                }
            });
        };

        // 加载 Summernote 编辑器
        var loadSummernote = function(editor){
            editor.summernote({
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            });
        };

        seajs.use('models/appModel', function(appModel) {
            var curAppId = 0;
            var renderAppList = function() {
                $("#tblAppDataList").html('<p class="ibox-loading-31"></p>');
                appModel.getList(null, function(data) {
                    for(var i in data.rows){
                        data.rows[i].intro = $('<div>'+data.rows[i].intro+'</div>').text();
                    }
                    $("#tblAppDataList").html(template('tplAppDataList', data));
                }, failure);
            };
            renderAppList();

            var renderAppDetails = function(){
                $("#tblAppReleaseList").html('<p class="ibox-loading-31"></p>');
                appModel.getAppInfo({"id":curAppId}, function(data){
                    $("#tblAppReleaseList").html(template("tplAppReleaseList", data));
                    renderAppReleaseList();
                });
            };

            var renderAppReleaseList = function(){
                $("#tblReleaseDataList").html('<p class="ibox-loading-31"></p>');
                appModel.getReleases({"id":curAppId}, function(data){
                    $("#tblReleaseDataList").html(template("tplReleaseDataList", data));
                });
            };

            $("#tblAppDataList").delegate("tr", "click", function(){
                var app_id = $(this).data("id");
                if(curAppId == app_id) return;
                curAppId = app_id;
                renderAppDetails();
            });

            $("#btnCreateApp").on("click", function(){
                $("#modalDialog").html(template("tplCreateAppPanel", null)).modal("show");
                loadSummernote($('#ueditor_intro'));
            });

            $("#modalDialog").delegate("#formCreate", "submit", function(){
                var form = this, formdata = new FormData();
                formdata.append("intro", $("#ueditor_intro").code());
                $.each($(form).serializeArray(), function(i, item) {
                    formdata.append(item.name, item.value);
                });
                appModel.insert(formdata, function(){
                    $("#modalDialog").html("").modal("hide");
                    renderAppList();
                }, failure);
                return false;
            });

            $("#tblAppReleaseList").delegate(".js-modify", "click", function(){
                if(!curAppId) return;
                appModel.getAppInfo({"id":curAppId}, function(data){
                    $("#modalDialog").html(template("tplModifyAppPanel", data)).modal("show");
                    loadSummernote($('#ueditor_intro'));
                });
            });

            $("#modalDialog").delegate("#formUpdate", "submit", function(){
                var form = this, formdata = new FormData();
                formdata.append("intro", $("#ueditor_intro").code());
                $.each($(form).serializeArray(), function(i, item) {
                    formdata.append(item.name, item.value);
                });
                appModel.update(formdata, function(){
                    $("#modalDialog").html("").modal("hide");
                    renderAppList();
                    renderAppDetails();
                }, failure);
                return false;
            });

            $("#tblAppReleaseList").delegate("#btnAddRelease", "click", function(){
                $("#modalDialog").html(template("tplAddReleasePanel", {"app_id":curAppId})).modal("show");
            });

            $("#modalDialog").delegate("#formAdd", "submit", function(){
                var formdata = $(this).serialize();
                appModel.addRelease(formdata, function(){
                    $("#modalDialog").html('').modal("hide");
                    renderAppReleaseList();
                }, failure);
                return false;
            });

            $("#tblAppReleaseList").delegate(".js-edit", "click", function(){
                var id = $(this).parents("tr").eq(0).data("id");
                appModel.getRelease({"id":id}, function(data){
                    $("#modalDialog").html(template("tplEditReleasePanel", data)).modal("show");
                }, failure);
            });

            $("#modalDialog").delegate("#formEdit", "submit", function(){
                var formdata = $(this).serialize();
                appModel.editRelease(formdata, function(){
                    $("#modalDialog").html('').modal("hide");
                    renderAppReleaseList();
                }, failure);
                return false;
            });

            $("#tblAppReleaseList").delegate(".js-remove", "click", function(e){
                var id = $(this).parents("tr").eq(0).data("id");
                dialog({
                    content:'<i class="fa fa-info-circle"></i> 确定要删除此版本吗？',
                    ok:function(){
                        appModel.removeRelease({'id':id}, function(){
                            $(e.target).parents("tr").remove();
                        }, failure);
                    },
                    cancel: true
                }).showModal();
            });

            $("#tblAppReleaseList").delegate(".js-delete", "click", function(){
                dialog({
                    content:'<i class="fa fa-info-circle"></i> 确定要删除此应用吗？',
                    ok:function(){
                        appModel.delete({'id':curAppId}, function(){
                            $("#tblAppReleaseList").html("");
                            $("#tblAppDataList tr[data-id='"+curAppId+"']").remove();
                            curAppId = 0;
                        }, failure);
                    },
                    cancel: true
                }).showModal();
            });

            $("#tblAppReleaseList").delegate('.js-pic1', 'click', function(){
                var data = {
                    'id': curAppId,
                    'src': $(this).attr("src")
                };
                $("#modalDialog").html(template("tplUploadPic1", data)).modal('show');
            });

            $("#modalDialog").delegate("#formUppic1", 'submit', function(){
                var id = this.id.value,
                    file = this.pic1.value;
                if (file == ""){
                    alert("请选择上传文件！");
                    return false;
                }
                if (file.lastIndexOf(".") == -1){
                    alert("文件类型不正确！");
                    return false;
                }
                var ext = file.substr(file.lastIndexOf(".") + 1).toLowerCase();
                if (ext != 'jpg' && ext != 'jpeg' && ext != 'gif' && ext != 'png'){
                    alert('图片必须是 JPG、JPEG、GIF 或者 PNG 格式！');
                    return false;
                }
                var data = new FormData();
                data.append('id', this.id.value);
                data.append('pic1', this.pic1.files[0]);
                appModel.uploadPic1(data, function(result){
                    $("#pictureViewer img").eq(0).attr("src", result);
                    $(".js-pic1").attr("src", result);
                }, failure);
                return false;
            });

            $("#tblAppReleaseList").delegate('.js-file1', 'click', function(){
                var data = {
                    'id': $(this).parents('tr').eq(0).data("id"),
                    'src': $(this).data("src")
                };
                $("#modalDialog").html(template("tplUploadFile1", data)).modal("show");
            });

            $("#modalDialog").delegate("#formUpfile1", 'submit', function(){
                var id = this.id.value,
                    file = this.file1.value;
                if (file == ""){
                    alert("请选择上传文件！");
                    return false;
                }
                var data = new FormData();
                data.append('id', id);
                data.append('file', this.file1.files[0]);
                appModel.uploadFile1(data, function(result){
                    $("#txtFile1").html(result);
                    var tr = $("#tblReleaseDataList").find("tr[data-id='"+ id +"']").eq(0),
                        ta = tr.find(".js-file1").eq(0);
                    ta.data("src", result);
                    ta.find("i").removeClass("fa-cloud-upload").addClass("fa-file-archive-o");
                }, failure);
                return false;
            });
        });
    </script>
@endsection
