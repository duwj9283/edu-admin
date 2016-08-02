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
</head>
<body>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                </li>
                @foreach($menulist as $item)
                    <li>
                        <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">{{$item->level1}}</span> <span
                                    class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            @foreach($item->level2 as $item2)
                                <li><a href="/?type=0&level1=1&level2=1">{{$item2->name}}</a></li>
                                @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <span class="m-r-sm text-muted welcome-message">欢迎来到帮助中心</span>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                            <i ></i> <span class="label label-warning"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Article</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a>Miscellaneous</a>
                    </li>
                    <li class="active">
                        <strong>Article</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
            </div>
        </div>

        <div class="wrapper wrapper-content  animated fadeInRight article">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="text-center article-title">
                                    {{$content}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Mainly scripts -->
<script src="{{cdn1('assets/jquery/1.11.3/jquery.min.js')}}"></script>
<script src="{{cdn1('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{cdn1('assets/jquery/plugins/jquery.metisMenu.js')}}"></script>
<script src="{{cdn1('assets/jquery/plugins/jquery.slimscroll.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{cdn1('assets/inspinia/js/inspinia.js')}}"></script>
<script src="{{cdn1('assets/inspinia/js/pace.min.js')}}"></script>

</body>

</html>
