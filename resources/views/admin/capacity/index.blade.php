@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>容量申请管理</h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive ">
                        <div class="m-b">
                            <div class="dropdown">
                                <div class="btn-group">
                                    <a class="btn btn-{{$status<0?'info':'white'}}" href="/admin/capacity" >全部状态</a>
                                    <a class="btn btn-{{$status==0?'warning':'white'}}" href="/admin/capacity?status=0" >审核中</a>
                                    <a class="btn btn-{{$status==1?'primary':'white'}}" href="/admin/capacity?status=1" >审核成功</a>
                                    <a class="btn btn-{{$status==2?'danger':'white'}}" href="/admin/capacity?status=2">已拒绝</a>
                                </div>
                            </div>
                        </div>
                        <table id="tblDataList" class="table  table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>申请人</th>
                                <th>申请原因</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $key=> $value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value['username']}}</td>
                                    <td>{{$value['reason']}}</td>
                                    <td>
                                        @if($value['status']==0)
                                            <span class="badge badge-warning">审核中</span>
                                        @elseif($value['status']==1)
                                            <span class="badge badge-primary">审核成功</span>
                                            <span class="label label-primary">已分配：{{$value['capacity']}} GB 容量</span>
                                        @else
                                            <span class="badge badge-danger">已拒绝</span>
                                            <span class="label label-danger">拒绝原因：{{$value['fail_reason']}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($value['status']==0)
                                            <a href="javascript:;" data-id="{{$value['id']}}" class="c_success btn btn-success btn-xs js-delete"> <i class="fa fa-check"></i>
                                                通过
                                            </a>
                                            <a href="javascript:;" data-id="{{$value['id']}}"  class="c_fail btn btn-danger btn-xs js-delete"> <i class="fa fa-trash"></i>
                                                拒绝
                                            </a>
                                        @else
                                            -
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">{!!$apply_list->render()!!}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script id="tplCapacitySuccess" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form name="capacity-edit" onsubmit="return false">
                <input name="id" value="<%id%>" type="hidden">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><%title%></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>配置空间大小</label>
                        <div class="input-group m-b">
                            <input type="text" required onkeyup="Num(this)" name="capacity" placeholder="请输入提供的空间大小" class="form-control">
                            <span class="input-group-addon">GB</span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</script>

<script id="tplCapacityFail" type="text/html">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <form name="capacity-reason" onsubmit="return false">
                <input name="id" value="<%id%>" type="hidden">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><%title%></h4>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>拒绝申请原因</label>
                        <textarea required name="reason" placeholder="请输入拒绝申请原因" class="form-control"></textarea>
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
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true"></div>
@endsection

@section('pageheader')
<style>
tr.current{
    background-color: #f5f5f5;
}
</style>
@endsection

@section('pagescript')

    <script type="text/javascript">
        $(".sidebar-collapse li[rel='capacity']").addClass("active");

        $('.c_success').on('click',function(){
            var id=$(this).data('id');
            $('#myModal').html(template('tplCapacitySuccess',{title:'配置空间容量',id:id})).modal('show');
            return false;
        });

        $('#myModal').delegate('form[name="capacity-edit"]','submit',function(){
            $.post('/admin/capacity/edit',$('form[name="capacity-edit"]').serialize(),function(data){
                $('#myModal').modal('hide');
                if(data==1)
                {
                    window.location.href='/admin/capacity?status=1';
                }
                else
                {
                    artInfo('配置失败')
                    return false;
                }
            })
        });

        $('.c_fail').on('click',function(){
            var id=$(this).data('id');
            $('#myModal').html(template('tplCapacityFail',{title:'添加拒绝申请原因',id:id})).modal('show');
            return false;
        });

        $('#myModal').delegate('form[name="capacity-reason"]','submit',function(){
            $.post('/admin/capacity/fail',$('form[name="capacity-reason"]').serialize(),function(data){
                $('#myModal').modal('hide');
                if(data==1)
                {
                    window.location.href='/admin/capacity?status=2';
                }
                else
                {
                    artInfo('拒绝失败失败')
                    return false;
                }
            })
        });

        function Num(obj){
            obj.value = obj.value.replace(/[^\d.]/g,""); //清除"数字"和"."以外的字符
            obj.value = obj.value.replace(/^\./g,""); //验证第一个字符是数字而不是
            obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的
            obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3'); //只能输入两个小数
        }
    </script>
@endsection

