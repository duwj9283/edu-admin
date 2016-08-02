<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsinfopic extends Model
{
    public $table = 'edu_news_info_pics';
    public $timestamps = false;

    public function info()
    {
        return $this->belongsTo('App\Models\Newsinfo');
    }

    public static function deleteByInfoId($id = 0)
    {
        $rows = parent::where('info_id', $id)->get();
        foreach ($rows as $row) {
            file_exists($row->pic1) && unlink($row->pic1);
            $row->delete();
        }
        return true;
    }

}
