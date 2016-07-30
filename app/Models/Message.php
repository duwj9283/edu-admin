<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $table = 'edu_messages';

    /**
     * message 表中的 send_status 作用： 0-已删除 1-垃圾箱 2-草稿箱 3-已发送
     */
}
