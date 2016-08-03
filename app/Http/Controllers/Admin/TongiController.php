<?php
namespace App\Http\Controllers\Admin;

/**
 * Created by PhpStorm.
 * User: fangxiaowan
 * Date: 2016/8/2
 * Time: 14:22
 */
use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
class TongiController extends Controller
{

    public function __construct(){
        $this->middleware("admin");
    }
    /*
     * 用户统计
     */
    public function getUsers(){
        $data['all_count']=WebUser::count();
        $data['student_count']=WebUser::where('role_id',1)->count();
        $data['teacher_count']=WebUser::where('role_id',2)->count();
        return view('admin/tongji/users',$data);
    }
    /**
     * ajax 获得用户统计图表数据
     * @param $type 1 今天 2：7天
     */
    public function getUsersChart(Request $Request){
        $type=$Request->input('type',2);
        switch($type){
            case 1:
                $start=date('Y-m-d'). ' 00:00:00';
                $end=date('Y-m-d'). ' 23:59:59';
                break;
            case 2://从今天往前7天
                $start=date('Y-m-d',strtotime("-7 days")). ' 00:00:00';
                $end=date('Y-m-d'). ' 23:59:59';
                break;
            case 3://从今天往前30天
                $start=date('Y-m-d',strtotime("-30 days")). ' 00:00:00';
                $end=date('Y-m-d'). ' 23:59:59';
                break;
            case 4://开始时间-》结束时间
                $start=$Request->input('start'). ' 00:00:00';
                $end=$Request->input('end'). ' 23:59:59';
                break;
        }
        $student=WebUser::where('role_id',1)->whereBetween('reg_time', [$start, $end])->select('uid','reg_time')->get();
        $teacher=WebUser::where('role_id',2)->whereBetween('reg_time', [$start, $end])->select('uid','reg_time')->get();
        for($i=0;$i<((strtotime($end)-strtotime($start))/86400);$i++){//开始时间到结束时间有多少天
            $days[$i]=date('Y-m-d',strtotime($start)+$i*86400);//开始时间到结束时间形成的数组

        }
        $data['student']=$this->dealUsersChart($student);
        $data['teacher']=$this->dealUsersChart($teacher);

        $data['day']=$days;

        return $this->response($data);


    }

    /**
     * 处理用户统计，返回时间=》数目
     * @param $list
     */
    public function dealUsersChart($list){
        if($list){
            $result=[];
            foreach($list as $key=>$value){
                $reg=date('Y-m-d',strtotime($value->reg_time));
                $result[$reg]=isset($result[$reg])?++$result[$reg]:1;
            }
            return $result;
        }
    }
}