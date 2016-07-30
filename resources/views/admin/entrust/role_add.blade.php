@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="m-b">
            <a href="javascript:;" onclick="history.back()" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回列表</a>
        </div>
        <div class="ibox">
            <div class="ibox-title">
                <h5>新增角色</h5>
            </div>
            <div class="ibox-content">
                <form id="formAddRole" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-4">
                            <input type="text" name="name" class="form-control" required="" autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">全名</label>
                        <div class="col-sm-6">
                            <input type="text" name="display_name" class="form-control" required="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">描述</label>
                        <div class="col-sm-9">
                            <input type="text" name="description" class="form-control" required="" />
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
@endsection

@section('pagescript')
    <script>
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='role']").addClass("active");

        seajs.use('models/entrustModel', function(entrustModel) {
            $("#formAddRole").on("submit", function(){
                entrustModel.createRole($(this).serialize(), function(){
                    window.location.href = document.referrer;
                }, failure);
                return false;
            });
        });
    </script>
@endsection
