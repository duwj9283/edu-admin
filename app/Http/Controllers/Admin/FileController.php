<?php
namespace App\Http\Controllers\Admin;
use App\Models\Subject;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\FilePush;
use App\Models\FileInfo;
use App\Models\ApplicationType;
use App\Models\WebUserInfo;
use App\Models\User;
use App\Models\WebUser;
use App\Models\Dynamic;
use App\Models\Message;
use App\Models\Messagestatus;
use Session;
/**
 * 文件管理
 * Class FileController
 * @package App\Http\Controllers\Admin
 */
class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getIndex(Request $request)
    {
        $status = $request->input('status');
        $uid = (int)$request->input('uid');
        $file_type = (int)$request->input('file_type');
        $status = isset($status)?(int)$status:-1;
        $token=Session::get('token');//取session
        $user=WebUserInfo::where('uid',$token['user_id'])->first();//登录人
        $limit = 20;
        $query = FilePush::select('*');
        if($status>=0)
        {
            $query->where('status', $status);
        }
        if($uid>0)
        {
            $query->where('uid', $uid);
        }
        if($file_type>0)
        {
            $query->where('file_type', $file_type);
        }
        $subject=[];//此登录账号 能查看的学科数组
        if($user->admin_subject){//文件按着管理人的学科查询,管理账号绑定的是subjec 第一级
            $subject=Subject::whereIn('father_id',explode(',',$user->admin_subject))->lists('id')->toArray();
        }
        $query->whereIn('subject_id', $subject);
        $result=$query->orderBy('addtime','desc')->paginate($limit);
        $uidArr = array();
        $fileIdArr = array();
        $fileList = array();
        foreach($result as $k=>$v)
        {
            if(!in_array($v->uid,$uidArr))
            {
                array_push($uidArr,$v->uid);
            }
            array_push($fileIdArr,$v->user_file_id);
            $fileList[$k] = $v->toArray();
        }
        if(!empty($uidArr))
        {
            $userInfoArr = array();
            $userInfos = WebUserInfo::whereIn('uid', $uidArr)->get();
            foreach($userInfos as $userInfo)
            {
                $userInfoArr[$userInfo->uid] = !empty($userInfo->realname)?$userInfo->realname:'-';
            }
            foreach($fileList as $k=>$v)
            {
                $fileList[$k]['username'] = $userInfoArr[$v['uid']];
            }
        }
        if(!empty($fileIdArr))
        {
            $fileInfoArr = array();
            $fileInfos = File::whereIn('id', $fileIdArr)->get();
            foreach($fileInfos as $fileInfo)
            {
                $fileInfoArr[$fileInfo->id]['file_size'] = fileSizeConv($fileInfo->file_size);
                $fileInfoArr[$fileInfo->id]['file_md5'] = $fileInfo->file_md5;
            }
            foreach($fileList as $k=>$v)
            {
                $fileList[$k]['file_size'] = $fileInfoArr[$v['user_file_id']]['file_size'];
                $fileList[$k]['file_md5'] = $fileInfoArr[$v['user_file_id']]['file_md5'];
            }
        }
        $data['status'] = $status;
        $data['uid'] = $uid;
        $data['file_type'] = $file_type;
        $data['lists'] = $fileList;
        $data['file_list'] = $result;
        return view('admin.file.index',$data);
    }

    public function getPublicFileInfo(Request $request)
    {
        $id=$request->input('id',0);
        $fileInfo = FileInfo::where('user_file_id',$id)->first();
        $subject = Subject::where('id',$fileInfo->subject_id)->first();
        $fileInfo->father_id = $subject->father_id;
        $data['fileInfo'] = $fileInfo;
        //应用类型
        $data['applicationType'] = ApplicationType::select('*')->get();
        return $this->response($data);
    }

    /*
     * 审核
     */
    public function postEdit(Request $request){
        $id=(int)$request->input('id');
        $fatherSub=(int)$request->input('fatherSub');
        $childSub=(int)$request->input('childSub');
        $subject = Subject::where('id',$childSub)->first();
        if($subject->father_id!=$fatherSub)
        {
            return 0;
        }
        $application_type=(int)$request->input('application_type');
        $knowledge_point=$request->input('knowledge_point');
        $language = (int)$request->input('language');
        $desc = $request->input('desc');
        if($id>0)
        {
            DB::beginTransaction();
            $filePush=FilePush::find($id);
            if($filePush->status>0)
            {
                return 0;
            }

            $user_id = session('token')['user_id'];
            $user = User::find($user_id);
            $filePush->status=1;
            $filePush->verifyer=$user->realname;
            $filePush->verifytime=time();
            if($filePush->save())
            {
                $fileInfo = FileInfo::where("user_file_id",$filePush->user_file_id)->first();
                if($fileInfo)
                {
                    $fileInfo->subject_id = $childSub;
                    $fileInfo->knowledge_point = $knowledge_point;
                    $fileInfo->application_type = $application_type;
                    $fileInfo->desc = $desc;
                    $fileInfo->language = $language;
                    if($fileInfo->save())
                    {
                        //记录空间动态
                        $dynamic = new Dynamic();
                        $dynamic->uid = $filePush->uid;
                        $dynamic->content = "发布文件[".$filePush->push_file_name."]";
                        $dynamic->addition = $filePush->user_file_id;
                        $dynamic->type = 2;
                        $dynamic->addtime = date("Y-m-d H:i:s");
                        if(!$dynamic->save())
                        {
                            DB::rollback();
                        }
                        //发送消息
                        $title = "恭喜您，您发布的文件：".$filePush->push_file_name."已通过审核";
                        $content = strval($title);
                        $row = new Message;
                        $sender_id = $user_id;
                        $receiver_ids = $filePush->uid;
                        $send_status = 3;
                        $data = compact('sender_id', 'receiver_ids', 'title', 'content', 'send_status');
                        foreach($data as $k => $v){
                            $row->$k = $v;
                        }
                        if(!$row->save())
                        {
                            DB::rollback();
                        }
                        $message_id = $row->id;
                        $status = 1;
                        $created_at = $updated_at = date('Y-m-d H:i:s');
                        $receiver_id = $filePush->uid;
                        $data = compact('message_id', 'sender_id', 'receiver_id', 'status', 'created_at', 'updated_at');
                        if(!Messagestatus::insert($data))
                        {
                            DB::rollback();
                        }
                        DB::commit();
                        return 1;
                    }
                    else
                    {
                        DB::rollback();
                        return 0;
                    }
                }
                else
                {
                    DB::rollback();
                    return 0;
                }
            }
            else
            {
                DB::rollback();
                return 0;
            }
        }
        else
        {
            return 0;
        }
    }

    /*
     * 拒绝
     */
    public function postFail(Request $request){
        $id=(int)$request->input('id');

        DB::beginTransaction();
        $filePush = FilePush::find($id);
        $filePush->status=2;
        $filePush->fail_reason=$request->input('reason');
        if(!$filePush->save())
        {
            DB::rollback();
            return $this->error('拒绝失败！');
        }
        $user_id = session('token')['user_id'];
        $title = "很遗憾，您的发布的文件：".$filePush->push_file_name.",审核失败【原因".$request->input('reason').",审核时间:".date('Y-m-d')."】";
        $content = strval($title);
        $row = new Message;
        $sender_id = $user_id;
        $receiver_ids = $filePush->uid;
        $send_status = 3;
        $data = compact('sender_id', 'receiver_ids', 'title', 'content', 'send_status');
        foreach($data as $k => $v){
            $row->$k = $v;
        }
        if(!$row->save())
        {
            DB::rollback();
        }
        $message_id = $row->id;
        $status = 1;
        $created_at = $updated_at = date('Y-m-d H:i:s');
        $receiver_id = $filePush->uid;
        $data = compact('message_id', 'sender_id', 'receiver_id', 'status', 'created_at', 'updated_at');
        if(!Messagestatus::insert($data))
        {
            DB::rollback();
        }
        DB::commit();
        return $this->response(true);
    }
}
