<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function response($data = '', $status = 200, $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    protected function error($msg = '', $status = 400)
    {
        $headers = ['Content-Type' => 'application/json'];
        return $this->response($msg, $status, $headers);
    }

    protected function warning($msg, $url = false)
    {
        $url = $url ?: HTTP_REFERER;
        $data = compact('msg', 'url');
        return view('common/warning', $data);
    }


}
