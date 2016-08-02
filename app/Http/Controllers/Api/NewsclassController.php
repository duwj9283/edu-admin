<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsclass;
use App\Models\Newsinfo;
use App\Models\Newspopedom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsclassController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:news');
    }

    /**
     * 获取子栏目列表
     */
    public function getList(Request $request)
    {
        $id = strval($request->input('id'));
        if (empty($id)) {
            $column = [
                'id' => '',
                'sortnum' => 0,
                'order_by' => '',
                'sort_by' => 'DESC',
                'depth' => 2,
                'mode' => 2,
                'allow_add' => 1,
                'allow_edit' => 1,
                'allow_del' => 0,
                'has_subtitle' => 0,
                'has_tags' => 0,
                'has_intro' => 0,
                'has_content' => 1,
                'has_website' => 0,
                'has_editor' => 0,
                'has_author' => 0,
                'has_source' => 0,
                'has_pic1' => 1,
                'has_pic2' => 0,
                'has_pics' => 0,
                'has_file1' => 0,
                'has_hot' => 0,
                'has_new' => 0,
                'has_top' => 0,
                'has_recommend' => 0,
            ];
        } else {
            $column = Newsclass::find($id);
        }
        $data['column'] = $column;
        $rows = Newsclass::where('id', 'LIKE', $id . '____')->orderBy('sortnum', 'ASC')->get();
        foreach ($rows as $key => $val) {
            $val->pic1 = empty($val->pic1) ? '' : url($val->pic1);
        }
        $data['rows'] = $rows;
        return $this->response($data);
    }

    /**
     * 获取栏目信息
     */
    public function getInfo(Request $request)
    {
        $id = strval($request->input('id'));
        $row = Newsclass::find($id);
        if (empty($row)) {
            return $this->error('无效的栏目');
        }
        $row->pic1 = $row->pic1 ? url($row->pic1) : '';
        return $this->response($row);
    }

    /**
     * 新建栏目
     */
    public function postInsert(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->warning('Access denied.');
        }
        $parent_id = strval($request->input('parent_id'));

        $id = Newsclass::where('id', 'LIKE', $parent_id . '____')->max('id');
        $id = $parent_id . ($id ? intval(substr($id, -4)) + 1 : '1001');

        $row = new Newsclass;
        $row->id = $id;

        $sortnum = toLimitLng($request->input('sortnum'), 0, 9999);
        if (!$sortnum) {
            $sortnum = Newsclass::where('id', 'LIKE', $parent_id . '____')->max('sortnum') + 10;
        } else {
            $cnt = Newsclass::where('id', 'LIKE', $parent_id . '____')->where('sortnum', $sortnum)->count();
            if ($cnt > 0) {
                return $this->error('序号已被占用');
            }
        }
        $row->sortnum = $sortnum;
        $pre_order_by = Newsclass::where('id', $parent_id)->pluck('order_by');
        $row->order_by = $pre_order_by . padLeft($sortnum, 4);

        $row->name = safe($request->input('name'), 50);
        $row->url = safe($request->input('url'));
        $row->mode = toLimitLng($request->input('mode'), 1, 4);
        $row->depth = toLimitLng($request->input('depth'), 1, 5);
        $row->sort_by = safe($request->input('sort_by'), 10);

        $row->allow_add = toLimitLng($request->input('allow_add'), 0, 1);
        $row->allow_edit = toLimitLng($request->input('allow_edit'), 0, 1);
        $row->allow_del = toLimitLng($request->input('allow_del'), 0, 1);

        $row->has_subtitle = toLimitLng($request->input('has_subtitle'), 0, 1);
        $row->has_tags = toLimitLng($request->input('has_tags'), 0, 1);
        $row->has_intro = toLimitLng($request->input('has_intro'), 0, 1);
        $row->has_content = toLimitLng($request->input('has_content'), 0, 1);
        $row->has_website = toLimitLng($request->input('has_website'), 0, 1);
        $row->has_editor = toLimitLng($request->input('has_editor'), 0, 1);
        $row->has_author = toLimitLng($request->input('has_author'), 0, 1);
        $row->has_source = toLimitLng($request->input('has_source'), 0, 1);

        $row->has_pic1 = toLimitLng($request->input('has_pic1'), 0, 1);
        $row->has_pic2 = toLimitLng($request->input('has_pic2'), 0, 1);
        $row->has_pics = toLimitLng($request->input('has_pics'), 0, 1);
        $row->has_file1 = toLimitLng($request->input('has_file1'), 0, 1);

        $row->has_hot = toLimitLng($request->input('has_hot'), 0, 1);
        $row->has_new = toLimitLng($request->input('has_new'), 0, 1);
        $row->has_top = toLimitLng($request->input('has_top'), 0, 1);
        $row->has_recommend = toLimitLng($request->input('has_recommend'), 0, 1);

        if ($row->save()) {
            $rows = Newspopedom::where('class_id', $parent_id)->get();
            if (!empty($rows)) {
                $data = [];
                foreach ($rows as $v) {
                    $data[] = ['class_id' => $id, 'role_id' => $v->role_id, 'popedom' => $v->popedom];
                }
                Newspopedom::insert($data);
            }
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 增加栏目
     */
    public function postAddColumn(Request $request)
    {
        $parent_id = strval($request->input('parent_id'));
        if (empty($parent_id)) {
            return $this->error('无效的栏目');
        }

        $prow = Newsclass::find($parent_id);
        $id = Newsclass::where('id', 'LIKE', $parent_id . '____')->max('id');
        $id = $parent_id . ($id ? intval(substr($id, -4)) + 1 : '1001');

        $row = new Newsclass;
        $row->id = $id;

        $sortnum = toLimitLng($request->input('sortnum'), 0, 9999);
        if (!$sortnum) {
            $sortnum = Newsclass::where('id', 'LIKE', $parent_id . '____')->max('sortnum') + 10;
        } else {
            $cnt = Newsclass::where('id', 'LIKE', $parent_id . '____')->where('sortnum', $sortnum)->count();
            if ($cnt > 0) {
                return $this->error('序号已被占用');
            }
        }
        $row->sortnum = $sortnum;
        $row->order_by = $prow->order_by . padLeft($sortnum, 4);

        $row->name = safe($request->input('name'), 50);
        $row->url = safe($request->input('url'));
        $row->mode = toLimitLng($request->input('mode'), 1, 4);
        $row->depth = $prow->depth;
        $row->sort_by = $prow->sort_by;

        $row->allow_add = 1;
        $row->allow_edit = 1;
        $row->allow_del = 1;

        $row->has_subtitle = $prow->has_subtitle;
        $row->has_tags = $prow->has_tags;
        $row->has_intro = $prow->has_intro;
        $row->has_content = $prow->has_content;
        $row->has_website = $prow->has_website;
        $row->has_editor = $prow->has_editor;
        $row->has_author = $prow->has_author;
        $row->has_source = $prow->has_source;

        $row->has_pic1 = $prow->has_pic1;
        $row->has_pic2 = $prow->has_pic2;
        $row->has_pics = $prow->has_pics;
        $row->has_file1 = $prow->has_file1;

        $row->has_hot = $prow->has_hot;
        $row->has_new = $prow->has_new;
        $row->has_top = $prow->has_top;
        $row->has_recommend = $prow->has_recommend;

        if ($row->save()) {
            $rows = Newspopedom::where('class_id', $parent_id)->get();
            if (!empty($rows)) {
                $data = [];
                foreach ($rows as $v) {
                    $data[] = ['class_id' => $id, 'role_id' => $v->role_id, 'popedom' => $v->popedom];
                }
                Newspopedom::insert($data);
            }
            $row->id = $id;
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 修改栏目
     */
    public function postEditColumn(Request $request)
    {
        $id = intval($request->input('id'));
        $parent_id = substr($id, 0, -4);

        $row = Newsclass::find($id);
        if (empty($row)) {
            return $this->error('无效的栏目');
        }
        if ($request->exists('sortnum')) {
            $sortnum = toLimitLng($request->input('sortnum'), 1, 9999);
            if ($sortnum != $row->sortnum) {
                $cnt = NewsClass::where('id', 'LIKE', $parent_id . '____')->where('sortnum', $sortnum)->count();
                if ($cnt > 0) {
                    return $this->error('序号已被占用');
                }
                $row->sortnum = $sortnum;
            }
        }
        if ($request->exists('name')) {
            $row->name = safe($request->get('name'), 50);
        }
        if ($request->exists('mode')) {
            $row->mode = intval($request->get('mode'));
        }
        if ($row->save()) {
            $order_by = padLeft($row->sortnum, 4);
            NewsClass::where('id', 'LIKE', $id . '%')->update(
                ['order_by' => DB::raw('INSERT(`order_by`, ' . (strlen($id) - 3) . ', 4, \'' . $order_by . '\')')]
            );
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 编辑栏目
     */
    public function postUpdate(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->warning('Access denied.');
        }
        $id = strval($request->input('id'));
        $parent_id = substr($id, 0, -4);

        $row = Newsclass::find($id);
        if (empty($row)) {
            return $this->error('无效的栏目');
        }

        if ($request->exists('sortnum')) {
            $sortnum = toLimitLng($request->input('sortnum'), 1, 9999);
            if ($sortnum != $row->sortnum) {
                $cnt = NewsClass::where('id', 'LIKE', $parent_id . '____')->where('sortnum', $sortnum)->count();
                if ($cnt > 0) {
                    return $this->error('序号已被占用');
                }
                $row->sortnum = $sortnum;
            }
        }

        if ($request->exists('name')) {
            $row->name = safe($request->get('name'), 50);
        }
        if ($request->exists('url')) {
            $row->url = safe($request->get('url'));
        }
        if ($request->exists('mode')) {
            $row->mode = intval($request->get('mode'));
        }
        if ($request->exists('sort_by')) {
            $row->sort_by = safe($request->get('sort_by'), 10);
        }
        if ($request->exists('depth')) {
            $row->depth = intval($request->get('depth'));
        }
        if ($request->exists('mode')) {
            $row->mode = intval($request->get('mode'));
        }
        if ($request->exists('allow_add')) {
            $row->allow_add = intval($request->get('allow_add'));
        }
        if ($request->exists('allow_edit')) {
            $row->allow_edit = intval($request->get('allow_edit'));
        }
        if ($request->exists('allow_del')) {
            $row->allow_del = intval($request->get('allow_del'));
        }
        if ($request->exists('has_subtitle')) {
            $row->has_subtitle = intval($request->get('has_subtitle'));
        }
        if ($request->exists('has_tags')) {
            $row->has_tags = intval($request->get('has_tags'));
        }
        if ($request->exists('has_intro')) {
            $row->has_intro = intval($request->get('has_intro'));
        }
        if ($request->exists('has_content')) {
            $row->has_content = intval($request->get('has_content'));
        }
        if ($request->exists('has_website')) {
            $row->has_website = intval($request->get('has_website'));
        }
        if ($request->exists('has_editor')) {
            $row->has_editor = intval($request->get('has_editor'));
        }
        if ($request->exists('has_author')) {
            $row->has_author = intval($request->get('has_author'));
        }
        if ($request->exists('has_source')) {
            $row->has_source = intval($request->get('has_source'));
        }
        if ($request->exists('has_pic1')) {
            $row->has_pic1 = intval($request->get('has_pic1'));
        }
        if ($request->exists('has_pic2')) {
            $row->has_pic2 = intval($request->get('has_pic2'));
        }
        if ($request->exists('has_pics')) {
            $row->has_pics = intval($request->get('has_pics'));
        }
        if ($request->exists('has_file1')) {
            $row->has_file1 = intval($request->get('has_file1'));
        }
        if ($request->exists('has_hot')) {
            $row->has_hot = intval($request->get('has_hot'));
        }
        if ($request->exists('has_new')) {
            $row->has_new = intval($request->get('has_new'));
        }
        if ($request->exists('has_top')) {
            $row->has_top = intval($request->get('has_top'));
        }
        if ($request->exists('has_recommend')) {
            $row->has_recommend = intval($request->get('has_recommend'));
        }
        if ($row->save()) {
            $order_by = padLeft($row->sortnum, 4);
            NewsClass::where('id', 'LIKE', $id . '%')->update([
                'depth' => $row->depth,
                'order_by' => DB::raw('INSERT(`order_by`, ' . (strlen($id) - 3) . ', 4, \'' . $order_by . '\')'),
            ]);
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 上传栏目图片
     */
    public function postUploadPic1(Request $request)
    {
        $id = intval($request->input('id'));
        $file = $_FILES['pic1'];

        $errorCode = $file['error'];
        if ($errorCode !== UPLOAD_ERR_OK) {
            return $this->error($errorCode);
        }

        $type = exif_imagetype($file['tmp_name']);
        if ($type === false) {
            return $this->error('无效的图片文件');
        }
        if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
            return $this->error('无效的图片格式');
        }

        $target_path = 'upload/' . date('Y/m/d/');
        $targetPath = public_path() . DIRECTORY_SEPARATOR . $target_path;
        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, 1)) {
                return $this->error('无法建立上传目录');
            }
        }

        $new_filename = uniqid(mt_rand()) . image_type_to_extension($type);
        $result = move_uploaded_file($file['tmp_name'], $targetPath . $new_filename);
        if ($result === false) {
            return $tis->error('保存文件失败');
        }
        $image = $target_path . $new_filename;

        $row = Newsclass::find($id);
        file_exists($row->pic1) and unlink($row->pic1);
        $row->pic1 = $image;
        if ($row->save()) {
            return $this->response(url($image));
        } else {
            return $this->error('Operation Failed.');
        }
    }

    /**
     * 删除栏目图片
     */
    public function postRemovePic1(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsclass::find($id);
        file_exists($row->pic1) and unlink($row->pic1);
        $row->pic1 = '';
        $row->save();
        return $this->response('ok');
    }

    /**
     * 增加栏目权限
     */
    public function postAddPopedom(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $class_id = strval($request->input('class_id'));
        $popedom = intval($request->input('popedom'));

        if ($role_id == 1) {
            return $this->response('ok');
        }

        $row = Newspopedom::where('class_id', $class_id)->where('role_id', $role_id)->first();
        if (empty($row)) {
            $row = new Newspopedom;
            $row->class_id = $class_id;
            $row->role_id = $role_id;
            $row->popedom = $popedom;
            $row->save();
        } else {
            $query = Newspopedom::where(['class_id' => $class_id, 'role_id' => $role_id]);
            $query->update(['popedom' => $row->popedom | $popedom]);
        }
        return $this->response('ok');
    }

    /**
     * 删除栏目权限
     */
    public function postRemovePopedom(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $class_id = strval($request->input('class_id'));
        $popedom = intval($request->input('popedom'));

        if ($role_id == 1) {
            return $this->response('ok');
        }

        $query = Newspopedom::where(['class_id' => $class_id, 'role_id' => $role_id]);
        $row = $query->first();
        if (empty($row)) {
            return $this->error('Operation Failed.');
        }
        $val = $row->popedom ^ $popedom;
        if ($val == 0) {
            $query->delete();
        } else {
            $query->update(['popedom' => $val]);
        }
        return $this->response('ok');
    }

    /**
     * 删除栏目
     */
    public function postDelete(Request $request)
    {
        $id = strval($request->input('id'));
        $row = Newsclass::find($id);
        if (empty($row)) {
            return $this->error('无效的栏目');
        }
        if ($row->allow_del == 0) {
            return $this->error('此分类不允许删除');
        }
        if (Newsclass::where('id', 'LIKE', $id . '_%')->count()) {
            return $this->error('请先删除其下级分类');
        }
        if (Newsinfo::where('class_id', $id)->count()) {
            return $this->error('请先删除此分类下的所有信息');
        }

        if ($row->delete()) {
            Newspopedom::where('class_id', $id)->delete();
            file_exists($row->pic1) && unlink($row->pic1);
            return $this->response('ok');
        }
        return $this->error('Operation Failed.');
    }
}
