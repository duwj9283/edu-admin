<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siteconfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['except' => ['getRegister', 'getForgot']]);
    }

    public function getIndex(Request $request)
    {
        $data['fileupload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">不支持</font>';
        $data['dbversion'] = DB::select('SELECT VERSION() as dbversion'); //MySQL 版本
        $data['client_ip'] = $request->getClientIp();
        $data['site_protocol'] = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $data['meta_title'] = Siteconfig::where('option_name', 'meta_title')->pluck('option_value');
        return view('admin/welcome', $data);
    }

    public function getRegister()
    {
        return view('admin/register');
    }

    public function getForgot()
    {
        return view('admin/forgot');
    }

    public function getTest()
    {
        DB::connection()->enableQueryLog();
        $user = User::more()->first();
        dump($user->toArray());
        $queries = DB::getQueryLog();
        dump($queries);
    }
}
