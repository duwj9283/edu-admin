<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Maatwebsite\Excel\Facades\Excel;


/**
 * 学科字典管理
 * Class SubjectController
 * @package App\Http\Controllers\Admin
 */
class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getIndex(Request $request)
    {
        $data['parents'] = Subject::where('father_id', 0)->orderBy('id')->get()->toArray();//得到所有父集list
        return view('admin.subject.index', $data);
    }

    /**
     * 由father_id得到子集list
     */
    public function getFatherList()
    {
        $father = Subject::where('father_id', 0)->orderBy('id')->get();//得到所有父集list
        return $this->response($father);
    }

    /**
     * 由father_id得到子集list
     */
    public function getChildList(Request $request)
    {
        $id = $request->input('id');
        $child = Subject::where('father_id', $id)->orderBy('id')->get();//得到所有父集list
        return $this->response($child);
    }

    /*
     * 保存提交
     */
    public function postEdit(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id) {
            $Subject = Subject::find($id);

        } else {
            $Subject = new Subject;
            $Subject->father_id = $request->input('father_id');
            $Subject->addtime = date('Y-m-d H:i:s');

        }
        $Subject->subject_name = $request->input('name');
        $Subject->subject_code = $request->input('code');
        $Subject->save();
        return $this->response($Subject);
    }

    /*
     * 删除
     */
    public function postDelete(Request $request)
    {
        $id = $request->input('id');
        if (Subject::where('father_id', $id)->count() > 0) {
            return $this->error('请先删除子集！');

        }
        if (Subject::find($id)->delete()) {
            return $this->response(true);
        }

        return $this->error('删除失败！');
    }


    /**
     * ajax得到所有学科list parents=>child
     */
    public function getListByAjax(Request $request)
    {
        $list = Subject::getListByTree();
        return $this->response($list);
    }

    /*
     * 下载所有二级专业
     */
    public function getDownload()
    {
        $parents = Subject::where('father_id', 0)->orderBy('id')->lists('subject_name', 'id');//得到所有父集list
        $childs = Subject::where('father_id', '!=', 0)->orderBy('id')->get();//得到所有子集list
        if ($childs) {
            foreach ($childs as $key => $value) {
                if (isset($parents[$value->father_id])) {
                    $childs[$key]->father = $parents[$value->father_id];
                }
            }
            Excel::create('学科专业', function ($excel) use ($childs) {

                $excel->sheet('学科专业', function ($sheet) use ($childs) {
                    // Manipulate first row
                    $sheet->row(1, array(
                        'ID', '学科', '专业'
                    ));
                    foreach ($childs as $key => $value) {
                        $sheet->row($key + 2, array(
                            $value->id, $value->father, $value->subject_name
                        ));
                    }
                });
            })->export('xls');
        }
    }

    /*
     * 显示/隐藏
     */
    public function postVisible(Request $request)
    {
        $id = $request->input('id', 0);
        $visible = $request->input('visible')=='block'?1:2;
        $type = $request->input('type');
        DB::beginTransaction();
        if ($id)
        {
            $Subject = Subject::find($id);
        }
        if($type=='parent')
        {
            $Subject->visible = $visible;
            if(!$Subject->save()){
                DB::rollback();
            }
            $update = Subject::where('father_id',$id)->update(['visible' => $visible]);
            if(!$update)
            {
                DB::rollback();
            }
        }
        else
        {
            $fatherSubject = Subject::find($Subject->father_id)->toArray();
            if($visible==1&&$fatherSubject['visible']==2)
            {
                return 0;
            }
            else
            {
                $Subject->visible = $visible;
                if(!$Subject->save()){
                    DB::rollback();
                }
            }
        }
        DB::commit();
        return 1;
    }
}
