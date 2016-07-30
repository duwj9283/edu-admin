<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApplicationType;

/**
 * 容量管理
 * Class CapacityController
 * @package App\Http\Controllers\Admin
 */
class AppTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getIndex(Request $request)
    {
        $data['appType']=ApplicationType::orderBy('id')->get();
        return view('admin.app-type.index',$data);
    }

    /*
     * 保存提交
     */
    public function postEdit(Request $request){
        $id=$request->input('id',0);
        if($id){
            $applicationType = ApplicationType::find($id);
        }else{
            $applicationType = new ApplicationType;
        }
        $applicationType->name=$request->input('name');
        $applicationType->save();
        return $this->response($applicationType);
    }

    /*
     * 删除
     */
    public function postDelete(Request $request){
        $id=$request->input('id');
        if(ApplicationType::find($id)->delete()){
            return $this->response(true);
        }
        return $this->error('删除失败！');
    }
}
