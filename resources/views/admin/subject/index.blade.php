@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>学科管理</h5>
        </div>
        <div class="ibox-content">

            <div class="row">
                <div class="col-lg-6">
                    <div class="table-responsive ">
                        <div class="m-b">
                            <div class="dropdown">
                                <a href="javascript:;"  class="btn btn-primary m-l-xs js-add"  data-type="parent"> <i class="fa fa-plus"></i>
                                    新建
                                </a>
                                <a href="javascript:;"  class="btn btn-warning m-l-xs js-delete" data-type="parent"> <i class="fa fa-trash"></i>
                                    删除
                                </a>
                                <a href="javascript:;" id="btnImport" class="btn btn-info m-l-xs"> <i class="fa fa-file-excel-o"></i>
                                    导入
                                </a>
                            </div>
                        </div>
                        <table id="tblDataList" class="table  table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>学科码</th>
                                <th>名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parents as $key=> $value)
                                <tr data-id="{{$value['id']}}" >
                                    <td>{{$key+1}}</td>
                                    <td>{{$value['subject_code']}}</td>
                                    <td>{{$value['subject_name']}}</td>
                                    <td><a href="javascript:;" class="js-edit" data-type="parent"><i class="fa fa-edit">编辑</i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class=" col-lg-6">
                    <div class="m-b">
                        <div class="dropdown">
                            <a href="javascript:;"  class="btn btn-primary m-l-xs js-add" data-type="child"> <i class="fa fa-plus"></i>
                                新建
                            </a>
                            <a href="javascript:;" id="btnImportM" class="btn btn-info m-l-xs"> <i class="fa fa-file-excel-o"></i>
                                导入
                            </a>
                            <span id="father_title" style="font-size: 20px;font-weight: bold"></span>
                        </div>

                    </div>
                    <div class="table-responsive ">
                        <table id="tblDataChildList" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>学科码</th>
                                <th>子集名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script id="tplSubjectEdit" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form name="subject-edit" onsubmit="return false">
                <input name="id" value="<%id%>" type="hidden">
                <input name="father_id" value="<%father_id%>" type="hidden">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><%title%></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>学科</label>
                        <input type="text" placeholder="请输入学科码" class="form-control" required value="<%code%>" name="code"><br/>
                        <input type="text" placeholder="请输入学科名称" class="form-control" required value="<%name%>" name="name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</script>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection

@section('pageheader')
<style>
tr.current{
    background-color: #f5f5f5;
}
</style>
@endsection

@section('pagescript')

    <script type="text/javascript">
        $(".sidebar-collapse li[rel='subject']").addClass("active");
        var parentId=$('#tblDataList tbody tr:first').data('id');//当前父集id
        var getChild=function(){
            $('#tblDataList tbody tr[data-id="'+parentId+'"]').siblings().removeClass('current').end().addClass('current');
            $.get('/admin/subject/child-list',{id:parentId},function(data){
                var html='';
                if(data.length>0){
                    for(var i=0;i<data.length;i++){
                        html+='<tr data-id="'+data[i].id+'"><td>'+(i+1)+'</td><td>'+data[i].subject_code+'</td><td>'+data[i].subject_name+'</td><td><a href="javascript:;" class="js-edit" data-type="child"><i class="fa fa-edit">编辑</i></a>  | <a href="javascript:;" class="js-delete" data-type="child"><i class="fa fa-trash">删除</i></a></td></tr>'
                    }
                }
                $('#tblDataChildList tbody').html(html);
            })
        };
        //编辑
        $('.ibox-content').delegate('.js-edit','click',function(){
            var type=$(this).data('type');
            var father_id=0;
            var id=parentId;
            var code=$(this).parents('tr').find('td:eq(1)').html();
            var name=$(this).parents('tr').find('td:eq(2)').html();
            if(type=='child'){
                father_id=parentId;
                id=$(this).parents('tr').data('id');
            }
            $('#myModal').html(template('tplSubjectEdit',{father_id:father_id,title:'编辑',id:id,name:name,code:code})).modal('show');
            return false;
        });
        $('#tblDataList').delegate('tr','click',function(){
            parentId=$(this).data('id');
            getChild();
            var code = $('#tblDataList tbody tr[data-id="'+parentId+'"]').find('td:eq(1)').text();
            var name = $('#tblDataList tbody tr[data-id="'+parentId+'"]').find('td:eq(2)').text();
            $('#father_title').html("【"+code+" | "+name+"】");
            $(document).scrollTop(0);
        });
        $('#tblDataList tbody tr:first').trigger('click');

        //删除
        $('.ibox-content').delegate('.js-delete','click',function(){
            var type=$(this).data('type');
            var id=(type=='child')?($(this).parents('tr').data('id')):parentId;
            var d = dialog({
                title: '提示',
                content: '确定删除么？',
                okValue: '确定',
                ok: function () {
                    this.close()    ;
                    $.post('/admin/subject/delete',{id:id},function(){
                        if(type=='child'){
                            $('#tblDataChildList tbody tr[data-id="'+id+'"]').remove();
                            return false;
                        }
                        $('#tblDataList tbody tr[data-id="'+id+'"]').remove();
                        return false;
                    }).fail(failure);
                    return false;
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show();
        });
        //新建
        $('.ibox-content').delegate('.js-add','click',function(){
            var type=$(this).data('type');
            var father_id=0;
            var title='新建';
            if(type=='child'){
                father_id=parentId;
                title='新建"'+$('#tblDataList tbody tr[data-id="'+parentId+'"]').find('td:eq(1)').html()+'"子集';
            }
            $('#myModal').html(template('tplSubjectEdit',{father_id:father_id,title:title})).modal('show');
        });

        //新建编辑 保存
        $('#myModal').delegate('form[name="subject-edit"]','submit',function(){
            var exist_id=$('form[name="subject-edit"]').find('input[name="id"]').val();
            $.post('/admin/subject/edit',$('form[name="subject-edit"]').serialize(),function(data){
                $('#myModal').modal('hide');
                if(exist_id>0){//编辑
                    if(parseInt(data.father_id) > 0){
                        $('#tblDataChildList tbody tr[data-id="'+data.id+'"] td:eq(1)').html(data.subject_code);
                        $('#tblDataChildList tbody tr[data-id="'+data.id+'"] td:eq(2)').html(data.subject_name);
                    }else{
                        $('#tblDataList tbody tr[data-id="'+data.id+'"] td:eq(1)').html(data.subject_code);
                        $('#tblDataList tbody tr[data-id="'+data.id+'"] td:eq(2)').html(data.subject_name);
                    }
                }else{
                    if(parseInt(data.father_id) > 0){
                        $('#tblDataChildList tbody ').append('<tr data-id="'+data.id+'" ><td>'+($('#tblDataChildList tbody tr').size()+1)+'</td><td>'+data.subject_code+'</td><td>'+data.subject_name+'</td><td><a href="javascript:;" class="js-edit" data-type="child"><i class="fa fa-edit">编辑</i></a><a href="javascript:;" class="js-delete" data-type="child"> | <i class="fa fa-trash">删除</i></a></td></tr>');
                    }else{
                        $('#tblDataList tbody ').append('<tr data-id="'+data.id+'" ><td>'+($('#tblDataList tbody tr').size()+1)+'</td><td>'+data.subject_code+'</td><td>'+data.subject_name+'</td><td><a href="javascript:;" class="js-edit" data-type="parent"><i class="fa fa-edit">编辑</i></a></td></tr>');
                    }
                }
            })
        });

        $("#btnImport").on("click", function(){
            $("#modalDialog").html(template("tplUploadFile", null)).modal("show");
        });
        $("#btnImportM").on("click", function(){
            $("#modalDialog").html(template("tplUploadFile", null)).modal("show");
            var fatherID = $('#tblDataList tbody tr[class="current"]').data('id');
            if(!fatherID) fatherID = 0;
            $("#fatherID").val(fatherID);
        });
        seajs.use('models/subjectModel', function(subjectModel) {
            $("#modalDialog").delegate("#formUpfile", 'submit', function(){
                var file = this.file1.value;
                if (file == ""){
                    alert("请选择上传文件！");
                    return false;
                }
                if (file.lastIndexOf(".") == -1){
                    alert("文件类型不正确！");
                    return false;
                }
                var ext = file.substr(file.lastIndexOf(".") + 1).toLowerCase();
                if (ext != 'xls' && ext != 'xlsx'){
                    alert('无效的文件类型！');
                    return false;
                }
                var data = new FormData();
                var fatherID = $("#fatherID").val();
                if(!fatherID) fatherID=0;
                data.append('file1', this.file1.files[0]);
                data.append('fatherID', fatherID);
                subjectModel.importSubject(data, function(result){
                    $("#modalDialog").html("").modal('hide');
                    if(result.length<=0)
                    {
                        alert('未导入任何数据，请查看文件数据格式是否正确');
                        return false;
                    }
                    else
                    {
                        for(var i=0;i<result.length;i++){
                            if(fatherID>0)
                            {
                                $('#tblDataChildList tbody ').append('<tr data-id="'+result[i].id+'" ><td>'+($('#tblDataChildList tbody tr').size()+1)+'</td><td>'+result[i].subject_code+'</td><td>'+result[i].subject_name+'</td><td><a href="javascript:;" class="js-edit" data-type="child"><i class="fa fa-edit">编辑</i></a><a href="javascript:;" class="js-delete" data-type="child"> | <i class="fa fa-trash">删除</i></a></td></tr>');
                            }
                            else
                            {
                                console.log(result[i]);
                                $('#tblDataList tbody ').append('<tr data-id="'+result[i].id+'" ><td>'+($('#tblDataList tbody tr').size()+1)+'</td><td>'+result[i].subject_code+'</td><td>'+result[i].subject_name+'</td><td><a href="javascript:;" class="js-edit" data-type="parent"><i class="fa fa-edit">编辑</i></a></td></tr>');
                            }
                        }
                    }
                }, failure);
                return false;
            });
        });
    </script>

@endsection
<script id="tplUploadFile" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">导入学科</h4>
            </div>
            <div class="modal-body">
                <form id="formUpfile" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">模板文件</label>
                        <div class="col-sm-9">
                            <p class="form-control-static">
                                <a href="{{asset('upload/subjects_template.xls')}}" target="_blank">[点击下载]</a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">选择文件</label>
                        <div class="col-sm-9">
                            <input name="file1" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-9 col-sm-offset-3">
                            <input type="hidden" id="fatherID" value="0">
                            <button type="submit" class="btn btn-primary btn-w-m"> <i class="fa fa-upload"></i>
                                立即导入
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>
