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
    <style>
        .help-list{font-size: 16px; border-bottom: 1px solid #ddd;padding:0 15px;}
        .help-list li{border-top: 1px dashed #e7eaec;padding: 10px 0 10px 15px; margin:0;}
        .help-list li p{margin: 0;}
        .help-list li:hover{background: #f5f5f5;}
        .help-list li:first-child{border-top: none;}
        .help-list strong{float: right;padding-right:1em;font-weight: normal;}
        .pagination{padding:0; margin:10px auto 0;}
    </style>
</head>
<body class="pace-done boxed-layout">
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <i class="fa fa-question-circle fa-3x"></i>
                            <strong class="text-white">帮助中心</strong>
                        </div>
                        <div class="logo-element">Help</div>
                    </li>
                    @foreach($app_rows as $row)
                    <li>
                        @if(empty($row->children))
                        <a href="/help/app/{{$row->id}}"> <i class="fa fa-th-large"></i>
                            <span class="nav-label">{{$row->name}}</span>
                        </a>
                        @else
                        <a href="javascript:;"> <i class="fa fa-th-large"></i>
                            <span class="nav-label">{{$row->name}}</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            @foreach($row->children as $row)
                            <li>
                                <a href="/help/app/{{$row->id}}">{{$row->version}}</a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                    @foreach($news_rows as $row)
                    <li>
                        @if(empty($row->children))
                        <a href="/help/news/{{$row->id}}"> <i class="fa fa-th-large"></i>
                            <span class="nav-label">{{$row->name}}</span>
                        </a>
                        @else
                        <a href="javascript:;"> <i class="fa fa-th-large"></i>
                            <span class="nav-label">{{$row->name}}</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level collapse">
                            @foreach($row->children as $row)
                            <li>
                                <a href="/help/news/{{$row->id}}">{{$row->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </nav>
        <div id="page-wrapper" class="gray-bg">
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>帮助中心</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            <a>Layout</a>
                        </li>
                        <li class="active"> <strong>Grid Opons</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2"></div>
            </div>
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <ul class="help-list list-unstyled">
                                    <li>
                                        <strong>2010-10-20 10:12</strong>
                                        <p><a href="#">But I must explain</a></p>
                                    </li>
                                    <li>
                                        <strong>2010-10-20 10:12</strong>
                                        <p><a href="#">To you how all this mistaken</a></p>
                                    </li>
                                </ul>
                                <div class="text-center">
                                <ul class="pagination">
                                    <li>
                                        <a href="#" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">1</a>
                                    </li>
                                    <li>
                                        <a href="#">2</a>
                                    </li>
                                    <li>
                                        <a href="#">3</a>
                                    </li>
                                    <li>
                                        <a href="#">4</a>
                                    </li>
                                    <li>
                                        <a href="#">5</a>
                                    </li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <div class="pull-right">
                    蚌埠医学院录播系统 © 2016
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
</body>
</html>
