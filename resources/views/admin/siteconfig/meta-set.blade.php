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
      <li>
        <a href="#tab-banner" data-toggle="tab">Banner图</a>
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
      <div id="tab-banner" class="tab-pane">
        <div class="panel-body row">
          <div class="col-lg-6">
            <div class="form-group">
              <select id="channelSelector" class="form-control" style="width:auto;">
                <option value="">- 请选择频道 -</option>
                <option value="site_banner1">发现</option>
                <option value="site_banner2">资源中心</option>
                <option value="site_banner3">在线课程</option>
                <option value="site_banner4">直播课堂</option>
                <option value="site_banner5">精品微课</option>
                <option value="site_banner6">名师空间</option>
                <option value="site_banner7">公告中心</option>
              </select>
            </div>
            <div id="tblBannerList"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script id="tplBannerList" type="text/html">
  <div id="bannerPicker"><i class="fa fa-cloud-upload"></i> 选择图片</div>
  <ul id="banner-list" class="list-unstyled">
    <%each list as item i%>
    <li>
      <button class="btn btn-danger" onclick="removeBanner('<%item%>')"><i class="fa fa-trash"></i> 删除此图片</button>
      <p><img src="{{$frontend['url']}}<%item%>" class="img-thumbnail img-responsive" /></p>
    </li>
    <%/each%>
  </ul>
</script>

<script id="tplBannerListTr" type="text/html">
  <li>
    <button class="btn btn-danger" onclick="removeBanner('<%url%>')"><i class="fa fa-trash"></i> 删除此图片</button>
    <p><img src="{{$frontend['url']}}<%url%>" class="img-thumbnail img-responsive" /></p>
  </li>
</script>
@endsection

@section('pageheader')
<link href="{{cdn1('assets/WebUploader/webuploader.css')}}" rel="stylesheet" />
<style>
#banner-list li{position:relative;}
#banner-list li button{position:absolute;left:10px;top:10px;display:none;}
#banner-list li:hover > button{display:block;}
</style>
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
    auto: true,
    swf: '/assets/WebUploader/Uploader.swf',
    server: "{{url('admin/sitecfg/upload-logo')}}",
    formData: {},
    pick: '#filePicker',
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

  var bannerUploader;
  /*var bannerUploaderInit = function(){
    bannerUploader = WebUploader.create({
      auto: true,
      swf: '/assets/WebUploader/Uploader.swf',
      server: "{{url('admin/sitecfg/upload-banner')}}",
      formData: {},
      pick: '#bannerPicker',
      accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
      }
    });
    bannerUploader.on("uploadSuccess", function(file, result){
      if(result.status == "0"){
        failure(result.msg);
        return;
      }
      $("#banner-list").append(template("tplBannerListTr", result));
    });
  };*/

  var removeBanner = function(src){
    var obj = event.target, option_name = $("#channelSelector").val();
    dialog({
      content:'<i class="fa fa-info-circle"></i> 确定要删除此图片吗？',
      ok:function(){
        $.post("{{url('admin/sitecfg/remove-banner')}}", {"option_name":option_name, "src":src}, function(){
          $(obj).parents("li").remove();
        }).fail(failure);
      },
      cancel: true
    }).showModal();
  };

  var loadBannerUploader = function(option_name){
    bannerUploader = WebUploader.create({
      auto: true,
      swf: "{{asset('assets/WebUploader/Uploader.swf')}}",
      server: "{{url('admin/sitecfg/upload-banner')}}",
      formData: {"option_name": option_name},
      pick: '#bannerPicker',
      accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
      }
    });
    bannerUploader.on("uploadSuccess", function(file, result){
      if(result.status == "0"){
        failure(result.msg);
        return;
      }
      $("#banner-list").append(template("tplBannerListTr", result));
    });
  };

  $("#channelSelector").on("change", function(){
    var option_name = this.value;
    if(option_name == ""){
      $("#tblBannerList").html("");
      bannerUploader = undefined;
      return;
    }
    $("#tblBannerList").html('<p class="ibox-loading-31"></p>');
    $.get("{{url('admin/sitecfg/banner-list')}}", {"option_name":option_name}, function(data){
      $("#tblBannerList").html(template("tplBannerList", data));
      loadBannerUploader(option_name);
    }).fail(failure);
  });

  /*$('a[data-toggle="tab"]').on('shown.bs.tab',function(e){
    var target = e.target.toString();
    if(target.indexOf('tab-banner') > 0){
      if(bannerUploader == undefined){
        bannerUploaderInit();
      }
    }
  });*/
</script>
@endsection
