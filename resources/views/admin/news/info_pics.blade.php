@extends('admin.main')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <h2>多图列表 <small>《{{$info->title}}》</small></h2>
        <ol class="breadcrumb">
            <li>资讯管理</li>
            <?php foreach ($navigation as $nav): ?>
                <li>{{$nav['name']}}</li>
            <?php endforeach;?>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="m-b">
        <a href="javascript:;" onclick="history.back();" class="btn btn-outline btn-info"><i class="fa fa-angle-double-left"></i> 返回列表</a>
        <a href="javascript:;" id="btnCreate" class="btn btn-outline btn-warning m-l"> <i class="fa fa-plus"></i> 新增</a>
    </div>
    <div id="masonry" class="row">
        @foreach($rows as $k => $row)
        <div class="col-lg-3 grid-item" data-id="{{$row->id}}">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>图片{{$k+1}}</h5>
                    <div class="pull-right">
                        <a href="javascript:;" class="btn btn-primary btn-xs m-r-xs js-edit">
                            <i class="fa fa-pencil-square-o fa-lg"></i> 编辑
                        </a>
                        <a href="javascript:;" class="btn btn-danger btn-xs js-del">
                            <i class="fa fa-times fa-lg"></i> 删除
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <p class="text-center">
                        <a href="{{$row->pic1}}" data-lightbox="image-set" data-title="{{$row->title}}" class="img-thumbnail"><img src="{{$row->pic1}}" class="img-responsive" /></a>
                    </p>
                    <h3>{{$row->title}}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="clearfix"></div>
</div>

<script id="tplGridItem" type="text/html">
    <div class="col-lg-3 grid-item" data-id="<%id%>">
        <div class="ibox">
            <div class="ibox-title">
                <h5>新图片</h5>
                <div class="pull-right">
                    <a href="javascript:;" class="btn btn-primary btn-xs m-r-sm">
                        <i class="fa fa-pencil-square-o fa-lg"></i> 编辑
                    </a>
                    <a href="javascript:;" class="btn btn-danger btn-xs js-del">
                        <i class="fa fa-times fa-lg"></i> 删除
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p class="text-center">
                    <a href="<%pic1%>" data-lightbox="image-set" data-title="<%title%>" class="img-thumbnail"><img src="<%pic1%>" class="img-responsive" /></a>
                </p>
                <h3><%title%></h3>
            </div>
        </div>
    </div>
</script>

<script id="tplCreatePannel" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title">新增</h4>
            </div>
            <div class="row modal-body">
                <form id="formAdd" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择图片</label>
                        <div class="col-sm-9">
                            <input id="filePic1" type="file" name="pic1" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">图片标题</label>
                        <div class="col-sm-9">
                            <input type="text" id="txtTitle" name="title" value="" placeholder="图片标题" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">图片说明</label>
                        <div class="col-sm-10">
                            <div id="ueditor_content" class="summernote"></div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="info_id" value="<%info_id%>" />
                            <button type="submit" class="btn btn-warning btn-w-m"><i class="fa fa-cloud-upload"></i> 上传图片</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>

<script id="tplUpdatePannel" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title">编辑</h4>
            </div>
            <form id="formEdit" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>图片标题</label>
                        <input type="text" name="title" value="<%title%>" placeholder="图片标题" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>图片说明</label>
                        <div id="ueditor_content" class="summernote"><%#content%></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" value="<%id%>" />
                    <button type="submit" class="btn btn-primary btn-w-m"><i class="fa fa-save"></i> 保存</button>
                    <button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times"></i> 关闭</button>
                </div>
            </form>
        </div>
    </div>
</script>
@endsection

@section('pageheader')
<link rel="stylesheet" href="{{cdn1('assets/iCheck/custom.css')}}" />
<link rel="stylesheet" href="{{cdn1('assets/lightbox2/css/lightbox.min.css')}}" />
<link rel="stylesheet" href="{{cdn1('assets/summernote/summernote.css')}}" />
@endsection

@section('pagescript')
<script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>
<script src="{{cdn1('assets/masonry/masonry.pkgd.min.js')}}"></script>
<script src="{{cdn1('assets/imagesLoaded/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{cdn1('assets/lightbox2/js/lightbox.min.js')}}"></script>
<script src="{{cdn1('assets/summernote/summernote.min.js')}}"></script>
<script>
    $("#side-menu li[rel='news-{{$column->id}}']").addClass("active")
        .parents("ul").addClass("in").parents("li").addClass("active");

    seajs.use(['models/newsclassModel','models/newsinfoModel'], function(newsclassModel, newsinfoModel) {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $("#modalDialog").delegate("#filePic1", "change", function(){
            var str = this.value, obj = $("#txtTitle");
            if(required_check(obj.val()) == false){
                obj.val(str.substring(str.lastIndexOf("\\")+1, str.lastIndexOf(".")));
            }
        });

        // 加载 Summernote 编辑器
        var loadSummernote = function(editor){
            editor.summernote({
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "italic", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link"]],
                    ["view", ["codeview"]]
                ]
            });
        };

        var info_id = "{{$info->id}}";
        $("#btnCreate").on("click", function(){
            var data = {"info_id":info_id};
            $("#modalDialog").html(template("tplCreatePannel", data)).modal("show");
            loadSummernote($('#ueditor_content'));
        });

        $("#modalDialog").delegate("#formAdd", "submit", function(){
            var file = this.pic1.value;
            if (file == ""){
                alert("请选择上传文件！");
                return false;
            }
            if (file.lastIndexOf(".") == -1){
                alert("文件类型不正确！");
                return false;
            }
            var ext = file.substr(file.lastIndexOf(".") + 1).toLowerCase();
            if (ext != 'jpg' && ext != 'jpeg' && ext != 'gif' && ext != 'png'){
                alert('图片必须是 JPG、JPEG、GIF 或者 PNG 格式！');
                return false;
            }
            if(required_check(this.title.value) == false){
                this.title.focus();
                return false;
            }
            var formdata = new FormData();
            $.each($(this).serializeArray(), function(i, item) {
                formdata.append(item.name, item.value);
            });
            formdata.append("content", $("#ueditor_content").code());
            formdata.append('pic1', this.pic1.files[0]);
            newsinfoModel.uploadPics(formdata, function(data){
                var html = $(template("tplGridItem", data));
                $("#masonry").append(html).masonry("appended", html);
                $("#modalDialog").html("").modal("hide");
            }, failure);
            return false;
        });

        $("#masonry").delegate(".js-edit", "click", function(){
            var obj = $(this).parents(".grid-item").eq(0),
                id = obj.data("id");
            newsinfoModel.getPicsInfo({"id":id}, function(data){
                $("#modalDialog").html(template("tplUpdatePannel", data)).modal("show");
                loadSummernote($('#ueditor_content'));
            }, failure);
        });

        $("#modalDialog").delegate("#formEdit", "submit", function(){
            if(required_check(this.title.value) == false){
                this.title.focus();
                return false;
            }
            var formdata = $(this).serializeArray();
            formdata.push({"name":"content", "value":$("#ueditor_content").code()});
            newsinfoModel.picsUpdate(formdata, function(data){
                $(".grid-item[data-id='"+data.id+"']").find("h3").text(data.title);
                $("#modalDialog").html("").modal("hide");
            }, failure);
            return false;
        });

        $("#masonry").delegate(".js-del", "click", function(){
            var obj = $(this).parents(".grid-item").eq(0),
                id = obj.data("id");
            dialog({
                content: '<i class="fa fa-info-circle"></i> 确定要删除此图片吗？',
                ok: function(){
                    newsinfoModel.picsDelete({"id":id}, function(){
                        $("#masonry").masonry("remove", obj).masonry("layout");;
                    }, failure);
                },
                cancel: true
            }).showModal();
        });

        var container = $("#masonry");
        container.imagesLoaded(function(){
            container.masonry({
                itemSelector : ".grid-item",
                columnWidth: ".grid-item",
                percentPosition: true
            });
        });
    });
</script>
@endsection