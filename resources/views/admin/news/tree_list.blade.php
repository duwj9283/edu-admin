@extends('admin.main')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-9">
    <h2>栏目结构</h2>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-3">
      <div class="ibox">
        <div class="ibox-title">
          <h5>栏目结构</h5>
          <div class="ibox-tools">
            <button id="btnAdd" type="button" class="btn btn-primary btn-xs"> <i class="fa fa-plus"></i>
              新建
            </button>
          </div>
        </div>
        <div class="ibox-content no-padding">
          <ul id="treeDemo" class="ztree"></ul>
        </div>
      </div>
    </div>
    <div id="iboxColumnDetails" class="col-lg-9"></div>
  </div>
</div>

<script id="tplAddColumn" type="text/html">
  <div class="ibox">
    <div class="ibox-title">
      <h5>新建栏目</h5>
    </div>
    <div class="ibox-content">
      <form id="formAdd" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label">序号：</label>
          <div class="col-sm-6">
            <input type="text" name="sortnum" class="form-control" value="" maxlength="4"></div>
          <label class="col-sm-4">
            <small>只允许输入数字</small>
          </label>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">栏目名称：</label>
          <div class="col-sm-10">
            <input type="text" name="name" value="" class="form-control" autocomplete="off" required="" autofocus=""></div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">显示方式：</label>
          <div class="col-sm-3">
            <select name="mode" class="form-control" required="">
              <option value="">- 请选择 -</option>
              <option value="1"<%if mode==1%> selected<%/if%>>内容模式</option>
              <option value="2"<%if mode==2%> selected<%/if%>>新闻列表</option>
              <option value="3"<%if mode==3%> selected<%/if%>>图片列表</option>
              <option value="4"<%if mode==4%> selected<%/if%>>图文列表</option>
            </select>
          </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <input type="hidden" name="parent_id" value="<%id%>" />
            <button class="btn btn-success btn-w-m" type="submit">提交</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</script>

<script id="tplEditColumn" type="text/html">
  <div class="ibox">
    <div class="ibox-title">
      <h5><%name%></h5>
    </div>
    <div class="ibox-content">
      <form id="formEdit" class="form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label">序号：</label>
          <div class="col-sm-6">
            <input type="text" name="sortnum" class="form-control" value="<%sortnum%>" maxlength="4"></div>
          <label class="col-sm-4">
            <small>只允许输入数字</small>
          </label>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">栏目名称：</label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<%name%>" class="form-control" autocomplete="off" required="" autofocus=""></div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">显示方式：</label>
          <div class="col-sm-3">
            <select name="mode" class="form-control" required="">
              <option value="">- 请选择 -</option>
              <option value="1"<%if mode==1%> selected<%/if%>>内容模式</option>
              <option value="2"<%if mode==2%> selected<%/if%>>新闻列表</option>
              <option value="3"<%if mode==3%> selected<%/if%>>图片列表</option>
              <option value="4"<%if mode==4%> selected<%/if%>>图文列表</option>
            </select>
          </div>
        </div>
        <div class="hr-line-dashed"></div>
        <div class="form-group">
          <div class="col-sm-4 col-sm-offset-2">
            <input type="hidden" name="id" value="<%id%>" />
            <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> 保存</button>
            <%if allow_del%>
              <a href="javascript:;" class="btn btn-danger m-l js-del"><i class="fa fa-trash"></i> 删除</a>
            <%/if%>
          </div>
        </div>
      </form>
    </div>
  </div>
</script>
@endsection

@section('pageheader')
<link rel="stylesheet" href="{{cdn1('assets/zTree/css/metroStyle/metroStyle.css')}}" />
@endsection

@section('pagescript')
<script src="{{cdn1('assets/zTree/js/jquery.ztree.core-3.5.js')}}"></script>
<script>
  $("#side-menu li[rel='news']").addClass("active")
    .find("ul").addClass("in")
    .find("li[rel='4']").addClass("active");

  seajs.use('models/newsclassModel', function(newsclassModel) {
    var curClass = {}, isLeaf;
    var onClick = function(e, treeId, treeNode) {
      newsclassModel.getInfo({'id': treeNode.id}, function(data){
        curClass = data;
        $("#iboxColumnDetails").html(template('tplEditColumn', data));
      }, failure);
    };
    var setting = {
      view: {dblClickExpand: false, selectedMulti: false},
      data: {simpleData: {enable: true, rootPId: ''}},
      callback: {'onClick': onClick}
    };
    var zNodes = {!!$zNodes!!};
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);

    $("#btnAdd").on('click', function(){
      if(!curClass.id){
        alert("请先选择一个栏目");
        return false;
      }
      if(curClass.id.length / 4 >= curClass.depth){
        alert('此分类不支持添加子类');
        return false;
      }
      $("#iboxColumnDetails").html(template('tplAddColumn', curClass));
    });

    $("#iboxColumnDetails").delegate("#formAdd", 'submit', function(){
      if(this.sortnum.value){
        if ( ! /^\d{1,4}$/.exec(this.sortnum.value)){
          alert("分类序号只能使用1-4位数字！");
          this.sortnum.focus();
          return false;
        }
      }
      newsclassModel.addColumn($(this).serialize(), function(data){
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
          nodes = zTree.getSelectedNodes(),
          treeNode = nodes[0];
        if(treeNode){
          zTree.addNodes(treeNode, {'id':data.id,'pId':treeNode.id,'name':data.name});
        }else{
          zTree.addNodes(null, {'id':data.id,'pId':'','name':data.name});
        }
        $("#iboxColumnDetails").html('');
      }, failure);
      return false;
    });

    $("#iboxColumnDetails").delegate("#formEdit", 'submit', function(){
      if(this.sortnum.value){
        if ( ! /^\d{1,4}$/.exec(this.sortnum.value)){
          alert("分类序号只能使用1-4位数字！");
          this.sortnum.focus();
          return false;
        }
      }
      $(this).find(":submit").attr("disabled", "disabled");
      newsclassModel.editColumn($(this).serialize(), function(data){
        var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
          nodes = zTree.getSelectedNodes(),
          treeNode = nodes[0];
        if(treeNode){
          treeNode.name = data.name;
          zTree.updateNode(treeNode);
        }
        $("#iboxColumnDetails").find('.ibox-title h5').html(data.name);
        $("#formEdit").find(":submit").removeAttr("disabled");
      }, failure);
      return false;
    });

    $("#iboxColumnDetails").delegate(".js-del", 'click', function(){
      var id = $("#formEdit")[0].id.value;
      dialog({
        content: '<i class="fa fa-info-circle"></i> 确定要删除吗？',
        ok: function(){
          newsclassModel.delete({'id':id}, function(){
            var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
              nodes = zTree.getSelectedNodes(),
              treeNode = nodes[0];
            if(treeNode){
              zTree.removeNode(treeNode);
            }
            $("#iboxColumnDetails").html('');
          }, failure);
        },
        cancel: true
      }).showModal();
    });
  });
</script>
@endsection
