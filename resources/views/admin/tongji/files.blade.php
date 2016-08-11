@extends('admin.main')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>文件总数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$all_count}}</h1>

                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>发布文件数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$all_push_count}}</h1>

                    </div>
                </div>
            </div>

        </div>
        <div class="ibox float-e-margins">

            <div class="ibox-title">
                <div class="btn-group">
                    <button class="btn btn-white primary" type="button" data-type="1">文件按学科统计报表</button>
                    <button class="btn btn-white" type="button"  data-type="2">文件按应用类型统计报表</button>
                    <button class="btn btn-white" type="button"  data-type="3">前十名上传文件数最多统计</button>
                </div>

            </div>
            <div class="ibox-content">


                <div id="c1"></div>

            </div>

        </div>
    </div>


@endsection

@section('pageheader')
    <style>
        .primary{
            background-color:#18a689;
            color:#FFFFFF;
            border-color: #18a689;
        }
    </style>
@endsection
@section('pagescript')
    <script src="{{asset('assets/g2/index.js')}}"></script>

    <script>
        $(function(){


            $("#side-menu li[rel='tongji']").addClass("active")
                .find("ul").addClass("in")
                .find("li[rel='2']").addClass("active");

            var Stat = G2.Stat;
            var chart = new G2.Chart({
                id: 'c1',
                forceFit: true,
                height: 700,
                plotCfg: {
                    margin: [20, 100, 60, 80]
                }
            });

            $('.btn-group button').click(function(){
                $(this).addClass('primary').siblings('button').removeClass('primary');
                type=$(this).data('type');
                $.get('/admin/tongji/files-by-type',{type:type},function(data){
                    chart.clear();
                    var name=(type==1)?'学科':((type==2)?'应用类型':'人员');
                    // 配置列定义,设置y轴的最小值
                    var colDefs = {
                        count: {
                            min: 0,
                            alias: '总数'
                        },
                        name: {
                            alias: name
                        }
                    };

                    chart.source(data, colDefs);
                    chart.interval().position(Stat.summary.mean('name*count')).color('name');
                    chart.render();
                })
            });


            $('.btn-group button:first').trigger('click');

        })
    </script>
@endsection