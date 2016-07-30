@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="m-b">
            <a href="javascript:;" onclick="history.back()" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回列表</a>
        </div>
        <div class="ibox">
            <div class="ibox-title">
                <h5>编辑角色</h5>
            </div>
            <div class="ibox-content">
                <form id="formEditRole" method="post" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称</label>
                        <div class="col-sm-4">
                            <p class="form-control-static">{{$name}}</p>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">全名</label>
                        <div class="col-sm-6">
                            <input type="text" name="display_name" value="{{$display_name}}" class="form-control" required="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">描述</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" value="{{$description}}" class="form-control" required="">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <input type="hidden" name="id" value="{{$id}}">
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
            $("#formEditRole").on("submit", function(){
                entrustModel.updateRole($(this).serialize(), function(){
                    window.location.href = document.referrer;
                }, failure);
                return false;
            });
        });
    </script>
@endsection
