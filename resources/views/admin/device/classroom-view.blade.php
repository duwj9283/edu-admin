@extends('admin.main')
@section('pageheader')
    <style>
        #tblDataList .hoverTr {
            background-color: #f5f5f5;
        }
        .wrapper-content{
            background-color: #20242a;
            padding:0;
        }
        #page-wrapper{
            background-color: #20242a;
            padding:0;
        }

        .row{
            background-color:#20242a;
        }
        .ibox-content{
            background-color:#20242a;
            padding:0px 20px 0 20px;
            border:0;
        }
        .play-video{
            width:768px;


        }
        h1 {
            font-size: 16px;
            font-family: "Microsoft YaHei";
            color: rgb(255, 255, 255);
            font-weight: normal;
            line-height: 50px;


        }

        .ibox{
            margin-bottom:0;
        }
        .four {
            width: 100%;
            list-style: none;
            padding:0px;
            margin:0;
            text-align: left;
            z-index: 999;


        }


        h3 {
            font-weight: normal;
            font-size: 16px;
            font-family: "Microsoft YaHei";
            color: rgb(188, 188, 188);
            line-height: 30px;
            height: 30px;
            background-color: #353e47;
            width:192px;
            padding-left: 14px;
            margin-top:0px !important;
            margin-bottom:0px;
            text-align: left;
        }

        .four li {
            float: left;
            width:192px;
        }
        #j-flashArea-detail-player{
            width:768px;
            height:108px;
            z-index:999;

        }
        tbody{
            border:1px solid #fff;
        }
    </style>
@endsection
@section('content')
    <div class="wrapper wrapper-content">


        <div class="ibox float-e-margins">

            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-2" style="max-height:800px;overflow:auto">
                        <h1>教室列表</h1>
                        <table id="tblDataList" class="table table-hover">

                            @foreach($rows as $key=>$row)
                                <tr data-id="{{$row->device_id}}">
                                    <td>{{$key+1}}.{{$row->title}}</td>

                                </tr>
                            @endforeach


                        </table>
                    </div>
                    <div class="col-sm-10">
                        <h1>教室巡视</h1>
                        <div class="play-video">
                            <div id="j-flashArea-player"></div>
                        </div>
                            <ul class="four">
                                <li>
                                    <h3>PPT</h3>

                                </li>
                                <li>
                                    <h3>老师近景</h3>

                                </li>
                                <li>
                                    <h3>老师全景</h3>

                                </li>
                                <li>
                                    <h3>学生全景</h3>

                                </li><li>
                                    <h3>PPT</h3>

                                </li>
                                <li>
                                    <h3>老师近景</h3>

                                </li>


                            </ul>

                        <div id="j-flashArea-detail-player"></div>



                    </div>
                </div>

            </div>
        </div>


    </div>



@endsection


@section('pagescript')
    <script src="{{asset('assets/swfobject/swfobject.js')}}"></script>

    <script>
        var artDialog;
        $("#side-menu li[rel='classroom']").addClass("active")
                .find("ul").addClass("in")
                .find("li[rel='2']").addClass("active");
        var vs2_serv = JSON.parse('{!! $vs2_serv!!}');
        var createStreamPlayer = function (c) {
            var xiSwfUrlStr = "{{asset('assets/swfobject/expressInstall.swf')}}";
            var flashvars = {
                "log": "all",
                "url": 'rtmp://' + c.ip + ':1935/live',
                "streamname": c.stream_name,
                "buffer": 0.1
            };
            swfobject.embedSWF(c.preview, c.id, "768px", "108px", "10.1.0", xiSwfUrlStr, flashvars);
        };

        $('#tblDataList tr').click(function () {
            var id = $(this).data('id');
            $(this).addClass('hoverTr').siblings('tr').removeClass('hoverTr');
            $.get('/admin/device/classroom-view-device', {id: id}, function (data) {
               
                createStreamPlayer({
                    ip: '192.168.1.98',
                    stream_name: 'stream5',
                    preview: '/assets/previewVideo.swf',
                    id: 'j-flashArea-player'
                });
                createStreamPlayer({
                    ip: data.ip,
                    stream_name: 'stream7',
                    preview: '/assets/previewVideo-small.swf',
                    id: 'j-flashArea-detail-player'
                });
            })

        });

        $('#tblDataList tr:first').trigger('click');
    </script>
@endsection