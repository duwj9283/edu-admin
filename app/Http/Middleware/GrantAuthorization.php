<?php
namespace App\Http\Middleware;

use App\Models\Siteconfig;
use App\Models\WebUser;
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
                $title = Siteconfig::where('option_name', 'meta_title')->pluck('option_value');
                return view('admin/login')->withTitle($title);
            }
        }
        $user = WebUser::find($token['user_id']);
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
