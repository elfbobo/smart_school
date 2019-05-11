<?php
namespace App\Http\ViewComposers;

use App\Libs\CommonUtils;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminComposer
{
    private $data = null;//CommonUtils对象

    public function __construct(Request $request) {
        $this->data = new CommonUtils($request);//新建一个CommonUtils对象
    }

    public function compose(View $view) {
        $view->with([
            'menus' => $this->data->menus,
            'location' => $this->data->location,
            'web_config' => $this->data->config['web_config'],
        ]);//填充数据
    }
}