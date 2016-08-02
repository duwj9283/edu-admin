<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mkapp;
use App\Models\Mkappver;

class WelcomeController extends Controller
{
    public function getIndex($type,$level1, $level2=null)
    {
        $menulist=array("level1"=>"","level2"=>"");
        $content="";

        //根据应用列表和帮助列表构造二级菜单
        $apps= Mkapp::all();
        foreach ($apps as $item){

        }


        if($type==0){//帮助

        }else{//更新日志
            if(!empty(level2)){
                $content= Mkappver::where("app_id",$level1)->where("version",$level2)->orderBy('is_top', 'DESC')->orderBy('id', 'DESC')->first()->description;
            }
        }
        $data["menulist"]= $menulist;
        $data["content"]= $content;
        return view('welcome',$data);

    }
}
