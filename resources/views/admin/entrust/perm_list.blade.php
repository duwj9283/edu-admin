@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>权限列表</h5>
        </div>
        <div class="ibox-content">
            @if($token['isHidden'])
            <div class="m-b">
                <a href="{{url('admin/entrust/perm-add')}}" class="btn btn-warning"><i class="fa fa-plus"></i> 新建权限</a>
            </div>
            @endif
            <table id="tblDataList" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>权限</th>
                        <th>权限名</th>
                        <th>描述</th>
                        <th width="200"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr data-id="{{$row->id}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->name}}</td>
                        <td>{{$row->display_name}}</td>
                        <td>{{$row->description}}</td>
                        <td>
                            <a href="{{url('admin/entrust/perm-role-list/'.$row->id)}}" class="m-r-xs" title="查看此权限的角色">
                                <i class="fa fa-trophy fa-lg"></i> 角色
                            </a>
                            <a href="{{url('admin/entrust/perm-edit/'.$row->id)}}" class="m-r-xs js-edit">
                                <i class="fa fa-pencil-square fa-lg"></i>编辑
                            </a>
                            @if($token['isHidden'])
                            <a href="javascript:;" class="m-r-xs js-del">
                                <i class="fa fa-times-circle fa-lg"></i>删除
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='entrust']").addClass("active")
        .find("ul").addClass("in")
        .find("li[rel='permission']").addClass("active");

    seajs.use('models/entrustModel', function(entrustModel) {
        $("#tblDataList").delegate('.js-del', 'click', function(e){
            var id = $(this).parents('tr').eq(0).data('id');
            dialog({
                content:'<i class="fa fa-info-circle"></i> 确定要删除此权限吗？',
                ok:function(){
                    entrustModel.deletePerm({'id':id}, function(){
                        $(e.target).parents("tr").remove();
                    }, failure);
                },
                cancel: true
            }).showModal();
        });
    });
</script>
@endsection
