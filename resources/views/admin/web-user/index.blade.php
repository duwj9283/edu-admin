@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>老师账号管理</h5>
        </div>
        <div class="ibox-content">

            <div class="row">
                <div class="table-responsive ">
                    <div class="m-b">
                        <div class="dropdown">
                            <a href="/admin/webuser/add"  class="btn btn-primary m-l-xs "  > <i class="fa fa-plus"></i>
                                新建
                            </a>
                            <a href="javascript:;"  class="btn btn-warning m-l-xs js-import" > <i class="fa  fa-upload"></i>
                                导入
                            </a>
                        </div>
                    </div>
                    <table id="tblDataList" class="table  table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>昵称</th>
                            <th>真实姓名</th>
                            <th>手机号</th>
                            <th>邮箱</th>
                            <th>性别</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lists as $key=> $value)
                            <tr data-id="{{$value['uid']}}" >
                                <td>{{$key+1}}</td>
                                <td>{{$value['nick_name']}}</td>
                                <td>{{$value['realname']}}</td>
                                <td>{{$value['phone']}}</td>
                                <td>{{$value['email']}}</td>
                                <td>{{$value['sex']}}</td>
                                <td>
                                    <a href="/admin/webuser/add?id={{$value['uid']}}" ><i class="fa fa-edit">编辑</i></a>
                                    @if($value['disable']==1)
                                        <a href="javascript:void(0)" class="js-disable" data-status="1"><i class="fa  fa-check-circle">启用</i></a>
                                    @else
                                        <a href="javascript:void(0)" class="js-disable" data-status="0"><i class="fa   fa-times-circle">禁用</i></a>
                                    @endif
                                    <a href="javascript:void(0)" class="role-set" ><i class="fa  fa-user">角色分配</i></a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">{!!$lists->render()!!}</div>

                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form name="import-form" onsubmit="return false" action="/admin/webuser/import" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">导入</h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">

                        <a href="{{asset('upload/web-user-import-template.xls')}}" target="_blank">导入模板下载</a> |
                        <a href="/admin/subject/download" >学科专业库下载</a>
                    </div>
                    <div class="form-group">
                        <label>选择</label>
                        <input type="file"  class="form-control" required  name="file"  accept="xls, xlsx"><br/>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="myModalDiv" tabindex="-1" role="dialog" aria-hidden="true"></div>

@endsection

@section('pageheader')
    <link href="{{cdn1('assets/iCheck/custom.css')}}" rel="stylesheet">

    <style>
tr.current{
    background-color: #f5f5f5;
}
</style>
@endsection

@section('pagescript')
    <script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>

    <script type="text/javascript">
        $(function(){
            $("#side-menu li[rel='entrust']").addClass("active")
                    .find("ul").addClass("in")
                    .find("li[rel='user']").addClass("active");

            //导入
            $('.js-import').click(function(){

                $('#myModal').modal('show');
                return false;
            });

            //新建编辑 保存
            $('#myModal').delegate('form[name="import-form"]','submit',function(){

                $form=$('form[name="import-form"]');
                formData_object = new FormData();
                $('#myModal').modal('hide');
                formData_object.append('file',$form.find('input[name="file"]')[0].files[0]);

                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: formData_object,
                    success:function(){
                        dialog({
                            title: false,
                            content: '导入成功’'
                        }).show();
                    },
                    error:failure
                })
            });

            //禁用 启用
            $('.ibox-content').delegate('.js-disable','click',function(){
                var status=$(this).data('status');
                var id=$(this).parents('tr').data('id');
                var content=(status==1)?'确定启用该账号么？':'确定禁用该账号么？';
                var d = dialog({
                    title: '提示',
                    content: content,
                    okValue: '确定',
                    ok: function () {
                        this.close()    ;
                        $.post('/admin/webuser/status',{id:id},function(){
                            window.location.reload();
                            return false;
                        }).fail(failure);
                        return false;
                    },
                    cancelValue: '取消',
                    cancel: function () {}
                }).show();

            })

            //角色分配
            $('.role-set').click(function(){
                var id=$(this).parents('tr').data('id');
                $.get('/admin/entrust/users-role-list/'+id,function(html){
                    $('#myModalDiv').html(html).modal('show');

                })
            })
        })




    </script>

@endsection
