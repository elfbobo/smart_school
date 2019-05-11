<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\AppListModel;
use App\Models\Admin\ServiceTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppManageController extends BaseController
{
    //应用类型
    private $appType = [
        'PC应用',
        '移动应用',
        'PC卡片',
        '移动卡片',
    ];

    private $appTypeValue = [
        'pc' => 'is_pc_app',
        'mobile' => 'is_mobile_app',
        'pccard' => 'has_pc_card',
        'mobilecard' => 'has_mobile_card',
    ];

    //应用属性
    private $appAttr = [
        'new' => '新应用',
        'recommended' => '推荐应用',
    ];

    private $appAttrValue = [
        'new' => 'is_new',
        'recommended' => 'is_recommended',
    ];

    //运行状态
    private $status = [
        '未上线',
        '上线中',
        '已停止',
    ];

    //周期服务
    private $isCycle = [
        '否',
        '是',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $requestParams = $request->all();
        $perPage = $request->get('perpage', config('custom.perPage'));
        $data = AppListModel::where(function ($query) use ($requestParams) {
            //关键字搜索
            if (isset($requestParams['search']) && $requestParams['search']) {
                $query->where('name', 'like', '%' . $requestParams['search'] . '%');
            }

            //应用类型搜索
            if (isset($requestParams['app_type']) && $requestParams['app_type']) {
                //$field = $this->appTypeValue[$requestParams['app_type']];
                $query->where('category', $requestParams['app_type']);
            }

            //应用属性搜索
            if (isset($requestParams['app_attr']) && $requestParams['app_attr']) {
                $field = $this->appAttrValue[$requestParams['app_attr']];
                $query->where($field, 1);
            }

            //状态搜索
            if (isset($requestParams['status']) && !is_null($requestParams['status'])) {
                $query->where('online_status', $requestParams['status']);
            }

            //周期服务搜索
            if (isset($requestParams['is_cycle']) && !is_null($requestParams['is_cycle'] != '')) {
                $query->where('is_cycle', $requestParams['is_cycle']);
            }
        })
            ->paginate($perPage);
        $service_type = ServiceTypeModel::where('state', 0)->select('id', 'parent_id as pid', 'name as title')->get()->toArray();
        $service_type = PHPTree::toList($service_type);
        return view('admin.app_manage.index', [
            'data' => $data,
            'service_type' => $service_type,
            'app_type' => $this->appType,
            'app_attr' => $this->appAttr,
            'status' => $this->status,
            'is_cycle' => $this->isCycle,
            'params' => $requestParams,
            'buildParams' => json_encode($requestParams),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.app_manage.create', [
            'category' => $this->appType
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $this->validation($request->post());
        $data['id'] = $this->getUUID();
        try {
            AppListModel::create($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '新增失败', 0);
        }

        return $this->responseToJson([], '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $info = AppListModel::find($id);
        return view('admin.app_manage.edit', [
            'info' => $info,
            'category' => $this->appType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $this->validation($request->post(), $id);
        try {
            AppListModel::where('id', $id)->update($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '编辑失败', 0);
        }

        return $this->responseToJson([], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $id = $request->get('id');

        try {
            AppListModel::destroy($id);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败', 0);
        }
        return $this->responseToJson([], '删除成功');
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'icon_url' => 'required',
            'name' => [
                'required',
                'max:30',
                !is_null($id) ? Rule::unique('t_app_list')->ignore($id) : 'unique:t_app_list'
            ],
        ];

        $msg = [
            'icon_url.required' => '请上传应用图标',
            'name.required' => '请输入应用名称',
            'name.max' => '应用名称不超过30个字符',
            'name.unique' => '应用名称已存在'
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 0,false));
        }

        if ($data['cycle_begin_end_time']) {
            $tmp = explode('~', $data['cycle_begin_end_time']);
            $data['cycle_begin_time'] = $tmp[0];
            $data['cycle_end_time'] = $tmp[1];
        }

        unset($data['cycle_begin_end_time']);
        return $data;
    }
}
