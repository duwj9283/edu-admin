<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/*
 * 前台用户表
 */
class WebUser extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'edu_user';

    protected $primaryKey = 'uid';

    public $timestamps=false;


}
