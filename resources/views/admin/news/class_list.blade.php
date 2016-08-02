@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox">
        <div class="ibox-title">
            <h5>栏目结构</h5>
        </div>
        <div class="ibox-content">
            <form class="form-inline m-b">
                <div id="js_classSelContainer" class="form-group">
                    <label>栏目选择：</label>
                </div>
                <a href="javascript:;" id="btnAdd" class="btn btn-warning"><i class="fa fa-plus"></i> 新增</a>
            </form>
            <table id="tblDataList" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th width="40" title="图片">图</th>
                        <th width="40" title="副标题">副</th>
                        <th width="40" title="简介">简</th>
                        <th width="40" title="内容">详</th>
                        <th width="40" title="标签">标</th>
                        <th width="40" title="链接">链</th>
                        <th width="40" title="编辑">编</th>
                        <th width="40" title="作者">作</th>
                        <th width="40" title="来源">源</th>
                        <th width="40" title="小图">小</th>
                        <th width="40" title="大图">大</th>
                        <th width="40" title="多图">多</th>
                        <th width="40" title="附件">附</th>
                        <th width="40" title="置顶">顶</th>
                        <th width="40" title="新品">新</th>
                        <th width="40" title="热点">热</th>
                        <th width="40" title="推荐">荐</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script id="tplDataListTr" type="text/html">
    <%each rows as row i%>
    <tr data-id="<%row.id%>">
        <td><%i+1%></td>
        <td><%row.name%></td>
        <td>
            <a href="javascript:;" class="js-pic1" data-src="<%row.pic1%>">
                <%if row.pic1%>
                <i class="fa fa-file-image-o fa-lg"></i>
                <%else%>
                <i class="fa fa-cloud-upload fa-lg"></i>
                <%/if%>
            </a>
        </td>
        <td><i class="<%row.has_subtitle | $vs%>"></td>
        <td><i class="<%row.has_intro | $vs%>"></td>
        <td><i class="<%row.has_content | $vs%>"></td>
        <td><i class="<%row.has_tags | $vs%>"></td>
        <td><i class="<%row.has_website | $vs%>"></td>
        <td><i class="<%row.has_editor | $vs%>"></td>
        <td><i class="<%row.has_author | $vs%>"></td>
        <td><i class="<%row.has_source | $vs%>"></td>
        <td><i class="<%row.has_pic1 | $vs%>"></td>
        <td><i class="<%row.has_pic2 | $vs%>"></td>
        <td><i class="<%row.has_pics | $vs%>"></td>
        <td><i class="<%row.has_file1 | $vs%>"></td>
        <td><i class="<%row.has_top | $vs%>"></td>
        <td><i class="<%row.has_new | $vs%>"></td>
        <td><i class="<%row.has_hot | $vs%>"></td>
        <td><i class="<%$vs(row.has_recommend)%>"></i></td>
        <td>
            <a href="javascript:;" class="js-edit m-r"><i class="fa fa-edit"></i>编辑</a>
            <a href="javascript:;" class="js-del"><i class="fa fa-trash"></i> 删除</a>
        </td>
    </tr>
    <%/each%>
</script>

<script id="tplAddPanel" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">新增栏目</h4>
            </div>
            <div class="modal-body">
                <form id="formAdd" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">序号：</label>
                        <div class="col-sm-6">
                            <input type="text" name="sortnum" class="form-control" value="" maxlength="4" />
                        </div>
                        <label class="col-sm-4"><small>只允许输入数字</small></label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">栏目名称：</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" autocomplete="off" required="" autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">转向地址：</label>
                        <div class="col-sm-10">
                            <input type="text" name="url" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">显示方式：</label>
                        <div class="col-sm-3">
                            <select name="mode" class="form-control" required="">
                                <option value="">- 请选择 -</option>
                                <option value="1"<%if mode == 1%> selected<%/if%>>内容模式</option>
                                <option value="2"<%if mode == 2%> selected<%/if%>>新闻列表</option>
                                <option value="3"<%if mode == 3%> selected<%/if%>>图片列表</option>
                                <option value="4"<%if mode == 4%> selected<%/if%>>图文列表</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">栏目深度：</label>
                        <div class="col-sm-2">
                            <input type="text" value="<%depth%>" name="depth" maxlength="1" size="4" class="form-control" required="" />
                        </div>
                        <div class="col-sm-3">
                            <select name="sort_by" class="form-control">
                                <option value="ASC"<%if sort_by == 'ASC'%> selected<%/if%>>升序排列</option>
                                <option value="DESC"<%if sort_by == 'DESC'%> selected<%/if%>>降序排列</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">记录状态：</label>
                        <div class="col-sm-3">
                            <select name="allow_add" class="form-control">
                                <option value="1"<%if allow_add == 1%> selected<%/if%>>允许增加</option>
                                <option value="0"<%if allow_add == 0%> selected<%/if%>>不允许增加</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="allow_edit" class="form-control">
                                <option value="1"<%if allow_edit == 1%> selected<%/if%>>允许修改</option>
                                <option value="0"<%if allow_edit == 0%> selected<%/if%>>不允许修改</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="allow_del" class="form-control">
                                <option value="1"<%if allow_del == 1%> selected<%/if%>>允许删除</option>
                                <option value="0"<%if allow_del == 0%> selected<%/if%>>不允许删除</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">记录设置：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_subtitle" value="1"<%if has_subtitle == 1%> checked="checked"<%/if%> />
                                副标题
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_intro" value="1"<%if has_intro == 1%> checked="checked"<%/if%> />
                                简介
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_content" value="1"<%if has_content == 1%> checked="checked"<%/if%> />
                                详细内容
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_tags" value="1"<%if has_tags == 1%> checked="checked"<%/if%> />
                                标签
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_author" value="1"<%if has_author == 1%> checked="checked"<%/if%> />
                                作者
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_editor" value="1"<%if has_editor == 1%> checked="checked"<%/if%> />
                                编辑
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_source" value="1"<%if has_source == 1%> checked="checked"<%/if%> />
                                来源
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_website" value="1"<%if has_website == 1%> checked="checked"<%/if%> />
                                链接
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pic1" value="1"<%if has_pic1 == 1%> checked="checked"<%/if%> />
                                小图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pic2" value="1"<%if has_pic2 == 1%> checked="checked"<%/if%> />
                                大图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pics" value="1"<%if has_pics == 1%> checked="checked"<%/if%> />
                                多图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_file1" value="1"<%if has_file1 == 1%> checked="checked"<%/if%> />
                                附件
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_top" value="1"<%if has_top == 1%> checked="checked"<%/if%> />
                                置顶
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_new" value="1"<%if has_new == 1%> checked="checked"<%/if%> />
                                新品
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_hot" value="1"<%if has_hot == 1%> checked="checked"<%/if%> />
                                热点
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_recommend" value="1"<%if has_recommend == 1%> checked="checked"<%/if%> />
                                推荐
                            </label>
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
    </div>
</script>

<script id="tplEditPanel" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">编辑栏目</h4>
            </div>
            <div class="modal-body">
                <form id="formEdit" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">序号：</label>
                        <div class="col-sm-6">
                            <input type="text" name="sortnum" class="form-control" value="<%sortnum%>" maxlength="4" />
                        </div>
                        <label class="col-sm-4"><small>只允许输入数字</small></label>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">栏目名称：</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="<%name%>" required="" autofocus />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">转向地址：</label>
                        <div class="col-sm-10">
                            <input type="text" name="url" value="<%url%>" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">显示方式：</label>
                        <div class="col-sm-3">
                            <select name="mode" class="form-control" required="">
                                <option value="">- 请选择 -</option>
                                <option value="1"<%if (mode==1)%> selected<%/if%>>内容模式</option>
                                <option value="2"<%if (mode==2)%> selected<%/if%>>新闻列表</option>
                                <option value="3"<%if (mode==3)%> selected<%/if%>>图片列表</option>
                                <option value="4"<%if (mode==4)%> selected<%/if%>>图文列表</option>
                            </select>
                        </div>
                        <label class="col-sm-2 control-label">栏目深度：</label>
                        <div class="col-sm-2">
                            <input type="text" value="<%depth%>" name="depth" maxlength="1" size="4" class="form-control" required="" />
                        </div>
                        <div class="col-sm-3">
                            <select name="sort_by" class="form-control">
                                <option value="ASC"<%if sort_by == 'ASC'%> selected<%/if%>>升序排列</option>
                                <option value="DESC"<%if sort_by == 'DESC'%> selected<%/if%>>降序排列</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">记录状态：</label>
                        <div class="col-sm-3">
                            <select name="allow_add" class="form-control">
                                <option value="1"<%if allow_add==1%> selected="selected"<%/if%>>允许增加</option>
                                <option value="0"<%if allow_add==0%> selected="selected"<%/if%>>不允许增加</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="allow_edit" class="form-control">
                                <option value="1"<%if allow_edit==1%> selected="selected"<%/if%>>允许修改</option>
                                <option value="0"<%if allow_edit==0%> selected="selected"<%/if%>>不允许修改</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="allow_del" class="form-control">
                                <option value="1"<%if allow_del==1%> selected="selected"<%/if%>>允许删除</option>
                                <option value="0"<%if allow_del==0%> selected="selected"<%/if%>>不允许删除</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">记录设置：</label>
                        <div class="col-sm-10">
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_subtitle" value="1"<%if (has_subtitle==1)%> checked="checked"<%/if%> />
                                副标题
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_intro" value="1"<%if (has_intro==1)%> checked="checked"<%/if%> />
                                简介
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_content" value="1"<%if (has_content==1)%> checked="checked"<%/if%> />
                                详细内容
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_tags" value="1"<%if (has_tags==1)%> checked="checked"<%/if%> />
                                标签
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_author" value="1"<%if (has_author==1)%> checked="checked"<%/if%> />
                                作者
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_editor" value="1"<%if (has_editor==1)%> checked="checked"<%/if%> />
                                编辑
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_source" value="1"<%if (has_source==1)%> checked="checked"<%/if%> />
                                来源
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_website" value="1"<%if (has_website==1)%> checked="checked"<%/if%> />
                                链接
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pic1" value="1"<%if (has_pic1==1)%> checked="checked"<%/if%> />
                                小图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pic2" value="1"<%if (has_pic2==1)%> checked="checked"<%/if%> />
                                大图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_pics" value="1"<%if (has_pics==1)%> checked="checked"<%/if%> />
                                多图
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_file1" value="1"<%if (has_file1==1)%> checked="checked"<%/if%> />
                                附件
                            </label>
                            <br />
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_top" value="1"<%if (has_top==1)%> checked="checked"<%/if%> />
                                置顶
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_new" value="1"<%if (has_new==1)%> checked="checked"<%/if%> />
                                新品
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_hot" value="1"<%if (has_hot==1)%> checked="checked"<%/if%> />
                                热点
                            </label>
                            <label class="checkbox-inline i-checks">
                                <input type="checkbox" name="has_recommend" value="1"<%if (has_recommend==1)%> checked="checked"<%/if%> />
                                推荐
                            </label>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <input type="hidden" name="id" value="<%id%>" />
                            <button class="btn btn-success btn-w-m" type="submit"><i class="fa fa-save"></i> 保存</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>

<script id="tplUploadPic1" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">图片上传</h4>
            </div>
            <div class="modal-body">
                <form id="formUppic1" method="post" enctype="multipart/form-data" class="form-horizontal container">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择图片</label>
                        <div class="col-sm-5">
                            <input name="pic1" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="id"  value="<%id%>">
                            <button type="submit" class="btn btn-primary btn-w-m"><i class="fa fa-cloud-upload"></i> 上传图片</button>
                            <button id="btnDeletePic1" type="button" class="btn btn-danger m-l" data-id="<%id%>"><i class="fa fa-trash"></i> 删除图片</button>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">预览</label>
                        <div id="pictureViewer" class="col-sm-10">
                            <%if src%>
                            <img src="<%src%>" class="img-thumbnail" />
                            <%else%>
                            <img src="/assets/images/blank.gif" class="img-thumbnail" />
                            <%/if%>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>
@endsection

@section('pageheader')
<link href="{{cdn1('assets/iCheck/custom.css')}}" rel="stylesheet">
<style>
    #js_classSelContainer select{margin-right:10px;}
</style>
@endsection

@section('pagescript')
<script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>
<script>
    $("#side-menu li[rel='news']").addClass("active")
        .find("ul").addClass("in")
        .find("li[rel='1']").addClass("active");

    seajs.use('models/newsclassModel', function(newsclassModel) {
        var column = {id: ""};
        var iCheckRender = function(){
            $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
          });
        };

        template.helper('$vs', function (flag) {
            return (flag == "1") ? 'fa fa-check text-navy' : 'fa fa-ban';
        });

        var renderClassSelector = function(data){
            var depth = column.id.length / 4 + 1;
            for(var i=depth; i<10; i++){
                if($("#ClassSelectorLv" + i).size() > 0){
                    $("#ClassSelectorLv" + i).remove();
                }else{
                    break;
                }
            }
            var objSelectId = "ClassSelectorLv" + depth,
            rows = data.rows;
            if(rows.length > 0){
                $("#js_classSelContainer").append('<select id="'+objSelectId+'" class="form-control"></select>');
                $("#"+objSelectId).append('<option value="'+column.id+'">- 请选择 -</option>');
                for(var i in rows){
                    $("#"+objSelectId).append('<option value="'+rows[i].id+'">'+rows[i].name+'</option>');
                }
            }
        };

        $("#js_classSelContainer").delegate('select', 'change', function(){
            column.id = $(this).val();
            renderList();
        });

        var renderList = function(){
            newsclassModel.getList(column, function(data){
                column = data.column;
                renderClassSelector(data);
                $("#tblDataList tbody").html(template('tplDataListTr', data));
            }, failure);
        };
        renderList();

        $("#btnAdd").on("click", function(){
            $("#modalDialog").html(template('tplAddPanel', column)).modal('show');
            iCheckRender();
        });

        $("#modalDialog").delegate('#formAdd', 'submit', function(){
            if(this.sortnum.value){
                if ( ! /^\d{1,4}$/.exec(this.sortnum.value)){
                    alert("分类序号只能使用1-4位数字！");
                    this.sortnum.focus();
                    return false;
                }
            }
            $(this).find(":submit").attr("disabled", "disabled");
            newsclassModel.insert($(this).serialize(), function(data){
                renderList();
                $("#modalDialog").html("").modal("hide");
            }, failure);
            return false;
        });

        $("#tblDataList").delegate('.js-edit', 'click', function(){
            var id = $(this).parents('tr').eq(0).data('id');
            newsclassModel.getInfo({'id':id}, function(data){
                $("#modalDialog").html(template("tplEditPanel", data)).modal("show");
                iCheckRender();
            }, failure);
        });

        $("#modalDialog").delegate('#formEdit', 'submit', function(){
            if ( ! /^\d{1,4}$/.exec(this.sortnum.value)){
                alert("分类序号只能使用1-4位数字！");
                this.sortnum.focus();
                return false;
            }
            $(this).find(":checkbox").each(function(){
                $(this).val(this.checked ? 1 : 0);
                this.checked = true;
            });
            $(this).find(":submit").attr("disabled", "disabled");
            newsclassModel.update($(this).serialize(), function(data){
                renderList();
                $("#modalDialog").html("").modal("hide");
            }, failure);
            return false;
        });

        $("#tblDataList").delegate('.js-pic1', 'click', function(){
            var data = {
                'id': $(this).parents('tr').eq(0).data("id"),
                'src': $(this).data("src")
            };
            $("#modalDialog").html(template("tplUploadPic1", data)).modal("show");
        });

        $("#modalDialog").delegate("#formUppic1", 'submit', function(){
            var id = this.id.value,
                file = this.pic1.value;
            if (file == ""){
                alert("请选择上传文件！");
                return false;
            }
            if (file.lastIndexOf(".") == -1){
                alert("文件类型不正确！");
                return false;
            }
            var ext = file.substr(file.lastIndexOf(".") + 1).toLowerCase();
            if (ext != 'jpg' && ext != 'jpeg' && ext != 'gif' && ext != 'png'){
                alert('图片必须是 JPG、JPEG、GIF 或者 PNG 格式！');
                return false;
            }
            var data = new FormData();
            data.append('id', this.id.value);
            data.append('pic1', this.pic1.files[0]);
            newsclassModel.uploadPic1(data, function(result){
                $("#pictureViewer img").eq(0).attr("src", result);
                var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                    ta = tr.find(".js-pic1").eq(0);
                ta.data("src", result);
                ta.find("i").removeClass("fa-cloud-upload").addClass("fa-file-image-o");
            }, failure);
            return false;
        });

        $("#modalDialog").delegate("#btnDeletePic1", "click", function(){
            var id = $(this).data("id");
            if(confirm("确定要删除此图片吗？")){
                newsclassModel.removePic1({"id":id}, function(){
                    $("#pictureViewer img").eq(0).attr("src", "/assets/images/blank.gif");
                    var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                        ta = tr.find(".js-pic1").eq(0);
                    ta.data("src", "");
                    ta.find("i").removeClass("fa-file-image-o").addClass("fa-cloud-upload");
                }, failure);
            }
        });

        $("#tblDataList").delegate('.js-del', 'click', function(e){
            var id = $(this).parents('tr').eq(0).data('id');
            dialog({
                content: '<i class="fa fa-info-circle"></i> 确定要删除吗？',
                ok: function(){
                    newsclassModel.delete({'id':id}, function(){
                        $(e.target).parents("tr").remove();
                        $("#js_classSelContainer select").find("option[value='"+ id +"']").remove();
                    }, failure);
                },
                cancel: true
            }).showModal();
        });
    });
</script>
@endsection
