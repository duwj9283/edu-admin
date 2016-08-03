<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mkapp;
use App\Models\Mkappver;
use App\Models\Newsclass;
use App\Models\Newsinfo;

class HelpController extends Controller
{
    public function getIndex()
    {
        $releaseid = Mkappver::orderBy('id', 'ASC')->pluck('id');
        return $this->getRelease($releaseid);
    }

    private function getNewsRows()
    {
        $newsclass = new Newsclass;
        $rows = $newsclass->getTree();
        $news_rows = collect();
        foreach ($rows as $row) {
            if (strlen($row->id) == 4) {
                $row->children = $this->getChildren($rows, $row->id);
                $news_rows->push($row);
            }
        }
        return $news_rows;
    }

    private function getAppRows()
    {
        $app_rows = Mkapp::select('id', 'name')->get();
        $ver_rows = Mkappver::select('id', 'app_id', 'version')->orderBy('app_id', 'ASC')->get();

        foreach ($app_rows as $app) {
            $children = [];
            foreach ($ver_rows as $key => $ver) {
                if ($app->id == $ver->app_id) {
                    array_push($children, $ver);
                }
            }
            $app->children = $children;
        }
        return $app_rows;
    }

    private function getChildren($obj, $id = '')
    {
        $data = [];
        foreach ($obj as $v) {
            if (substr($v->id, 0, strlen($v->id) - 4) == $id) {
                $v->children = $this->getChildren($obj, $v->id);
                array_push($data, $v);
            }
        }
        return $data;
    }

    public function getRelease($id)
    {
        $data['app_rows'] = $this->getAppRows();
        $data['news_rows'] = $this->getNewsRows();

        $row = Mkappver::find($id);
        if (empty($row)) {
            return warning('无效的应用');
        }
        $data['app'] = Mkapp::find($row->app_id);
        $data['info'] = $row;
        return view('help/help_release', $data);
    }

    public function getColumn($id)
    {
        $data['app_rows'] = $this->getAppRows();
        $data['news_rows'] = $this->getNewsRows();

        $row = Newsclass::find($id);
        if (empty($row)) {
            return warning('无效的栏目');
        }
        $data['column'] = $row;

        $query = Newsinfo::where('class_id', $id)->where('status', 1);
        $query->orderBy('is_top', 'DESC')->orderBy('sortnum', 'DESC');
        $total_rows = $query->count();

        if ($total_rows < 1) {
            $data['info'] = false;
            return view('help/help_newsinfo', $data);
        }

        if ($total_rows == 1) {
            $row = $query->first();
            $data['info'] = $row;
            return view('help/help_newsinfo', $data);
        }

        $data['rows'] = $query->paginate(12);
        return view('help/help_newslist', $data);
    }

    public function getInfo($id)
    {
        $info = Newsinfo::where('status', 1)->find($id);
        if (empty($info)) {
            return $this->warning('无效的信息');
        }
        $data['info'] = $info;
        $data['column'] = Newsclass::find($info->class_id);
        $data['app_rows'] = $this->getAppRows();
        $data['news_rows'] = $this->getNewsRows();
        return view('help/help_newsinfo', $data);
    }
}
