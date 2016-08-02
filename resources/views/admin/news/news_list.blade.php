<?php $token = session('token');?>
@extends('admin.main')

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div id="ibox01" class="ibox">
        <div class="ibox-title">
            <h5>
                资讯管理
                <?php foreach ($navigation as $nav): ?>
                <i class="fa fa-angle-double-right m-l m-r"></i>
                {{$nav['name']}}
                <?php endforeach;?>
            </h5>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-sm-9 m-b">
                    <div class="dropdown">
                        <a href="javascript:;" id="btnCreate" class="btn btn-primary m-l-xs"> <i class="fa fa-plus"></i>
                            新建
                        </a>
                        <a href="javascript:;" id="btnReverse" class="btn btn-info m-l-xs">反向选择</a>
                        <a href="javascript:;" id="btnMove" class="btn btn-warning m-l-xs"> <i class="fa fa-cut"></i>
                            移动
                        </a>
                        <a href="javascript:;" id="btnRemove" class="btn btn-danger m-l-xs">
                            <i class="fa fa-remove"></i>
                            删除
                        </a>
                    </div>
                </div>
                <div class="col-sm-3">
                    <form id="searchForm">
                        <div class="input-group">
                            <input type="text" name="keyword" placeholder="Search" class="input-sm form-control">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-sm btn-primary">搜索</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table id="tblDataList" class="table table-striped table-hover">
                    <thead></thead>
                    <tbody></tbody>
                </table>
                <div class="text-center">
                    <ul class="pagination m-n"></ul>
                </div>
            </div>
        </div>
    </div>
    <div id="ibox02" class="ibox" style="display:none;">
        <p class="ibox-loading-31"></p>
    </div>
</div>

<script id="tplColumnTree" type="text/html">
    <h4>请选择移动到哪个栏目：</h4>
    <ul id="columnTree" class="ztree"></ul>
</script>

<script id="tplDataListTh" type="text/html">
    <tr>
        <th>
            <input type="checkbox" id="checkAll" />
        </th>
        <th>序号</th>
        <th width="40"><a data-toggle="tooltip" data-placement="top" title="编辑">改</a></th>
        <th>标题</th>
        <th><a data-toggle="tooltip" data-placement="top" title="审核">审</a></th>
        <th>发布时间</th>
        <th>浏览量</th>
        <%if has_pic1==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="图片">图片</a></th>
        <%/if%>
        <%if has_pic2==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="大图">大图</a></th>
        <%/if%>
        <%if has_pics==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="多图">多图</a></th>
        <%/if%>
        <%if has_file1==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="附件">附</a></th>
        <%/if%>
        <%if has_hot==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="热点">热</a></th>
        <%/if%>
        <%if has_new==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="新品">新</a></th>
        <%/if%>
        <%if has_top==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="置顶">顶</a></th>
        <%/if%>
        <%if has_recommend==1%>
        <th><a data-toggle="tooltip" data-placement="top" title="推荐">荐</a></th>
        <%/if%>
    </tr>
</script>

<script id="tplDataListTr" type="text/html">
    <%each rows as row i%>
    <tr class="listTr" data-id="<%row.id%>">
        <td>
            <input type="checkbox" name="ids" value="<%row.id%>" />
        </td>
        <td><%row.sortnum%></td>
        <td><a href="javascript:;" class="js-edit"><i class="fa fa-edit"></i></a></td>
        <td><%row.title%></td>
        <td>
            <a href="javascript:;" class="js-status">
                <%if row.status==1%>
                <i class="fa fa-check text-navy"></i>
                <%else%>
                <i class="fa fa-ban text-danger"></i>
                <%/if%>
            </a>
        </td>
        <td><%row.publish_at%></td>
        <td><%row.views%></td>
        <%if column.has_pic1==1%>
        <td>
            <a href="javascript:;" class="js-pic1" data-src="<%row.pic1%>">
                <%if row.pic1%>
                <i class="fa fa-file-image-o fa-lg"></i>
                <%else%>
                <i class="fa fa-cloud-upload fa-lg"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_pic2==1%>
        <td>
            <a href="javascript:;" class="js-pic2" data-src="<%row.pic2%>">
                <%if row.pic2%>
                <i class="fa fa-file-image-o fa-lg"></i>
                <%else%>
                <i class="fa fa-cloud-upload fa-lg"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_pics==1%>
        <td>
            <a href="{{url('admin/newsinfo/pics')}}?id=<%row.id%>">
                <i class="icons icon-pics" title="多图"></i>
            </a>
        </td>
        <%/if%>
        <%if column.has_file1==1%>
        <td>
            <a href="javascript:;" class="js-file1" data-src="<%row.file1%>">
                <%if row.file1%>
                <i class="fa fa-file fa-lg"></i>
                <%else%>
                <i class="fa fa-cloud-upload fa-lg"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_hot==1%>
        <td>
            <a href="javascript:;" class="js-hot">
                <%if row.is_hot==1%>
                <i class="fa fa-check-circle fa-lg text-navy"></i>
                <%else%>
                <i class="fa fa-times-circle fa-lg text-danger"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_new==1%>
        <td>
            <a href="javascript:;" class="js-new">
                <%if row.is_new==1%>
                <i class="fa fa-check-circle fa-lg text-navy"></i>
                <%else%>
                <i class="fa fa-times-circle fa-lg text-danger"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_top==1%>
        <td>
            <a href="javascript:;" class="js-top">
                <%if row.is_top==1%>
                <i class="fa fa-check-circle fa-lg text-navy"></i>
                <%else%>
                <i class="fa fa-times-circle fa-lg text-danger"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
        <%if column.has_recommend==1%>
        <td>
            <a href="javascript:;" class="js-recommend">
                <%if row.is_recommend==1%>
                <i class="fa fa-check-circle fa-lg text-navy"></i>
                <%else%>
                <i class="fa fa-times-circle fa-lg text-danger"></i>
                <%/if%>
            </a>
        </td>
        <%/if%>
    </tr>
    <%/each%>
</script>

<script id="tplAddPanel" type="text/html">
    <div class="ibox-title">
        <h5>信息发布</h5>
        <div class="ibox-tools">
            <a class="close-link">
                <i class="fa fa-times js-cancel"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <form id="formAdd" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-2 control-label">序号</label>
                <div class="col-lg-3">
                    <input type="text" name="sortnum" value="" class="form-control" />
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">首字母</span>
                        <input type="text" id="txtFirstLetter" name="first_letter" value="" class="form-control" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">浏览</span>
                        <input type="text" name="views" value="" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">标题</label>
                <div class="col-lg-7">
                    <input type="text" id="txtTitle" name="title" value="" class="form-control" required="" autofocus />
                </div>
                <div class="col-lg-2">
                    <input type="text" id="txtTitleColor" name="title_color" class="form-control" value="" />
                </div>
            </div>
            <%if has_subtitle==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">副标题</label>
                <div class="col-lg-10">
                    <input type="text" name="subtitle" value="" class="form-control" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="subtitle" value="" />
            <%/if%>
            <%if has_tags==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">标签</label>
                <div class="col-lg-10">
                    <input type="text" id="txtInfoTags" name="tags" value="" class="form-control" placeholder="" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="tags" value="" />
            <%/if%>
            <%if has_website==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">链接</label>
                <div class="col-lg-10">
                    <input type="text" name="website" value="" class="form-control" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="website" value="" />
            <%/if%>
            <%if has_intro==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">简介</label>
                <div class="col-lg-10">
                    <textarea name="intro" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <%else%>
            <input type="hidden" name="intro" value="" />
            <%/if%>
            <%if has_content==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">内容</label>
                <div class="col-lg-10">
                    <textarea id="ueditor_content" name="content" rows="10"></textarea>
                </div>
            </div>
            <%else%>
            <input type="hidden" id="ueditor_content" name="content" value="" />
            <%/if%>
            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <%if has_author==1%>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">作者</span>
                        <input type="text" name="author" value="" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="author" value="" />
                <%/if%>
                <%if has_editor==1%>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">编辑</span>
                        <input type="text" name="editor" value="" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="editor" value="" />
                <%/if%>
                <%if has_source==1%>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">来源</span>
                        <input type="text" name="source" value="" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="source" value="" />
                <%/if%>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">时间</label>
                <div class="col-lg-2">
                    <input type="text" name="publish_at" value="<%today%>" required="" class="form-control date-picker" />
                </div>
                <div class="col-lg-8">
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="status" checked />
                        审核
                    </label>
                    @if($token['isHidden'])
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_locked" />
                        锁定
                    </label>
                    @endif
                    <%if has_top==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_top" />
                        置顶
                    </label>
                    <%else%>
                    <input type="hidden" name="is_top" value="0" />
                    <%/if%>
                    <%if has_new==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_new" />
                        新品
                    </label>
                    <%else%>
                    <input type="hidden" name="is_new" value="0" />
                    <%/if%>
                    <%if has_hot==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_hot" />
                        热点
                    </label>
                    <%else%>
                    <input type="hidden" name="is_hot" value="0" />
                    <%/if%>
                    <%if has_recommend==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_recommend" />
                        推荐
                    </label>
                    <%else%>
                    <input type="hidden" name="is_recommend" value="0" />
                    <%/if%>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input type="hidden" name="class_id" value="<%id%>" />
                    <button class="btn btn-primary btn-w-m m-r" type="submit">提 交</button>
                    <button class="btn btn-default js-cancel" type="button">取消</button>
                </div>
            </div>
        </form>
    </div>
</script>

<script id="tplEditPanel" type="text/html">
    <div class="ibox-title">
        <h5>编辑</h5>
        <div class="ibox-tools">
            <a class="close-link">
                <i class="fa fa-times js-cancel"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content">
        <form id="formEdit" class="form-horizontal">
            <div class="form-group">
                <label class="col-lg-2 control-label">序号</label>
                <div class="col-lg-3">
                    <input type="text" name="sortnum" value="<%info.sortnum%>" class="form-control" />
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">首字母</span>
                        <input type="text" id="txtFirstLetter" name="first_letter" value="<%info.first_letter%>" class="form-control" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">浏览</span>
                        <input type="text" name="views" value="<%info.views%>" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">标题</label>
                <div class="col-lg-7">
                    <input type="text" id="txtTitle" name="title" value="<%info.title%>" style="color:<%info.title_color%>;" class="form-control" required="" autofocus />
                </div>
                <div class="col-lg-2">
                    <input type="text" id="txtTitleColor" name="title_color" class="form-control" value="<%info.title_color%>" />
                </div>
            </div>
            <%if column.has_subtitle==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">副标题</label>
                <div class="col-lg-10">
                    <input type="text" name="subtitle" value="<%info.subtitle%>" class="form-control" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="subtitle" value="<%info.subtitle%>" />
            <%/if%>
            <%if column.has_tags==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">标签</label>
                <div class="col-lg-10">
                    <input type="text" id="txtInfoTags" name="tags" value="<%info.tags%>" class="form-control" placeholder="" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="tags" value="<%info.tags%>" />
            <%/if%>
            <%if column.has_website==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">链接</label>
                <div class="col-lg-10">
                    <input type="text" name="website" value="<%info.website%>" class="form-control" />
                </div>
            </div>
            <%else%>
            <input type="hidden" name="website" value="<%info.website%>" />
            <%/if%>
            <%if column.has_intro==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">简介</label>
                <div class="col-lg-10">
                    <textarea name="intro" class="form-control" rows="3"><%info.intro%></textarea>
                </div>
            </div>
            <%else%>
            <input type="hidden" name="intro" value="<%info.intro%>" />
            <%/if%>
            <%if column.has_content==1%>
            <div class="form-group">
                <label class="col-lg-2 control-label">内容</label>
                <div class="col-lg-10">
                    <textarea id="ueditor_content" name="content" rows="10"><%info.content%></textarea>
                </div>
            </div>
            <%else%>
            <input type="hidden" id="ueditor_content" name="content" value="<%info.content%>" />
            <%/if%>
            <div class="form-group">
                <label class="col-lg-2 control-label"></label>
                <%if column.has_author==1%>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">作者</span>
                        <input type="text" name="author" value="<%info.author%>" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="author" value="<%info.author%>" />
                <%/if%>
                <%if column.has_editor==1%>
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">编辑</span>
                        <input type="text" name="editor" value="<%info.editor%>" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="editor" value="<%info.editor%>" />
                <%/if%>
                <%if column.has_source==1%>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon">来源</span>
                        <input type="text" name="source" value="<%info.source%>" class="form-control" />
                    </div>
                </div>
                <%else%>
                <input type="hidden" name="source" value="<%info.source%>" />
                <%/if%>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label">时间</label>
                <div class="col-lg-2">
                    <input type="text" name="publish_at" value="<%info.publish_at%>" required="" class="form-control date-picker" />
                </div>
                <div class="col-lg-8">
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="status"<%if info.status==1%> checked<%/if%> />
                        审核
                    </label>
                    @if($token['isHidden'])
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_locked"<%if info.is_locked==1%> checked<%/if%> />
                        锁定
                    </label>
                    @endif
                    <%if column.has_top==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_top"<%if info.is_top==1%> checked<%/if%> />
                        置顶
                    </label>
                    <%else%>
                    <input type="hidden" name="is_top" value="<%info.is_top%>" />
                    <%/if%>
                    <%if column.has_new==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_new"<%if info.is_new==1%> checked<%/if%> />
                        新品
                    </label>
                    <%else%>
                    <input type="hidden" name="is_new" value="<%info.is_new%>" />
                    <%/if%>
                    <%if column.has_hot==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_hot"<%if info.is_hot==1%> checked<%/if%> />
                        热点
                    </label>
                    <%else%>
                    <input type="hidden" name="is_hot" value="<%info.is_hot%>" />
                    <%/if%>
                    <%if column.has_recommend==1%>
                    <label class="checkbox-inline i-checks">
                        <input type="checkbox" value="1" name="is_recommend"<%if info.is_recommend==1%> checked<%/if%> />
                        推荐
                    </label>
                    <%else%>
                    <input type="hidden" name="is_recommend" value="<%info.is_recommend%>" />
                    <%/if%>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <input type="hidden" name="id" value="<%info.id%>" />
                    <button class="btn btn-primary btn-w-m" type="submit">保 存</button>
                    <button class="btn btn-default m-l js-cancel" type="button">取消</button>
                </div>
            </div>
        </form>
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
                <form id="formUppic1" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择图片</label>
                        <div class="col-sm-5">
                            <input name="pic" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="id"  value="<%id%>" />
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

<script id="tplUploadPic2" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">图片上传</h4>
            </div>
            <div class="modal-body">
                <form id="formUppic2" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择图片</label>
                        <div class="col-sm-5">
                            <input name="pic" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="id"  value="<%id%>" />
                            <button type="submit" class="btn btn-primary btn-w-m"><i class="fa fa-cloud-upload"></i> 上传图片</button>
                            <button id="btnDeletePic2" type="button" class="btn btn-danger m-l" data-id="<%id%>"><i class="fa fa-trash"></i> 删除图片</button>
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

<script id="tplUploadFile1" type="text/html">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal" type="button">&times;</button>
                <h4 class="modal-title" id="avatar-modal-label">附件上传</h4>
            </div>
            <div class="modal-body">
                <form id="formUpfile1" method="post" enctype="multipart/form-data" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">选择文件</label>
                        <div class="col-sm-10">
                            <input name="file1" type="file" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10" id="txtFile1"><%src%></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="hidden" name="id"  value="<%id%>" />
                            <button type="submit" class="btn btn-primary btn-w-m"><i class="fa fa-cloud-upload"></i> 上传附件</button>
                            <button id="btnDeleteFile1" type="button" class="btn btn-danger m-l" data-id="<%id%>"><i class="fa fa-trash"></i> 删除文件</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>
@endsection

@section('pageheader')
<link href="{{cdn1('assets/common/bootstrap-tag.css')}}" rel="stylesheet">
<link href="{{cdn1('assets/iCheck/custom.css')}}" rel="stylesheet">
<link href="{{cdn1('assets/zTree/css/metroStyle/metroStyle.css')}}" / rel="stylesheet">
<link href="{{cdn1('assets/colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet">
<link href="{{cdn1('assets/datepicker/datepicker3.css')}}" rel="stylesheet">
@endsection

@section('pagescript')
<script src="{{cdn1('assets/jquery/plugins/bootstrap-tag.min.js')}}"></script>
<script src="{{cdn1('assets/jquery/plugins/typeahead-bs2.min.js')}}"></script>
<script src="{{cdn1('assets/iCheck/icheck.min.js')}}"></script>
<script src="{{cdn1('assets/zTree/js/jquery.ztree.core-3.5.js')}}"></script>
<script src="{{cdn1('assets/ueditor/ueditor.config.js')}}"></script>
<script src="{{cdn1('assets/ueditor/ueditor.all.js')}}"></script>
<script src="{{cdn1('assets/colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{cdn1('assets/datepicker/bootstrap-datepicker.js')}}"></script>
<script src="{{cdn1('assets/controllers/CnFirstPy.js')}}"></script>
<script src="{{cdn1('assets/controllers/stringExt.js')}}"></script>
<script src="{{cdn1('assets/controllers/dateExt.js')}}"></script>
<script>
    var curMenu = $("li[rel='news-{{$column->id}}'] > a");
    curMenu.parents("ul").addClass("in");
    curMenu.parents("li").addClass("active");

    seajs.use(['models/newsclassModel','models/newsinfoModel'], function(newsclassModel, newsinfoModel) {
        var ueditor, curClass = {!!$column!!},
            filter = {class_id:curClass.id,limit:20, page:1, keyword:''};

        $("#tblDataList").delegate("#checkAll", "click", function(){
            var that = this;
            $("input[name='ids']").each(function(){
                this.checked = that.checked;
            });
        });

        $("#btnReverse").on('click', function(){
            $("input[name='ids']").each(function(){
               this.checked = ! this.checked;
            });
            var m = $(".listTr").size();
            var n = $(".listTr input:checked").size();
            $("#checkAll").prop("checked", (m === n));
        });

        $("#searchForm").submit(function(){
            filter.page = 1;
            filter.keyword = this.keyword.value;
            renderList();
            return false;
        });

        var iboxRender = function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });
            $('#txtTitleColor').colorpicker();
            $('.date-picker').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
            ueditor = UE.getEditor('ueditor_content', {zIndex: 9});
            $("#txtTitle").on('blur', function(){
                var str = this.value.replace(/(^\s*)|(\s*$)/g, "");
                $('#txtFirstLetter').val(makePy(str.charAt(0)).toString().substr(0,1).toUpperCase());
            });
            var tag_input = $('#txtInfoTags');
            if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase()))){
                tag_input.tag({
                    placeholder:tag_input.attr('placeholder'),
                    source: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"],
                });
            } else {
                tag_input.after('<input id="'+tag_input.attr('id')+'" name="'+tag_input.attr('name')+'" value="'+tag_input.val()+'" />').remove();
            }
        };

        var renderList = function(){
            $("#tblDataList tbody").html('<tr><td><p class="ibox-loading-31">&nbsp;</p></td></tr>');
            newsinfoModel.getPageList(filter, function(data){
                $("#tblDataList thead").html(template('tplDataListTh', curClass));
                $("#tblDataList tbody").html(template('tplDataListTr', {'rows':data.rows, 'column':curClass}));
                $(".pagination").html(page(data.page_count, data.total_rows, data.page));
                $('[data-toggle="tooltip"]').tooltip();
            }, failure);
        };
        renderList();

        $(".pagination").delegate("li a", "click", function(){
            filter.page = $(this).attr("rel");
            renderList();
        });

        $("#btnCreate").on('click', function(){
            $("#ibox01").hide("slow");
            var data = curClass;
            data.today = new Date().Format("yyyy-mm-dd");
            $("#ibox02").html(template('tplAddPanel', data)).show("slow");
            iboxRender();
        });

        $("#ibox02").delegate("#formAdd", "submit", function(){
            if(required_check(this.title.value) == false){
                alert("文章标题不能为空！");
                this.title.focus();
                return false;
            }
            $(this).find(":checkbox").each(function(){
                $(this).val(this.checked ? 1 : 0);
                this.checked = true;
            });
            $(this).find(":submit").attr("disabled", "disabled");
            ueditor.sync();
            ueditor.destroy();
            newsinfoModel.create($(this).serializeArray(), function(data){
                if(curClass.sort_by == 'ASC'){
                    $("#tblDataList tbody").append(template('tplDataListTr', {'rows':[data], 'column':curClass}));
                }else{
                    $("#tblDataList tbody").prepend(template('tplDataListTr', {'rows':[data], 'column':curClass}));
                }
                $("#ibox02").hide("slow");
                $("#ibox01").show("slow");
            }, failure);
            return false;
        });

        $(".ibox").delegate(".js-cancel", 'click', function(){
            $(this).parents(".ibox").hide("slow");
            $("#ibox01").show("slow");
            ueditor.destroy();
        });

        $("#tblDataList").delegate('.js-edit', 'click', function(){
            var id = $(this).parents('tr').eq(0).data('id');
            $("#ibox01").hide("slow");
            $("#ibox02").html('<p class="ibox-loading-31"></p>').show("slow");
            newsinfoModel.getInfo({'id':id}, function(data){
                $("#ibox02").html(template('tplEditPanel', data));
                iboxRender();
            }, failure);
        });

        $("#ibox02").delegate("#formEdit", 'submit', function(){
            if(required_check(this.title.value) == false){
                alert("文章标题不能为空！");
                this.title.focus();
                return false;
            }
            $(this).find(":checkbox").each(function(){
                $(this).val(this.checked ? 1 : 0);
                this.checked = true;
            });
            ueditor.sync();
            ueditor.destroy();
            $(this).find(":submit").attr("disabled", "disabled");
            var formdata = $(this).serializeArray();
            newsinfoModel.update(formdata, function(data){
                var tr = $("#tblDataList").find("tr[data-id='"+ data.id +"']").eq(0);
                tr.after(template('tplDataListTr', {'rows':[data], 'column':curClass})).remove();
                $("#ibox02").hide("slow");
                $("#ibox01").show("slow");
            }, failure);
            return false;
        });

        $("#tblDataList").delegate('.js-status', 'click', function(){
            var obj = $(this).find('i'),
                id = $(this).parents('tr').eq(0).data('id'),
                status = obj.hasClass('fa-check') ? 0 : 1;
            obj.attr('class', 'fa fa-refresh animated rotateIn');
            newsinfoModel.update({'id':id,'status':status}, function(){
                if(status){
                    obj.attr("class", 'fa fa-check text-navy');
                }else{
                    obj.attr("class", 'fa fa-ban text-danger');
                }
            }, failure);
        });

        $("#tblDataList").delegate('.js-hot', 'click', function(){
            var obj = $(this).find('i'),
                id = $(this).parents('tr').eq(0).data('id'),
                status = obj.hasClass('fa-check-circle') ? 0 : 1;
            obj.attr('class', 'fa fa-refresh animated rotateIn');
            newsinfoModel.update({'id':id,'is_hot':status}, function(){
                if(status){
                    obj.attr("class", 'fa fa-check-circle fa-lg text-navy');
                }else{
                    obj.attr("class", 'fa fa-times-circle fa-lg text-danger');
                }
            }, failure);
        });

        $("#tblDataList").delegate('.js-new', 'click', function(){
            var obj = $(this).find('i'),
                id = $(this).parents('tr').eq(0).data('id'),
                status = obj.hasClass('fa-check-circle') ? 0 : 1;
            obj.attr('class', 'fa fa-refresh animated rotateIn');
            newsinfoModel.update({'id':id,'is_new':status}, function(){
                if(status){
                    obj.attr("class", 'fa fa-check-circle fa-lg text-navy');
                }else{
                    obj.attr("class", 'fa fa-times-circle fa-lg text-danger');
                }
            }, failure);
        });

        $("#tblDataList").delegate('.js-top', 'click', function(){
            var obj = $(this).find('i'),
                id = $(this).parents('tr').eq(0).data('id'),
                status = obj.hasClass('fa-check-circle') ? 0 : 1;
            obj.attr('class', 'fa fa-refresh animated rotateIn');
            newsinfoModel.update({'id':id,'is_top':status}, function(){
                if(status){
                    obj.attr("class", 'fa fa-check-circle fa-lg text-navy');
                }else{
                    obj.attr("class", 'fa fa-times-circle fa-lg text-danger');
                }
            }, failure);
        });

        $("#tblDataList").delegate('.js-recommend', 'click', function(){
            var obj = $(this).find('i'),
                id = $(this).parents('tr').eq(0).data('id'),
                status = obj.hasClass('fa-check-circle') ? 0 : 1;
            obj.attr('class', 'fa fa-refresh animated rotateIn');
            newsinfoModel.update({'id':id,'is_recommend':status}, function(){
                if(status){
                    obj.attr("class", 'fa fa-check-circle fa-lg text-navy');
                }else{
                    obj.attr("class", 'fa fa-times-circle fa-lg text-danger');
                }
            }, failure);
        });

        $("#btnMove").click(function(){
            var keys = new Array;
            $("input[name='ids']").each(function(){
                if(this.checked){
                    keys.push($(this).val());
                }
            });
            var keyValue = keys.join("|");
            if(keyValue){
                var target_class_id;
                dialog({
                    title: '请选择',
                    content: template('tplColumnTree'),
                    onshow: function(){
                        var beforeSetTargetClass = function(treeId, treeNode){
                            var checked = (treeNode && !treeNode.isParent);
                            if(!checked){
                                var treeObj = $.fn.zTree.getZTreeObj(treeId);
                                treeObj.expandNode(treeNode);
                                return false;
                            }
                        };
                        var setTargetClass = function(e, treeId, treeNode) {
                            target_class_id = treeNode.id;
                        };
                        var options = {
                            view: {dblClickExpand: false, selectedMulti: false},
                            data: {simpleData: {enable: true, rootPId: ''}},
                            callback: {'onClick': setTargetClass, 'beforeClick': beforeSetTargetClass}
                        };
                        $.fn.zTree.init($("#columnTree"), options, {!!$zNodes!!});
                    },
                    ok: function(){
                        if(target_class_id == undefined){
                            return false;
                        }
                        if(target_class_id == curClass.id){
                            return true;
                        }else{
                            newsinfoModel.move({'ids':keyValue, 'class_id':target_class_id}, function(ids){
                                $(".listTr").each(function(){
                                    if($.inArray($(this).data('id'), ids) > -1){
                                        $(this).remove();
                                    }
                                });
                                $("#checkAll").prop("checked", false);
                            }, failure);
                        }
                    },
                    cancel: true
                }).showModal();
            }else{
                dialog({
                    content:'<i class="fa fa-info-circle"></i>请先选择准备移动的记录！',
                    ok: true
                }).showModal();
            }
        });

        $("#btnRemove").click(function(){
            var keys = new Array;
            $("input[name='ids']").each(function(){
                if(this.checked){
                    keys.push($(this).val());
                }
            });
            var keyValue = keys.join("|");
            if(keyValue){
                dialog({
                    content: '<i class="fa fa-info-circle"></i> 即将删除所选择记录, 且该操作不能恢复! 是否继续 ?',
                    ok: function(){
                        newsinfoModel.delete({"ids":keyValue}, function(ids){
                            $(".listTr").each(function(){
                                if($.inArray($(this).data('id'), ids) > -1){
                                    $(this).remove();
                                }
                            });
                            $("#checkAll").prop("checked", false);
                        }, failure);
                    },
                    cancel: true
                }).showModal();
            }else{
                dialog({
                    content:'<i class="fa fa-info-circle"></i>请先选择准备删除的记录！',
                    ok: true
                }).showModal();
            }
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
                    file = this.pic.value;
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
            data.append('id', id);
            data.append('pic', this.pic.files[0]);
            newsinfoModel.uploadPic1(data, function(result){
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
                newsinfoModel.removePic1({"id":id}, function(){
                    $("#pictureViewer img").eq(0).attr("src", "/assets/images/blank.gif");
                    var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                        ta = tr.find(".js-pic1").eq(0);
                    ta.data("src", "");
                    ta.find("i").removeClass("fa-file-image-o").addClass("fa-cloud-upload");
                }, failure);
            }
        });

        $("#tblDataList").delegate('.js-pic2', 'click', function(){
            var data = {
                'id': $(this).parents('tr').eq(0).data("id"),
                'src': $(this).data("src")
            };
            $("#modalDialog").html(template("tplUploadPic2", data)).modal("show");
        });

        $("#modalDialog").delegate("#formUppic2", 'submit', function(){
            var id = this.id.value,
                    file = this.pic.value;
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
            data.append('id', id);
            data.append('pic', this.pic.files[0]);
            newsinfoModel.uploadPic2(data, function(result){
                $("#pictureViewer img").eq(0).attr("src", result);
            var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                    ta = tr.find(".js-pic2").eq(0);
            ta.data("src", result);
            ta.find("i").removeClass("fa-cloud-upload").addClass("fa-file-image-o");
            }, failure);
            return false;
        });

        $("#modalDialog").delegate("#btnDeletePic2", "click", function(){
            var id = $(this).data("id");
            if(confirm("确定要删除此图片吗？")){
                newsinfoModel.removePic2({"id":id}, function(){
                    $("#pictureViewer img").eq(0).attr("src", "/assets/images/blank.gif");
                    var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                        ta = tr.find(".js-pic2").eq(0);
                    ta.data("src", "");
                    ta.find("i").removeClass("fa-file-image-o").addClass("fa-cloud-upload");
                }, failure);
            }
        });

        $("#tblDataList").delegate('.js-file1', 'click', function(){
            var data = {
                'id': $(this).parents('tr').eq(0).data("id"),
                'src': $(this).data("src")
            };
            $("#modalDialog").html(template("tplUploadFile1", data)).modal("show");
        });

        $("#modalDialog").delegate("#formUpfile1", 'submit', function(){
            var id = this.id.value,
                file = this.file1.value;
            if (file == ""){
                alert("请选择上传文件！");
                return false;
            }
            var data = new FormData();
            data.append('id', id);
            data.append('file', this.file1.files[0]);
            newsinfoModel.uploadFile1(data, function(result){
                $("#txtFile1").html(result);
            var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                    ta = tr.find(".js-file1").eq(0);
            ta.data("src", result);
            ta.find("i").removeClass("fa-cloud-upload").addClass("fa-file");
            }, failure);
            return false;
        });

        $("#modalDialog").delegate("#btnDeleteFile1", "click", function(){
            var id = $(this).data("id");
            if(confirm("确定要删除此文件吗？")){
                newsinfoModel.removeFile1({"id":id}, function(){
                    $("#txtFile1").html("");
                    var tr = $("#tblDataList").find("tr[data-id='"+ id +"']").eq(0),
                        ta = tr.find(".js-file1").eq(0);
                    ta.data("src", "");
                    ta.find("i").removeClass("fa-file").addClass("fa-cloud-upload");
                }, failure);
            }
        });
    });
</script>
@endsection