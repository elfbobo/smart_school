<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\AppFolderManageModel;
use App\Models\Admin\AppFolderModel;
use App\Models\Admin\AppListModel;
use App\Models\Admin\AppRoleModel;
use App\Models\Admin\AppServiceModel;
use App\Models\Admin\RoleModel;
use App\Models\Admin\ServiceTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppManageController extends BaseController
{
    //卡片类型
    private $cardType = [
        4 => '普通卡片',
        2 => '通知公告',
        1 => '系统文件夹',
        3 => '消息中心',
    ];
    //应用类型
    private $appType = [
        'PC应用',
        '移动应用',
        'PC卡片',
        '移动卡片',
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
        if (!empty($requestParams)) {
            foreach ($requestParams as $column => $value) {
                if (!strlen($value)) {
                    unset($requestParams[$column]);
                }
            }
        }
        $data = AppListModel::from('t_app_list as a')
            //->join('t_app_service_app as b', 'b.app_id', '=', 'a.id')
            //->leftJoin('t_service_type as c', 'c.id', 'b.service_type_id')
            ->where(function ($query) use ($requestParams) {
                //关键字搜索
                if (isset($requestParams['search']) && $requestParams['search']) {
                    $query->where('name', 'like', '%' . $requestParams['search'] . '%');
                }
            })
            ->where(function ($query) use ($requestParams) {
            //应用类型搜索
            if (isset($requestParams['app_type']) && strlen($requestParams['app_type'])) {
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

            //服务类别搜索
            if (isset($requestParams['service_type']) && $requestParams['service_type']) {
                $query->whereRaw('id in (select app_id from t_app_service_app where service_type_id = ?)', [$requestParams['service_type']]);
            }
        })
            //->select('a.*', 'c.name as service_name')
            ->orderBy('sort')
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
        $folders = AppFolderManageModel::where('state', 0)->pluck('name', 'id')->toArray();
        $nextSortId = AppListModel::max('sort')+1;
        return view('admin.app_manage.create', [
            'category' => $this->appType,
            'folders' => json_encode($folders),
            'cardType' => $this->cardType,
            'nextSortId' => $nextSortId,
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
        $data['sort'] = $data['sort'] + 1;
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
        $folders = AppFolderManageModel::where('state', 0)->pluck('name', 'id')->toArray();
        $info = AppListModel::find($id);
        return view('admin.app_manage.edit', [
            'info' => $info,
            'category' => $this->appType,
            'folders' => json_encode($folders),
            'cardType' => $this->cardType,
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

    public function auth(Request $request)
    {
        $id = $request->get('id', null);
        if ($request->isMethod('post')) {
            $serviceType = $request->service_type ?: [];
            $appRoles = $request->app_role ?: [];
            $serviceIds = AppServiceModel::where('app_id', $id)
                ->where('state', 0)
                ->pluck('service_type_id')
                ->toArray();
            $approleIds = AppRoleModel::where('app_id', $id)
                ->where('state', 0)
                ->pluck('role_id')
                ->toArray();
            $diffTypeIds = array_diff($serviceIds, $serviceType); //待删除的
            $diffRoleIds = array_diff($approleIds, $appRoles);
            DB::beginTransaction();
            try {
                if ($serviceType) {
                    foreach ($serviceType as $type) {
                        AppServiceModel::updateOrCreate([
                            'app_id' => $id,
                            'service_type_id' => $type,
                            'state' => 0,
                        ], [
                            'app_id' => $id,
                            'service_type_id' => $type
                        ]);
                    }
                }

                if ($appRoles) {
                    foreach ($appRoles as $role_id) {
                        AppRoleModel::updateOrCreate([
                            'app_id' => $id,
                            'role_id' => $role_id,
                            'state' => 0,
                        ], [
                            'app_id' => $id,
                            'role_id' => $role_id,
                        ]);
                    }
                }

                if ($diffTypeIds) {
                    AppServiceModel::whereIn('service_type_id', $diffTypeIds)
                        ->where('app_id', $id)
                        ->delete();
                }

                if ($diffRoleIds) {
                    AppRoleModel::whereIn('role_id', $diffRoleIds)
                        ->where('app_id', $id)
                        ->delete();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->responseToJson([], '保存失败' . $e->getMessage(), 201);
            }

            return $this->responseToJson([], '保存成功');
        }
        $serviceType = ServiceTypeModel::where('state', 0)
            ->orderBy('disp_order', 'desc')
            ->select('id', 'parent_id as pid', 'name as title')
            ->get()
            ->toArray();
        $serviceType = PHPTree::toLayer($serviceType);
        $roles = RoleModel::get();
        $serviceTypeIds = AppServiceModel::where('app_id', $id)->where('state', 0)->pluck('service_type_id')->toArray();
        $appRoleIds = AppRoleModel::where('app_id', $id)->where('state', 0)->pluck('role_id')->toArray();
        return view('admin.app_manage.auth', [
            'serviceType' => $serviceType,
            'roles' => $roles,
            'id' => $id,
            'serviceTypeIds' => $serviceTypeIds,
            'appRoleIds' => $appRoleIds,
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'icon_url' => 'required',
            'name' => [
                'required',
                'max:30',
                //!is_null($id) ? Rule::unique('t_app_list')->ignore($id) : 'unique:t_app_list'
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
        $data['first_pinyin'] = strtoupper(substr(pinyin_abbr($data['name']), 0,1));
        unset($data['card_type']);
        return $data;
    }
}
