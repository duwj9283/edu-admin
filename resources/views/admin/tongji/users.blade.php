@extends('admin.main')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>总用户数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$all_count}}</h1>

                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>老师总数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$teacher_count}}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>学生总数</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$student_count}}</h1>
                    </div>
                </div>
            </div>

        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>用户统计</h5>

            </div>
            <div class="ibox-content">

                <div class="input-group">
                    <div class="btn-group" style="float:left;">
                        <button type="button" class="btn btn-white js-search " data-type=1>今天</button>
                        <button type="button" class="btn  btn-white active js-search" data-type=2>7天</button>
                        <button type="button" class="btn  btn-white js-search" data-type=3>30天</button>
                    </div>
                    <div class="form-group" id="data_5" style="float:left;">
                        <label class="col-sm-3 control-label"></label>
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class=" form-control" name="start" value="{{date('Y-m-d',strtotime('-1 week'))}}">
                            <span class="input-group-addon">至</span>
                            <input type="text" class=" form-control" name="end" value="{{date('Y-m-d')}}">
                            <span class="input-group-btn"><button type="button" class="btn  btn-primary js-range-search" > 查询</button> </span>
                        </div>
                    </div>


                </div>

                <div id="c1"></div>

            </div>
        </div>
    </div>


@endsection

@section('pageheader')
    <link href="{{cdn1('assets/datepicker/datepicker3.css')}}" rel="stylesheet">
@endsection
@section('pagescript')
    <script src="{{asset('assets/g2/index.js')}}"></script>
    <script src="{{asset('assets/datepicker/bootstrap-datepicker.js')}}"></script>

    <script>
        $(function(){


            $("#side-menu li[rel='tongji']").addClass("active")
                .find("ul").addClass("in")
                .find("li[rel='1']").addClass("active");

            $('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true,
                format:'yyyy-mm-dd'
            });

            var param={type:2};//初始化查询条件
            var day=[];//时间数组
            var student=[];//学生数组
            var teacher=[];//老师数组

            var chart = new G2.Chart({
                id: 'c1',
                forceFit: true,

                height: 500
            });
            var getChart=function(param){

                $.get('/admin/tongji/users-chart',param,function(list){
                    chart.clear();
                    day=list.day;
                    for(i=0;i<day.length;i++){
                        student_obj={};
                        student_obj.day=day[i];
                        student_obj.count=list.student[day[i]]?list.student[day[i]]:0;
                        student_obj.name='学生';
                        student.push(student_obj);
                        teacher_obj={};//老师
                        teacher_obj.day=day[i];
                        teacher_obj.count=list.teacher[day[i]]?list.teacher[day[i]]:0;
                        teacher_obj.name='老师';
                        teacher.push(teacher_obj);
                    }
                    var data = student.concat(teacher);//学生与老师数组合并


                    var defs = {
                        'day': {
                            alias: '天数',
                            type: 'cat',
                            values: day
                        },
                        'name': {
                            alias: '统计'
                        },
                        'count': {
                            alias: '人数'
                        }
                    };

                    chart.source(data, defs);
                    chart.line().position('day*count').color('name').size(2);
                    chart.render();
                })

            }
            getChart(param);

            //今天 7天 30天 按钮
            $('.js-search').click(function(){
                $(this).addClass('active').siblings('button').removeClass('active');
                param.type=$(this).data('type');
                getChart(param);
            })
            //时间区域查询
            $('.js-range-search').click(function(){

                param.type=4;
                param.start=$('input[name="start"]').val();
                param.end=$('input[name="end"]').val();
                if(!param.start){
                    $('input[name="start"]').focus();return false;
                }
                if(!param.end){
                    $('input[name="end"]').focus();return false;
                }
                if( Date.parse(new Date(param.start))> Date.parse(new Date(param.end))){
                    //转换为时间戳比较大小
                    failure('请正确填写时间区域！');return false;
                }
                $('.js-search').removeClass('active');
                getChart(param);
            })

        })
    </script>
@endsection