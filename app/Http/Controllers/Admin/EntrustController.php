<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\WebUser;
use App\Models\Subject;
use Illuminate\Http\Request;

class EntrustController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:system');
    }

    /**
     * 进入角色列表页面
     */
    public function getRoleList()
    {
        $token = session('token');
        $rows = Role::orderBy('id', 'ASC')->get();
        $data = ['rows' => $rows, 'token' => $token];
        return view('admin/entrust/role_list', $data);
    }

    /**
     * 进入权限列表页面
     */
    public function getPermList()
    {
        $token = session('token');
        $rows = Permission::orderBy('id', 'ASC')->get();
        $data = ['rows' => $rows, 'token' => $token];
        return view('admin/entrust/perm_list', $data);
    }

    /**
     * 进入用户列表页面
     */
    public function getUserList()
    {
        return view('admin/entrust/user_list');
    }

    public function getUserAdd()
    {
        $data['subject']=Subject::where('father_id',0)->orderBy('id')->get();//得到所有父集学科list
        return view('admin/entrust/user_add',$data);
    }

    public function getUserEdit(Request $request)
    {
        $id = intval($request->input('id'));
        $user = User::find($id);
        if (empty($user)) {
            return $this->warning('无效的用户');
        }
        $user->subjectArr=Subject::where('father_id',0)->orderBy('id')->get();//得到所有父集学科list
        return view('admin/entrust/user_edit', $user);
    }

    /**
     * 进入新建角色页面
     */
    public function getRoleAdd()
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->warning('Access denied.');
        }
        return view('admin/entrust/role_add');
    }

    /**
     * 进入增加权限页面
     */
    public function getPermAdd()
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->warning('Access denied.');
        }
        return view('admin/entrust/perm_add');
    }

    /**
     * 进入角色编辑页面
     */
    public function getRoleEdit(Request $request)
    {
        $id = intval($request->input('id'));
        $row = Role::find($id);
        if (empty($row)) {
            return $this->warning('无效的角色');
        }
        return view('admin/entrust/role_edit', $row);
    }

    /**
     * 进入角色编辑页面
     */
    public function getPermEdit($id = 0)
    {
        $row = Permission::find($id);
        if (empty($row)) {
            return $this->warning('无效的权限');
        }
        return view('admin/entrust/perm_edit', $row);
    }

    /**
     * 查看角色权限列表
     */
    public function getRolePermsList($id = 0)
    {
        $role = Role::find($id);
        if (empty($role)) {
            return $this->warning('无效的角色');
        }
        $grant_perms = $role->perms->map(function ($perm) {
            return $perm->id;
        });
        $perms_rows = Permission::orderBy('id', 'ASC')->get();
        $data = compact('role', 'perms_rows', 'grant_perms');
        return view('admin/entrust/role_perms', $data);
    }

    /**
     * 查看权限角色列表
     */
    public function getPermRoleList($id = 0)
    {
        $perm = Permission::find($id);
        if (empty($perm)) {
            return $this->warning('无效的权限');
        }
        $roles_rows = Role::orderBy('id', 'ASC')->get();
        $grant_roles = $perm->roles->map(function ($role) {
            return $role->id;
        });
        $data = compact('perm', 'roles_rows', 'grant_roles');
        return view('admin/entrust/perm_roles', $data);
    }

    /**
     * 进入角色成员列表页面
     */
    public function getRoleUsersList($role_id = 0)
    {
        $role = Role::find($role_id);
        if (empty($role)) {
            return $this->warning('无效的角色');
        }
        return view('admin/entrust/role_users', compact('role'));
    }

    /**
     * 根据成员得到所属角色
     */
    public function getUsersRoleList($user_id = 0)
    {
        $user = WebUser::find($user_id);
        $userRole = $user->roles->map(function($role){
            return $role->id;
        })->toArray();
        $roles_rows = Role::orderBy('id', 'ASC')->get();

        return view('admin/entrust/users_role', compact('user','userRole', 'roles_rows'));
    }
}
