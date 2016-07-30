<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class GrantAuthorization
{
    public function handle($request, Closure $next, $perm)
    {
        $token = $request->session()->get('token');
        if ($token == false) {
            if ($request->ajax()) {
                return response()->json('Unauthorized.', 400);
            } else {
                return view('admin/login');
            }
        }
        $user = User::find($token['user_id']);
        if ($user->can($perm) == false) {
            if ($request->ajax()) {
                return response()->json('Access denied.', 400);
            } else {
                return view('common/warning', ['msg' => 'Access denied.', 'url' => HTTP_REFERER]);
            }
        }
        return $next($request);
    }
}
