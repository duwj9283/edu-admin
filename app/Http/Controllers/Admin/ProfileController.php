<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * 进入个人资料页面
     */
    public function getIndex()
    {
        $user_id = session('token')['user_id'];
        $user = User::find($user_id);
        $user->avatar = getPhoto($user_id);
        return view('admin/profile/profile', $user);
    }

    /**
     * 进入修改密码页面
     */
    public function getChangepwd()
    {
        return view('admin/profile/changepwd');
    }

}
