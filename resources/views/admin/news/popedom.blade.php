@extends('admin.main')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>资讯栏目权限分配</h2>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>角色列表</h5>
                </div>
                <div class="ibox-content no-padding">
                    <ul class="list-group">
                        @foreach($roles as $role)
                        @if($role->id == $role_id)
                        <li class="list-group-item current">
                            <span class="label label-success">{{$role->id}}</span>
                            {{$role->name}}
                        </li>
                        @else
                        <li class="list-group-item">
                            <a href="?role_id={{$role->id}}">
                                <span class="label label-success">{{$role->id}}</span>
                                {{$role->name}}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 animated fadeInRight">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>栏目权限分配</h4>
                    <small>系统管理员（Administrators）具有不受限制的所有操作权限</small>
                    <div class="hr-line-dashed"></div>
                    <div id="nestableNodes" style="margin:0 10px;">
                        <?php $j = 0;?>
                        @foreach ($columns as $row)
                        <?php $class_id = $row->id;?>
                        @if ($row->depth < $j)
                        <?php $n = $j - $row->depth;?>
                        @for ($i = 0; $i < $n; $i++)
                        </div>
                        @endfor
                        @endif
                        <div class="treeNode" data-id="{{$row->id}}">
                            @if($row->child > 0)
                            <a href="javascript:;" class="js-expand"> <i class="fa fa-plus-square"></i>
                                <span>{{$row->name}}</span>
                            </a>
                            @else
                            <i class="fa fa-leaf"></i>
                            <span>{{$row->name}}</span>
                            @endif
                        </div>
                        @if ($row->child > 0)
                        <div id="span_{{$row->id}}" style="text-indent:0.5em;display:none;">
                        @endif
                            @if ($row->depth > $j)
                                <?php $j++;?>
                            @elseif ($row->depth < $j)
                                <?php $j--;?>
                            @endif
                        @endforeach
                        @for ($i = 1; $i < $j; $i++)
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="tplPopedomChecker" type="text/html">
    <div class="pull-right">
        <label class="checkbox-inline i-checks">
            <input data-id="a<%id%>" type="checkbox" value="1"<%if perms[id] & 1%> checked<%/if%> />
            增加
        </label>
        <label class="checkbox-inline i-checks">
            <input data-id="e<%id%>" type="checkbox" value="2"<%if perms[id] & 2%> checked<%/if%> />
            编辑
        </label>
        <label class="checkbox-inline i-checks">
            <input data-id="c<%id%>" type="checkbox" value="4"<%if perms[id] & 4%> checked<%/if%> />
            审核
        </label>
        <label class="checkbox-inline i-checks">
            <input data-id="d<%id%>" type="checkbox" value="8"<%if perms[id] & 8%> checked<%/if%> />
            删除
        </label>
    </div>
</script>
@endsection

@section('pageheader')
<link rel="stylesheet" href="{{cdn1('assets/iCheck/custom.css')}}">
<style>
    .list-group li.current{background-color: #f9f9f9;}
    .treeNode{padding:10px 1em;border-bottom:1px dotted #c1c1c1;}
    .treeNode > span{display:inline-block;width:30%;}
</style>
@endsection

@section('pagescript')
<script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>
<script>
    $("#side-menu li[rel='news']").addClass("active")
        .find("ul").addClass("in")
        .find("li[rel='3']").addClass("active");

    seajs.use('models/newsclassModel', function(newsclassModel) {
        var perms = {!! json_encode($perms) !!};

        $("#nestableNodes .treeNode").each(function(){
            var id = $(this).data('id');
            $(this).prepend(template('tplPopedomChecker', {'id':id,'perms':perms}));
        });
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });

        $("#nestableNodes").delegate(".js-expand", "click", function(){
            $("#span_" + $(this).parent().data("id")).toggle();
            var obj = $(this).find("i").eq(0);
            if(obj.hasClass("fa-plus-square")){
                obj.removeClass("fa-plus-square").addClass("fa-minus-square");
            }else{
                obj.removeClass("fa-minus-square").addClass("fa-plus-square");
            }
        });

        $("#nestableNodes").delegate(".i-checks input", 'ifToggled', function(){
            var id = $(this).data("id");
            var checked = $(this).prop("checked");
            $(".i-checks input").each(function(){
                if($(this).data("id").indexOf(id) == 0){
                    if($(this).prop("checked") != checked){
                        $(this).parent().iCheck(checked ? 'check' : 'uncheck');
                    }
                }
            });
            var data = {
                'role_id': {{$role_id}},
                'class_id': $(this).data("id").substr(1),
                'popedom': $(this).val()
            };
            if(data.role_id == 1) return false;
            if($(this).prop("checked")){
                newsclassModel.addPerm(data, null, failure);
            }else{
                newsclassModel.removePerm(data, null, failure);
            }
        });
    });
</script>
@endsection