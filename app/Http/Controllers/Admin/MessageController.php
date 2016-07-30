<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Memberinfo;
use App\Models\Message;
use App\Models\Messagestatus;
use Illuminate\Http\Request;

/**
 * message 表中的 send_status 作用： 0-已删除 1-垃圾箱 2-草稿箱 3-已发送
 * message_status 表中的 view_status 作用：0-未查看 1-已查看
 * message_status 表中的 status 作用：0-垃圾箱 1-收件箱
 */
class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * 收件箱
     */
    public function getInbox(Request $request)
    {
        $user_id = session('token')['user_id'];
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 12);

        $query = Messagestatus::where(['receiver_id' => $user_id, 'edu_message_status.status' => 1]);
        if($request->exists('is_new')){
            $query->where('view_status', 0);
        }
        $query->select('edu_message_status.id', 'edu_message_status.message_id', 'edu_admins.realname AS sender_name', 'edu_messages.receiver_ids', 'edu_messages.title', 'edu_messages.content', 'edu_message_status.view_status', 'edu_messages.created_at', 'edu_message_status.updated_at');
        $query->leftJoin('edu_messages', 'edu_message_status.message_id', '=', 'edu_messages.id');
        $query->leftJoin('edu_admins', 'edu_messages.sender_id', '=', 'edu_admins.id');
        $data['rows'] = $query->orderBy('id', 'DESC')->paginate($limit);

        $data['viewState'] = ['0' => '未查看', '1' => '已查看'];
        return view('admin/message/inbox', $data);
    }

    /**
     * 草稿箱
     */
    public function getDrafts(Request $request)
    {
        $user_id = session('token')['user_id'];
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 12);

        $rows = Memberinfo::select('uid', 'realname')->get();
        $userMap = [];
        foreach($rows as $row){
            $userMap[$row->uid] = $row->realname;
        }

        $query = Message::select('id', 'title', 'receiver_ids', 'created_at');
        $query->where('sender_id', $user_id)->where('send_status', 2);
        $rows = $query->orderBy('id', 'DESC')->paginate($limit);
        foreach($rows->items() as $row){
            $arr = [];
            foreach(explode(',', $row->receiver_ids) as $k){
                if($k == 0) {
                    array_push($arr, 'Everyone');
                } else {
                    array_push($arr, $userMap[$k]);
                }
            }
            $row->receiver_names = implode(',', $arr);
        }
        $data['rows'] = $rows;

        return view('admin/message/drafts', $data);
    }

    /**
     * 发件箱
     */
    public function getOutbox(Request $request)
    {
        $user_id = session('token')['user_id'];
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 12);

        $rows = Memberinfo::select('uid', 'realname')->get();
        $userMap = [];
        foreach($rows as $row){
            $userMap[$row->uid] = $row->realname;
        }

        $query = Message::select('id', 'title', 'content', 'receiver_ids', 'created_at');
        $query->where('sender_id', $user_id)->where('send_status', 3);
        $rows = $query->orderBy('id', 'DESC')->paginate($limit);
        foreach($rows->items() as $row){
            $arr = [];
            foreach(explode(',', $row->receiver_ids) as $k){
                if($k == 0) {
                    array_push($arr, 'Everyone');
                } else {
                    array_push($arr, $userMap[$k]);
                }
            }
            $row->receiver_names = implode(',', $arr);
        }
        $data['rows'] = $rows;

        return view('admin/message/outbox', $data);
    }

    /**
     * 垃圾箱
     */
    public function getTrash(Request $request)
    {
        $user_id = session('token')['user_id'];
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 12);

        $ids = [0];
        $rows = Messagestatus::where(['receiver_id' => $user_id, 'status'=>0])->get();
        foreach($rows as $row)
        {
            array_push($ids, $row->message_id);
        }
        $rows = Message::where(['sender_id' => $user_id, 'send_status'=>1])->get();
        foreach($rows as $row)
        {
            array_push($ids, $row->id);
        }
        $query = Message::whereIn('edu_messages.id', array_unique($ids));
        $query->select('edu_messages.id', 'edu_messages.receiver_ids', 'edu_messages.title', 'edu_messages.content', 'edu_messages.created_at');
        $query->addSelect('edu_admins.realname AS sender_name');
        $query->join('edu_admins', 'edu_messages.sender_id', '=', 'edu_admins.id');
        $data['rows'] = $query->orderBy('edu_messages.id', 'DESC')->paginate($limit);

        return view('admin/message/trash', $data);
    }

    /**
     * 获取消息详细内容
     */
    public function getDetails(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));

        $rows = Memberinfo::select('uid', 'realname')->get();
        $userMap = [];
        foreach($rows as $row){
            $userMap[$row->uid] = $row->realname;
        }

        $msg = Message::find($id);
        $flag = false;
        do {
            if($msg->sender_id == $user_id){
                $flag = true;
                break;
            }
            if(in_array(0, explode(',', $msg->receiver_ids))) {
                $flag = true;
                break;
            }
            if(in_array($user_id, explode(',', $msg->receiver_ids))) {
                $flag = true;
                break;
            }
        } while (false);
        if(!$flag) {
            return $this->error('您无权查看此消息');
        }

        $arr = [];
        foreach(explode(',', $msg->receiver_ids) as $k){
            if($k == 0) {
                array_push($arr, 'Everyone');
            } else {
                array_push($arr, $userMap[$k]);
            }
        }
        $receiver_names = implode(',', $arr);

        $query = Messagestatus::where(['message_id' => $id, 'receiver_id' => $user_id]);
        $query->update(['view_status' => 1]);

        $data = [
            'id' => $msg->id,
            'title' => $msg->title,
            'content' => $msg->content,
            'file_name' => empty($msg->file1) ? '' : basename($msg->file1),
            'created_at' => $msg->created_at->format('Y-m-d H:i:s'),
            'receiver_names' => $receiver_names,
        ];
        return $this->response($data);
    }

    /**
     * 写消息
     */
    public function getWrite(Request $request)
    {
        $data['user_rows'] = Memberinfo::select('uid', 'realname')->orderBy('uid', 'ASC')->get();
        return view('admin/message/write', $data);
    }

    /**
     * 编辑消息
     */
    public function getEdit(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));

        $row = Message::where('sender_id', $user_id)->where('send_status', 2)->find($id);
        if(empty($row)){
            return $this->warning('无效的消息');
        }
        $data['msg'] = $row;
        $data['user_rows'] = Memberinfo::select('uid', 'realname')->orderBy('uid', 'ASC')->get();
        return view('admin/message/edit', $data);
    }

    /**
     * 保存到草稿箱
     */
    public function postSaveDrafts(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));
        $title = safe($request->input('title'), 100);
        $content = strval($request->input('content'));
        $receiver_ids = $request->input('receiver_ids');

        if(empty($title) || empty($content)){
            return $this->error('无效的参数');
        }
        if(!is_array($receiver_ids)){
            return $this->error('无效的收件人');
        }

        $row = Message::where('sender_id', $user_id)->find($id);
        if(empty($row)){
            $row = new Message;
            $file1 = '';
        } else {
            $file1 = $row->file1;
        }

        if($request->hasFile('file1')){
            $file = $request->file('file1');
            if(!$file->isValid()){
                return $this->error($file->getError());
            }
            $file_ext = $file->getClientOriginalExtension();
            $target_path = 'upload/' . date('Y/m/d/');
            $targetPath = public_path() . DIRECTORY_SEPARATOR . $target_path;
            if (!is_dir($targetPath)) {
                if (!mkdir($targetPath, 0777, 1)) {
                    return $this->error('无法建立上传目录');
                }
            }
            $new_filename = uniqid(mt_rand()) . '.' . $file_ext;
            $file->move($targetPath, $new_filename);
            $file1 = $target_path . $new_filename;
        }

        $sender_id = $user_id;
        $receiver_ids = implode(',', $receiver_ids);
        $send_status = 2;
        $data = compact('sender_id', 'receiver_ids', 'title', 'content', 'file1', 'send_status');
        foreach($data as $k => $v){
            $row->$k = $v;
        }
        $row->save();
        $data = ['id' => $row->id, 'file1' => empty($file1) ? '' : asset($file1)];
        return $this->response($data);
    }

    /**
     * 删除附件
     */
    public function postDeleteFile1(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));
        $row = Message::where('sender_id', $user_id)->find($id);
        if(empty($row)){
            return $this->error('无效的消息');
        }
        $file1 = $row->file1;
        $row->file1 = '';
        if($row->save()){
            file_exists($file1) && unlink($file1);
        }
        return $this->response($id);
    }

    /**
     * 下载附件
     */
    public function getDownloadFile1(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));

        $row = Message::find($id);
        if(empty($row)){
            return $this->warning('无效的消息');
        }
        $file = $row->file1;
        if(empty($file)){
            return $this->warning('无效的附件');
        }
        if(!file_exists($file)){
            return $this->warning('文件不存在');
        }

        $flag = false;
        do {
            if($row->sender_id == $user_id){
                $flag = true;
                break;
            }
            if(in_array(0, explode(',', $row->receiver_ids))){
                $flag = true;
                break;
            }
            if(in_array($user_id, explode(',', $row->receiver_ids))){
                $flag = true;
                break;
            }
        } while (false);
        if($flag == false){
            return $this->warning('无效的权限');
        }

        $file_name = basename($file);
        $file_size = filesize($file);

        Header('Content-type: application/octet-stream');
        Header('Accept-Ranges: bytes');
        Header('Accept-Length: ' . $file_size);
        Header("Content-Disposition: attachment; filename=" . $file_name);

        $fp = fopen($file, 'r+');
        while (!feof($fp)) {
            $file_data = fread($fp, 1024);
            echo $file_data;
        }
        fclose($fp);
    }

    /**
     * 发送消息
     */
    public function postSend(Request $request)
    {
        $user_id = session('token')['user_id'];
        $id = intval($request->input('id'));
        $title = safe($request->input('title'));
        $content = strval($request->input('content'));
        $receiver_ids = $request->input('receiver_ids');

        if(empty($title) || empty($content)){
            return $this->error('无效的参数');
        }
        if(!is_array($receiver_ids)){
            return $this->error('无效的收件人');
        }

        $row = Message::where('sender_id', $user_id)->find($id);
        if(empty($row)){
            $row = new Message;
            $file1 = '';
        }else{
            $file1 = $row->file1;
        }

        if($request->hasFile('file1')){
            $file = $request->file('file1');
            if(!$file->isValid()){
                return $this->error($file->getError());
            }
            $file_ext = $file->getClientOriginalExtension();
            $target_path = 'upload/' . date('Y/m/d/');
            $targetPath = public_path() . DIRECTORY_SEPARATOR . $target_path;
            if (!is_dir($targetPath)) {
                if (!mkdir($targetPath, 0777, 1)) {
                    return $this->error('无法建立上传目录');
                }
            }
            $new_filename = uniqid(mt_rand()) . '.' . $file_ext;
            $file->move($targetPath, $new_filename);
            $file1 = $target_path . $new_filename;
        }

        $sender_id = $user_id;
        $receiver_ids = implode(',', $receiver_ids);
        $send_status = 3;
        $data = compact('sender_id', 'receiver_ids', 'title', 'content', 'file1', 'send_status');
        foreach($data as $k => $v){
            $row->$k = $v;
        }
        if($row->save()){
            $message_id = $row->id;
            $status = 1;
            $created_at = $updated_at = date('Y-m-d H:i:s');
            $receiver_ids = array_unique(explode(',', $receiver_ids));
            if(in_array(0, $receiver_ids)){
                $users = Memberinfo::select('uid')->get();
                $receiver_ids = array_unique($users->map(function($user){
                    return $user->uid;
                })->toArray());
            }
            $data = [];
            foreach($receiver_ids as $receiver_id){
                $data[] = compact('message_id', 'sender_id', 'receiver_id', 'status', 'created_at', 'updated_at');
            }
            Messagestatus::insert($data);
        }
        return $this->response($id);
    }

    /**
     * 由收件箱移至垃圾箱
     */
    public function postInboxToTrash(Request $request)
    {
        $user_id = session('token')['user_id'];
        $ids = explode('|', $request->input('ids'));
        $query = Messagestatus::where('receiver_id', $user_id);
        $query->whereIn('id', $ids)->update(['status' => 0]);
        return $this->response($ids);
    }

    /**
     * 由发件箱移至垃圾箱
     */
    public function postOutboxToTrash(Request $request)
    {
        $user_id = session('token')['user_id'];
        $ids = explode('|', $request->input('ids'));
        $query = Message::where('sender_id', $user_id)->where('send_status', 3);
        $query->whereIn('id', $ids)->update(['send_status' => 1]);
        return $this->response($ids);
    }

    /**
     * 从草稿箱直接删除消息
     */
    public function postDeleteDrafts(Request $request)
    {
        $user_id = session('token')['user_id'];
        $ids = explode('|', $request->input('ids'));
        $query = Message::where('sender_id', $user_id)->where('send_status', 2);
        $rows = $query->whereIn('id', $ids)->get();
        foreach($rows as $row){
            $file1 = $row->file1;
            if($row->delete()){
                file_exists($file1) && unlink($file1);
            }
        }
        return $this->response($ids);
    }

    /**
     * 删除垃圾箱中的消息
     */
    public function postDelete(Request $request)
    {
        $user_id = session('token')['user_id'];
        $ids = explode('|', $request->input('ids'));
        if(empty($ids)){
            return $this->error('无效的消息Id');
        }

        // 删除由收件箱转到垃圾箱的消息
        Messagestatus::where('receiver_id', $user_id)->whereIn('message_id', $ids)->delete();

        // 删除由发件箱转到垃圾箱的消息
        Message::where('sender_id', $user_id)->where('send_status', 1)->whereIn('id', $ids)->update(['send_status' => 0]);

        $this->clearInvalidMessages($ids);
        return $this->response($ids);
    }

    /**
     * 清除无效的消息
     */
    private function clearInvalidMessages($ids = [])
    {
        if(empty($ids)){
            $rows = Messagestatus::select('message_id')->distinct()->get();
        }else{
            $rows = Messagestatus::select('message_id')->whereIn('message_id', $ids)->distinct()->get();
        }
        $ids = $rows->map(function($row){
            return $row->message_id;
        });
        $rows = Message::where('send_status', 0)->whereNotIn('id', $ids)->get();
        foreach($rows as $row){
            $file1 = $row->file1;
            if($row->delete()){
                file_exists($file1) && unlink($file1);
            }
        }
        return true;
    }

    /**
     * 获取新消息数目
     */
    public function getNewMsgCount(Request $request)
    {
        $user_id = session('token')['user_id'];
        $query = Messagestatus::where('receiver_id', $user_id)->where('view_status', 0);
        $n = $query->count('id');
        return $this->response($n);
    }
}
