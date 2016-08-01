<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Device extends Model {

	protected $table = 'edu_device';

	/**
	 * 检查属性是否存在
	 * @param $field 指定字段
	 * @param $value 字段对应值
	 * @param $id id
	 * @return bool true 存在
	 */
	static function chectExist($field,$value,$id=0){
		if($id>0){
			$count=self::where('id','!=',$id)->where($field,$value)->count();
		}else{
			$count=self::where($field,$value)->count();

		}
		return $count>0?true:false;
	}

}
