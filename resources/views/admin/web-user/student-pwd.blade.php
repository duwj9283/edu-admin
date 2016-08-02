@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="m-b">
            <a href="{{url('admin/webuser/student')}}" class="btn btn-default"> <i class="fa fa-arrow-left"></i>
                返回列表
            </a>
        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>修改密码</h5>
            </div>
            <div class="ibox-content">

                <div class="row">

                    <form method="post" class="form-horizontal webUser_add" action="{{url('admin/webuser/pwd')}}">
                        <input name="uid" type="hidden" value="{{$id}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">新密码：</label>
                            <div class="col-sm-8">
                                <input type="tel" name="new_pwd" class="form-control" required="" value="" placeholder="请输入新密码">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">确认密码：</label>
                            <div class="col-sm-8">
                                <input type="tel" name="new_rpwd" class="form-control" required="" value="" placeholder="请输入确认密码">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group ">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <a href="{{url('admin/webuser/student')}}"><button type="button" class="btn btn-w-m btn-default">取消</button></a>
                                <button class="btn btn-primary btn-w-m butsub" type="submit">保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('pageheader')
@endsection
