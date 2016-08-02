@extends('admin.main')

@section('title', 'Administrator\'s Control Panel')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox m-b-none">
        <div class="ibox-title">
          <h5> <i class="fa fa-flag"></i>
            欢迎您
          </h5>
          <div class="ibox-tools">
            <a class="collapse-link"> <i class="fa fa-chevron-down"></i></a>
            <a class="close-link"> <i class="fa fa-times"></i></a>
          </div>
        </div>
        <div class="ibox-content">
          <div class="p-xs">
            <div class="pull-left m-r-md">
              <img alt="image" class="img-circle" src="{{getAvatar(session('token')['user_id'])}}" width="66" />
            </div>
            <h2>Welcome</h2>
            欢迎进入 <strong>{{$meta_title}}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row m-t">
    <div class="col-lg-8">
      <div class="ibox">
        <div class="ibox-title">
          <h5> <i class="fa fa-dashboard"></i>
            系统基本信息
          </h5>
        </div>
        <div class="ibox-content">
          <table class="table table-bordered table-striped">
            <tr>
              <th class="text-right">主机名：</th>
              <td>
                <b class="green">{{ $site_protocol . $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] }}</b>
              </td>
            </tr>
            <tr>
              <th class="col-md-3 text-right">服务器IP地址：</th>
              <td class="col-md-9"> <b class="red">{{ gethostbyname('') }}</b>
              </td>
            </tr>
            <tr>
              <th class="text-right">本机IP地址：</th>
              <td> <b class="green">{{ $client_ip }}</b>
              </td>
            </tr>
            <tr>
              <th class="text-right">服务器软件：</th>
              <td>
                <b class="red">{{ $_SERVER['SERVER_SOFTWARE'] }}</b>
              </td>
            </tr>
            <tr>
              <th class="text-right">操作系统及 PHP：</th>
              <td>
                <b class="red">{{ PHP_OS . ' / PHP v' . PHP_VERSION }}</b>
              </td>
            </tr>
            <tr>
              <th class="text-right">MySQL 版本：</th>
              <td>
                <b class="green">{{ head(head($dbversion)) }}</b>
              </td>
            </tr>
            <tr>
              <th class="text-right">上传许可：</th>
              <td>
                <b class="red">{{ $fileupload }}</b>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-4" style="display:none;">
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-group"></i> 开发团队</h5>
          <div class="ibox-tools">
            <a class="collapse-link"> <i class="fa fa-chevron-down"></i>
            </a>
            <a class="close-link">
              <i class="fa fa-times"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              界面交互设计：<a href="http://hi.baidu.com/ibweb" target="_blank">alex（华东）</a>
            </li>
            <li class="list-group-item ">
              前端框架开发：<a href="http://www.chinamx.com" target="_blank">jess（郭俊杰）</a>
            </li>
            <li class="list-group-item">
              程序代码开发：<a href="http://www.iemaker.com" target="_blank">daing（戴能干）</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="ibox">
        <div class="ibox-title">
          <h5><i class="fa fa-list"></i> 特别感谢</h5>
          <div class="ibox-tools">
            <a class="collapse-link"> <i class="fa fa-chevron-up"></i>
            </a>
            <a class="close-link">
              <i class="fa fa-times"></i>
            </a>
          </div>
        </div>
        <div class="ibox-content no-padding">
          <ul class="list-group">
            <li class="list-group-item">
              为 WEB 艺术家创造的 PHP 框架 -
              <a href="http://www.golaravel.com/" target="_blank">Laravel</a>
              v5.1.11
            </li>
            <li class="list-group-item ">
              简洁、直观、强悍的前端开发框架 -
              <a href="http://www.bootcss.com/" target="_blank">Bootstrap</a>
            </li>
            <li class="list-group-item">
              简单，强大，优雅的对话框组件 -
              <a href="http://aui.github.io/artDialog/" target="_blank">artDialog</a>
              作者：
              <a href="http://t.qq.com/tangbin" target="_blank">糖饼</a>
            </li>
            <li class="list-group-item">
              <a href="http://aui.github.io/artTemplate/" target="_blank">artTemplate</a>
              - 超快的前端模板引擎 -
              <a href="http://cdc.tencent.com/?p=5723" target="_blank">查看引擎原理</a>
            </li>
            <li class="list-group-item">
              <span class="lbl">
                <a href="http://seajs.org/" target="_blank">Seajs</a>
                - 提供简单、极致的模块化开发体验
              </span>
            </li>
            <li class="list-group-item">
            <a href="http://fex.baidu.com/" target="_blank">百度WEB前端研发部</a>开发的富文本web编辑器 -
              <span class="lbl">
                <a href="http://ueditor.baidu.com/" target="_blank">UEditor</a>
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('pagescript')
<script>
  $("#side-menu li[rel='welcome']").addClass("active");
</script>
@endsection
