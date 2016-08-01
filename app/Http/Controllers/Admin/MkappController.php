<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mkapp;
use App\Models\Mkappver;
use Illuminate\Http\Request;

class MkappController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:system');
    }

    /**
     * 进入应用列表页面
     */
    public function getIndex()
    {
        return view('admin/mkapp/index');
    }

    /**
     * 获取所有应用
     */
    public function getAllRows()
    {
        $rows = Mkapp::where('status', 1)->orderBy('id', 'ASC')->get();
        foreach ($rows as $row) {
            $row->pic1 = uri($row->pic1);
        }
        $data['rows'] = $rows;
        return $this->response($data);
    }

    /**
     * 获取应用信息
     */
    public function getAppInfo(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkapp::find($id);
        if (empty($row)) {
            return $this->error('无效的App');
        }
        $row->pic1 = uri($row->pic1);
        $data['app'] = $row;
        return $this->response($data);
    }

    /**
     * 新增应用
     */
    public function postInsert(Request $request)
    {
        $name = safe($request->input('name'), 50);
        $version = safe($request->input('version'), 30);
        $intro = safe2($request->input('intro'));
        $description = safe2($request->input('description'));

        if (empty($name) || empty($version)) {
            return $this->error('参数填写不完整');
        }

        $row = new Mkapp;
        $row->name = $name;
        $row->intro = $intro;
        $row->status = 1;
        if ($row->save()) {
            $ver = new Mkappver;
            $ver->app_id = $row->id;
            $ver->version = $version;
            $ver->description = $description;
            $ver->is_top = 1;
            $ver->save();
        } else {
            return $this->error('添加新应用失败');
        }
        return $this->response('ok');
    }

    /**
     * 编辑应用
     */
    public function postUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkapp::find($id);
        if (empty($row)) {
            return $this->error('无效的App');
        }

        if ($request->exists('name')) {
            $row->name = safe($request->input('name'), 50);
        }
        if ($request->exists('intro')) {
            $row->intro = safe2($request->input('intro'));
        }
        $row->save();
        return $this->response('ok');
    }

    /**
     * 获取一个应用的所有版本
     */
    public function getAllReleases(Request $request)
    {
        $app_id = intval($request->input('id'));
        $row = Mkapp::find($app_id);
        if (empty($row)) {
            return $this->error('无效的App');
        }

        $rows = Mkappver::where('app_id', $app_id)->orderBy('is_top', 'DESC')->orderBy('id', 'DESC')->get();
        foreach ($rows as $row) {
            $row->file1 = empty($row->file1) ? '' : asset($row->file1);
        }
        return $this->response(['rows' => $rows]);
    }

    /**
     * 获取一个应用版本的信息
     */
    public function getReleaseInfo(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkappver::find($id);
        if (empty($row)) {
            return $this->error('无效的版本号');
        }
        return $this->response($row);
    }

    /**
     * 新增一个应用的版本
     */
    public function postAddRelease(Request $request)
    {
        $app_id = intval($request->input('app_id'));
        $row = Mkapp::find($app_id);
        if (empty($row)) {
            return $this->error('无效的App');
        }

        $version = safe($request->input('version'), 30);
        $file1 = safe($request->input('file1'));
        $description = safe2($request->input('description'));
        $is_top = toLimitLng($request->input('is_top'), 0, 1);

        if ($is_top) {
            Mkappver::where('app_id', $app_id)->update(['is_top' => 0]);
        }

        $row = new Mkappver;
        $row->app_id = $app_id;
        $row->version = $version;
        $row->description = $description;
        $row->file1 = $file1;
        $row->is_top = $is_top;
        $row->save();
        return $this->response('ok');
    }

    /**
     * 编辑应用的版本信息
     */
    public function postEditRelease(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkappver::find($id);
        if (empty($row)) {
            return $this->error('无效的版本号');
        }

        if ($request->exists('version')) {
            $row->version = safe($request->input('version'), 30);
        }
        if ($request->exists('file1')) {
            $row->file1 = safe($request->input('file1'));
        }
        if ($request->exists('description')) {
            $row->description = safe2($request->input('description'));
        }
        $is_top = toLimitLng($request->input('is_top'), 0, 1);
        if ($is_top > 0) {
            Mkappver::where('app_id', $row->app_id)->where('id', '<>', $id)->update(['is_top' => 0]);
        }
        $row->is_top = $is_top;
        $row->save();
        return $this->response('ok');
    }

    /**
     * 删除某应用的一个版本
     */
    public function postRemoveRelease(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkappver::find($id);
        if (empty($row)) {
            return $this->error('无效的版本号');
        }

        $row->delete();
        return $this->response('ok');
    }

    /**
     * 上传应用图片
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
            return $this->error('保存文件失败');
        }

        $image = $target_path . $new_filename;
        $row = Mkapp::find($id);
        file_exists($row->pic1) and unlink($row->pic1);
        $row->pic1 = $image;
        $row->save();
        return $this->response(asset($image));
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

        $row = Mkappver::find($id);
        $row->file1 = $file_url;
        if ($row->save()) {
            return $this->response(asset($file_url));
        }
        return $this->error('Operation Failed.');
    }

    /**
     * 逻辑删除一个应用
     */
    public function postDelete(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Mkapp::find($id);
        if (empty($row)) {
            return $this->error('无效的App');
        }
        $row->status = 0;
        $row->save();
        return $this->response('ok');
    }

}
