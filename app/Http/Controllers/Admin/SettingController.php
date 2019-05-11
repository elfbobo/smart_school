<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ConfigModel;
use App\Models\Admin\LogModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends BaseController
{
    //
    public function index()
    {
        $web_config = ConfigModel::where('code', 'web_config')->value('value');
        $web_config = json_decode($web_config, true);
        return view('admin.setting.index', [
            'web_config' => $web_config,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $json = json_encode($data, 320);
        try {
            ConfigModel::updateOrCreate(['code' => 'web_config'], [
                'code' => 'web_config',
                'value' => $json
            ]);
        } catch (\Exception $e) {
            return $this->responseToJson([], '保存失败：【' . $e->getMessage() . '】', 201);
        }

        LogModel::writeLog('【系统设置】修改系统设置：【' . $json . '】', 2);
        return $this->responseToJson([], '保存成功');
    }
}
