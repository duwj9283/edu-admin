<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Subject extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'edu_subject';

    public $timestamps=false;
    /**
     * 以树状结构返回list
     */
    static function getListByTree(){
        $parents=self::where('father_id',0)->get();//得到所有父集list
        $child=[];
        if($parents){
            $parents_ids=[];//所有父集id
            foreach($parents as $value){
                array_push($parents_ids,$value->id);
            }
            //print_R($parents_ids);
            $childs=self::whereIn('father_id',$parents_ids)->get();//得到所有子集，因为只有两层，此处写死
            //print_R($childs);
            if($childs){
                foreach($childs as $value1){
                    $child[$value1->father_id][$value1->id]=$value1;//所以子集  以父集为key 重构数组
                }

                foreach($parents as $key=>$value2){

                    $parents[$key]->child=isset($child[$value2->id])?$child[$value2->id]:'';
                }
            }
        }

        return $parents;

    }

    /**
     * 根据id 得到 name
     * @param $id void
     * @param $type parent父集 child 子集
     */
    static function getNameById($id,$type){
        $subject=self::find($id);
        if($type=='child'){
            $subject_parent=self::where('id',$subject->father_id)->first();
            return [$subject_parent,$subject];
        }
        return [$subject];
    }

}
