@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox">
            <div class="ibox-title">
                <h5>修改密码</h5>
            </div>
            <div class="ibox-content">
                <form id="frmChangePwd" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">当前密码：</label>
                        <div class="col-sm-6">
                            <input type="password" name="curpass" value="" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">新 密 码：</label>
                        <div class="col-sm-6">
                            <input type="password" name="newpass" value="" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">确认密码：</label>
                        <div class="col-sm-6">
                            <input type="password" name="repass" value="" class="form-control" required />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-warning btn-w-m" type="submit">修改密码</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<script src="{{asset('assets/models/userModel.js')}}"></script>
<script>
    $("#side-menu li[rel='profile']").addClass("active")
        .find("ul").addClass("in")
        .find("li[rel='2']").addClass("active");

    seajs.use('models/userModel', function(userModel) {
        $("#frmChangePwd").on("submit", function(){
            event.preventDefault();
            var that = this;
            if (this.repass.value != this.newpass.value){
                artInfo('<i class="fa fa-info-circle"></i>确认密码有误，请重新输入！');
                this.repass.focus();
                return false;
            }
            var formdata = $(this).serializeArray();
            userModel.changePwd(formdata, function(data){
                artInfo('<i class="fa fa-check-circle"></i>密码修改成功！');
                that.reset();
            }, failure);
            return false;
        });
    });

</script>
@endsection
