@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="m-b">
        <a href="javascript:;" onclick="history.back()" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回列表</a>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>新建权限</h5>
        </div>
        <div class="ibox-content">
            <form id="formPermAdd" method="post" action="" class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-2 control-label">权限码</label>
                    <div class="col-sm-4">
                        <input type="text" name="name" class="form-control" required="" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">权限名</label>
                    <div class="col-sm-6">
                        <input type="text" name="display_name" class="form-control" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-6">
                        <textarea name="description" rows="5" class="form-control" required=""></textarea>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
    $(function () {
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='permission']").addClass("active");
    });

    seajs.use('models/entrustModel', function(entrustModel) {
        $("#formPermAdd").on("submit", function(){
            entrustModel.createPerm($(this).serialize(), function(){
                window.location.href = document.referrer;
            }, failure);
            return false;
        });
    });
</script>
@endsection
