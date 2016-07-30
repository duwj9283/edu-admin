@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                消息管理
                <i class="fa fa-angle-double-right m-l m-r"></i>
                写消息
            </h5>
        </div>
        <div class="ibox-content">
            <form id="formAdd" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="col-sm-2 control-label">收信人</label>
                    <div class="col-sm-10">
                        <select id="txtReceiverIds" name="receiver_ids[]" data-placeholder="请选择" multiple class="form-control chosen-select">
                            <option value="0">All - 所有人</option>
                            @foreach($user_rows as $row)
                            <option value="{{$row->uid}}">{{$row->realname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">主题</label>
                    <div class="col-sm-10">
                        <input type="text" name="title" class="form-control" required="" autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">正文</label>
                    <div class="col-sm-10">
                        <textarea id="ueditor_content" name="content" rows="10"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">附件</label>
                    <div class="col-sm-10" id="boxAttachment">
                        <input type="file" name="file1" class="form-control" />
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <input type="hidden" name="id" value="0" />
                        <button type="button" id="btnSend" class="btn btn-primary btn-w-m"><i class="fa fa-send"></i> 立即发送</button>
                        <button type="button" id="btnSave" class="btn btn-warning btn-w-m m-l"><i class="fa fa-save"></i> 保存草稿</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script id="tplAttachment" type="text/html">
    <%if file1 == ''%>
        <input type="file" name="file1" class="form-control" />
    <%else%>
        <div class="input-group">
            <span class="input-group-btn">
                <button type="button" class="btn btn-danger js-deleteAttachment"> <i class="fa fa-times"></i>
                </button>
            </span>
            <input type="text" value="<%basename(file1)%>" class="form-control" readonly />
        </div>
    <%/if%>
</script>
@endsection

@section('pageheader')
<link href="{{cdn1('assets/chosen/chosen.min.css')}}" rel="stylesheet" />
@endsection

@section('pagescript')
<script src="{{cdn1('assets/chosen/chosen.jquery.min.js')}}"></script>
<script src="{{cdn1('assets/ueditor/ueditor.config.js')}}"></script>
<script src="{{cdn1('assets/ueditor/ueditor.all.js')}}"></script>
<script>
    $("#side-menu li[rel='msg_write']").addClass("active")
        .parents("ul").addClass("in").parents("li").addClass("active");

    var ueditor = UE.getEditor('ueditor_content');
    $('.chosen-select').chosen();

    template.helper('basename', function(str){
        return str.substring(str.lastIndexOf('/') + 1, str.length);
    });

    seajs.use('models/messageModel', function(messageModel) {
        $("#btnSave").on("click", function(){
            var form = $("#formAdd")[0];
            var n = $("#txtReceiverIds option:selected").length;
            if(n < 1){
                alert('请选择收件人');
                return false;
            }
            if(required_check(form.title.value) == false){
                alert("请填写标题");
                form.title.focus();
                return false;
            }
            if(ueditor.hasContents() == false){
                alert("请填写正文");
                ueditor.focus();
                return false;
            }
            ueditor.sync();
            var formdata = new FormData();
            $.each($(form).serializeArray(), function(i, item) {
                formdata.append(item.name, item.value);
            });
            if(form.file1 && form.file1.value != ''){
                formdata.append('file1', form.file1.files[0]);
            }
            messageModel.saveDrafts(formdata, function(data){
                form.id.value = data.id;
                $("#boxAttachment").html(template("tplAttachment", data));
                artInfo("保存成功");
            }, failure);
            return false;
        });

        $("#btnSend").on("click", function(){
            var form = $("#formAdd")[0];
            var n = $("#txtReceiverIds option:selected").length;
            if(n < 1){
                alert('请选择收件人');
                return false;
            }
            if(required_check(form.title.value) == false){
                alert("请填写标题");
                form.title.focus();
                return false;
            }
            if(ueditor.hasContents() == false){
                alert("请填写正文");
                ueditor.focus();
                return false;
            }
            ueditor.sync();
            var formdata = new FormData();
            $.each($(form).serializeArray(), function(i, item) {
                formdata.append(item.name, item.value);
            });
            if(form.file1 && form.file1.value != ''){
                formdata.append('file1', form.file1.files[0]);
            }
            messageModel.sendMsg(formdata, function(result){
                artInfo("发送成功");
                form.reset();
                $("#txtReceiverIds").trigger("chosen:updated");
                ueditor.setContent("");
                $("#boxAttachment").html('<input type="file" name="file1" class="form-control" />');
            }, failure);
            return false;
        });

        $("#boxAttachment").delegate(".js-deleteAttachment", "click", function(){
            if(confirm('确定要删除此附件吗？')){
                var id = document.getElementById("formAdd").id.value;
                messageModel.deleteFile1({"id":id}, function(){
                    $("#boxAttachment").html('<input type="file" name="file1" class="form-control" />');
                }, failure);
            }
        });
    });
</script>
@endsection
