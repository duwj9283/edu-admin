<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\QiniuUploadFile;
use App\Models\Newsclass;
use App\Models\Newsinfo;
use App\Models\Newsinfopic;
use App\Models\Newspopedom;
use App\Models\Qiniutask;
use App\Models\User;
use Illuminate\Http\Request;

class NewsinfoController extends Controller
{

    public function __construct()
    {
        $this->middleware('grant:news');
    }

    /**
     * 权限检查
     */
    private function checkPopedom($class_id = '')
    {
        $result = ['create' => false, 'update' => false, 'confirm' => false, 'delete' => false];
        $token = session('token');
        $popedom = Newspopedom::getPopedom($class_id, $token['user_id']);
        if ($popedom) {
            $result['create'] = ($popedom & 1) ? true : false;
            $result['update'] = ($popedom & 2) ? true : false;
            $result['confirm'] = ($popedom & 4) ? true : false;
            $result['delete'] = ($popedom & 8) ? true : false;
        }
        $user_id = $token['user_id'];
        $user = User::find($user_id);
        if ($user->hasRole('Administrators')) {
            $result = ['create' => true, 'update' => true, 'confirm' => true, 'delete' => true];
        }
        return $result;
    }

    /**
     * 获取资讯分页列表
     */
    public function getPageList(Request $request)
    {
        $class_id = strval($request->input('class_id'));
        $column = Newsclass::find($class_id);
        $sort_by = empty($column) ? 'DESC' : $column->sort_by;

        $keyword = strval($request->input('keyword'));
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 1);
        $offset = ($page - 1) * $limit;

        $query = Newsinfo::where('class_id', 'LIKE', $class_id . '%')->where('title', 'LIKE', '%' . $keyword . '%');
        $total_rows = $query->count();
        $rows = $query->orderBy('sortnum', $sort_by)->skip($offset)->take($limit)->get();
        foreach ($rows as $row) {
            $row->pic1 = empty($row->pic1) ? '' : asset($row->pic1);
            $row->pic2 = empty($row->pic2) ? '' : asset($row->pic2);
            $row->file1 = empty($row->file1) ? '' : asset($row->file1);
        }
        $data['rows'] = $rows;

        $data['total_rows'] = $total_rows;
        $data['page_count'] = ceil($total_rows / $limit);
        $data['page'] = $page;

        return $this->response($data);
    }

    /**
     * 获取一条资讯信息
     */
    public function getInfo(Request $request)
    {
        $id = intval($request->input('id'));
        $info = Newsinfo::find($id);
        if (empty($info)) {
            return $this->error('无效的信息');
        }

        $column = Newsclass::find($info->class_id);
        $data['column'] = $column;
        $data['info'] = $info;
        return $this->response($data);
    }

    /**
     * 增加资讯
     */
    public function postInsert(Request $request)
    {
        $token = session('token');
        $class_id = strval($request->input('class_id'));
        $popedom = $this->checkPopedom($class_id);
        if ($popedom['create'] == false) {
            return $this->error('无此操作权限');
        }

        $row = new Newsinfo;
        $row->class_id = $class_id;
        $sortnum = intval($request->input('sortnum'));
        if (!$sortnum) {
            $sortnum = Newsinfo::max('sortnum') + 10;
        }
        $row->sortnum = $sortnum;
        $row->title = safe($request->input('title'));
        $row->subtitle = safe($request->input('subtitle'));
        $row->title_color = safe($request->input('title_color'), 10);
        $row->title_bold = safe($request->input('title_bold'), 10);
        $row->first_letter = safe($request->input('first_letter'), 1);

        $row->website = safe($request->input('website'));
        $row->editor = safe($request->input('editor'), 30);
        $row->author = safe($request->input('author'), 30);
        $row->source = safe($request->input('source'), 50);
        $row->publish_at = safe($request->input('publish_at'), 30);
        $row->tags = safe($request->input('tags'));
        $row->intro = safe2($request->input('intro'));
        $row->content = safe2($request->input('content'));

        $row->views = intval($request->input('views'));
        $row->is_top = intval($request->input('is_top'));
        $row->is_hot = intval($request->input('is_hot'));
        $row->is_new = intval($request->input('is_new'));
        $row->is_recommend = intval($request->input('is_recommend'));
        $row->is_locked = intval($request->input('is_locked'));
        $row->status = intval($request->input('status'));

        $row->created_user_id = $token['user_id'];
        $row->updated_user_id = $token['user_id'];

        if ($row->save()) {
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 修改资讯
     */
    public function postUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $info = Newsinfo::find($id);
        if (empty($info)) {
            return $this->error('无效的信息');
        }

        $token = session('token');
        $popedom = $this->checkPopedom($info->class_id);

        if ($popedom['update']) {
            if ($request->exists('sortnum')) {
                $info->sortnum = intval($request->input('sortnum'));
            }
            if ($request->exists('title')) {
                $info->title = safe($request->input('title'));
            }
            if ($request->exists('subtitle')) {
                $info->subtitle = safe($request->input('subtitle'));
            }
            if ($request->exists('title_color')) {
                $info->title_color = safe($request->input('title_color'), 10);
            }
            if ($request->exists('title_bold')) {
                $info->title_bold = safe($request->input('title_bold'), 10);
            }
            if ($request->exists('first_letter')) {
                $info->first_letter = safe($request->input('first_letter'), 1);
            }
            if ($request->exists('website')) {
                $info->website = safe($request->input('website'));
            }
            if ($request->exists('author')) {
                $info->author = safe($request->input('author'), 30);
            }
            if ($request->exists('editor')) {
                $info->editor = safe($request->input('editor'), 30);
            }
            if ($request->exists('source')) {
                $info->source = safe($request->input('source'), 50);
            }
            if ($request->exists('tags')) {
                $info->tags = safe($request->input('tags'));
            }
            if ($request->exists('intro')) {
                $info->intro = safe2($request->input('intro'));
            }
            if ($request->exists('content')) {
                $info->content = safe2($request->input('content'));
            }
            if ($request->exists('is_top')) {
                $info->is_top = intval($request->input('is_top'));
            }
            if ($request->exists('is_new')) {
                $info->is_new = intval($request->input('is_new'));
            }
            if ($request->exists('is_hot')) {
                $info->is_hot = intval($request->input('is_hot'));
            }
            if ($request->exists('is_recommend')) {
                $info->is_recommend = intval($request->input('is_recommend'));
            }
            if ($request->exists('is_locked')) {
                $info->is_locked = intval($request->input('is_locked'));
            }
            if ($request->exists('views')) {
                $info->views = intval($request->input('views'));
            }
            if ($request->exists('publish_at')) {
                $info->publish_at = safe($request->input('publish_at'), 30);
            }
            $info->updated_user_id = $token['user_id'];
        }

        if ($popedom['confirm']) {
            if ($request->exists('status')) {
                $info->status = intval($request->input('status'));
            }
        }

        if ($info->save()) {
            return $this->response($info);
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 上传资讯小图
     */
    public function postUploadPic1(Request $request)
    {
        $id = intval($request->input('id'));
        $file = $_FILES['pic'];

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

        $row = Newsinfo::find($id);
        file_exists($row->pic1) and unlink($row->pic1);
        $row->pic1 = $image;
        if ($row->save()) {
            // $this->toQiniutask($row, 'pic1');
            return $this->response(url($image));
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 删除资讯小图
     */
    public function postRemovePic1(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfo::find($id);
        file_exists($row->pic1) and unlink($row->pic1);
        $row->pic1 = '';
        $row->save();
        return $this->response('ok');
    }

    /**
     * 上传资讯大图
     */
    public function postUploadPic2(Request $request)
    {
        $id = intval($request->input('id'));
        $file = $_FILES['pic'];

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

        $row = Newsinfo::find($id);
        file_exists($row->pic2) and unlink($row->pic2);
        $row->pic2 = $image;
        if ($row->save()) {
            // $this->toQiniutask($row, 'pic2');
            return $this->response(url($image));
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 删除资讯大图
     */
    public function postRemovePic2(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfo::find($id);
        file_exists($row->pic2) and unlink($row->pic2);
        $row->pic2 = '';
        $row->save();
        return $this->response('ok');
    }

    public function getPicsInfo(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfopic::find($id);
        if (empty($row)) {
            return $this->error('无效的信息');
        }
        return $this->response($row);
    }

    public function postPicsInsert(Request $request)
    {
        $info_id = intval($request->input('info_id'));
        $title = safe($request->input('title'));
        $content = safe2($request->input('content'));
        $file = $_FILES['pic1'];

        $info = Newsinfo::find($info_id);
        if (empty($info)) {
            return $this->error('无效的信息');
        }

        $class_id = $info->class_id;
        $popedom = $this->checkPopedom($class_id);
        if ($popedom['update'] === false) {
            return $this->error('无此操作权限');
        }

        $errorCode = $file['error'];
        if ($errorCode !== UPLOAD_ERR_OK) {
            return $this->error($errorCode);
        }

        $file_ext = strtolower(getFileExt($file['name']));
        if (!in_array($file_ext, config('extensions.allowImage'))) {
            return $this->error('无效的文件格式');
        }
        $target_path = 'upload/' . date('Y/m/d/');
        $targetPath = public_path() . DIRECTORY_SEPARATOR . $target_path;
        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, 1)) {
                return $this->error('无法建立上传目录');
            }
        }
        $new_filename = uniqid(mt_rand()) . '.' . $file_ext;
        $result = move_uploaded_file($file['tmp_name'], $targetPath . $new_filename);
        if ($result === false) {
            return $tis->error('保存文件失败');
        }
        $file_url = $target_path . $new_filename;

        $row = new Newsinfopic;
        $row->info_id = $info_id;
        $row->title = $title;
        $row->content = $content;
        $row->pic1 = $file_url;
        if ($row->save()) {
            $row->pic1 = asset($file_url);
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    public function postPicsUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfopic::find($id);
        if (empty($row)) {
            return $this->error('无效的信息！');
        }

        $info = Newsinfo::find($row->info_id);
        if (empty($info)) {
            return $this->error('无效的信息');
        }

        $class_id = $info->class_id;
        $popedom = $this->checkPopedom($class_id);
        if ($popedom['update'] === false) {
            return $this->error('无此操作权限');
        }

        if ($request->exists('title')) {
            $row->title = safe($request->input('title'));
        }
        if ($request->exists('content')) {
            $row->content = safe2($request->input('content'));
        }
        if ($row->save()) {
            return $this->response($row);
        }
        return $this->error('Operation Failed.');
    }

    public function postPicsDelete(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfopic::find($id);
        if (empty($row)) {
            return $this->error('无效的信息！');
        }

        $info = Newsinfo::find($row->info_id);
        if (empty($info)) {
            return $this->error('无效的信息');
        }

        $class_id = $info->class_id;
        $popedom = $this->checkPopedom($class_id);
        if ($popedom['update'] === false) {
            return $this->error('无此操作权限');
        }

        file_exists($row->pic1) and unlink($row->pic1);
        $row->delete();
        return $this->response($id);
    }

    /**
     * 上传附件
     */
    public function postUploadFile1(Request $request)
    {
        $id = intval($request->input('id'));
        $file = $_FILES['file'];

        $errorCode = $file['error'];
        if ($errorCode !== UPLOAD_ERR_OK) {
            return $this->error($errorCode);
        }
        $file_ext = strtolower(getFileExt($file['name']));
        if (!in_array($file_ext, config('extensions.allowExt'))) {
            return $this->error('无效的文件格式');
        }

        $target_path = 'upload/' . date('Y/m/d/');
        $targetPath = public_path() . DIRECTORY_SEPARATOR . $target_path;
        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, 1)) {
                return $this->error('无法建立上传目录');
            }
        }
        $new_filename = uniqid(mt_rand()) . '.' . $file_ext;
        $result = move_uploaded_file($file['tmp_name'], $targetPath . $new_filename);
        if ($result === false) {
            return $this->error('保存文件失败');
        }
        $file_url = $target_path . $new_filename;

        $row = Newsinfo::find($id);
        file_exists($row->file1) and unlink($row->file1);
        $row->file1 = $file_url;
        if ($row->save()) {
            return $this->response(asset($file_url));
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 删除附件
     */
    public function postRemoveFile1(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfo::find($id);
        file_exists($row->file1) and unlink($row->file1);
        $row->file1 = '';
        $row->save();
        return $this->response('ok');
    }

    /**
     * 信息移动
     */
    public function postMove(Request $request)
    {
        $target_class_id = strval($request->input('class_id'));
        $popedom = $this->checkPopedom($target_class_id);
        if ($popedom['create'] == false) {
            return $this->error('目录栏目没有写入权限');
        }

        $ids = strval($request->input('ids'));
        if (empty($ids)) {
            return $this->error('无效的操作');
        }
        $ids = explode('|', $ids);
        $rows = Newsinfo::whereIn('id', $ids)->get();
        $result = [];
        foreach ($rows as $row) {
            if ($row->is_locked) {
                continue;
            }
            if ($row->class_id == $target_class_id) {
                continue;
            }
            $popedom = $this->checkPopedom($row->class_id);
            if ($popedom['delete'] == false) {
                return $this->error('无此操作权限');
            }
            $row->class_id = $target_class_id;
            if ($row->save()) {
                $result[] = $row->id;
            }
        }
        return $this->response($result);
    }

    /**
     * 删除资讯
     */
    public function postDelete(Request $request)
    {
        $ids = strval($request->input('ids'));
        if (empty($ids)) {
            return $this->error('无效的操作');
        }
        $ids = explode('|', $ids);
        $rows = Newsinfo::whereIn('id', $ids)->get();
        $result = [];
        foreach ($rows as $row) {
            $id = $row->id;
            if ($row->is_locked) {
                continue;
            }
            file_exists($row->pic1) && unlink($row->pic1);
            file_exists($row->pic2) && unlink($row->pic2);
            file_exists($row->file1) && unlink($row->file1);
            if ($row->delete()) {
                Newsinfopic::deleteByInfoId($id);
                $result[] = $id;
            }
        }
        return $this->response($result);
    }

    /**
     * 加入七牛上传任务队列
     */
    private function toQiniutask(Newsinfo $row, $field)
    {
        $task = new Qiniutask;
        $task->master_id = $row->id;
        $task->table_name = 'news_info';
        $task->field_name = $field;
        $task->file_path = realpath($row->$field);
        $task->target_path = '';
        $task->need_upload = 1;
        $task->need_convert = 0;
        $task->status = 0; //-1:任务处理失败,0:待处理任务,1:任务处理中,2:任务处理结束,3:任务完成
        $task->save();

        $this->dispatch(new QiniuUploadFile($task));
        return true;
    }

}
