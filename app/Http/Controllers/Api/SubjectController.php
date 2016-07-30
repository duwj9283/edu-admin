<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:subject');
    }
    /**
     * 导入学科
     */
    public function postSubjectImport()
    {
        $file = $_FILES['file1'];
        if (empty($file)) {
            return $this->error('无效的文件');
        }
        $fatherID = (int)$_POST['fatherID'];
        $title = $file['name'];
        $file_ext = strtolower(getFileExt($file['name']));
        $tempFile = $file['tmp_name'];

        $allow_file_type = explode('|', 'xls|xlsx');
        if (!in_array($file_ext, $allow_file_type)) {
            return $this->error('无效的文件类型');
        }

        $target_path = '/upload/';
        $targetPath = public_path() . $target_path;
        if (!is_dir($targetPath)) {
            return $this->error('无效的上传目录');
        }

        $new_filename = uniqid() . '.' . $file_ext;
        $targetFile = $targetPath . $new_filename;
        if (!move_uploaded_file($tempFile, $targetFile)) {
            return $this->error('上传文件失败');
        }
        $subjects = $this->importSubjects($targetFile,$fatherID);
        return $this->response($subjects);
    }

    private function importSubjects($file,$fatherID)
    {
        $subjects = [];
        Excel::load($file, function ($reader) use (&$subjects,$fatherID) {
            $reader = $reader->getSheet(0);
            $results = $reader->toArray();
            array_shift($results);
            foreach ($results as $row) {
                if(!empty($row[0])&&!empty($row[1]))
                {
                    $subject['code'] = $row[0];
                    $subject['name'] = $row[1];
                    $subject['fatherID'] = $fatherID;
                    $result = $this->createSubject($subject);
                    if ($result)
                    {
                        array_push($subjects, $result);
                    }
                }else
                {
                    break;
                }
            }
        });
        unlink($file);
        return $subjects;
    }

    private function createSubject($data)
    {
        extract($data);
        $n = Subject::where('subject_code', $code)->count();
        if ($n > 0) {
            return false;
        }
        $now_time = date("Y-m-d H:i:s");
        $subject = new Subject;
        $subject->father_id = $fatherID;
        $subject->subject_code = safe($code, 16);
        $subject->subject_name = safe($name,50);
        $subject->addtime = $now_time;
        if(!$subject->save())
        {
            return false;
        }
        else
        {
            return $subject->toArray();
        }
    }
}