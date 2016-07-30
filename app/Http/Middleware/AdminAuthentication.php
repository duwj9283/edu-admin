<?php
namespace App\Http\Middleware;

use Closure;
use Session;

class AdminAuthentication
{
    public function handle($request, Closure $next)
    {
        if (Session::get('token') == false) {
            return view('admin/login');
        }
        return $next($request);
    }
}
