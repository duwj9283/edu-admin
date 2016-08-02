<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
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
        $user->subject_names='';

        if($user->subject){
            $subject_names=Subject::whereIn('id',explode(',',$user->subject))->lists('subject_name')->toArray();
            $user->subject_names=implode(',',$subject_names);
        }
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
