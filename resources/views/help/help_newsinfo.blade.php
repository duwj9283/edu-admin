@extends('help.main')

@section('pageheader')
    <style>
        .help-list{font-size: 16px; border-bottom: 1px solid #ddd;padding:0 15px;}
        .help-list li{border-top: 1px dashed #e7eaec;padding: 10px 0 10px 15px; margin:0;}
        .help-list li p{margin: 0;}
        .help-list li:hover{background: #f5f5f5;}
        .help-list li:first-child{border-top: none;}
        .help-list strong{float: right;padding-right:1em;font-weight: normal;}
        .pagination{padding:0; margin:10px auto 0;}
    </style>
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <a href="javascript:;" class="navbar-minimalize btn btn-primary pull-left m-t m-r-xs">
                <i class="fa fa-bars"></i>
            </a>
            <h2>帮助中心</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('help')}}">帮助中心</a>
                </li>
                <li>
                    <a>{{$column->name}}</a>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        @if($info)
                        <div class="text-center">
                            <h1>{{$info->title}}</h1>
                            <span class="text-muted"> <i class="fa fa-clock-o"></i>
                                {{$info->updated_at}}
                            </span>
                        </div>
                        <hr />
                        <div style="line-height:220%;">{!! $info->content !!}</div>
                        @else
                        <div class="text-center">
                            <h1>{{$column->name}}</h1>
                        </div>
                        <hr />
                        <div style="line-height:220%;">暂无内容</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script id="tplNewsList" type="text/html">
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
        <div id="pagination" class="text-center"></div>
    </script>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='{{$column->id}}']").addClass("active").parents("li").addClass("active");
</script>
@endsection
