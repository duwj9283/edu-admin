<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messagestatus extends Model
{
    public $table = 'edu_message_status';

    /**
     * message_status 表中的 view_status 作用：0-未查看 1-已查看
     * message_status 表中的 status 作用：0-垃圾箱 1-收件箱
     */
}
