@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="m-b">
            <a href="{{url('admin/webuser/teacher')}}" class="btn btn-default"> <i class="fa fa-arrow-left"></i>
                返回列表
            </a>
        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加教师账号</h5>
            </div>
            <div class="ibox-content">

                <div class="row">

                    <form method="post" class="form-horizontal webUser_add"  action="{{url('admin/webuser/add')}}" onsubmit="return false">
                        <input name="uid" type="hidden" value="{{$detail['uid']}}">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">角色</label>
                            <div class="col-sm-8">
                                <select name="role_id" class="form-control" required="" >
                                    @foreach($roles as $role)
                                        <option value="{{$role['id']}}" @if($role['id']==$detail['role_id']) selected @endif>{{$role['role_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">手机号</label>
                            <div class="col-sm-8">
                                <input type="tel" name="phone" class="form-control" required="" value="{{$detail['phone']}}" placeholder="请输入您的手机号">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">邮箱</label>
                            <div class="col-sm-8">
                                <input type="text" name="email" class="form-control" required="" value="{{$detail['email']}}" placeholder="请输入您的邮箱">

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">性别</label>
                            <div class="col-sm-8">
                                <select name="sex" class="form-control">

                                    <option value="女" @if($detail['sex']=='女') selected @endif>女</option>
                                    <option value="男" @if($detail['sex']=='男') selected @endif>男</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">昵称</label>
                            <div class="col-sm-8"><input type="text" name="nick_name" class="form-control" required="" value="{{$detail['nick_name']}}"  placeholder="请输入您的昵称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">真实姓名</label>
                            <div class="col-sm-8"><input type="text" name="realname" class="form-control" required="" value="{{$detail['realname']}}" placeholder="请输入您的真实姓名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-8">
                                <textarea name="desc" class="form-control" required="" placeholder="请输入您的描述">{{$detail['desc']}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">QQ</label>
                            <div class="col-sm-8"><input type="text" name="qq" class="form-control"  value="{{$detail['qq']}}" placeholder="请输入您的QQ">
                            </div>
                        </div>
                        <div class="form-group" id="city">
                            <label class="col-sm-2 control-label">地址</label>
                            <div class="col-sm-8">
                                <div class="col-sm-3" style="padding-left:0px;">
                                    <select class="prov form-control" name="prov"></select>
                                </div>
                                <div class="col-sm-3">
                                    <select class="city form-control" name="city"></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">岗位</label>
                            <div class="col-sm-8"><input  name="job" class="form-control" value="{{$detail['job']}}" placeholder="请输入您的岗位" ></div>
                        </div>
                        <div class="form-group" id="subject-select">
                            <label class="col-sm-2 control-label">学科</label>
                            <div class="col-sm-8">
                                <div class="col-sm-3"  style="padding-left:0px;">
                                    <select class="sub-type form-control" ></select>
                                </div>
                                <div class="col-sm-3">
                                    <select class="sub-child-type form-control"  name="subject"></select>
                                </div>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group ">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <a href="{{url('admin/webuser/teacher')}}"><button type="button" class="btn btn-w-m btn-default">取消</button></a>
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
@section('pagescript')
    <script src="{{cdn1('assets/cityselect/jquery.cityselect.js')}}"></script>
    <script src="{{cdn1('assets/subjectselect/jq.subselect.js')}}"></script>

    <script>
        $(function(){

            $(".sidebar-collapse li[rel='webuser']").addClass("active");
            /*
             * 省市二级联动
             * ie select问题
             * */
            var city='{{$detail['city']}}';
            var citySetting={

                nodata:"none" //当子集无数据时，隐藏select
            };
            if(city){
                var cityArray = city.split(',');
                citySetting={
                    prov:cityArray[0], //省份
                    city:cityArray[1], //城市
                    nodata:"none" //当子集无数据时，隐藏select
                }
            }


            $("#city").citySelect(citySetting);
            //学科
            subObject.init({
                pt:'{{$subject_parent}}',
                ct:'{{$subject_child}}'
            });



            //提交保存
            var $form=$('.webUser_add');
            $form.submit(function(){
                $.post($form.attr('action'),$form.serialize(),saveResult).fail(failure);

            })


            var saveResult=function(data){
                window.location.href="{{url('admin/webuser/teacher')}}";
            }

        })
    </script>
@endsection