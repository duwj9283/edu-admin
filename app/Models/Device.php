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

	/**
	 * 更新device 信息
	 * @param ip [string] edu_device表ip
	 * @param data [array] 需要更新的字段数组
	 */
	static function updateParam($ip,$data){
		$device=self::where('ip',$ip)->first();
		if(isset($data['win_type'])){
			$device->win_type=$data['win_type'];
		}
		if(isset($data['subtitle_status'])){
			$device->subtitle_status=$data['subtitle_status'];
		}
		if(isset($data['subtitle_color'])){
			$device->subtitle_color=$data['subtitle_color'];
		}
		if(isset($data['subtitle_fam_id'])){
			$device->subtitle_fam_id=$data['subtitle_fam_id'];
		}
		if(isset($data['subtitle_txt'])){
			$device->subtitle_txt=$data['subtitle_txt'];
		}
		if(isset($data['volume'])){
			$device->volume=$data['volume'];
		}
		if(isset($data['record_status'])){
			$device->record_status=$data['record_status'];
		}
		if(isset($data['record_time'])){
			$device->record_time=$data['record_time'];
		}
		$device->save();
		return true;
	}

}
