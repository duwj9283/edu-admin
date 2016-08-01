@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="tabs-container">
    <ul class="nav nav-tabs">
      <li class="active">
        <a href="#tab-logo" data-toggle="tab">Logo图片</a>
      </li>
      <li>
        <a href="#tab-meta" data-toggle="tab">Meta设置</a>
      </li>
    </ul>
    <div class="tab-content">
      <div id="tab-logo" class="tab-pane active">
        <div class="panel-body">
          <div id="filePicker">选择图片</div>
          <img id="imgLogo" src="{{$logo}}" class="img-thumbnail img-responsive" />
        </div>
      </div>
      <div id="tab-meta" class="tab-pane">
        <div class="panel-body">
          <form id="formSiteinfo" method="post" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label">网站标题</label>
              <div class="col-sm-10">
                <input type="text" name="meta_title" value="{{$meta['meta_title']}}" class="form-control" required />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">底部信息</label>
              <div class="col-sm-10">
                <textarea id="ueditor_copyright" name="meta_copyright">{{$meta['meta_copyright']}}</textarea>
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> 保存配置</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('pageheader')
<link href="{{cdn1('assets/WebUploader/webuploader.css')}}" rel="stylesheet" />
@endsection

@section('pagescript')
<script src="{{cdn1('assets/ueditor/ueditor.config.js')}}"></script>
<script src="{{cdn1('assets/ueditor/ueditor.all.js')}}"></script>
<script src="{{cdn1('assets/WebUploader/webuploader.js')}}"></script>
<script>
  $("#side-menu li[rel='entrust']").addClass("active")
    .find("li[rel='siteconfig']").addClass("active");

  var ueditor = UE.getEditor('ueditor_copyright');
  $("#formSiteinfo").on('submit', function(){
    var that = this;
    $(this).find(":submit").attr("disabled", "disabled");
    var url = "{{url('admin/sitecfg/set-meta')}}";
    $.post(url, $(this).serialize(), function(data){
      $(that).find(":submit").removeAttr("disabled");
      var d = dialog({
        title: null,
        content: '<i class="fa fa-check-circle"></i> 修改成功',
      }).show();
      setTimeout(function(){d.close().remove();}, 2000);
    }).fail(failure);
    return false;
  });

  var uploader = WebUploader.create({
    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径
    swf: '/assets/WebUploader/Uploader.swf',
    // 文件接收服务端。
    server: "{{url('admin/sitecfg/upload-logo')}}",
    // 文件上传请求的参数表
    formData: {},
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
      title: 'Images',
      extensions: 'gif,jpg,jpeg,bmp,png',
      mimeTypes: 'image/*'
    }
  });
  uploader.on("uploadSuccess", function(file, result){
    if(result.status == "0"){
      failure(result.msg);
      return;
    }
    $("#imgLogo").attr("src", result.url);
  });
</script>
@endsection
