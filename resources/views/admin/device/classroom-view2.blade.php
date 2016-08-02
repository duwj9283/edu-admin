<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title></title>
    <link href="/assets/css/system.css" rel="stylesheet">

</head>

<body>
<main>
    <div class="header" class="clearfix">
        <h1>直播课堂</h1>
    </div>
    <div class="mainer" class="clearfix">
        <div class="left">
            <div class="video">
                <div id="j-flashArea-player"></div>
            </div>
        </div>
        <div class="right">
            <h4>教室列表</h4>
            <ul class="rightbox" id="tblDataList">
                @foreach($rows as $key=>$row)
                    <li data-id="{{$row->device_id}}"><a style="cursor:pointer;">{{$row->title}}</a></li>
                @endforeach
            </ul>
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
            </li>
        </ul>
        <div id="j-flashArea-detail-player"></div>
    </div>
</main>
<script src="/assets/jquery/1.11.3/jquery.min.js"></script>
<script src="/assets/swfobject/swfobject.js"></script>
<script>
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

        $('#tblDataList li').click(function () {
            var id = $(this).data('id');
            $('#tblDataList li').each(function() {
                $(this).attr("class","li-no");
            })
            $(this).attr("class", "li-act");

            $.get('/admin/device/classroom-view-device', {id: id}, function (data) {
                createStreamPlayer({
                    ip: data.ip,
                    stream_name: 'stream5',
                    preview: '/assets/previewVideo.swf',
                    id: 'j-flashArea-player',
                    width:'100%',
                    height:'100%'
                });
                createStreamPlayer({
                    ip: data.ip,
                    stream_name: 'stream7',
                    preview: '/assets/previewVideo-small.swf',
                    id: 'j-flashArea-detail-player',
                    width:'1198px',
                    height:'112px'
                });
            });
        });
        $('#tblDataList li:first').trigger('click');
    })
</script>
</body>

</html>