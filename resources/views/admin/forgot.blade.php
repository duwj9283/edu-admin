@extends('admin.layout')

@section('title', '找回登录密码')

@section('pagebody')
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <h2>找回登录密码</h2>
        <form id="formForgot" class="m-t" role="form" action="" onsubmit="return false;">
            <div class="form-group">
                <input type="text" id="txtUsername" name="username" class="form-control" placeholder="请输入注册邮箱/手机号" required="" autofocus="" />
                <div class="js-tips text-left m-t-xs">
                    <p>请输入您的注册邮箱/手机号，以便找回密码。</p>
                </div>
            </div>
            <div class="form-group">
                <div id="simple-slider" class="scale dragdealer">
                    <div class="scale-in">
                        <span class="scale-bar handle"></span>
                    </div>
                    <div class="scale-text">
                        <span class="scale-textin">请按住滑块，拖动到最右边</span>
                    </div>
                </div>
            </div>
            <div id="captchaTr" style="display:none;">
                <p class="text-left">请查收您的注册邮箱/手机短信，输入验证码</p>
                <div class="form-group">
                    <input type="text" name="code" class="form-control" placeholder="请输入验证码" />
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="设置您的新密码" />
                </div>
                <div class="form-group">
                    <input type="password" name="password_confirm" class="form-control" placeholder="请再次输入您的新密码" />
                </div>
            </div>
            <button type="submit" class="btn btn-warning block full-width m-b">提交</button>
            <p class="clearfix">
                想起密码了？
                <a href="{{url('admin')}}">
                    <small>点此登录</small>
                </a>
            </p>
        </form>
    </div>
@endsection

@section('pageheader')
    <link href="{{cdn1('assets/dragdealer/dragdealer.css')}}" rel="stylesheet" />
    <style>
        body {background-color: #f3f3f4;}
    </style>
@endsection

@section('pagescript')
    <script src="{{cdn1('assets/dragdealer/dragdealer.js')}}"></script>
    <script src="{{cdn1('assets/controllers/stringExt.js')}}"></script>
    <script>
        seajs.use('models/adminModel', function(adminModel) {
            var slider_valid = 0;
            new Dragdealer('simple-slider', {
                animationCallback: function(x, y) {
                    var percent = Math.round(x * 100);
                    $(".scale-in").css("width", percent+"%");
                },
                dragStopCallback: function(x, y){
                    var that = this, str = $("#txtUsername").val();
                    if(str.length < 1){
                        this.setValue(0, 0);
                        return false;
                    }
                    slider_valid = Math.floor(x);
                    if (slider_valid) {
                        adminModel.forget({"name":str}, function(data){
                            that.disable();
                            $("#txtUsername").prop("readonly", true);
                            $(".scale-textin").addClass("textin-none").text("验证通过");
                            $("#captchaTr").fadeIn();
                        }, function(data){
                            that.setValue(0, 0);
                            slider_valid = 0;
                            $(".js-tips").html('<p class="text-danger">'+data.responseJSON+'</p>');
                        });
                    }
                    return false;
                }
            });

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

            $("#formForgot").on("submit", function(){
                var that = this;
                if(!slider_valid){
                    failure('请按住滑块，拖动到最右边');
                    return false;
                }
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
                adminModel.findpwd($(this).serialize(), function(data){
                    dialog({
                        content: '<i class="fa fa-check-circle"></i> 找回密码操作成功，请使用请密码登录系统！',
                        ok: function(){
                            window.location.href = "{{url('admin')}}";
                        }
                    }).showModal();
                }, function(result){
                    $(that).find(":submit").removeAttr("disabled");
                    changeCaptchaImg();
                    failure(result);
                });
                return false;
            });
        });
    </script>
@endsection
