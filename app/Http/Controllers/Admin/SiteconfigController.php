<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteconfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('grant:system');
    }

    public function getIndex()
    {
    }

    public function getMetaSet(Request $request)
    {
    }
}
