<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siteconfig;
use Illuminate\Http\Request;

class SiteconfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:system');
    }

    public function getMetaSet()
    {
        $meta = [];
        $rows = Siteconfig::where('option_name', 'LIKE', 'meta_%')->get();
        foreach ($rows as $row) {
            $meta[$row->option_name] = $row->option_value;
        }
        $data['meta'] = $meta;

        $logo = Siteconfig::where('option_name', 'site_logo')->pluck('option_value');
        if (empty($logo)) {
            $frontend = config('services.frontend');
            $logo = $frontend['upload_url'] . '/' . 'logo.png';
        }
        $data['logo'] = $logo;

        return view('admin/siteconfig/meta-set', $data);
    }

    public function postSetMeta(Request $request)
    {
        $rows = Siteconfig::where('option_name', 'LIKE', 'meta_%')->get();
        foreach ($rows as $row) {
            if ($request->exists($row->option_name)) {
                $row->option_value = $request->input($row->option_name);
                $row->save();
            }
        }
        return $this->response('ok');
    }

    public function postUploadLogo(Request $request)
    {
        $file = $_FILES['file'];
        $fileTypes = ['jpg', 'jpeg', 'gif', 'png'];
        $file_ext = strtolower(pathinfo($file['name'])['extension']);
        if (!in_array($file_ext, $fileTypes)) {
            $result = ['status' => 0, 'msg' => '无效的图片格式'];
            return $this->response($result);
        }

        $frontend = config('services.frontend');
        $target_path = 'logo/';
        $targetPath = $frontend['upload_path'] . '/' . $target_path;
        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, 1)) {
                $result = ['status' => 0, 'msg' => '无法建立上传目录'];
                return $this->response($result);
            }
        }
        $new_filename = uniqid(mt_rand()) . '.' . $file_ext;
        $result = move_uploaded_file($file['tmp_name'], $targetPath . $new_filename);
        if ($result === false) {
            $result = ['status' => 0, 'msg' => '保存文件失败'];
            return $this->response($result);
        }

        $new_file = $frontend['upload_url'] . '/' . $target_path . $new_filename;
        $row = Siteconfig::where('option_name', 'site_logo')->first();
        if (!empty($row)) {
            $row->option_value = $new_file;
            $row->save();
        }

        $result = ['status' => 1, 'url' => $new_file];
        return $this->response($result);
    }
}
