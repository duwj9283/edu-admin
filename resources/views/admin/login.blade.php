@extends('admin.layout')

@section('title', 'Administrator\'s Login')

@section('pagebody')
<div class="loginColumns animated fadeInDown">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h2 class="text-center">{{$title}}</h2>
            <div class="ibox-content">
                <form id="formLogin" class="m-t" role="form" method="post" action="">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="用户名/邮箱/手机号" required="" autofocus="" />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="请输入密码" required="" />
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-7">
                            <input type="text" name="captcha" class="form-control" placeholder="验证码" maxlength="4" autocomplete="off" required="" />
                        </div>
                        <div class="col-sm-5">
                            <a href="javascript:;" onclick="changeCaptchaImg()" title="点击更换">
                                <img id="captchaImg" src="{{captcha_src()}}" />
                            </a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info block full-width m-b">登录系统</button>
                    <div style="display:none;">
                        <p class="text-right">
                            <a href="{{url('admin/forgot')}}">
                                <small>忘记密码？</small>
                            </a>
                        </p>
                        <p class="text-muted">
                            <small>没有帐号？</small>
                        </p>
                        <a class="btn btn-sm btn-default btn-block" href="{{url('admin/register')}}">注册新用户</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<footer>
    <div class="text-center">
        <p>安徽乐行云享信息科技有限公司 &copy; 2016</p>
    </div>
</footer>
@endsection

@section('pageheader')
    <style>
        body {background-color: #f3f3f4;}
        footer{border-top:1px solid #d3d3d3;padding-top:15px;margin-top:15px;}
        footer .row{max-width:800px;margin:0 auto;}
    </style>
@endsection

@section('pagescript')
    <script>
        seajs.use('models/adminModel', function(adminModel) {
            $("#formLogin").on("submit", function(){
                var formdata = $(this).serialize();
                adminModel.login(formdata, function(result){
                    window.location.reload();
                }, function(result){
                    changeCaptchaImg();
                    failure(result);
                });
                return false;
            });
        });

        var changeCaptchaImg = function(){
            $("#captchaImg").attr('src', '{{captcha_src()}}' + Math.random());
        };

        $(function(){
            var supportPlaceholder = 'placeholder' in document.createElement('input');
            if (!supportPlaceholder) {
                $(".form-group input").each(function() {
                    var that = this, txtPlaceholder = $(this).attr("placeholder");
                    var tt = $("<tt></tt>").text(txtPlaceholder);
                    tt.css({
                        "position":"absolute",
                        "left":"14px",
                        "top":"0",
                        "line-height":"34px",
                        "color": "gray",
                    }).on("click", function(){
                        $(that).focus();
                    });
                    $(this).parent().css("position", "relative");
                    $(this).after(tt);
                    $(this).on("change keyup", function(){
                        this.value == this.defaultValue ? tt.show() : tt.hide();
                    });
                });
            }
        });
    </script>
@endsection
