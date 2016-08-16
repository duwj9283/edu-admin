<div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
        <form name="import-form" onsubmit="return false" action="/admin/webuser/import" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">角色列表如下：<small>请勾选为其授权</small></h4>
            </div>
            <div class="modal-body">

                <table id="tblDataList" class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th width="60" class="text-center"><a href="javascript:;" onclick="checkAll()">全选</a></th>
                        <th>名称</th>
                        <th>全名</th>
                        <th>描述</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles_rows as $row)
                        <tr data-id="{{$row->id}}">
                            <td class="text-center">
                                <label class="i-checks">
                                    @if (in_array($row->id, $userRole))
                                        <input type="checkbox" value="{{$row->id}}" class="js-check" checked="" />
                                    @else
                                        <input type="checkbox" value="{{$row->id}}" class="js-check" />
                                    @endif
                                </label>
                            </td>
                            <td>{{$row->name}}</td>
                            <td>{{$row->display_name}}</td>
                            <td>{{$row->description}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">取消</button>
            </div>
        </form>
    </div>
</div>





<script>


    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });

    var checkAll = function(){
        $(".js-check").iCheck("check");
    };

    seajs.use('models/entrustModel', function(entrustModel) {
        var uid = '{{$user->uid}}';
        $(".js-check").on('ifChanged', function(){
            var checked = $(this).prop("checked");
            entrustModel.updateUsersRole({'user_id':uid,'role_id':$(this).val()}, null, function(data){
                artInfo(data);
                return false;
            });
            /*if(checked){
                entrustModel.updateUsersRole({'user_id':uid,'role_id':$(this).val()}, null, failure);
            }else{
                entrustModel.removePerm({'user_id':uid,'role_id':$(this).val()}, null, function(data){
                    artInfo(data);
                    return false;
                });
            }*/
        });
    });
</script>

