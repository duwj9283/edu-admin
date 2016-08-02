<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Newsclass extends Model
{
    public $table = 'edu_news_class';
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany('App\Models\Newsinfo', 'class_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'edu_news_popedom', 'class_id', 'role_id');
    }

    public static function getTree()
    {
        $tb = DB::getTablePrefix() . 'edu_news_class';
        $rows = DB::select('SELECT `id`,`name`,FLOOR((LENGTH(`a`.`id`) / 4)) AS `depth`,(SELECT COUNT(`b`.`id`) FROM `' . $tb . '` `b` WHERE (`b`.`id` LIKE CONCAT(`a`.`id`,\'____\'))) AS `child` FROM `' . $tb . '` `a` ORDER BY `order_by` ASC');
        return $rows;
    }

}
