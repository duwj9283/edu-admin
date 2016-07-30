@extends('admin.main')

@section('content')
  <div class="wrapper wrapper-content animated fadeInRight">
    <div class="m-b">
      <a href="javascript:;" onclick="history.back()" class="btn btn-default m-r">
        <i class="fa fa-arrow-left"></i> 返回列表
      </a>
    </div>
    <div class="row">
      <div class="col-sm-8">
        <div class="ibox">
          <div class="ibox-title">
            <h5>{{$role->display_name}} <small>成员列表</small></h5>
          </div>
          <div class="ibox-content">
            <div class="row">
              <div class="col-lg-6">
                <a href="javascript:;" id="btnRefresh" class="btn btn-default m-l-xs"><i class="fa fa-refresh"></i> 刷新</a>
                <a href="javascript:;" id="btnMemberAdd" class="btn btn-warning"><i class="fa fa-user-plus"></i> 添加…</a>
              </div>
              <form id="formSearch" class="input-group col-lg-6">
                <input type="text" name="kw" placeholder="用户名/邮箱/手机号" class="input form-control">
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i>
                    搜索
                  </button>
                </span>
              </form>
            </div>
            <div class="clients-list table-responsive">
              <table id="tblDataList" class="table table-striped table-hover">
                <tbody></tbody>
              </table>
            </div>
            <div id="pagination" class="text-center"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="ibox">
          <div class="ibox-title">
            <h5>用户信息</h5>
          </div>
          <div id="tblMemberInfo" class="ibox-content"></div>
        </div>
      </div>
    </div>
  </div>

  <div id="bootstrapModal" class="modal fade" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">添加成员</h4>
        </div>
        <div class="modal-body">
          <form id="formOuterSearch" class="input-group">
            <input type="text" name="kw" placeholder="用户名/邮箱/手机号" class="input form-control">
            <span class="input-group-btn">
              <button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i>
                搜索
              </button>
            </span>
          </form>
          <div class="clients-list table-responsive">
            <table id="tblOuterMemberList" class="table table-striped table-hover">
              <tbody></tbody>
            </table>
          </div>
          <div id="pagination2" class="text-center"></div>
        </div>
      </div>
    </div>
  </div>

  <script id="tplOuterMemberList" type="text/html">
    <%each rows as row i%>
    <tr data-id="<%row.id%>">
      <td class="client-avatar">
        <img src="<%row.avatar%>" /></td>
      <td><strong><%row.username%></strong></td>
      <td><%row.realname%></td>
      <td><i class="fa fa-envelope"></i> <%row.email%></td>
      <td><i class="fa fa-mobile fa-lg"></i> <%row.mobile || '　　　　　'%></td>
      <td><a href="javascript:;" class="label label-success js-plus"><i class="fa fa-user-plus"></i> 添加</a></td>
    </tr>
    <%/each%>
  </script>

  <script id="tplDataList" type="text/html">
    <%each rows as row i%>
    <tr data-id="<%row.id%>">
      <td class="client-avatar">
        <img src="<%row.avatar%>" /></td>
      <td><strong><%row.username%></strong></td>
      <td><%row.realname%></td>
      <td><i class="fa fa-envelope"></i> <%row.email%></td>
      <td><i class="fa fa-mobile fa-lg"></i> <%row.mobile%></td>
    </tr>
    <%/each%>
  </script>

  <script id="tplMemberInfo" type="text/html">
    <div class="row m-b-lg">
      <div class="col-lg-4 text-center">
        <div class="m-b-sm">
          <img class="img-circle" src="<%avatar%>" style="width: 65px"></div>
      </div>
      <div class="col-lg-8">
        <h3><%realname%></h3>
        <a href="javascript:;" data-id="<%id%>" class="btn btn-danger btn-sm">
          <i class="fa fa-user-times fa-lg"></i>
          从成员中移除
        </a>
      </div>
    </div>
    <h4>用户资料</h4>
    <ul class="list-group clear-list">
      <li class="list-group-item fist-item">
        <span class="pull-right"><%cardid%></span>
        <i class="fa fa-credit-card"></i> 身份证
      </li>
      <li class="list-group-item">
        <span class="pull-right"><%username%></span>
        <i class="fa fa-user"></i> 帐户
      </li>
      <li class="list-group-item">
        <span class="pull-right"><%email%></span>
        <i class="fa fa-envelope"></i> 邮箱
      </li>
      <li class="list-group-item">
        <span class="pull-right"><%mobile%></span>
        <i class="fa fa-phone"></i> 手机
      </li>
      <li class="list-group-item">
        <span class="pull-right"><%created_at%></span>
        <i class="fa fa-clock-o"></i> 注册时间
      </li>
      <li class="list-group-item">
        <span class="pull-right"><%updated_at%></span>
        上次访问时间
      </li>
    </ul>
    <h4>签名</h4>
    <div><%resumes%></div>
  </script>
@endsection

@section('pageheader')
<style type="text/css">
  .pagination{margin:0;}
</style>
@endsection

@section('pagescript')
  <script>
    $("#side-menu li[rel='entrust']").addClass("active")
      .find("ul").addClass("in")
      .find("li[rel='role']").addClass("active");

    seajs.use('models/entrustModel', function(entrustModel) {
      var filter = {"role_id":"{{$role->id}}", "page":1, "limit":12, "kw":""};
      var renderPageList = function(){
        entrustModel.getMembers(filter, function(data){
          $("#tblDataList tbody").html(template("tplDataList", data));
          $("#pagination").html(page(data.page_count, data.total_rows, data.page));
        }, failure);
      };

      $("#pagination").delegate("li a", "click", function(){
        filter.page = $(this).attr("rel");
        renderPageList();
      });

      $("#btnRefresh").on("click", function(){
        renderPageList();
      });

      $("#formSearch").on("submit", function(){
        filter.kw = this.kw.value;
        filter.page = 1;
        renderPageList();
        return false;
      });

      var cur_user_id = 0;
      $("#tblDataList").delegate("tr", "click", function(){
        var user_id = $(this).data("id");
        if(cur_user_id == user_id) return;
        cur_user_id = user_id;
        $("#tblMemberInfo").html('<p class="ibox-loading-31"></p>');
        entrustModel.getMemberInfo({"user_id":user_id}, function(data){
          $("#tblMemberInfo").html(template("tplMemberInfo", data));
        });
      });

      $("#tblMemberInfo").delegate(".btn-danger", "click", function(){
        var user_id = $(this).data("id");
        dialog({
          content: '<i class="fa fa-info-circle"></i> 从角色中移除此用户！是否继续？',
          ok: function(){
            entrustModel.removeMember({"role_id":filter.role_id,"user_id":user_id}, function(data){
              $("#tblMemberInfo").html("");
              $("#tblDataList tr[data-id='"+user_id+"']").remove();
            }, failure);
          },
          cancel: true
        }).showModal();
      });

      $("#btnMemberAdd").on("click", function(){
        $("#bootstrapModal").modal();
        renderOuterList();
      });

      var filte2 = {"page":1, "limit":8, "kw":""};
      filte2.role_id = filter.role_id;
      var renderOuterList = function(){
        $("#tblOuterMemberList tbody").html('<tr><td><p class="ibox-loading-31"></p></td></tr>');
        entrustModel.getOuterMembers(filte2, function(data){
          $("#tblOuterMemberList").html(template('tplOuterMemberList', data));
          $("#pagination2").html(page(data.page_count, data.total_rows, data.page));
        });
      };

      $("#pagination2").delegate("li a", "click", function(){
        filte2.page = $(this).attr("rel");
        renderOuterList();
      });

      $("#formOuterSearch").on("submit", function(){
        filte2.kw = this.kw.value;
        filte2.page = 1;
        renderOuterList();
        return false;
      });

      $("#tblOuterMemberList").delegate(".js-plus", "click", function(){
        var tr = $(this).parents("tr").eq(0),
          param = {"role_id":filte2.role_id, "user_id":tr.data("id")};
        entrustModel.addMember(param, function(data){
          tr.remove();
          var d = dialog({
            title: false,
            content: '<i class="fa fa-check-circle"></i>添加成员操作成功！',
            zIndex: 3000,
          }).show();
          setTimeout(function () {
            d.close().remove();
          }, 2000);
        }, failure);
      });

      renderPageList();
    });
  </script>
@endsection
