<?php
$token = session('token');
$user = App\Models\WebUser::find($token['user_id']);
$meta_title = App\Models\Siteconfig::where('option_name', 'meta_title')->pluck('option_value');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrator\'s Control Panel')</title>
    <link href="{{cdn1('assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/inspinia/css/animate.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/inspinia/css/style.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/artDialog/css/ui-dialog.min.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/common/app.css')}}" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('pageheader')
</head>
<body>
    <div id="wrapper">
        @include('admin/navbar')
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="javascript:;">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="m-r">
                            <a href="{{config('services.frontend')['url']}}" target="_blank"> <i class="fa fa-home"></i>网站首页</a>
                        </li>
                        <li class="m-r">
                            <a href="{{url('help')}}" target="_blank"> <i class="fa fa-question-circle"></i>帮助中心</a>
                        </li>
                        <li>
                            <a href="{{url('admin/logout')}}" onclick="return confirm('您确定要退出吗？');"> <i class="fa fa-sign-out"></i>退出</a>
                        </li>
                    </ul>
                </nav>
            </div>
            @yield('content')
            <div class="footer">
                <div class="pull-right">
                    {{$meta_title}} &copy; 2016
                </div>
            </div>
        </div>
    </div>
    <!-- FullScreenMask & ModalDialog -->
    <div id="loadingMask" class="loading" aria-label="Loading" tabindex="-1"></div>
    <div id="modalDialog" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"></div>
    <!-- jquery & bootstrap -->
    <script src="{{cdn1('assets/jquery/1.11.3/jquery.min.js')}}"></script>
    <script src="{{cdn1('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- Custom and plugin javascript -->
    <script src="{{cdn1('assets/jquery/plugins/jquery.metisMenu.js')}}"></script>
    <script src="{{cdn1('assets/jquery/plugins/jquery.slimscroll.min.js')}}"></script>
    <script src="{{cdn1('assets/inspinia/js/inspinia.js')}}"></script>
    <script src="{{cdn1('assets/inspinia/js/pace.min.js')}}"></script>
    <!-- artDialog & artTemplate -->
    <script src="{{cdn1('assets/artDialog/dist/dialog-plus-min.js')}}"></script>
    <script src="{{cdn1('assets/artTemplate/template.js')}}"></script>
    <script id="seajsnode" src="{{cdn1('assets/seajs/sea.js')}}"></script>
    <script src="{{cdn1('assets/controllers/util.js')}}"></script>
    @yield('pagescript')
</body>
</html>
