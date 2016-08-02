@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="m-b">
        <a href="javascript:;" onclick="history.back()" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回列表</a>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>新建用户</h5>
        </div>
        <div class="ibox-content">
            <form id="formAddUser" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">帐户</label>
                    <div class="col-sm-4">
                        <input type="text" name="username" class="form-control" required="" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">密码</label>
                    <div class="col-sm-4">
                        <input type="password" name="password" class="form-control" required="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">确认密码</label>
                    <div class="col-sm-4">
                        <input type="password" name="repassword" class="form-control" required="">
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">姓名</label>
                    <div class="col-sm-4">
                        <input type="text" name="realname" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-4">
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">手机</label>
                    <div class="col-sm-4">
                        <input type="text" name="mobile" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">学科</label>
                    <div class="col-sm-8">
                        @foreach($subject as $sub)
                        <label class="checkbox-inline i-checks"> <input type="checkbox" name="subject[]" value="{{$sub->id}}">{{$sub->subject_name}}</label>
                        @endforeach
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

@section('pageheader')
<link href="{{cdn1('assets/iCheck/custom.css')}}" rel="stylesheet">
@endsection

@section('pagescript')
<script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>
<script src="{{cdn1('assets/models/userModel.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='user']").addClass("active");

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    seajs.use('models/entrustModel', function(entrustModel) {
        $("#formAddUser").on("submit", function(){
            if(this.repassword.value != this.password.value){
                this.repassword.focus();
                return false;
            }
            entrustModel.insertUser($(this).serialize(), function(){
                window.location.href = document.referrer;
            }, failure);
            return false;
        });
    });
</script>
@endsection
