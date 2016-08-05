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
use App\Models\File;
use App\Models\FilePush;
use App\Models\ApplicationType;
use App\Models\Subject;
use DB;
use Illuminate\Http\Request;

class TongiController extends Controller
{

    public function __construct(){
        $this->middleware("admin");
    }
    /********************************用户统计-S************************************/
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
    /********************************用户统计-E************************************/

    /********************************文件统计-S************************************/
    /*
     * 文件统计页面
     */
    public function getFiles(){
        $data['all_count']=File::count();//统计用户所有文件
        $data['subject_count']=json_encode($this->getFilesSubject());//文件按学科统计
        $data['applicationType_count']=json_encode($this->getFilesApplicationType());//文件按文件类型统计
        $users_count=DB::table('edu_user_file_push as eufp')->leftJoin('edu_user_info as eui','eui.uid','=','eufp.uid')
            ->select(DB::raw('count(*) as count'),'eui.realname')
            ->GroupBy('eufp.uid')->orderBy('count','DESC')->take(10)->get();//前十名上传文件最多数目
        $data['users_count']=json_encode($users_count);
        return view('admin/tongji/files',$data);
    }
    /**
     * 用户已经发布的文件，按学科统计
     */
    private function getFilesSubject(){
        $subjectTree=Subject::getListByTree();//得到所有学科父集=》子集
        //得到对应subject_id 下count数目
        $fileBySubject=DB::table('edu_user_file_push')->GroupBy('subject_id')->lists(DB::raw('count(*) as count'),'subject_id');
        if($subjectTree){
            foreach($subjectTree as $key=>$value){
                $subject[$key]['id']=$value->id;
                $subject[$key]['subject_name']=$value->subject_name;
                $subject[$key]['count']=0;
                if($value->child){
                    foreach($value->child as $child){
                        foreach($fileBySubject as $subject_id=>$count){
                            //如果edu_user_file_push表中有subject_id数目，加入
                            if($subject_id==$child['id']){
                                $subject[$key]['count']=$subjectTree[$key]['count']+$count;
                            }
                        }
                    }
                }

            }
            return $subject;//返回学科以及对应数目 数组

        }

    }
    /**
     * 用户已经发布的文件，按文件类型统计
     */
    private function getFilesApplicationType(){
        $applicationArr=ApplicationType::get();//得到所有文件类型
        $fileByType=DB::table('edu_user_file_info')->GroupBy('application_type')->lists(DB::raw('count(*) as count'),'application_type');

        foreach($applicationArr as $key=>$value){
            $application[$key]['count']=0;
            $application[$key]['name']=$value->name;
            foreach($fileByType as $type=>$count){

                if($type==$value->id){
                    $application[$key]['count']=$application[$key]['count']+$count;
                }
            }
        }
        return $application;

    }
    /********************************文件统计-E************************************/

}