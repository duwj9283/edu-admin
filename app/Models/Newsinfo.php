<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsinfo extends Model
{
    public $table = 'edu_news_info';
    public $timestamps = true;

    public function column()
    {
        return $this->belongsTo('App\Models\Newsclass', 'class_id');
    }

    public function pics()
    {
        return $this->hasMany('App\Models\Newsinfopic');
    }

}
