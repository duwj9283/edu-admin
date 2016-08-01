@extends('admin.main')
@section('content')
<div class="wrapper wrapper-content">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>教室管理列表</h5>
		</div>
		<div class="ibox-content">
			<div class="m-b">
				<a href="javascript:void(0)" class="btn btn-warning js-add"><i class="fa fa-plus"></i> 新建教室</a>
			</div>
			<table id="tblDataList" class="table table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>教室名称</th>
						<th>设备名称</th>
						<th>设备状态</th>
						<th width="200"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($rows as $key=>$row)
					<tr data-id="{{$row->id}}">
						<td>{{$key+1}}</td>
						<td>{{$row->title}}</td>
						<td>{{$row->ed_title}}</td>

						<td>
							@if($row->ed_status=='true')在线 @else 离线@endif
						</td>
						<td>

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
			<form id="formEdit" method="post" action="{{url('admin/device/classroom-update')}}" class="form-horizontal" onsubmit="return false">
				<div class="modal-header">
					<button class="close" data-dismiss="modal" type="button">&times;</button>
					<h4 class="modal-title" id="avatar-modal-label">教室</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-2 control-label">名称</label>
						<div class="col-sm-5">
							<input type="text" name="title" value="<%title%>" class="form-control" required="" autofocus>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">设备</label>
						<div class="col-sm-5">
							<select name="device_id" class="form-control"  required="" >
								<%if device.length >0%>
									<% each device as item %>
									<option value="<%item.id%>" <%if item.id==device_id %> selected<%/if%>><%item.title%></option>
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
							<input type="hidden" name="id"  value="<%id%>">
							<button class="btn btn-primary btn-w-m js-sub" type="submit">确定</button>
						</div>
					</div>
				</div>
			</form>
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
		.find("li[rel='2']").addClass("active");

	var list=JSON.parse('{!! $rows_json !!}');
	var device=JSON.parse('{!! $device !!}');

	$('.js-add').click(function(){

		$("#myEditModal").html(template('tplEditPannel',{device:device})).modal('show')
	});
	$('.js-edit').click(function(){
		var id = $(this).parents('tr').eq(0).data('id');
		for(i in list.data){
			if(list.data[i].id==id){
				$("#myEditModal").html(template('tplEditPannel',{title:list.data[i].title,id:list.data[i].id,device_id:list.data[i].device_id,device:device})).modal('show');
				break;
			}
		}

	});
	$("#tblDataList").delegate('.js-del', 'click', function(e){
		var id = $(this).parents('tr').eq(0).data('id');
		dialog({
			content:'<i class="fa fa-info-circle"></i> 确定要删除此设备吗？',
			ok:function(){
				$.post('/admin/device/classroom-delete', {'id':id}, function(){
					$(e.target).parents("tr").remove();
				}).fail(failure);
			},
			cancel: true
		}).showModal();
	});
	$("#myEditModal").delegate('#formEdit', 'submit', function(e){
		var $this=$(this);

		if($this.find('select[name="device_id"]').val()<=0){
			failure('请先添加设备');return false;
		}
		$.post($this.prop('action'),$this.serialize(),function(){
			window.location.reload();
		}).fail(failure)
	});
</script>
@endsection