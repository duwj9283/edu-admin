<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsclass;
use App\Models\Newsinfo;
use App\Models\Newsinfopic;
use App\Models\Newspopedom;
use App\Models\User;
use App\Models\WebUserInfo;
use Illuminate\Http\Request;

class NewsinfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:news');
    }

    /**
     * 权限检查
     */
    private function checkPopedom($class_id = '')
    {
        $result = ['create' => false, 'update' => false, 'confirm' => false, 'delete' => false];
        $token = session('token');
        $popedom = Newspopedom::getPopedom($class_id, $token['user_id']);
        if ($popedom) {
            $result['create'] = ($popedom & 1) ? true : false;
            $result['update'] = ($popedom & 2) ? true : false;
            $result['confirm'] = ($popedom & 4) ? true : false;
            $result['delete'] = ($popedom & 8) ? true : false;
        }
        $user_id = $token['user_id'];
        $user = WebUserInfo::find($user_id);
        if ($user->hasRole('Administrators')) {
            $result = ['create' => true, 'update' => true, 'confirm' => true, 'delete' => true];
        }
        return $result;
    }

    /**
     * 进入资讯列表页面
     */
    public function getIndex()
    {
        $rows = Newsclass::select('id', 'name')->get();
        foreach ($rows as $row) {
            $row->pId = substr($row->id, 0, -4);
        }
        $data['zNodes'] = $rows;
        return view('admin/news/info_list', $data);
    }

    /**
     * 进入栏目资讯列表页面
     */
    public function getList($class_id, Request $request)
    {
        $column = Newsclass::find($class_id);
        $data['column'] = $column;
        $data['navigation'] = $this->getNavigation($class_id);

        $rows = Newsclass::select('id', 'name')->get();
        foreach ($rows as $row) {
            $row->pId = substr($row->id, 0, -4);
        }
        $data['zNodes'] = $rows;
        return view('admin/news/news_list', $data);
    }

    private function getNavigation($class_id)
    {
        $ids = [];
        for ($i = 1; $i <= strlen($class_id) / 4; $i++) {
            $ids[] = substr($class_id, 0, $i * 4);
        }
        $rst = Newsclass::select('id', 'name')->whereIn('id', $ids)->get();
        return $rst->toArray();
    }

    /**
     * 进入资讯多图列表页面
     */
    public function getPics(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Newsinfo::find($id);
        if (empty($row)) {
            return $this->warning('无效的信息');
        }
        $data['info'] = $row;

        $class_id = $row->class_id;
        $popedom = $this->checkPopedom($class_id);
        if ($popedom['update'] === false) {
            return $this->warning('无此操作权限');
        }

        $data['column'] = Newsclass::find($class_id);
        $data['navigation'] = $this->getNavigation($class_id);

        $rows = Newsinfopic::where('info_id', $id)->orderBy('id', 'ASC')->get();
        foreach ($rows as $row) {
            $row->pic1 = empty($row->pic1) ? '' : asset($row->pic1);
        }
        $data['rows'] = $rows;

        return view('admin/news/info_pics', $data);
    }

}
