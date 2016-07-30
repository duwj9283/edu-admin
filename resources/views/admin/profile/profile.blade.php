@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>个人资料</h5>
        </div>
        <div class="ibox-content">
            <form id="formProfile" method="post" class="form-horizontal">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">帐户</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">{{$username}}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">姓名</label>
                            <div class="col-sm-6">
                                <input type="text" name="realname" value="{{$realname}}" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">邮箱</label>
                            <div class="col-sm-8">
                                <input type="email" name="email" value="{{$email}}" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">手机</label>
                            <div class="col-sm-6">
                                <input type="text" name="mobile" value="{{$mobile}}" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-md-offset-1">
                        <a href="javascript:;" id="btnChanageAvatar" title="点击修改头像">
                            <img src="{{$avatar}}" class="img-thumbnail" />
                        </a>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-2">
                        <button class="btn btn-warning btn-w-m" type="submit">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
<script src="{{cdn1('assets/swfobject/swfobject.js')}}"></script>
<script src="{{cdn1('assets/controllers/stringExt.js')}}"></script>
<script>
    var artDialog;
    var uploadAvatar = function(data){
        if(data.status == "1"){
            var timenow = Math.ceil(new Date().getTime() / 1000);
            var src = data.url+'?'+timenow;
            $("#btnChanageAvatar").find('img').attr('src', src);
            artDialog.close().remove();
        }else{
            alert(data.info);
        }
    };

    seajs.use('models/userModel', function(userModel) {
        $("#side-menu li[rel='profile']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='1']").addClass("active");

        var loadFaustCplus = function(){
            var img_src = $("#btnChanageAvatar").find("img").eq(0).attr('src');
            var xiSwfUrlStr = "{{asset('assets/swfobject/expressInstall.swf')}}";
            var flashvars = {
                "jsfunc": "uploadAvatar",
                "imgUrl": img_src,
                "uploadSrc": true,
                "showBrow": true,
                "showCame": true,
                "pSize": "300|300|180|180|80|80",
                "uploadUrl": "{{url('api/user/upload-avatar/'.$id)}}"
            };
            var params = {
                menu: "false",
                scale: "noScale",
                allowFullscreen: "true",
                allowScriptAccess: "always",
                wmode:"transparent",
                bgcolor: "#FFFFFF"
            };
            var attributes = {'id':"FaustCplus"};
            swfobject.embedSWF("{{asset('assets/iemaker/FaustCplus.swf')}}", "flashContent", "650", "450", "10.1.0", xiSwfUrlStr, flashvars, params, attributes);
            swfobject.createCSS("#flashContent", "display:block;text-align:left;");
        };

        $("#btnChanageAvatar").on('click', function(){
          artDialog = dialog({
              title: '编辑头像',
              content: '<div id="flashContent"></div>',
              width: '650px',
              height: '450px',
              onshow: function(){
                  setTimeout(function(){
                      loadFaustCplus();
                  }, 500);
              }
          }).showModal();
        });

        $("#formProfile").on("submit", function(){
            var formdata = $(this).serialize();
            userModel.setProfile(formdata, function(data){
                artInfo('<i class="fa fa-check-circle"></i>修改成功！');
            }, failure);
            return false;
        });
    });
</script>
@endsection
