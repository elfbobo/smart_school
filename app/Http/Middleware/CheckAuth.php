<?php

namespace App\Http\Middleware;

use App\Models\Admin\MenuModel;
use App\Models\Admin\PermissionRoleModel;
use Closure;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_auth = session('user_auth');
        if ($user_auth['code'] != config('custom.super_manager')) {

            if (!cache()->has('menu_urls')) {
                $menu_ids = PermissionRoleModel::whereIn('role_id', $user_auth['roles'])->pluck('menu_id')->toArray();
                if (!$menu_ids) {
                    abort(403,'无权限');
                }
                $menu_ids = array_unique($menu_ids);
                $menu_urls = MenuModel::whereIn('id', $menu_ids)
                    ->whereNotNull('uri')
                    ->pluck('uri')->toArray();
                $menu_urls = array_unique($menu_urls);
                $menu_urls = ',' . implode(',', $menu_urls) . ',';
                //cache()->forever('menu_urls', $menu_urls);
            }
            //$menu_urls = cache('menu_urls');
            $uri = $request->route()->getName(); //当前路由
            if ($uri == 'home.index' && false === strpos($menu_urls, ',home.index,')) {
                return redirect()->route('user.profile');
            }
            //这个地方用来处理资源路由的 store和update的权限，这两个权限没有在节点管理里，所以需要特别处理下
            //其实这两个和create和edit是对应的，只不过请求方式不同
            $prefix = strstr($uri, '.', true);
            $action = substr(strstr($uri, '.'), 1);
            if (in_array($action, ['store', 'update'])) {
                $compare = [
                    'store' => 'create',
                    'update' => 'edit',
                ];

                //如果没有这两个动作，并且有 creat和edit权限，则放行
                if (false === strpos($menu_urls, ',' . ($prefix . '.' . $compare[$action]) . ',')) {
                    return $this->exception($request);
                }
            } else {
                if (false === strpos($menu_urls, ',' . $uri . ',')) {
                    return $this->exception($request);
                }
            }
        }

        return $next($request);
    }

    private function exception($request)
    {
        if ($request->ajax()) {
            exit(responseToJson([], '抱歉，您无权限操作，请联系管理员', 201));
        }
        abort(403);
    }
}
