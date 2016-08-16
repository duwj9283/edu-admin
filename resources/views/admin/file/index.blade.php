@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>文件发布审核管理</h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive ">
                        <div class="m-b">
                            <div class="dropdown">
                                <div class="btn-group">
                                    <a class="btn btn-{{$status<0?'info':'white'}}" href="/admin/file" >全部状态</a>
                                    <a class="btn btn-{{$status==0?'warning':'white'}}" href="/admin/file?status=0" >审核中</a>
                                    <a class="btn btn-{{$status==1?'primary':'white'}}" href="/admin/file?status=1" >审核成功</a>
                                    <a class="btn btn-{{$status==2?'danger':'white'}}" href="/admin/file?status=2">已拒绝</a>
                                </div>

                            </div>
                        </div>
                        <table id="tblDataList" class="table  table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>发布人</th>
                                <th>文件名称</th>
                                <th>文件大小</th>
                                <th>发布时间</th>
                                <th>审核时间</th>
                                <th>审核人</th>
                                <th>审核状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $key=> $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value['username']}}</td>
                                    <td>{{$value['push_file_name']}}</td>
                                    <td>{{$value['file_size']}}</td>
                                    <td>{{$value['addtime']}}</td>
                                    <td>{{$value['verifytime']>0?date("Y-m-d H:i:s",$value['verifytime']):'-'}}</td>
                                    <td>{{!empty($value['verifyer'])?$value['verifyer']:'-'}}</td>
                                    <td>
                                        @if($value['status']==0)
                                            <span class="badge badge-warning">审核中</span>
                                        @elseif($value['status']==1)
                                            <span class="badge badge-primary">审核成功</span>
                                        @else
                                            <span class="badge badge-danger">已拒绝</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" data-type="{{$value['file_type']}}" data-fid="{{$value['user_file_id']}}" data-fmd5="{{$value['file_md5']}}" data-name="{{$value['push_file_name']}}" data-uid="{{$value['uid']}}" data-date="{{$value['push_date_folder']}}" data-id="{{$value['id']}}"  class="f_search btn btn-primary btn-xs"> <i class="fa fa-search"></i>
                                            查看
                                        </a>
                                        @if($value['status']==0)
                                            <a href="javascript:;" data-id="{{$value['id']}}" data-fid="{{$value['user_file_id']}}" class="f_success btn btn-success btn-xs"> <i class="fa fa-check"></i>
                                                通过
                                            </a>
                                            <a href="javascript:;" data-id="{{$value['id']}}"  class="f_fail btn btn-danger btn-xs"> <i class="fa fa-trash"></i>
                                                拒绝
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">{!!$file_list->render()!!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
</div>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 800px;">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">文件审核</h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
<div id="preview" style="display: none"></div>
<script id="tplFileVerify" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">文件审核</h4>
                </div>
            <form name="file-push" class="form-horizontal" onsubmit="return false">
                <div class="modal-body">
                        <input name="id" value="<%id%>" type="hidden">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">学科</label>
                            <div class="col-sm-5">
                                <select class="form-control m-b" id="fatherSub" name="fatherSub"></select>
                            </div>
                            <div class="col-sm-5">
                                <select class="form-control m-b" id="childSub" name="childSub"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">应用类型</label>
                            <div class="col-sm-5">
                                <select class="form-control m-b" id="application_type" name="application_type"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">知识点</label>
                            <div class="col-sm-10">
                                <input type="text" id="knowledge_point" name="knowledge_point" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">语言</label>
                            <div class="col-sm-10">
                                <input type="radio" value="0" name="language">中文&nbsp;
                                <input type="radio" value="1" name="language">英文&nbsp;
                                <input type="radio" value="2" name="language">双语&nbsp;
                                <input type="radio" value="3" name="language">其他
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">简介</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="desc" name="desc"></textarea>
                            </div>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">确定</button>
                </div>
            </form>
        </div>
    </div>
</script>
<script id="tplFileFail" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form name="file-reason" onsubmit="return false">
                <input name="id" value="<%id%>" type="hidden">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><%title%></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>拒绝原因</label>
                        <textarea required name="reason" placeholder="请输入拒绝原因" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">确定</button>
                </div>
            </form>
        </div>
    </div>
</script>
@endsection

@section('pageheader')
<style>
tr.current{background-color: #f5f5f5;}
#iframe-pdf{
    width:100%;
    height:600px;
}
</style>
@endsection

@section('pagescript')
<script type="text/javascript" src="{{cdn1('assets/jwplay/jwplayer.js')}}"></script>

<script type="text/javascript">
    $(".sidebar-collapse li[rel='file']").addClass("active");

    $('.f_fail').on('click',function(){
        var id=$(this).data('id');
        $('#myModal2').html(template('tplFileFail',{title:'添加拒绝原因',id:id})).modal('show');
        return false;
    });

    $('#myModal2').delegate('form[name="file-reason"]','submit',function(){
        $.post('/admin/file/fail',$('form[name="file-reason"]').serialize(),function(data){
            $('#myModal').modal('hide');
            if(data==1)
            {
                artInfo('拒绝成功');
                window.location.href='/admin/file?status=2';
            }
            else
            {
                artInfo('拒绝失败失败');
                return false;
            }
        })
    });

    $('#myModal2').delegate('form[name="file-push"]','submit',function(){
        $.post('/admin/file/edit',$('form[name="file-push"]').serialize(),function(data){
            $('#myModal').modal('hide');
            if(data==1)
            {
                artInfo('审核成功')
                window.location.href='/admin/file?status=1';
            }
            else
            {
                artInfo('审核失败')
                return false;
            }
        })
    });

    //通过审核
    $('.f_success').on('click',function(){
        var id=$(this).data('id');
        var fid=$(this).data('fid');
        //加载文件信息
        $.get('/admin/file/public-file-info',{id:fid},function(result){
            var html='';
            for(var i=0;i<result['applicationType'].length;i++){
                if(result['fileInfo'].application_type==result['applicationType'][i].id)
                {
                    html+="<option selected value='"+result['applicationType'][i].id+"'>"+result['applicationType'][i].name+"</option>";
                }else{
                    html+="<option value='"+result['applicationType'][i].id+"'>"+result['applicationType'][i].name+"</option>";
                }
            }
            $('#application_type').html(html);
            $('#knowledge_point').val(result['fileInfo'].knowledge_point);
            $('#desc').val(result['fileInfo'].desc);
            $("input[type=radio][name=language][value="+result['fileInfo'].language+"]").attr("checked",'checked');
            //获取所有父类
            $.get('/admin/subject/father-list',function(data){
                var html='';
                if(data.length>0){
                    for(var i=0;i<data.length;i++){
                        if(result['fileInfo'].father_id==data[i].id)
                        {
                            html+="<option selected value='"+data[i].id+"'>"+data[i].subject_name+"</option>";
                        }else{
                            html+="<option value='"+data[i].id+"'>"+data[i].subject_name+"</option>";
                        }
                    }
                }
                $('#fatherSub').html(html);
                //加载默认父类的子类
                getChild(result['fileInfo'].father_id,result['fileInfo'].subject_id);
                //父类联级响应
                $('#fatherSub').on('change',function(){
                    getChild($(this).val(),null);
                });
            });
        });

        var getChild=function(fatherId,child){
            $.get('/admin/subject/child-list',{id:fatherId},function(data){
                var html='';
                if(data.length>0){
                    for(var i=0;i<data.length;i++){
                        if(child==data[i].id)
                        {
                            html+="<option selected value='"+data[i].id+"'>"+data[i].subject_name+"</option>";
                        }else{
                            html+="<option value='"+data[i].id+"'>"+data[i].subject_name+"</option>";
                        }
                    }
                }
                $('#childSub').html(html);
            })
        };
        $('#myModal2').html(template('tplFileVerify',{id:id})).modal('show');
        return false;
    });

    jwplayer.key="O4G/7OoH6r9ioOg0VZQ1Ptmr+rAfP9BNQQzQYQ==";
    $('.f_search').on('click',function(){
        var id=$(this).data('id');
        var type=$(this).data('type');
        var fid=$(this).data('fid');
        var fmd5=$(this).data('fmd5');
        if(type==2)
        {
            var uid=$(this).data('uid');
            var name=$(this).data('name');
            var date=$(this).data('date');
            var fileNAmeArr = name.split('.');
            var fileFormat = fileNAmeArr[fileNAmeArr.length - 1];
            var url = 'rtmp://lubo.iemaker.cn:1935/vod/'+fileFormat+':publicpool/'+uid+'/'+date+'/'+name;
            video(url,fid);
        }
        else if(type==3)
        {
            image(fid);
        }
        else if(type==4)
        {
            audio(fid,fmd5);
        }
        else if(type==5)
        {
            pdf(fid);
        }
        $('#myModal .modal-body').html($('#preview'));
        $('#preview').show();
        $('#myModal').modal('show');
        return false;
    });
    function audio(fId,fmd5)
    {
        jwplayer('preview').setup({
            flashplayer: '{{cdn1('assets/jwplay/jwplayer.flash.swf')}}',
            file:'http://lubo.iemaker.cn/mp3/'+fId+'/'+fmd5+'.mp3',
            image: 'http://lubo.iemaker.cn/img/frontend/tem_material/audio.png',
            width: '100%',
            height:'400',
            //aspectratio:"4:3",
            dock: false,
            skin: {
                name: "vapor"
            }
        });
    }
    function video(fUrl,fId)
    {
        jwplayer('preview').setup({
            flashplayer: '{{cdn1('assets/jwplay/jwplayer.flash.swf')}}',
            file:fUrl,
            image: 'http://lubo.iemaker.cn/api/source/getPublicImageThumb/'+fId+'/738/400',
            width: '100%',
            height:'400',
            //aspectratio:"4:3",
            dock: false,
            skin: {
                name: "vapor"
            }
        });
    }
    function pdf(fId)
    {
        var file = "http://lubo.iemaker.cn/api/file/filePreview/"+fId;
        var htmlStr = "<iframe id='iframe-pdf' src="+file+"></iframe>";
        $('#preview').append(htmlStr);
    }

    function image(fId)
    {
        var file = "http://lubo.iemaker.cn/api/source/getPublicImageThumb/"+fId+'/738/400';
        var htmlStr = "<img style='display:block;margin:0 auto' src="+file+"/>";
        $('#preview').html(htmlStr);
    }
</script>
@endsection

