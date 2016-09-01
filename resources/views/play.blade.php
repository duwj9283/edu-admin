<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link href="{{cdn1('assets/bootstrap/css/bootstrap.min2.3.2.css')}}" rel="stylesheet">
    <link href="{{cdn1('assets/artDialog/css/ui-dialog.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{cdn1('assets/play/css/sty.css')}}">
    <title>蚌埠</title>
    <style>

    </style>
</head>
<body>
<div class="header">
    <div class="warp-width">
        <div class="top-header"><img src="/assets/play/images/logo.png" width="300" alt=""></div>
    </div>
</div>
<div class="main">
    <div class="warp-width">
        <div class="content">
            <div class="top">
                <div class="left" data-volume="{{$device['volume']}}" data-ip="{{$device['ip']}}" data-live="{{$liveId}}"  data-record="@if($device['record_time']) {{time()-$device['record_time']}} @else 0 @endif"   id="player">
                    <div id="j-flashArea-player" ></div>
                </div>
                <div class="right">
                    <div class="hand-bro">
                        <div class="hand-title">
                            手动导播
                        </div>
                        <ul class="ul-box" id="play-win">
                            <li><img src="/assets/play/images/li1.png" alt=""></li>
                            <li><img src="/assets/play/images/li2.png" alt=""></li>
                            <li><img src="/assets/play/images/li3.png" alt=""></li>
                            <li><img src="/assets/play/images/li4.png" alt=""></li>
                            <li><img src="/assets/play/images/li5.png" alt=""></li>
                            <li><img src="/assets/play/images/li6.png" alt=""></li>
                            <li><img src="/assets/play/images/li7.png" alt=""></li>
                            <li><img src="/assets/play/images/li8.png" alt=""></li>
                        </ul>
                        <div class="" style="clear:both;"></div>

                    </div>
                    <div class="info-box">
                        <ul class="top-ul">
                            <li class="active info-cont-title">字幕</li>
                            <li class="info-set-title">台标</li>
                            <li class="info-cloud-title">云台</li>
                            <li class="info-lx-title">录像</li>
                        </ul>
                        <div class="info-cont">
                            <form name="subtitle" onsubmit="return false">
                                <label class="mui-music">
                                    <input class="mui-switch mui-switch-anim" type="checkbox" @if($device['subtitle_status']==1) checked @endif>
                                </label><span>使用字幕</span>
                                <div class="select row">
                                    <span>颜色：</span>
                                    <!--<input type="button" value="FF0000" class="color-bar" id="unique-id-4" name="unique-name-4" value="accent">-->
                                    <input class="color color-bar" value="{{$device['subtitle_color']}}" style="width:50px" name="txt_col">
                                    <span>字体：</span>
                                    <div class="btn-group" style="margin-right:0; display:inline-block;">
                                        <select  name="fam_id" style="width: 125px; margin: 0;">
                                            @foreach($famIdArr as $id =>$value)
                                            <option value="{{$id}}" @if($device['subtitle_fam_id']==$id) selected @endif>{{$value}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <textarea class="form-control" rows="4" name="txt" placeholder="请输入字幕">{{$device['subtitle_txt']}}</textarea>
                                <input type="submit" value="提交" name="submit" class="sub-box">
                            </form>
                        </div>
                        <div class="info-set" style="display:none;">
                            <ul id="play-tb">
                                <li><img src="/assets/play/images/box1.png" alt=""></li>
                                <li><img src="/assets/play/images/box2.png" alt=""></li>
                                <li><img src="/assets/play/images/box3.png" alt=""></li>
                                <li><img src="/assets/play/images/box4.png" alt=""></li>
                                <li><img src="/assets/play/images/box5.png" alt=""></li>
                            </ul>
                            <input type="button" value="预设" name="submit" class="sub-box" id="play-tb-detail">
                        </div>
                        <div class="info-cloud" style="display:none;">
                            <div class="left-cloud">
                                <div class="cloud" id="play-ctrl-top">
                                    <a href="javascript:void(0);" data-operation="1"></a>
                                    <a href="javascript:void(0);" data-operation="2"></a>
                                    <a href="javascript:void(0);" data-operation="3"></a>
                                    <a href="javascript:void(0);" data-operation="0"></a>

                                    <a href="javascript:void(0);" data-operation="5"></a>
                                    <a href="javascript:void(0);" data-operation="4"></a>
                                    <a href="javascript:void(0);" data-operation="7"></a>
                                    <a href="javascript:void(0);" data-operation="6"></a>
                                    <img src="/assets/play/images/circular.png" alt="" style="position:absolute;top:32px;left:35px;cursor:pointer">
                                </div>
                                <div class="increase" id="play-ctrl-bottom">
                                    <a href="javascript:;" class="left-a" data-operation="8">+</a>
                                    <a href="javascript:;" class="left-b" data-operation="9">-</a>
                                </div>
                                <input type="submit" value="自动跟踪：开" name="submit" class="sub-box">
                            </div>
                            <div class="mid"><img src="/assets/play/images/mid.png" alt=""></div>
                            <div class="right-cloud">
                                预位置：
                                <ul id="play-ctrl-top">
                                    <li>1</li>
                                    <li>2</li>
                                    <li>3</li>
                                    <li>4</li>
                                    <li>5</li>
                                    <li>6</li>
                                    <li>7</li>
                                    <li>8</li>
                                    <li>9</li>
                                    <li>10</li>
                                    <li>11</li>
                                    <li>12</li>
                                </ul>
                            </div>

                        </div>
                        <div class="info-lx" style="display:none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="middle">
                <button type="button" class="btn btn-default " id="play-RTMP" data-enable="{{$device['rtmp_status']}}">@if($device['rtmp_status']==1)直播：关@else直播：开@endif</button>
                <button type="button" class="btn btn-default btn-angle" id="play-record">@if($device['record_status']==2)录制@else停止@endif</button>
                {{--<button type="button" class="btn btn-default btn-angle" id="play-pause-record" style="display:none">@if($device['record_status']==3)恢复@else暂停@endif</button>--}}
                <span id="play-record-time">
                    @if(in_array($device['record_status'],[1,4]))录制中 [00:00:00]
                    @elseif($device['record_status']==2)未录制 [00:00:00]
                    @elseif($device['record_status']==3)暂停中 [00:00:00]
                    @endif</span>
                <button type="button" class="btn btn-default">自动导播：关</button>
                <span style="background:none;color:#126472;text-indent:0px;width:30px;">音量</span>
                <ul style="position:absolute;display:inline-block;left:755px;top:15px;">
                    <li>
                        <div class="scale_panel">

                            <div class="scale" id="bar">
                                <div></div>
                                <span id="btn"></span>
                            </div>
                        </div>
                    </li>
                </ul>

            </div>
            <div class="bot">
                <ul>
                    <li>
                        <div class="title"><input  type="checkbox" value="2" name="winMode"><span></span>VGA</div>
                    </li>
                    <li>
                        <div class="title"><span></span>老师<input type="checkbox" value="2" name="winMode"></div>
                    </li>
                    <li>
                        <div class="title"><span></span>学生<input type="checkbox" value="2" name="winMode"></div>
                    </li>
                    <li>
                        <div class="title"><span></span>板书<input type="checkbox" value="2" name="winMode"></div>
                    </li>
                    <div style="clear: both"></div>
                </ul>
                <div class="flash-wrap">
                    <div class="flash-box"></div>
                    <div class="flash-box"></div>
                    <div class="flash-box"></div>
                    <div class="flash-box"></div>
                    <div id="j-flashArea-detail-player"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myEditModal" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"></div>

<script type="text/html" id="play-tb-detail-div" >

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal setting-bar" name="tbSet" id="tbSet-form" onsubmit="return false">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal" type="button">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label">台标预设</h4>
                </div>
                <div class="modal-body">
                        <input type="hidden" value="1" name="action">
                        <input type="hidden" value="1" name="enabled">


                        <div class="form-group">
                            <label for="" class="col-sm-1 control-label">坐标X:</label>
                            <div class="col-sm-11">
                                <input type="number" class="form-control" name="x" placeholder="请输入坐标X" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-1 control-label">坐标Y:</label>
                            <div class="col-sm-11">
                                <input type="number" class="form-control" name="y" placeholder="请输入坐标Y" required>
                            </div>
                        </div>


                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-primary btn-w-m js-tb-sub" type="submit">确定</button>
                            <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</script>



<script src="{{cdn1('assets/jquery/1.11.3/jquery.min.js')}}"></script>
<script src="{{cdn1('assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{cdn1('assets/play/js/jscolor.js')}}"></script>
<script src="/assets/swfobject/swfobject.js"></script>
<script src="{{cdn1('assets/play/js/play.js')}}"></script>
<script src="{{cdn1('assets/artDialog/dist/dialog-plus-min.js')}}"></script>
<script src="{{cdn1('assets/artTemplate/template.js')}}"></script>
<script type="text/javascript">

    $(".info-box ul li").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
    });


    $(".info-cont-title").click(function () {
        $(".info-cont").show();
        $(".info-set").hide();
        $(".info-cloud").hide();
    });
    $(".info-cloud-title").click(function () {
        $(".info-cloud").show();
        $(".info-set").hide();
        $(".info-cont").hide();

    });
    $(".info-set-title").click(function () {
        $(".info-set").show();
        $(".info-cont").hide();
        $(".info-cloud").hide();
    });




    $(function () {

        var createStreamPlayer = function (c) {
            var xiSwfUrlStr = "{{asset('assets/swfobject/expressInstall.swf')}}";
            var flashvars = {
                "log": "all",
                "url": 'rtmp://' + c.ip + ':1935/live',
                "streamname": c.stream_name,
                "buffer": 0.1
            };
            swfobject.embedSWF(c.preview, c.id, c.width, c.height, "10.1.0", xiSwfUrlStr, flashvars);
        };
        //主屏幕
        createStreamPlayer({
            ip: '192.168.1.98',
            stream_name: 'stream5',
            preview: '/assets/previewVideo.swf',
            id: 'j-flashArea-player',
            width: '100%',
            height: '100%'
        });
        //分屏信息
        createStreamPlayer({
            ip: '192.168.1.98',
            stream_name: 'stream7',
            preview: '/assets/previewVideo-small.swf',
            id: 'j-flashArea-detail-player',
            width: '1800px',
            height: '168px'
        });
    })
</script>
</body>
</html>
</body>
</html>