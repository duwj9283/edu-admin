@extends('admin.main')
@section('content')
    <div class="wrapper wrapper-content">


        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>教室轮询</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-3" style="max-height:800px;overflow:auto">
                        <table id="tblDataList" class="table table-hover">

                            @foreach($rows as $key=>$row)
                                <tr data-id="{{$row->device_id}}">
                                    <td>{{$key+1}}.{{$row->title}}</td>

                                </tr>
                            @endforeach


                        </table>
                    </div>
                    <div class="col-sm-9">

                        <div class="play-video" style="height:500px;">
                            <div id="j-flashArea-player"></div>
                        </div>
                        <div class="play-video">
                            <div id="j-flashArea-detail-player"></div>
                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>

    <script id="tplEditPannel" type="text/html">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="formEdit" method="post" action="{{url('admin/device/classroom-update')}}"
                      class="form-horizontal" onsubmit="return false">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4 class="modal-title" id="avatar-modal-label">教室</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-5">
                                <input type="text" name="title" value="<%title%>" class="form-control" required=""
                                       autofocus>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">设备</label>
                            <div class="col-sm-5">
                                <select name="device_id" class="form-control" required="">
                                    <%if device.length >0%>
                                    <% each device as item %>
                                    <option value="<%item.id%>"
                                    <%if item.id==device_id %> selected<%/if%>><%item.title%></option>
                                    <%/each%>
                                    <%else%>
                                    <option value="0">请先添加设备</option>
                                    <%/if%>
                                </select>
                            </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <input type="hidden" name="id" value="<%id%>">
                                <button class="btn btn-primary btn-w-m js-sub" type="submit">确定</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </script>

@endsection

@section('pageheader')
    <style>
        #tblDataList .hoverTr {
            background-color: #f5f5f5;
        }
    </style>
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
            swfobject.embedSWF(c.preview, c.id, "100%", "100%", "10.1.0", xiSwfUrlStr, flashvars);
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