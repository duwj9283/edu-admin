@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                消息管理
                <i class="fa fa-angle-double-right m-l m-r"></i>
                草稿箱
            </h5>
        </div>
        <div class="ibox-content">
            <div class="m-b">
                <a href="javascript:;" id="btnRefresh" class="btn btn-default m-l-xs"><i class="fa fa-refresh"></i> 刷新</a>
                <a href="javascript:;" id="btnDelete" class="btn btn-danger m-l"><i class="fa fa-trash"></i> 删除</a>
            </div>
            <table id="tblDataList" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="50" class="text-center"><input type="checkbox" id="checkAll" title="全选" /></th>
                        <th>主题</th>
                        <th>收件人</th>
                        <th width="150">时间</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr data-id="{{$row->id}}" style="cursor:pointer;">
                        <td class="text-center">
                            <input type="checkbox" name="ids" value="{{$row->id}}" />
                        </td>
                        <td><strong>{{$row->title}}</strong></td>
                        <td>{{$row->receiver_names}}</td>
                        <td>{{$row->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center"><?php echo $rows->render(); ?></div>
        </div>
    </div>
</div>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='msg_drafts']").addClass("active")
        .parents("ul").addClass("in").parents("li").addClass("active");

    $("#checkAll").click(function(){
        var checked = $(this).prop("checked");
        $("input[name='ids']").each(function(){
            $(this).prop("checked", checked);
        });
    });

    $("#btnRefresh").on("click", function(){
        window.location.reload();
    });

    seajs.use('models/messageModel', function(messageModel) {
        $("#tblDataList tbody tr").on("click", function(e){
            if(e.target.tagName == "INPUT"){
                return true;
            }
            var that = this, id = $(this).data("id");
            window.location.href = "{{url('admin/message/edit')}}?id=" + id;
        });

        $("#btnDelete").on('click', function(){
            var keys = new Array();
            $("input[name='ids']").each(function(){
                if(this.checked){
                    keys.push($(this).val());
                }
            });
            var keyValue = keys.join("|");
            if(keyValue){
                dialog({
                    content: '<i class="fa fa-info-circle"></i> 即将删除所选择的 <b>'+ keys.length +'</b> 条记录, 且该操作不能恢复! 是否继续 ?',
                    ok: function(){
                        messageModel.deleteDrafts({"ids":keyValue}, function(data){
                            $("tr[data-id]").each(function(){
                                if($(this).find("input[name='ids']").prop("checked")){
                                    $(this).remove();
                                }
                            });
                            $("#checkAll").prop("checked", false);
                        }, failure);
                    },
                    cancel: true
                }).showModal();
            }else{
                failure('请先选择准备删除的记录！');
            }
        });
    });
</script>
@endsection
