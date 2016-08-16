@extends('admin.main')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox">
            <div class="ibox-title">
                <h5>角色</h5>
            </div>
            <div class="ibox-content">
                @if($token['isHidden'])
                <div class="m-b">
                    <a href="{{url('admin/entrust/role-add')}}" class="btn btn-warning"><i class="fa fa-plus"></i> 新建角色</a>
                </div>
                @endif
                <div class="row">
                    <div class="col-lg-4">

                        <table id="tblDataList" class="table table-hover ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>名称</th>
                                    {{--<th width="260"></th>--}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $row)
                                <tr data-id="{{$row->id}}">
                                    <td>{{$row->id}}</td>
                                    <td>{{$row->display_name}}</td>

                                    {{--<td>
                                        <a href="{{url('admin/entrust/role-users-list/'.$row->id)}}" class="m-r-xs" title="查看角色用户">
                                            <i class="fa fa-users fa-lg"></i>成员
                                        </a>
                                        <a href="{{url('admin/entrust/role-perms-list/'.$row->id)}}" class="m-r-xs" title="查看角色权限">
                                            <i class="fa fa-unlock-alt fa-lg"></i>权限
                                        </a>
                                        <a href="javascript:;" class="m-r-xs js-edit">
                                            <i class="fa fa-pencil-square fa-lg"></i>编辑
                                        </a>
                                        @if($token['isHidden'])
                                        <a href="javascript:;" class="m-r-xs js-del">
                                            <i class="fa fa-times-circle fa-lg"></i>删除
                                        </a>
                                        @endif
                                    </td>--}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-8" id="perms-list">


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageheader')
    <link href="{{cdn1('assets/iCheck/custom.css')}}" rel="stylesheet">
    <style>
        #tblDataList .selected{
            background-color:#EBEBEB;
        }
    </style>
@endsection

@section('pagescript')
    <script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>

    <script>
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='role']").addClass("active");

        seajs.use('models/entrustModel', function(entrustModel) {
            $("#tblDataList").delegate('.js-edit', 'click', function(){
                var id = $(this).parents('tr').eq(0).data('id');
                window.location.href = "{{url('admin/entrust/role-edit')}}?id=" + id;
                return false;
            });

            $("#tblDataList").delegate('.js-del', 'click', function(e){
                var id = $(this).parents('tr').eq(0).data('id');
                dialog({
                    content:'<i class="fa fa-info-circle"></i> 确定要删除此角色吗？',
                    ok:function(){
                        entrustModel.deleteRole({'id':id}, function(){
                            $(e.target).parents("tr").remove();
                        }, failure);
                    },
                    cancel: true
                }).showModal();
            });
        });

        $('#tblDataList tbody tr').click(function(){
            var $this=$(this);
            var id=$(this).data('id');
            $this.addClass('selected').siblings('tr').removeClass('selected');
            $.get('/admin/entrust/role-perms-list/'+id,function(html){

                $('#perms-list').html(html);
            });
        })
        $('#tblDataList tbody tr:eq(0)').trigger('click')
    </script>
@endsection
