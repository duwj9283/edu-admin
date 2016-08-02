<?php
namespace App\Http\Middleware;

use App\Models\Siteconfig;
use Closure;

class AdminAuthentication
{
    public function handle($request, Closure $next)
    {
        $data['title'] = Siteconfig::where('option_name', 'meta_title')->pluck('option_value');
        if (session('token') == false) {
            return view('admin/login', $data);
        }
        return $next($request);
    }
}
