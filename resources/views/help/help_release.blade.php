@extends('help.main')

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
                    <a>{{$app->name}}</a>
                </li>
                <li class="active"> <strong>{{$info->version}}</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="text-center">
                            <h1>{{$app->name}} - {{$info->version}}</h1>
                            <span class="text-muted"> <i class="fa fa-clock-o"></i>
                                {{$info->updated_at}}
                            </span>
                        </div>
                        <hr />
                        <div style="line-height:220%;">{!! nl2br($info->description) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
<script>
    $("#side-menu li[rel='helpapps-{{$app->id}}']").addClass("active")
        .find("li[rel='{{$info->id}}']").addClass("active");
</script>
@endsection
