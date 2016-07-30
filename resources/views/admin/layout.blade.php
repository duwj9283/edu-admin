<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{cdn1('assets/bootstrap/css/bootstrap.min.css')}}" />
    <!--[if lt IE 9]>
    <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="{{cdn1('assets/font-awesome/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{cdn1('assets/inspinia/css/animate.css')}}" />
    <link rel="stylesheet" href="{{cdn1('assets/inspinia/css/style.css')}}" />
    <link rel="stylesheet" href="{{cdn1('assets/common/app.css')}}" />
    @yield('pageheader')
</head>
<body>
    @yield('pagebody')
    <!-- FullScreenMask & ModalDialog -->
    <div id="loadingMask" class="loading" aria-label="Loading" tabindex="-1"></div>
    <div id="modalDialog" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"></div>
    <!-- jquery & bootstrap -->
    <script src="{{cdn1('assets/jquery/1.11.3/jquery.min.js')}}"></script>
    <script src="{{cdn1('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- artDialog & artTemplate -->
    <link href="{{cdn1('assets/artDialog/css/ui-dialog.min.css')}}" rel="stylesheet" />
    <script src="{{cdn1('assets/artDialog/dist/dialog-plus-min.js')}}"></script>
    <script id="seajsnode" src="{{cdn1('assets/seajs/sea.js')}}"></script>
    <script src="{{cdn1('assets/controllers/util.js')}}"></script>
    @yield('pagescript')
</body>
</html>
