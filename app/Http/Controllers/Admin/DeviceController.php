<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\ClassRoom;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getIndex()
    {
        $data['rows'] = Device::paginate(20);
        $data['rows_json'] = json_encode($data['rows']->toArray());
        $data['vs2_serv'] = json_encode(config('services.vs2_serv'));
        return view('admin/device/index', $data);
    }

    /**
     * 删除设备
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Request $request)
    {
        $id = $request->input('id');
        $device = Device::find($id);
        $cr = ClassRoom::where('device_id',$id)->count();
        if($cr>0){
            return $this->error("设备已有教室使用，禁止删除！");
        }
        if ($device->delete()) {
            return $this->response(true);
        }
        return $this->error(false);
    }

    /**
     * 编辑设备
     */
    public function postUpdate(Request $request)
    {
        $id = intval($request->input('id', 0));
        $title = trim($request->input('title'));
        $ip = trim($request->input('ip'));
        $no = trim($request->input('no'));
        $stream_name = trim($request->input('stream_name'));
        if (Device::chectExist('title', $title, $id)) {
            return $this->error('名称已经存在！');
        }
        if (Device::chectExist('ip', $ip, $id)) {
            return $this->error('IP已经存在！');
        }
        if (Device::chectExist('no', $no, $id)) {
            return $this->error('编号已经存在！');
        }
        if (Device::chectExist('stream_name', $stream_name, $id)) {
            return $this->error('流名称已经存在！');
        }
        if ($id > 0) {
            $device = Device::find($id);
        } else {
            $device = new Device();
        }

        $device->title = $title;
        $device->ip = $ip;
        $device->no = $no;
        $device->stream_name = $stream_name;
        $device->status = $request->input('status', 'false');

        if ($device->save()) {
            return $this->response($device);
        }
        return $this->error('操作失败');
    }

    /*******************************教室管理-S************************************/
    /**
     * 教室管理list
     */
    public function getClassroom(){

        $data['rows'] = ClassRoom::leftJoin('edu_device as ed','ed.id','=','edu_class_room.device_id')
            ->select('edu_class_room.*','ed.title as ed_title','ed.status as ed_status')->paginate(20);
        $data['rows_json'] = json_encode($data['rows']->toArray());
        $data['device'] = json_encode(Device::get()->toArray());
        return view('admin/device/classroom', $data);
    }
    /**
     * 编辑教室
     */
    public function postClassroomUpdate(Request $request)
    {
        $id = intval($request->input('id', 0));
        $title = trim($request->input('title'));
        $device_id = trim($request->input('device_id'));

        if ($id > 0) {
            $detail = ClassRoom::find($id);
        } else {
            $detail = new ClassRoom();
        }
        $detail->title = $title;
        $detail->device_id = $device_id;

        if ($detail->save()) {
            return $this->response($detail);
        }
        return $this->error('操作失败');
    }

    /**
     * 删除教室
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postClassroomDelete(Request $request)
    {
        $id = $request->input('id');
        $detail = ClassRoom::find($id);
        if ($detail->delete()) {
            return $this->response(true);
        }
        return $this->error(false);
    }

    /*******************************教室管理-E************************************/

    /*******************************教室轮询管理-S************************************/
    /**
     * 教室轮询
     */
    public function getClassroomView(){

        $data['rows'] = ClassRoom::get();
        $data['vs2_serv'] = json_encode(config('services.vs2_serv'));

        return view('admin/device/classroom-view', $data);
    }
    /**
     * 教室轮询-得到具体设备
     */
    public function getClassroomViewDevice(Request $request){

        $id = $request->input('id');
        $detail = Device::find($id);
        return $this->response($detail);
    }
    /*******************************教室轮询管理-E************************************/




}
