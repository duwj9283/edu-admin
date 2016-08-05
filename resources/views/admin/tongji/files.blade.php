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


        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>文件按学科统计报表</h5>

            </div>
            <div class="ibox-content">

                <div id="c1"></div>

            </div>
            <div class="ibox-title">
                <h5>文件按应用类型统计报表</h5>

            </div>
            <div class="ibox-content">


                <div id="c2"></div>

            </div>
            <div class="ibox-title">
                <h5>前十名上传文件数最多统计</h5>

            </div>
            <div class="ibox-content">



                <div id="c3"></div>

            </div>
        </div>
    </div>


@endsection

@section('pageheader')
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
                height: 800,
                plotCfg: {
                    margin: [20, 100, 60, 80]
                }
            });
            // 配置列定义,设置y轴的最小值
            var colDefs = {
                count: {
                    min: 0,
                    alias: '总数'
                },
                subject_name: {
                    alias: '学科'
                }
            };
            var subject_count=JSON.parse('{!! $subject_count !!}');

            chart.source(subject_count, colDefs);
            chart.interval().position(Stat.summary.mean('subject_name*count')).color('subject_name');
            chart.render();

            var chart = new G2.Chart({
                id: 'c2',
                forceFit: true,
                height: 600,
                plotCfg: {
                    margin: [20, 100, 60, 80]
                }
            });
            // 配置列定义,设置y轴的最小值
            var colDefs = {
                count: {
                    min: 0,
                    alias: '总数'
                },
                name: {
                    alias: '应用类型'
                }
            };
            var applicationType_count =JSON.parse('{!! $applicationType_count !!}');
            chart.source(applicationType_count, colDefs);
            chart.interval().position(Stat.summary.mean('name*count')).color('name');
            chart.render();

            var chart = new G2.Chart({
                id: 'c3',
                forceFit: true,
                height: 500,
                plotCfg: {
                    margin: [20, 100, 60, 80]
                }
            });
            // 配置列定义,设置y轴的最小值
            var colDefs = {
                count: {
                    min: 0,
                    alias: '总数'
                },
                realname: {
                    alias: '人员'
                }
            };
            var users_count=JSON.parse('{!! $users_count !!}');


            chart.source(users_count, colDefs);
            chart.interval().position(Stat.summary.mean('realname*count')).color('realname');
            chart.render();


        })
    </script>
@endsection