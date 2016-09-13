<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\ConvertTask;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * ��ȡ�����б�
     */
    public function getQueue(Request $request)
    {
        $limit = toLimitLng($request->input('limit'), 1);
        $query = ConvertTask::where('status', 0);
        $row = $query->orderBy('id', 'ASC')->first();
        if(!empty($row))
        {
            return $this->response($row);
        }
        else
        {
            return null;
        }
    }

    public function getQueue2(Request $request)
    {
        $limit = toLimitLng($request->input('limit'), 1);
        $query = ConvertTask::where('status', -1);
        $row = $query->orderBy('id', 'ASC')->first();
        if(!empty($row))
        {
            return $this->response($row);
        }
        else
        {
            return null;
        }
    }

    /**
     * ���������
     */
    public function getCallback(Request $request)
    {
        $id = intval($request->input('task_id'));
        $target_path = strval($request->input('url'));
        //txtStatus 1:����ʼ,-2:ת��ʧ��,2:ת���ɹ�
        $txtStatus = strval($request->input('status_code'));

        $task = ConvertTask::find($id);
        if (empty($task)) {
            return $this->error('Invalid Task.');
        }

        //status -1:������ʧ��,0:����������,1:��������,2:���������
        switch ($txtStatus) {
            case '1':
                $task->status = 1;
            case '-2':
                $task->status = -1;
            case '2':
                $task->status = 2;
        }
        $task->save();
        return $this->response('ok');
    }
}