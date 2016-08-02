<?php
namespace App\Models;

use App\Models\Newsclass;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Newspopedom extends Model
{
    public $table = 'edu_news_popedom';
    public $timestamps = false;

    public static function getPopedom($class_id = '', $user_id = 0)
    {
        $popedom = 0;
        $user = User::find($user_id);
        $rows = parent::select('role_id', 'popedom')->where('class_id', $class_id)->get();
        foreach ($rows as $row) {
            $role_name = Role::where('id', $row->role_id)->pluck('name');
            if ($user->hasRole($role_name)) {
                $popedom |= $row->popedom;
            }
        }
        return $popedom;
    }

    public static function getRolePopedoms($role_id = 0)
    {
        $data = [];
        $rows = parent::select('class_id', 'popedom')->where('role_id', $role_id)->get();
        foreach ($rows as $row) {
            $data[$row->class_id] = $row->popedom;
        }

        $result = [];
        $rows = Newsclass::all();
        foreach ($rows as $row) {
            $v = isset($data[$row->id]) ? $data[$row->id] : 0;
            if ($role_id == 1) {
                $v = 1 | 2 | 4 | 8;
            }
            $result[$row->id] = $v;
        }
        return $result;
    }
}
