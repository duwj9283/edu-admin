@extends('admin.main')
@section('content')
<div class="wrapper wrapper-content">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>设备管理列表</h5>
		</div>
		<div class="ibox-content">
			<div class="m-b">
				<a href="javascript:void(0)" class="btn btn-warning js-add"><i class="fa fa-plus"></i> 新建设备</a>
			</div>
			<table id="tblDataList" class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>设备名称</th>
						<th>编号</th>
						<th>ip</th>
						<th>流名称</th>
						<th>状态</th>
						<th width="200"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($rows as $key=>$row)
					<tr data-id="{{$row->id}}">
						<td>{{$key+1}}</td>
						<td>{{$row->title}}</td>
						<td>{{$row->no}}</td>
						<td>{{$row->ip}}</td>
						<td>{{$row->stream_name}}</td>
						<td>
							@if($row->status=='true')在线 @else 离线@endif

						</td>
						<td>
							<a href="javascript:void(0)" class="m-r-xs js-eye">
								<i class="fa fa-eye fa-lg"></i>预览
							</a>
							<a href="javascript:void(0)" class="m-r-xs js-edit">
								<i class="fa fa-pencil-square fa-lg"></i>编辑
							</a>
							<a href="javascript:;" class="m-r-xs js-del">
								<i class="fa fa-times-circle fa-lg"></i>删除
							</a>

						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="text-center">{!! $rows->render() !!}</div>

		</div>
	</div>
</div>

<div id="myEditModal" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1"></div>
<script id="tplEditPannel" type="text/html">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="formEdit" method="post" action="{{url('admin/device/update')}}" class="form-horizontal" onsubmit="return false">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" type="button">&times;</button>
					<h4 class="modal-title" id="avatar-modal-label">设备</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">名称</label>
						<div class="col-sm-5">
							<input type="text" name="title" value="<%title%>" class="form-control" required="" autofocus>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">编号</label>
						<div class="col-sm-5">
							<input type="text" name="no" value="<%no%>" class="form-control" required="" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">IP</label>
						<div class="col-sm-5">
							<input type="text" name="ip" value="<%ip%>" class="form-control" required="" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">流名称</label>
						<div class="col-sm-5">
							<input type="text" name="stream_name" value="<%stream_name%>" class="form-control" required="" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">状态</label>
						<div class="col-sm-5">
							<select name="status" class="form-control"  required="">
								<option value="false" <%if type=='false'%> selected <%/if%> >在线</option>
								<option value="true" <%if type=='true'%> selected <%/if%>>离线</option>
							</select>
						</div>
					</div>

					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<div class="col-sm-4 col-sm-offset-2">
							<input type="hidden" name="id"  value="<%id%>">
							<button class="btn btn-primary btn-w-m js-sub" type="submit">确定</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</script>
<script id="tplEyePannel" type="text/html">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal" type="button">&times;</button>
				<h4 class="modal-title" id="avatar-modal-label">预览</h4>
			</div>
			<div class="modal-body">
				<div class="play-video">
					<div id="j-flashArea-player" ></div>
				</div>
			</div>
		</div>
	</div>
</script>
@endsection

@section('pagescript')
	<script src="{{asset('assets/swfobject/swfobject.js')}}"></script>

	<script>
	var artDialog;
	$("#side-menu li[rel='devices']").addClass("active")
		.find("ul").addClass("in")
		.find("li[rel='1']").addClass("active");

	var list=JSON.parse('{!! $rows_json !!}');
	var vs2_serv=JSON.parse('{!! $vs2_serv!!}');


	$('.js-eye').click(function(){
		var id = $(this).parents('tr').eq(0).data('id');
		for(i in list.data){
			if(list.data[i].id==id){
				$("#myEditModal").html(template('tplEyePannel')).modal('show');
				createStreamPlayer(list.data[i]);

				break;
			}
		}
	});



	var createStreamPlayer = function(c){
		var swfVersionStr = "12.0.0";
		// To use express install, set to playerProductInstall.swf, otherwise the empty string.
		var xiSwfUrlStr = "playerProductInstall.swf";
		var flashvars = {};
		flashvars.url = 'rtmp://'+vs2_serv.host+':'+vs2_serv.port+'/'+vs2_serv.app_name+'';
		flashvars.streamname = c.stream_name;
		var params = {};
		params.quality = "high";
		params.bgcolor = "#ffffff";
		params.allowscriptaccess = "sameDomain";
		params.allowfullscreen = "true";

		var attributes = {};
		attributes.id = "Main";
		attributes.name = "Main";
		attributes.align = "middle";
		swfobject.embedSWF(
				"/assets/videoplayerLuboPreview.swf", 'j-flashArea-player',
				"100%", "500",
				swfVersionStr, xiSwfUrlStr,
				flashvars, params, attributes);
	}
	$('.js-add').click(function(){
		$("#myEditModal").html(template('tplEditPannel',{type:'false'})).modal('show')
	});
	$('.js-edit').click(function(){
		var id = $(this).parents('tr').eq(0).data('id');
		for(i in list.data){
			if(list.data[i].id==id){
				$("#myEditModal").html(template('tplEditPannel',list.data[i])).modal('show');
				break;
			}
		}

	});
	$("#tblDataList").delegate('.js-del', 'click', function(e){
		var id = $(this).parents('tr').eq(0).data('id');
		dialog({
			content:'<i class="fa fa-info-circle"></i> 确定要删除此设备吗？',
			ok:function(){
				$.post('/admin/device/delete', {'id':id}, function(){
					$(e.target).parents("tr").remove();
				}).fail(failure);
			},
			cancel: true
		}).showModal();
	});
	$("#myEditModal").delegate('#formEdit', 'submit', function(e){
		var $this=$(this);
		$.post($this.prop('action'),$this.serialize(),function(){
			window.location.reload();
		}).fail(failure)
	});
</script>
@endsection