<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CapacityApply;
use App\Models\Capacity;
use App\Models\WebUserInfo;
use App\Models\Memberinfo;
use App\Models\Message;
use App\Models\Messagestatus;
/**
 * 容量管理
 * Class CapacityController
 * @package App\Http\Controllers\Admin
 */
class CapacityController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getIndex(Request $request)
    {
        $status = $request->input('status');
        $status = isset($status)?(int)$status:-1;
        $limit = 20;
        $query = CapacityApply::select('*');
        if($status>=0)
        {
            $query->where('status', $status);
        }
        $apply_list=$query->orderBy('addtime','desc')->paginate($limit);
        $uidArr = array();
        $applyList = array();
        foreach($apply_list as $k=>$apply)
        {
            if(!in_array($apply->uid,$uidArr))
            {
                array_push($uidArr,$apply->uid);
            }
            $applyList[$k] = $apply->toArray();
            $applyList[$k]['capacity'] =  number_format($apply->capacity/1024/1024/1024,2);
        }
        if(!empty($uidArr))
        {
            $userInfoArr = array();
            $userInfos = WebUserInfo::whereIn('uid', $uidArr)->get();
            foreach($userInfos as $userInfo)
            {
                $userInfoArr[$userInfo->uid] = !empty($userInfo->realname)?$userInfo->realname:'-';
            }

            foreach($applyList as $k=>$v)
            {
                $applyList[$k]['username'] = $userInfoArr[$v['uid']];
            }
        }
        $data['status'] = $status;
        $data['lists'] = $applyList;
        $data['apply_list'] = $apply_list;
        return view('admin.capacity.index',$data);
    }


    /*
     * 审核
     */
    public function postEdit(Request $request){
        $id=(int)$request->input('id');
        if($id>0)
        {
            DB::beginTransaction();
            $Capacity=CapacityApply::find($id);
            if($Capacity->status>0)
            {
                return 0;
            }
            $Capacity->capacity=(int)$request->input('capacity')*1024*1024*1024;
            $Capacity->status=1;
            if($Capacity->save())
            {
                $result = Capacity::where("uid",$Capacity->uid)->increment('capacity_all', $Capacity->capacity);
                if($result==1)
                {
                    $user_id = session('token')['user_id'];
                    $title = "恭喜您，你已经成功申请".(int)$request->input('capacity')."GB空间容量";
                    $content = strval($title);
                    $row = new Message;
                    $sender_id = $user_id;
                    $receiver_ids = $Capacity->uid;
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
                    $receiver_id = $Capacity->uid;
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
            return 0;
        }
    }

    /*
     * 拒绝
     */
    public function postFail(Request $request){
        $id=(int)$request->input('id');

        DB::beginTransaction();
        $Capacity = CapacityApply::find($id);
        $Capacity->status=2;
        $Capacity->fail_reason=$request->input('reason');
        if(!$Capacity->save())
        {
            DB::rollback();
            return $this->error('拒绝失败！');
        }
        $user_id = session('token')['user_id'];
        $title = "很遗憾，您的空间容量申请审核失败【".$Capacity->reason.",申请时间:".$Capacity->addtime."】";
        $content = strval($title);
        $row = new Message;
        $sender_id = $user_id;
        $receiver_ids = $Capacity->uid;
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
        $receiver_id = $Capacity->uid;
        $data = compact('message_id', 'sender_id', 'receiver_id', 'status', 'created_at', 'updated_at');
        if(!Messagestatus::insert($data))
        {
            DB::rollback();
        }
        DB::commit();
        return $this->response(true);
    }

}
