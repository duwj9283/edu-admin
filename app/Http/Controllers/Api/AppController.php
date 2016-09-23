<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\WebUser;
use App\Models\WebUserInfo;
use App\Models\File;
use App\Libraries\Token;
use App\Libraries\Unzip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AppController extends Controller
{
    protected $user_id;

    public function anyIndex(Request $request)
    {
        $method = strval($request->input('method'));
        if (empty($method)) {
            return $this->err('Invalid method.');
        }
        if ($request->exists('token')) {
            $token_code = strval($request->input('token'));
            if (empty($token_code)) {
                return $this->err('��Ч��Token.');
            }
            $uToken = new Token(['auth' => 'app']);
            $token = $uToken->get($token_code);
            if ($token == false) {
                return $this->err('����ѹ���');
            }
            $this->user_id = $token['id'];
        }
        switch ($method) {
            case 'pad.login':
                return $this->login($request);
            case 'pad.logOut':
                return $this->logOut($request);
            case 'pad.getMaterialList':
                return $this->getMaterialList($request);
            default:
                return $this->err('Invalid method!');
        }
    }

    private function login($request)
    {
        $username = strval($request->input('username'));
        $password = strval($request->input('password'));
        $user = WebUser::Where('username', $username)->first();
        if (empty($user)) {
            return $this->err('�û�������');
        }
        if (!Hash::check($password, $user->password)) {
            return $this->err('�������');
        }
        $userInfo = WebUserInfo::where('uid',$user->uid)->first();
        $headpic = "http://lubo.iemaker.cn/img/frontend/camtasiastudio/default-avatar-small.png";
        if(!empty($userInfo->headpic))
        {
            $headpic = "http://lubo.iemaker.cn/frontend/source/getFrontImageThumb/header/".$user->uid."/120/120";
        }
        $token = new Token(['auth' => 'app']);
        $token_code = $token->set([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ], 86400 * 60);
        $data = [
            'name' => !empty($userInfo->realname)?$userInfo->realname:$user->username,
            'email' => $user->email,
            'img' => $headpic,
            'token' => $token_code,
        ];
        return $this->res($data);
    }

    /**
     *�˳�
     */
    private function logOut($request)
    {
        $token = strval($request->input('token'));
        $uToken = new Token(['auth' => 'app']);
        $uToken->del($token);
        $uToken->clear();
        return $this->res();
    }

    private function getMaterialList($request)
    {
        $page = to_limit_lng($request->input('page'), 1);
        $limit = to_limit_lng($request->input('pagesize'), 10);
        $offset = ($page - 1) * $limit;

        $data['folder'] = null;
        $query = File::where('uid', $this->user_id)->where('percent', 100)->where('file_status', 0)->orWhere('file_type',3)->orWhere('file_type',5);
        $file_cnt = $query->count();
        $total_rows = $file_cnt;

        $data['page'] = $page;
        $data['total_rows'] = $total_rows;
        $data['page_count'] = ceil($total_rows / $limit);

        $data['folder_rows'] = array();

        $file_rows = $query->orderBy('addtime', 'DESC')->skip($offset)->take($limit)->get();

        $data['file_rows'] = [];
        foreach ($file_rows as $row) {
            $data['file_rows'][] = [
                'id' => $row->id,
                'name' => $row->file_name,
                'fileURL' => '',
                'convertStatus' => 1,
                'frontCoverUrl' => '',
                'fileSize' => $row->file_size,
                'createdAt' => date('Y-m-d H:i:s',$row->addtime),
                'previewURL' => 'http://lubo.iemaker.cn/api/source/getPreviewImage/'.$row->id.'/1',
                'content' => $row->intro,
            ];
        }
        return $this->res($data);
    }

    /**
     * ��Ч���������Json��ʽ��
     */
    private function res($data = [])
    {
        $data['result'] = 'success';
        return $this->response($data);
    }
    private function err($msg)
    {
        $data['result'] = 'fail';
        $data['msg'] = $msg;
        return $this->error($data);
    }
}
