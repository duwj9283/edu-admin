@extends('admin.layout')

@section('title', '注册新用户')

@section('pagebody')
    <div class="middle-box text-center animated fadeInDown">
        <h2>注册新用户</h2>
        <form id="formRegister" class="form-horizontal m-t" role="form" action="" onsubmit="return false;">
            <div class="form-group">
                <div class="col-md-12">
                    <input type="text" id="txtUsername" name="username" class="form-control" placeholder="请输入注册邮箱/手机号" required="" autofocus="" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" name="code" class="form-control" placeholder="请输入验证码" />
                        <span class="input-group-btn">
                            <button type="button" id="btnGetCode" class="btn btn-white">获取验证码</button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="password" name="password" class="form-control" placeholder="设置您的登录密码" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <input type="password" name="password_confirm" class="form-control" placeholder="请再次输入您的登录密码" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success block full-width m-b">注册</button>
                </div>
            </div>
            <p class="clearfix">
                已经注册过用户了？返回
                <a href="{{url('admin')}}">
                    <small>直接登录</small>
                </a>
            </p>
        </form>
    </div>
@endsection

@section('pageheader')
    <style>
        body {background-color: #f3f3f4;}
    </style>
@endsection

@section('pagescript')
    <script src="{{cdn1('assets/controllers/stringExt.js')}}"></script>
    <script>
        seajs.use('models/adminModel', function(adminModel) {
            $("#btnGetCode").on("click", function(){
                var obj = $("#txtUsername"), str = obj.val();
                if(str.length < 1){
                    obj.focus();
                    return false;
                }
                adminModel.sendCode({"name":str}, function(){
                    obj.prop("readonly", true);
                    artInfo('请查收您的注册邮箱/手机短信，输入验证码');
                }, failure);
            });

            $("#formRegister").on("submit", function(){
                var that = this;
                if(required_check(this.code.value) == false){
                    this.code.focus();
                    return false;
                }
                if(required_check(this.password.value) == false){
                    this.password.focus();
                    return false;
                }
                if(this.password.value != this.password_confirm.value){
                    this.password_confirm.focus();
                    return false;
                }
                $(this).find(':submit').prop('disabled', true);
                adminModel.register($(this).serialize(), function(data){
                    window.location.href = "{{url('admin')}}";
                }, function(result){
                    $(that).find(":submit").removeAttr("disabled");
                    artInfo(result);
                });
                return false;
            });
        });
    </script>
@endsection
