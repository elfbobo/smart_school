<?php
namespace App\Libs;

use App\Models\Admin\ConfigModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\PermissionRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommonUtils
{
    public $menus = [];
    public $location = [];
    public $config = [];

    /**
     * 构造函数
     */
    public function __construct(Request $request) {
        $this->init($request);
    }

    /**
     * 初始化函数
     */
    private function init(Request $request) {
        $this->getMenu($request);
        $this->getLocation($request);
        $this->getConfig();
    }

    /**
     * 获取菜单列表
     */
    private function getMenu(Request $request)
    {
        if (!Cache::has('menus')) {
            $menus = [];
            $user_auth = session('user_auth');
            if ($user_auth) {
                if ($user_auth['code'] == config('custom.super_manager')) {
                    $menus = MenuModel::where('is_menu', 1)
                        ->where('status', 0)
                        ->orderBy('sort')
                        ->select('id', 'parent_id', 'title', 'icon', 'uri')
                        ->get()
                        ->toArray();
                } else {
                    $menu_ids = PermissionRoleModel::whereIn('role_id', $user_auth['roles'])->pluck('menu_id')->toArray();
                    if ($menu_ids) {
                        $menu_ids = array_unique($menu_ids);
                        $menus = MenuModel::where('is_menu', 1)
                            ->where('status', 0)
                            ->whereIn('id', $menu_ids)
                            ->orderBy('sort')
                            ->select('id', 'parent_id', 'title', 'icon', 'uri')
                            ->get()
                            ->toArray();
                    }
                }
            }

            if ($menus) {
                $tree = new PHPTree(['pid' => 'parent_id']);
                $menus = $tree::toLayer($menus);
                Cache::forever('menus', $menus);
            }
        } else {
            $menus = Cache::get('menus', []);
        }

        $menus = $menus ? getTopMenu($menus) : [];
        $this->menus = $menus;
    }

    /**
     * 获取当前位置
     */
    private function getLocation(Request $request)
    {
        $location = [];
        if ($request->route()) {
            $uri = $request->route()->getName();
            $curren_id = MenuModel::where('uri', $uri)->value('id');
            if ($curren_id > 0) {
                $location = getLocation(MenuModel::select('id', 'parent_id', 'title', 'uri', 'icon')->get()->toArray(), $curren_id);
            }
        }
        $this->location = $location;
    }

    /**
     * 获取配置信息
     */
    private function getConfig()
    {
        $config = ConfigModel::where('code', 'web_config')->value('value');
        $config = [
            'web_config' => json_decode($config, true),
        ];
        $this->config = $config;
    }
}