<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EntrustController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:system');
    }

    /**
     * 获取用户分页列表
     */
    public function getUserPageRows(Request $request)
    {
        $limit = toLimitLng($request->input('limit'), 1);
        $users = User::orderBy('id', 'ASC')->paginate($limit);
        foreach ($users as $user) {
            $user->avatar = getAvatar($user->id);
        }
        return $this->response($users);
    }

    public function postUserInsert(Request $request)
    {
        $username = safe($request->input('username'), 50);
        $password = strval($request->input('password'));
        $email = safe($request->input('email'));
        $mobile = safe($request->input('mobile'), 30);
        $status = $request->has('status') ? 0 : 1;

        $realname = safe($request->input('realname'), 50);
        $cardid = safe($request->input('cardid'), 30);
        $sex = safe($request->input('sex'), 10);
        $subject = $request->input('subject');

        $n = User::where('username', $username)->count();
        if ($n > 0) {
            return $this->error('帐户已经存在');
        }
        if (!empty($email)) {
            if (User::where('email', $email)->count()) {
                return $this->error('邮箱已经存在');
            }
        }
        if (!empty($mobile)) {
            if (User::where('mobile', $mobile)->count()) {
                return $this->error('手机号已经被使用');
            }
        }

        $user = new User;
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->realname = $realname;
        $user->email = $email;
        $user->mobile = $mobile;
        $user->status = $status;
        $user->subject = !empty($subject)?implode(',',$subject):'';
        $user->save();
        return $this->response('ok');
    }

    /**
     * 编辑用户信息
     */
    public function postUserUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $user = User::find($id);
        if (empty($user)) {
            return $this->error('无效的用户');
        }
        if ($request->exists('password')) {
            $password = strval($request->input('password'));
            if (!empty($password)) {
                $user->password = bcrypt($password);
            }
        }
        if ($request->exists('email')) {
            $email = strval($request->input('email'));
            if (empty($email) || $user->email == $email) {
            } else {
                if (User::where('email', $email)->count()) {
                    return $this->error('邮箱已经存在');
                }
            }
            $user->email = $email;
        }
        if ($request->exists('mobile')) {
            $mobile = strval($request->input('mobile'));
            if (empty($mobile) || $user->mobile == $mobile) {
            } else {
                if (User::where('mobile', $mobile)->count()) {
                    return $this->error('手机号已经被使用');
                }
            }
            $user->mobile = $mobile;
        }
        if ($request->exists('realname')) {
            $realname = safe($request->input('realname'), 50);
            $user->realname = $realname;
        }
        if ($request->exists('status')) {
            // 不能禁用管理员帐户
            $status = ($id == 1) ? 1 : toLimitLng($request->input('status'), 0, 1);
            $user->status = $status;
        }

        $subject = $request->input('subject');// 学科
        $user->subject = !empty($subject)?implode(',',$subject):'';


        if ($user->save()) {
            return $this->response();
        }
        return $this->error('操作失败');
    }

    /**
     * 删除用户
     */
    public function postUserDelete(Request $request)
    {
        $id = intval($request->input('id'));
        if ($id == 1) {
            return $this->error('禁止删除系统管理员帐户');
        }
        $user = User::find($id);
        if (empty($user)) {
            return $this->error('无效的用户');
        }
        DB::transaction(function () use ($user) {
            $user->delete();
            $user->roles()->sync([]);
            $user->forceDelete();
        });
        return $this->response('ok');
    }

    /**
     * 导入用户
     */
    public function postUserImport()
    {
        $file = $_FILES['file1'];
        if (empty($file)) {
            return $this->error('无效的文件');
        }

        $title = $file['name'];
        $file_ext = strtolower(getFileExt($file['name']));
        $tempFile = $file['tmp_name'];

        $allow_file_type = explode('|', 'xls|xlsx');
        if (!in_array($file_ext, $allow_file_type)) {
            return $this->error('无效的文件类型');
        }

        $target_path = '/upload/';
        $targetPath = public_path() . $target_path;
        if (!is_dir($targetPath)) {
            return $this->error('无效的上传目录');
        }

        $new_filename = uniqid() . '.' . $file_ext;
        $targetFile = $targetPath . $new_filename;
        if (!move_uploaded_file($tempFile, $targetFile)) {
            return $this->error('上传文件失败');
        }
        $users = $this->importUsers($targetFile);
        return $this->response($users);
    }

    private function importUsers($file)
    {
        $users = [];
        Excel::load($file, function ($reader) use (&$users) {
            $reader = $reader->getSheet(0);
            $results = $reader->toArray();
            array_shift($results);
            foreach ($results as $row) {
                $user = [
                    'username' => $row[1],
                    'password' => $row[2],
                    'realname' => $row[3],
                    'sex' => $row[4],
                ];
                if ($this->createUser($user)) {
                    array_push($users, $user);
                }
            }
        });
        unlink($file);
        return $users;
    }

    private function createUser($data)
    {
        extract($data);
        $n = User::where('username', $username)->count();
        if ($n > 0) {
            return false;
        }
        $user = new User;
        $user->username = safe($username, 50);
        $user->password = bcrypt($password);
        $user->realname = safe($realname, 50);
        $user->email = '';
        $user->mobile = '';
        $user->status = 1;
        $user->save();
        return true;
    }

    /**
     * 获取角色列表
     */
    public function getRoleRows()
    {
        $rows = Role::orderBy('id', 'ASC')->get();
        return $this->response($rows);
    }

    /**
     * 新增角色
     */
    public function postRoleInsert(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->error('Access denied.');
        }

        $name = safe($request->input('name'), 50);
        $display_name = safe($request->input('display_name'));
        $description = safe($request->input('description'), 200);

        if (Role::where('name', $name)->count()) {
            return $this->error('权限名称重复');
        }

        $role = new Role;
        $role->name = $name;
        $role->display_name = $display_name;
        $role->description = $description;
        $role->save();
        return $this->response();
    }

    /**
     * 修改角色
     */
    public function postRoleUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $role = Role::find($id);
        if (empty($role)) {
            return $this->error('无效的角色');
        }
        if ($request->exists('display_name')) {
            $role->display_name = safe($request->input('display_name'));
        }
        if ($request->exists('description')) {
            $role->description = safe($request->input('description'), 200);
        }
        if ($role->save()) {
            return $this->response();
        } else {
            return $this->error('操作失败');
        }
    }

    /**
     * 删除角色
     */
    public function postRoleDelete(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->error('Access denied.');
        }
        $id = intval($request->input('id'));
        $role = Role::find($id);
        if (empty($role)) {
            return $this->error('无效的角色');
        }
        DB::transaction(function () use ($role) {
            $role->delete();
            $role->users()->sync([]);
            $role->perms()->sync([]);
            $role->forceDelete();
        });
        return $this->response($id);
    }

    /**
     * 新建权限
     */
    public function postPermInsert(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->error('Access denied.');
        }

        $name = safe($request->input('name'), 50);
        $display_name = safe($request->input('display_name'));
        $description = safe($request->input('description'), 200);

        if (empty($name) || empty($display_name)) {
            return $this->error('数据填空不完整');
        }

        if (Permission::where('name', $name)->count()) {
            return $this->error('权限名称重复');
        }

        $perm = new Permission;
        $perm->name = $name;
        $perm->display_name = $display_name;
        $perm->description = $description;
        $perm->save();
        return $this->response();
    }

    /**
     * 编辑权限
     */
    public function postPermUpdate(Request $request)
    {
        $id = intval($request->input('id'));
        $perm = Permission::find($id);
        if (empty($perm)) {
            return $this->error('无效的权限');
        }
        if ($request->exists('name')) {
            $name = strval($request->input('name'));
            if ($perm->name != $name) {
                if (Permission::where('name', $name)->count()) {
                    return $this->warning('权限名称重复');
                }
                $perm->name = $name;
            }
        }
        if ($request->exists('display_name')) {
            $perm->display_name = safe($request->input('display_name'));
        }
        if ($request->exists('description')) {
            $perm->description = safe($request->input('description'), 200);
        }
        if ($perm->save()) {
            return $this->response();
        } else {
            return $this->error('操作失败');
        }
    }

    /**
     * 删除权限
     */
    public function postPermDelete(Request $request)
    {
        $token = session('token');
        if ($token['isHidden'] == false) {
            return $this->error('Access denied.');
        }
        $id = intval($request->input('id'));
        $perms = Permission::find($id);
        if ($perms->delete()) {
            $perms->roles()->detach();
        }
        return $this->response($id);
    }

    /**
     * 获取成员分页列表
     */
    public function getMemberRows(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $kw = strval($request->input('kw'));
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 1);
        $offset = ($page - 1) * $limit;

        $query = User::select('id', 'username', 'realname', 'email', 'mobile');
        $query->join('edu_role_admin', 'edu_admins.id', '=', 'edu_role_admin.admin_id');
        $query->where('role_id', $role_id)->where(function ($q) use ($kw) {
            $q->where('username', 'LIKE', '%' . $kw . '%');
            $q->orWhere('realname', 'LIKE', '%' . $kw . '%');
            $q->orWhere('email', 'LIKE', '%' . $kw . '%');
            $q->orWhere('mobile', 'LIKE', '%' . $kw . '%');
        });
        $total_rows = $query->count();
        $rows = $query->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        foreach ($rows as $row) {
            $row->avatar = getAvatar($row->id);
        }
        $data['rows'] = $rows;

        $data['total_rows'] = $total_rows;
        $data['page_count'] = ceil($total_rows / $limit);
        $data['page'] = $page;

        return $this->response($data);
    }

    /**
     * 获取非当前角色的成员列表
     */
    public function getOuterMemberRows(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $kw = strval($request->input('kw'));
        $page = toLimitLng($request->input('page'), 1);
        $limit = toLimitLng($request->input('limit'), 1);
        $offset = ($page - 1) * $limit;

        $query = User::select('id', 'username', 'realname', 'email', 'mobile');
        $query->leftJoin('edu_role_admin', 'edu_admins.id', '=', 'edu_role_admin.admin_id');
        $query->where(function ($q) use ($kw) {
            $q->where('username', 'LIKE', '%' . $kw . '%');
            $q->orWhere('realname', 'LIKE', '%' . $kw . '%');
            $q->orWhere('email', 'LIKE', '%' . $kw . '%');
            $q->orWhere('mobile', 'LIKE', '%' . $kw . '%');
        });
        $query->where(function ($q) use ($role_id) {
            $q->where('role_id', '<>', $role_id)->orWhereNull('role_id');
        });
        $total_rows = $query->distinct('id')->count('id');
        $rows = $query->distinct()->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        foreach ($rows as $row) {
            $row->avatar = getAvatar($row->id);
        }
        $data['rows'] = $rows;
        $data['total_rows'] = $total_rows;
        $data['page_count'] = ceil($total_rows / $limit);
        $data['page'] = $page;

        return $this->response($data);
    }

    /**
     * 获取成员信息
     */
    public function getMemberInfo(Request $request)
    {
        $user_id = intval($request->input('user_id'));
        $user = User::find($user_id);
        if (empty($user)) {
            return $this->error('无效的用户');
        }
        $user->avatar = getAvatar($user->id);
        return $this->response($user);
    }

    /**
     * 增加角色成员
     */
    public function postAddMember(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $user_id = intval($request->input('user_id'));
        $role = Role::find($role_id);
        $user = User::find($user_id);
        if ($user->hasRole($role->name) == false) {
            $user->roles()->attach($role_id);
        }
        return $this->response('ok');
    }

    /**
     * 移除角色成员
     */
    public function postRemoveMember(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $user_id = intval($request->input('user_id'));
        if ($user_id == 1 && $role_id == 1) {
            return $this->error('系统管理员用户的角色被锁定');
        }
        $user = User::find($user_id);
        if ($user) {
            $user->roles()->detach($role_id);
        }
        return $this->response('ok');
    }

    /**
     * 为角色授权
     */
    public function postGrantPerm(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $perm_id = intval($request->input('perm_id'));
        if ($role_id == 1 && $perm_id == 1) {
        } else {
            $role = Role::find($role_id);
            if ($role) {
                $role->perms()->attach($perm_id);
            }
        }
        return $this->response('ok');
    }

    /**
     * 移除角色权限
     */
    public function postRemovePerm(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $perm_id = intval($request->input('perm_id'));
        if ($role_id == 1 && $perm_id == 1) {
            return $this->error('系统管理员的系统管理权限被锁定');
        } else {
            $role = Role::find($role_id);
            if ($role) {
                $role->perms()->detach($perm_id);
            }
        }
        return $this->response('ok');
    }
    /**
     * 增加用户角色
     */
    public function postAddMemberRole(Request $request)
    {
        $role_id = intval($request->input('role_id'));
        $user_id = intval($request->input('user_id'));
        $role = Role::find($role_id);
        $user = WebUser::find($user_id);
        if ($user->hasRole($role->name) == false) {
            $user->roles()->attach($role_id);
        }else{
            $user->roles()->detach($role_id);
        }
        return $this->response('ok');
    }
}
