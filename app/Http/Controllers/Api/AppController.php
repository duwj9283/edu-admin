<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\WebUser;
use App\Models\WebUserInfo;
use App\Models\File;
use App\Libraries\Token;
use App\Libraries\Unzip;
use Illuminate\Http\Request;
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
                return $this->err('无效的Token.');
            }
            $uToken = new Token(['auth' => 'app']);
            $token = $uToken->get($token_code);
            if ($token == false) {
                return $this->err('身份已过期');
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
            case 'pad.getMaterialFileIndex':
                return $this->getMaterialFileIndex($request);
            default:
                return $this->err('Invalid method!');
        }
    }

    private function login($request)
    {
        $username = strval($request->input('username'));
        $password = strval($request->input('password'));
        $user = WebUser::where('username', $username)->first();
        if (empty($user)) {
            return $this->err('无效的用户');
        }
        if (sha1(md5($password)."sdkjf*^#HRGF*")!=$user->password) {
            return $this->error('密码错误');
        }
        if ($user->disable == 1) {
            return $this->error('此用户已被管理员禁用');
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
     *退出
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
            $total = 0;
            $list_txt_file = '/home/debian/www/upload/previewpool/'.$row->uid.'/'.$row->id.'/List.txt';
            if (file_exists($list_txt_file)) {
                if($fp=fopen($list_txt_file,"a+"))
                {
                    $conn=fread($fp,filesize($list_txt_file));
                    $images = explode('|',strstr($conn,"1.jpg"));
                    $total = count($images);
                }
            }
            $frontCoverUrl = 'http://lubo.iemaker.cn/api/source/getPreviewImage/'.$row->id.'/1';
            if($row->file_type==3)
            {
                $frontCoverUrl = 'http://lubo.iemaker.cn/api/source/getImageThumb/'.$row->id.'/100/80';
            }
            $data['file_rows'][] = [
                'id' => $row->id,
                'name' => $row->file_name,
                'fileURL' => '',
                'convertStatus' => 1,
                'frontCoverUrl' => $frontCoverUrl,
                'fileSize' => $row->file_size,
                'createdAt' => date('Y-m-d H:i:s',$row->addtime),
                'previewURL' => $total.'',
                'content' => '',
            ];
        }
        return $this->res($data);
    }

    private function getMaterialFileIndex($request)
    {
        $id = intval($request->input('id'));
        $row = File::find($id);
        if (empty($row)) {
            return $this->err('无效的素材');
        }
        $list_txt_file = '/home/debian/www/upload/previewpool/'.$row->uid.'/'.$row->id.'/List.txt';
        $filesArr = array();
        if (file_exists($list_txt_file)) {
            if($fp=fopen($list_txt_file,"a+"))
            {
                $conn=fread($fp,filesize($list_txt_file));
                $images = explode('|',strstr($conn,"1.jpg"));
                foreach($images as $image)
                {
                    $basename = strstr($image,".jpg",true);
                    array_push($filesArr,'http://lubo.iemaker.cn/api/source/getPreviewImage/'.$row->id.'/'.$basename);
                }
            }
        }
        return $this->res(['list' => $filesArr]);
    }

    /**
     * 有效数据输出（Json格式）
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
