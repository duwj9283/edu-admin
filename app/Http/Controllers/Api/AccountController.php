<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendForgotEmail;
use App\Jobs\SendSigninEmail;
use App\Libraries\WeimiSender;
use App\Models\Profile;
use App\Models\User;
use App\Models\WebUser;
use App\Models\WebUserInfo;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    /**
     * 用户登录
     */
    public function postLogin(Request $request)
    {
        $username = strval($request->input('username'));
        $password = strval($request->input('password'));

        if (empty($username) or empty($password)) {
            return $this->error('用户名和密码不能为空');
        }

        if ($request->exists('captcha')) {
            $captcha = strval($request->input('captcha'));
            if (captcha_check($captcha) === false) {
                return $this->error('验证码错误,请重新输入');
            }
        }

        $user = WebUser::where('username', $username)->first();
        if (empty($user)) {
            return $this->error('无效的用户');
        }

        if (sha1(md5($password)."sdkjf*^#HRGF*")!=$user->password) {
            return $this->error('密码错误');
        }
        if ($user->disable == 1) {
            return $this->error('此用户已被管理员禁用');
        }
        $hide = false;
        $userInfo= WebUserInfo::where('uid',$user->uid)->first();

        $data = [
            'user_id' => $user->uid,
            'username' => $user->username,
            'realname' => $userInfo->realname,
            'isHidden' => $hide,
        ];
        Session::put('token', $data);
        return $this->response('ok');
    }

    /**
     * 根据邮箱/手机号发送注册验证码
     */
    public function postRegStep1(Request $request)
    {
        $str = strval($request->input('name'));

        if (isEmail($str)) {
            $n = User::where('email', $str)->count();
            if ($n > 0) {
                return $this->error('此邮箱已经注册');
            }
            $this->dispatch(new SendSigninEmail($str));
            return $this->response('ok');
        }

        if (isMobile($str)) {
            $n = User::where('mobile', $str)->count();
            if ($n > 0) {
                return $this->error('此手机号已注册');
            }
            $this->sendSignupSms($str);
            return $this->response('ok');
        }

        return $this->error('无效的注册邮箱/手机号');
    }

    /**
     * 发送验证码短信
     */
    private function sendSignupSms($mobile)
    {
        $uCode = UserCode::where('mobile', $mobile)->orderBy('id', 'DESC')->first();
        if ($uCode) {
            if (time() - (strtotime($uCode->created_at)) < 3 * 60) {
                return $this->error(['同一个手机号码每条验证码间隔时间至少是3分钟']);
            }
        }

        $n = UserCode::where('mobile', $mobile)->where(DB::raw('to_days(created_at)'), DB::raw('to_days(now())'))->count();
        if ($n > 2) {
            return $this->error(['每天对同一个手机号码最多发送3条验证码']);
        }

        $code = randStr(6, 1);
        $weimisd = new WeimiSender;
        $result = $weimisd->send($mobile, '0jiqqiooebRB', ['p1' => '', 'p2' => $code]);
        if ($result !== true) {
            return $this->error($result);
        }

        $row = new UserCode;
        $row->mobile = $mobile;
        $row->code = $code;
        $row->expired_at = date('Y-m-d H:i:s', time() + 600);
        $row->save();
    }

    /**
     * 注册新用户
     */
    public function postRegister(Request $request)
    {
        $name = strval($request->input('username'));
        $password = strval($request->input('password'));
        $code = strval($request->input('code'));

        $mobile = '';
        if (isMobile($name)) {
            $mobile = $name;
            $n = User::where('mobile', $mobile)->count();
            if ($n > 0) {
                return $this->error('此手机号已注册');
            }
        }
        $email = '';
        if (isEmail($name)) {
            $email = $name;
            $n = User::where('email', $email)->count();
            if ($n > 0) {
                return $this->error('此邮箱已经注册');
            }
        }

        $condition = ['mobile' => $mobile, 'email' => $email, 'code' => $code];
        $uCode = UserCode::where($condition)->first();
        if (empty($uCode)) {
            return $this->error('验证码不正确');
        }
        if (time() > strtotime($uCode->expired_at)) {
            return $this->error('验证码已失效');
        }

        $user = new User;
        $user->username = 'u_' . randStr(8, 1);
        $user->realname = '轶名用户';
        $user->password = bcrypt($password);
        $user->email = $email;
        $user->mobile = $mobile;
        $user->status = 1;
        $user->save();

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->save();

        $data = [
            'user_id' => $user->id,
            'username' => $user->username,
            'realname' => $user->realname,
            'isHidden' => false,
        ];
        Session::put('token', $data);

        return $this->response('ok');
    }

    /**
     * 找回密码，根据邮箱/手机号发送验证码
     */
    public function postForget(Request $request)
    {
        $str = strval($request->input('name'));

        if (isEmail($str)) {
            $user = User::where('email', $str)->first();
            if (empty($user)) {
                return $this->error('无效的注册邮箱');
            }
            $this->dispatch(new SendForgotEmail($user));
            return $this->response('ok');
        }

        if (isMobile($str)) {
            $user = User::where('mobile', $str)->first();
            if (empty($user)) {
                return $this->error('无效的手机号');
            }
            $this->sendForgotSms($str);
            return $this->response('ok');
        }
        return $this->error('无效的注册邮箱/手机号');
    }

    /**
     * 发送找回密码的短信验证码
     */
    private function sendForgotSms($mobile)
    {
        $uCode = UserCode::where('mobile', $mobile)->orderBy('id', 'DESC')->first();
        if ($uCode) {
            if (time() - (strtotime($uCode->created_at)) < 3 * 60) {
                return $this->error(['同一个手机号码每条验证码间隔时间至少是3分钟']);
            }
        }

        $n = UserCode::where('mobile', $mobile)->where(DB::raw('to_days(created_at)'), DB::raw('to_days(now())'))->count();
        if ($n > 2) {
            return $this->error(['每天对同一个手机号码最多发送3条验证码']);
        }

        $code = randStr(6, 1);
        $weimisd = new WeimiSender;
        $result = $weimisd->send($mobile, '0jiqqiooebRB', ['p1' => '', 'p2' => $code]);
        if ($result !== true) {
            return $this->error($result);
        }

        $row = new UserCode;
        $row->mobile = $mobile;
        $row->code = $code;
        $row->expired_at = date('Y-m-d H:i:s', time() + 600);
        $row->save();
    }

    /**
     * 找回密码
     */
    public function postFindpwd(Request $request)
    {
        $name = strval($request->input('username'));
        $code = strval($request->input('code'));
        $password = strval($request->input('password'));

        if (isMobile($name)) {
            $user = User::where('mobile', $name)->first();
            $query = UserCode::where('mobile', $name);
        }
        if (isEmail($name)) {
            $user = User::where('email', $name)->first();
            $query = UserCode::where('email', $name);
        }
        if (empty($user)) {
            return $this->error('无效的用户');
        }
        $uCode = $query->where('code', $code)->first();
        if (empty($uCode)) {
            return $this->error('验证码不正确');
        }
        if (time() - (strtotime($uCode->created_at)) > 15 * 60) {
            $uCode->delete();
            return $this->error('验证码已失效');
        }
        $uCode->delete();
        $user->password = bcrypt($password);
        $user->save();
        return $this->response('ok');
    }

    /**
     * 退出登录
     */
    public function postLogout(Request $request)
    {
        Session::forget('token');
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->guest('admin');
        }
    }

}
