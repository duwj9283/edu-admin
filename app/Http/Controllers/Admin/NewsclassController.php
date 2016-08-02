<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsclass;
use App\Models\Newspopedom;
use App\Models\Role;
use Illuminate\Http\Request;

class NewsclassController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:news');
    }

    /**
     * 进入栏目列表页面
     */
    public function getIndex()
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->warning('Access denied.');
        }
        return view('admin/news/class_list');
    }

    /**
     * 进入栏目树结构页面
     */
    public function getTreeList()
    {
        $rows = Newsclass::select('id', 'name')->get();
        foreach ($rows as $row) {
            $row->pId = substr($row->id, 0, -4);
        }
        $data['zNodes'] = $rows;
        return view('admin/news/tree_list', $data);
    }

    /**
     * 进入权限设置页面
     */
    public function getPopedom(Request $request)
    {
        $role_id = toLimitLng($request->input('role_id'), 1);
        $data['role_id'] = $role_id;
        $data['perms'] = Newspopedom::getRolePopedoms($role_id);
        $data['roles'] = Role::all();
        $data['columns'] = Newsclass::getTree();
        return view('admin/news/popedom', $data);
    }
}
