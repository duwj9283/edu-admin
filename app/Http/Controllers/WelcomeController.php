<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mkapp;
use App\Models\Mkappver;
use App\Models\Newsclass;
use App\Models\Device;

class WelcomeController extends Controller
{
    public function getIndex()
    {
        $app_rows = Mkapp::select('id', 'name')->get();
        $ver_rows = Mkappver::select('id', 'app_id', 'version')->orderBy('app_id', 'ASC')->get();

        $newsclass = new Newsclass;
        $news_rows = $newsclass->getTree();

        $data = compact('app_rows', 'ver_rows', 'news_rows');
        return response()->json($data);

        /*$menulist=array("level1"=>"","level2"=>"");
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
        $data["content"]= $content;*/
        return view('welcome', $data);
    }

    /**
     * 导播台测试
     */
    public function getPlay(){
        $id=6;//测试 edu_device id
        $data['device']=Device::find($id);
        $data['famIdArr']=[3=>'华文行楷',5=>'华文琥珀',6=>'华文彩云',8=>'黑体常规',11=>'微软雅黑'];//字体库
        return view('play',$data);
    }
}
