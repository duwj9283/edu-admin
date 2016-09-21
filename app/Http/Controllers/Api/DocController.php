<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;

class DocController extends Controller
{
    /**
     * 获取文档图片
     */
    public function getDocImg(Request $request)
    {
        $id = intval($request->input('id'));
        $query = File::where('id', $id);
        $file = $query->first();
        $filePath = '/home/debian/www/upload/previewpool/'.$file->uid.'/'.$file->id.'/List.txt';
        if(!file_exists($filePath))
        {
            $responseData = array("code" => 1, "msg" => "文档还未转换完成", "line" => __LINE__);
            return $this->response($responseData);
        }
        if($fp=fopen($filePath,"a+"))
        {
            $conn=fread($fp,filesize($filePath));
            $images = explode('|',strstr($conn,"1.jpg"));
            $arr = array();
            foreach($images as $image)
            {
                $basename = strstr($image,".jpg",true);
                array_push($arr,'/api/source/getPreviewImage/'.$file->id.'/'.$basename);
            }
            $data['total'] = count($images);
            $data['images'] = $arr;
            $responseData = array("code"=>0,"msg"=>"","line"=>__LINE__,"data"=>$data);
        }
        else
        {
            $responseData = array("code" => 1, "msg" => "文件打不开", "line" => __LINE__);
        }
        return $this->response($responseData);
    }
}
