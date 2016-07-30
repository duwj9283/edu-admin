<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * 修改个人资料
     */
    public function postSetProfile()
    {
        $id = session('token')['user_id'];
        $user = User::find($id);
        if (empty($user)) {
            return $this->error('无效的用户');
        }

        if (Request::exists('password')) {
            $password = strval(Request::input('password'));
            if (!empty($password)) {
                $user->password = bcrypt($password);
            }
        }

        if (Request::exists('email')) {
            $email = strval(Request::input('email'));
            if (empty($email) or $user->email == $email) {
                $user->email = $email;
            } else {
                if (User::where('email', $email)->count()) {
                    return $this->error('邮箱已经存在');
                }
                $user->email = $email;
            }
        }

        if (Request::exists('mobile')) {
            $mobile = strval(Request::input('mobile'));
            if (empty($mobile) or $user->mobile == $mobile) {
                $user->mobile = $mobile;
            } else {
                if (User::where('mobile', $mobile)->count()) {
                    return $this->error('手机号已经被使用');
                }
                $user->mobile = $mobile;
            }
        }

        if (Request::exists('realname')) {
            $realname = safe(Request::input('realname'), 50);
            $user->realname = $realname;
            $token = session('token');
            $token['realname'] = $realname;
            Request::session()->put('token', $token);
        }
        $user->save();
        return $this->response(Request::input());
    }

    /**
     * 修改密码
     */
    public function postChangepwd()
    {
        $id = session('token')['user_id'];
        $user = User::find($id);
        if (empty($user)) {
            return $this->error('无效的用户');
        }

        $curpass = strval(Request::input('curpass'));
        $newpass = strval(Request::input('newpass'));

        if (!Hash::check($curpass, $user->password)) {
            return $this->error('原密码输入错误');
        }
        $user->password = Hash::make($newpass);
        $user->save();
        return $this->response();
    }

    /**
     * 上传用户头像
     */
    public function postUploadAvatar($id = null)
    {
        $id = is_null($id) ? session('token')['user_id'] : intval($id);
        $avatar_data = file_get_contents('php://input');
        $data = explode('--------------------', $avatar_data);
        $targetPath = uploadPath() . '/avatar';

        if (!is_dir($targetPath)) {
            if (!mkdir($targetPath, 0777, 1)) {
                $result = ['status' => '0', 'info' => '无法建立上传目录'];
                return $this->response($result);
            }
        }

        $targetFile = $targetPath . '/' . $id . '.jpg';
        if (!file_put_contents($targetFile, $data[0])) {
            return $this->response(['status' => '0', 'info' => '保存文件失败']);
        }

        return $this->response(['status' => '1', 'url' => getPhoto($id)]);
    }

}
