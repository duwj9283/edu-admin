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
        $row = $query->first();
        print_r($row->toArray());
        exit;
    }
}
