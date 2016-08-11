

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox">
            <div class="ibox-title">
                <h5><span class="text-danger">{{$role->display_name}}</span>权限列表 <small>请勾选为其授权</small></h5>
            </div>
            <div class="ibox-content">
                <table id="tblDataList" class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th width="60" class="text-center">
                                <a href="javascript:;" onclick="checkAll()">全选</a>
                            </th>
                            <th>权限</th>
                            <th>权限名</th>
                            <th>描述</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perms_rows as $row)
                        <tr data-id="{{$row->id}}">
                            <td class="text-center">
                                <label class="i-checks">
                                    @if (in_array($row->id, $grant_perms->all()))
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
        </div>
    </div>



    <script>
        $("#side-menu li[rel='entrust']").addClass("active")
            .find("ul").addClass("in")
            .find("li[rel='role']").addClass("active");

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        var checkAll = function(){
            $(".js-check").iCheck("check");
        };

        seajs.use('models/entrustModel', function(entrustModel) {
            var role_id='{{$role->id}}';
            $(".js-check").on('ifChanged', function(){
                var checked = $(this).prop("checked");
                if(checked){
                    entrustModel.grantPerm({'role_id':role_id,'perm_id':$(this).val()}, null, failure);
                }else{
                    entrustModel.removePerm({'role_id':role_id,'perm_id':$(this).val()}, null, function(data){
                        artInfo(data);
                        return false;
                    });
                }
            });
        });
    </script>
