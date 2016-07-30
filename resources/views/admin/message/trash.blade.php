@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                消息管理
                <i class="fa fa-angle-double-right m-l m-r"></i>
                垃圾箱
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
                        <th width="100">发件人</th>
                        <th>主题</th>
                        <th>内容</th>
                        <th width="150">时间</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr data-id="{{$row->id}}" style="cursor:pointer;">
                        <td class="text-center">
                            <input type="checkbox" name="ids" value="{{$row->id}}" />
                        </td>
                        <td>{{$row->sender_name}}</td>
                        <td>{{$row->title}}</td>
                        <td title="{{strip_tags($row->content)}}">{!! cutStr2($row->content, 80) !!}</td>
                        <td>{{$row->created_at}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center"><?php echo $rows->render(); ?></div>
        </div>
    </div>
</div>

<script id="tplMessagePanel" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><%title%></h4>
                <small class="font-bold">收件人：</small><span class="label"><%receiver_names%></span>
                <p><%created_at%></p>
            </div>
            <div class="modal-body">
                <%#content%>
                <%if file_name%>
                <div class="hr-line-dashed"></div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-1 control-label">附件</label>
                        <div class="col-sm-9">
                            <input type="text" value="<%file_name%>" class="form-control" readonly />
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-info js-download" data-id="<%id%>"><i class="fa fa-download"></i> 下载</button>
                        </div>
                    </div>
                </div>
                <%/if%>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>
            </div>
        </div>
    </div>
</script>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='msg_trash']").addClass("active")
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
            messageModel.getDetails({"id":id}, function(data){
                $(that).find(".label-info").html('已查看');
                $("#modalDialog").addClass("inmodal");
                $("#modalDialog").html(template("tplMessagePanel", data)).modal('show');
            }, failure);
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
                    content: '<i class="fa fa-info-circle"></i> 即将删除所选择的 <b>'+ keys.length +'</b> 条记录, 是否继续 ?',
                    ok: function(){
                        messageModel.delete({"ids":keyValue}, function(data){
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
            } else {
                failure('请先选择准备删除的记录！');
            }
        });

        $("#modalDialog").delegate(".js-download", "click", function(){
            var id = $(this).data("id");
            window.open("{{url('admin/message/download-file1')}}?id=" + id);
        });
    });
</script>
@endsection
