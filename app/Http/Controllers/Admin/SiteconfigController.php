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

    /**
     * 进入站点配置页面
     */
    public function getMetaSet()
    {
        $meta = [];
        $rows = Siteconfig::where('option_name', 'LIKE', 'meta_%')->get();
        foreach ($rows as $row) {
            $meta[$row->option_name] = $row->option_value;
        }
        $data['meta'] = $meta;

        // 前台项目路径
        $frontend = config('services.frontend');
        $data['frontend'] = $frontend;

        // logo图片
        $logo = Siteconfig::where('option_name', 'site_logo')->pluck('option_value');
        if (empty($logo)) {
            $logo = 'upload/logo.png';
        }
        $data['logo'] = $frontend['url'] . $logo;
        return view('admin/siteconfig/meta-set', $data);
    }

    /**
     * 获取频道 Banner 图
     */
    public function getBannerList(Request $request)
    {
        $option_name = strval($request->input('option_name'));
        if (!in_array($option_name, ['site_banner1', 'site_banner2', 'site_banner3', 'site_banner4', 'site_banner5', 'site_banner6', 'site_banner7'])) {
            return $this->error('无效的频道');
        }

        $ban = Siteconfig::where('option_name', $option_name)->first();
        if (empty($ban)) {
            $ban = new Siteconfig;
            $ban->option_title = $option_name;
            $ban->option_name = $option_name;
            $ban->option_value = '';
            $ban->save();
        }
        $data['list'] = arrayTrim(explode('|', $ban->option_value));
        return $this->response($data);
    }

    /**
     * 修改 meta 设置
     */
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

    /**
     * 上传 logo 图片
     */
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
        $target_path = 'upload/logo/';
        $targetPath = $frontend['path'] . $target_path;
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

        $new_file = $target_path . $new_filename;
        $row = Siteconfig::where('option_name', 'site_logo')->first();
        if (!empty($row)) {
            $row->option_value = $new_file;
            $row->save();
        }

        $result = ['status' => 1, 'url' => $frontend['url'] . $new_file];
        return $this->response($result);
    }

    /**
     * 上传一张Banner图
     */
    public function postUploadBanner(Request $request)
    {
        $option_name = strval($request->input('option_name'));
        $file = $_FILES['file'];
        $fileTypes = ['jpg', 'jpeg', 'gif', 'png'];
        $file_ext = strtolower(pathinfo($file['name'])['extension']);
        if (!in_array($file_ext, $fileTypes)) {
            $result = ['status' => 0, 'msg' => '无效的图片格式'];
            return $this->response($result);
        }

        $frontend = config('services.frontend');
        $target_path = 'upload/banner/';
        $targetPath = $frontend['path'] . $target_path;
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

        $new_file = $target_path . $new_filename;
        $row = Siteconfig::where('option_name', $option_name)->first();
        $data = arrayTrim(explode('|', $row->option_value));
        array_push($data, $new_file);
        $row->option_value = implode('|', $data);
        $row->save();

        $result = ['status' => 1, 'url' => $new_file];
        return $this->response($result);
    }

    /**
     * 删除一个Banner图
     */
    public function postRemoveBanner(Request $request)
    {
        $option_name = strval($request->input('option_name'));
        $src = strval($request->input('src'));
        $row = Siteconfig::where('option_name', $option_name)->first();
        $banners = arrayTrim(explode('|', $row->option_value));
        foreach ($banners as $key => $val) {
            if ($val == $src) {
                unset($banners[$key]);
            }
        }
        $banner = implode('|', $banners);
        $row->option_value = $banner;
        $row->save();
        return $this->response('ok');
    }
}
