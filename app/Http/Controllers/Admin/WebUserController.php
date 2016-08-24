<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebUser;
use App\Models\WebUserInfo;
use App\Models\WebUserRole;
use App\Models\Subject;
use Validator;
use Maatwebsite\Excel\Facades\Excel;

/**
 * 用户管理
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class WebUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }


    /*************************************后台教师账号管理-S*********************************************/
    /**
     * 后台教师账号管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTeacher(Request $request)
    {
        $data['keyword']=$keyword=trim($request->input('keyword'));
        $data['roles']=WebUserRole::lists('role_name','id');
        $query=WebUser::leftJoin('edu_user_info as ui','ui.uid','=','edu_user.uid')
                    ->whereIn('edu_user.role_id',[2,3]);//1学生 2教师 3	学科管理员
        if($keyword){
            $query->where(function($query) use($keyword){
                $query->where('ui.realname','like','%'.$keyword.'%')
                    ->orWhere('ui.email','like','%'.$keyword.'%')
                    ->orWhere('edu_user.phone','like','%'.$keyword.'%');
            });
        }
        $data['lists']=$query->select('edu_user.username','edu_user.phone','edu_user.role_id','edu_user.disable','ui.*')
            ->orderBy('edu_user.uid')->paginate(20);//得到所有list
        if($data['lists']){
            foreach($data['lists'] as $key=>$list){
                $roles=[];
                foreach($list->roles as $key1=>$value){
                    $roles[$key1]=$value->display_name;
                }
                $data['lists'][$key]->roles=$roles;

            }
        }

        return view('admin.web-user.index',$data);
    }

    /**
     * 添加后台教师账号管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAdd(Request $request)
    {
        $id=$request->input('id');
        $detail=$id?(WebUser::leftJoin('edu_user_info as ui','ui.uid','=','edu_user.uid')->select('edu_user.username','edu_user.phone','edu_user.role_id','ui.*')->where('edu_user.uid',$id)->first()):(new WebUser);//得到所有list

        if($detail->subject){
            list($subject_parent,$subject_child)=Subject::getNameById($detail->subject,'child');
        }
        $data['subject']=Subject::where('father_id',0)->where('visible',1)->orderBy('id')->get();//得到所有父集学科list
        $data['subject_parent']=isset($subject_parent)?$subject_parent->subject_name:'';
        $data['subject_child']=isset($subject_child)?$subject_child->subject_name:'';
        $data['detail']=$detail;
        $data['roles']=WebUserRole::where('id','!=',1)->get();//去掉学生

        return view('admin.web-user.add',$data);
    }

    /*
    * 保存提交
    */
    public function postAdd(Request $request){

        $v = Validator::make($request->all(), [
            'role_id' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'sex' => 'required',
            'nick_name' => 'required',
        ]);

        if ($v->fails()) {
            return $this->error($v->errors()->all()[0]);
        }

        $uid = intval($request->input('uid'));
        $role_id = $request->input('role_id');
        $username = $request->input('username');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $sex = $request->input('sex');
        $nick_name = $request->input('nick_name');
        $realname = $request->input('realname');
        $desc = $request->input('desc');
        $qq = $request->input('qq');
        $prov = $request->input('prov');
        $city = $request->input('city');
        $job = $request->input('job');
        $subject = $request->input('subject');
        $admin_subject = $request->input('admin_subject');
        if(!isMobile($phone)){
            return $this->error('请填写正确的手机号');

        }
        $exist_username=$uid?(WebUser::where('username',$username)->where('uid','!=',$uid)->count()):(WebUser::where('username',$username)->count());
        if($exist_username>0){
            return $this->error('账号已经存在');
        }
        $exist_phone=$uid?(WebUser::where('phone',$phone)->where('uid','!=',$uid)->count()):(WebUser::where('phone',$phone)->count());
        if($exist_phone>0){
            return $this->error('手机号已经存在');
        }

        if(!isEmail($email)){
            return $this->error('请填写正确的邮箱');

        }
        $exist_email=$uid?(WebUser::where('email',$email)->where('uid','!=',$uid)->count()):(WebUser::where('email',$email)->count());
        if($exist_email>0){
            return $this->error('邮箱已经存在');
        }
        if($uid){
            $user =WebUser::where('uid',$uid)->first();
            $userinfo =WebUserInfo::where('uid',$uid)->first();
        }else{
            $user = new WebUser;
            $user->username = $username;
            $user->reg_time = date('Y-m-d H:i:s');
            $user->password = '$2a$08$vLDj4vNtYVkX4X13nIhPjO2jfVkAip1Cec2L253pOHTJptTCaitdS';//默认为123456
            $userinfo = new WebUserInfo;
        }
        $user->phone = $phone;
        $user->email = $email;
        $user->role_id = $role_id;
        $user->save();
        $userinfo->admin_subject = !empty($admin_subject)?implode(',',$admin_subject):'';
        $userinfo->uid = $user->uid;
        $userinfo->role_id = $role_id;
        $userinfo->email = $email;
        $userinfo->realname = $realname;
        $userinfo->nick_name = $nick_name;
        $userinfo->desc = $desc;
        $userinfo->qq = $qq;
        $userinfo->sex = $sex;
        $userinfo->job = $job;
        $userinfo->city = $prov.','.$city;
        $userinfo->subject = $subject;
        $userinfo->save();

        return $this->response($user);
    }

    /**
     * 禁用、启用账号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postStatus(Request $request){
        $id=$request->input('id');
        $user =WebUser::where('uid',$id)->first();
        $user->disable=($user->disable==0)?1:0;
        $user->save();
        return $this->response($user);
    }

    /**
     * 导入老师账号
     * @param Request $request
     * @return array
     */
    public function postImport(Request $request){
        $file=$request->file('file');
        $file_ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($file_ext, ['xls','xlsx'])) {
            return $this->error('无效的文件格式，请上传xls/xlsx');
        }
        $target_path='/upload';
        $targetPath = public_path() . $target_path;
        $new_filename = uniqid() . '.' . $file_ext;
        $file->move($targetPath, $new_filename);
        $path =  $targetPath . '/' . $new_filename;
        $list='';//需要导入list列表
        Excel::load($path, function($reader) use(&$list){
            $reader = $reader->getSheet(0);
            //获取表中的数据
            $list = $reader->toArray();

        });
        unlink($path);
        unset($list[0]);
        foreach($list as $key=>$value){
            //手机号
            if(!isMobile($value[1])){
                return $this->error('序号'.$key.'：请填写正确的手机号');
                break;
            }
            if(WebUser::where('phone',$value[1])->count()>0){
                return $this->error('序号'.$key.'：手机号已经注册');
                break;
            }
            //邮箱
            if(!isEmail($value[2])){
                return $this->error('序号'.$key.'：请填写正确的邮箱');
                break;
            }
            if(WebUser::where('email',$value[2])->count()>0){
                return $this->error('序号'.$key.'：邮箱已经存在');
                break;
            }
            //专业
            if(Subject::where('id',$value[9])->count()<=0){
                return $this->error('序号'.$key.'：请填写正确的专业ID,此专业ID不存在');
                break;
            }
            $user = new WebUser;
            $user->reg_time = date('Y-m-d H:i:s');
            $user->password = '690534bf801de16dffcdaeb9da84c059b33f68c7';
            $userinfo = new WebUserInfo;
            $user->username = $user->phone = $value[1];
            $user->email = $value[2];
            $user->role_id = 2;//默认是老师
            $user->save();
            $userinfo->uid = $user->uid;
            $userinfo->role_id = 2;
            $userinfo->email = $value[2];
            $userinfo->nick_name = $value[3];
            $userinfo->realname = $value[4];
            $userinfo->sex = $value[5];
            $userinfo->qq = $value[6];
            $userinfo->desc = $value[7];
            $userinfo->job = $value[8];
            $userinfo->subject = $value[9];
            $userinfo->save();

        }
        return $this->response(true);


    }
    /*************************************后台教师账号管理-E*********************************************/


    /*************************************后台学生账号管理-S*********************************************/
    /**
     * 学生账号管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStudent(Request $request)
    {
        $data['roles']=WebUserRole::lists('role_name','id');
        $data['lists']=WebUser::leftJoin('edu_user_info as ui','ui.uid','=','edu_user.uid')
            ->where('edu_user.role_id',1)//1学生 2教师 3	学科管理员
            ->select('edu_user.username','edu_user.phone','edu_user.role_id','edu_user.disable','edu_user.is_forget','ui.*')
            ->orderBy('edu_user.is_forget','desc')->paginate(20);//得到所有list
        return view('admin.web-user.student',$data);
    }

    /**
     * 学生账号重置密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postPwd(Request $request)
    {
        $uid = $request->input('uid');
        $user = WebUser::find($uid);
        $user->password = '690534bf801de16dffcdaeb9da84c059b33f68c7';
        if($user->save())
        {
            return $this->response(true);
        }
        else
        {
            return $this->response(false);
        }
    }
}
