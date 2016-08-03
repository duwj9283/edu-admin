<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>帮助中心</title>
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
<body class="pace-done boxed-layout">
    <div id="wrapper">
        @include('help/navbar')
        <div id="page-wrapper" class="gray-bg">
            @yield('content')
            <div class="footer">
                <div class="pull-right">
                    蚌埠医学院录播系统 © 2016
                </div>
            </div>
        </div>
    </div>
    <!-- jquery & bootstrap -->
    <script src="{{cdn1('assets/jquery/1.11.3/jquery.min.js')}}"></script>
    <script src="{{cdn1('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- Custom and plugin javascript -->
    <script src="{{cdn1('assets/jquery/plugins/jquery.metisMenu.js')}}"></script>
    <script src="{{cdn1('assets/jquery/plugins/jquery.slimscroll.min.js')}}"></script>
    <script src="{{cdn1('assets/inspinia/js/inspinia.js')}}"></script>
    <script src="{{cdn1('assets/inspinia/js/pace.min.js')}}"></script>
    @yield('pagescript')
</body>
</html>
