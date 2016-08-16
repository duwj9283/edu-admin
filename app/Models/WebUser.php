<?php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
// use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Foundation\Auth\Access\Authorizable;
use Zizaco\Entrust\Traits\EntrustUserTrait;


/*
 * 前台用户表
 */

class WebUser extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    // use Authenticatable, Authorizable, CanResetPassword, EntrustUserTrait;
    use Authenticatable, CanResetPassword, EntrustUserTrait;



    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'edu_user';

    protected $primaryKey = 'uid';

    public $timestamps=false;


}
