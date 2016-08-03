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
                        <ul class="help-list list-unstyled">
                            @foreach($rows as $row)
                            <li>
                                <strong>{{$row->created_at}}</strong>
                                <p><a href="{{url('help/info/'.$row->id)}}">{{$row->title}}</a></p>
                            </li>
                            @endforeach
                        </ul>
                        <div id="pagination" class="text-center">{!! $rows->render(); !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='{{$column->id}}']").addClass("active").parents("li").addClass("active");
</script>
@endsection
