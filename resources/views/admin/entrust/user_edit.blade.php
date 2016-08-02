@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="m-b">
        <a href="javascript:;" onclick="history.back()" class="btn btn-default"><i class="fa fa-arrow-left"></i> 返回列表</a>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>编辑用户</h5>
        </div>
        <div class="ibox-content">
            <form id="formEditUser" method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="col-md-2 control-label">帐户</label>
                    <div class="col-md-3">
                        <p class="form-control-static">{{$username}}</p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">姓名</label>
                    <div class="col-md-3">
                        <input type="text" name="realname" value="{{$realname}}" class="form-control" required="" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">邮箱</label>
                    <div class="col-md-3">
                        <input type="email" name="email" value="{{$email}}" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">手机</label>
                    <div class="col-md-3">
                        <input type="text" name="mobile" value="{{$mobile}}" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">状态</label>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="1"{{ $status == 1 ? ' selected' : '' }}>启用</option>
                            <option value="0"{{ $status == 0 ? ' selected' : '' }}>禁用</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">学科</label>
                    <div class="col-sm-8">
                        @foreach($subjectArr as $sub)
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="subject[]" value="{{$sub->id}}"  @if(strstr($subject,(string)($sub->id))) checked @endif >{{strstr($subject,$sub->id)}}{{$sub->subject_name}}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-md-4 col-md-offset-2">
                        <input type="hidden" name="id" value="{{$id}}">
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

    <script>
    $(function () {
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='user']").addClass("active");

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

    seajs.use('models/entrustModel', function(entrustModel) {
        $("#formEditUser").on("submit", function(){
            entrustModel.updateUser($(this).serialize(), function(){
                window.location.href = document.referrer;
            }, failure);
            return false;
        });
    });
</script>
@endsection
