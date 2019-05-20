<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AppListModel;
use App\Models\Admin\DesktopAppModel;
use App\Models\Admin\DesktopManageModel;
use App\Models\Admin\DesktopRoleModel;
use App\Models\Admin\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DesktopManageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        $cards = DesktopAppModel::select('desktop_id', DB::raw('count(*) as total'))
            ->groupBy('desktop_id')
            ->get()->toArray();
        if ($cards) {
            $cards = array_column($cards, 'total', 'desktop_id');
        }

        $data = DesktopManageModel::where('state', 0)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('name_eng', 'like', '%' . $search . '%');
            })
            ->orderBy('disp_order')
            ->get();
        return view('admin.desktop_manage.index', [
            'data' => $data,
            'cards' => $cards,
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
        $roles = RoleModel::where('state', 0)
            ->pluck('name', 'id')->toArray();
        return view('admin.desktop_manage.create', [
            'roles' => $roles,
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
        $data = $this->validation($request->all());
        DB::beginTransaction();
        try {
            $desktop_id = $this->getUUID();
            $desktopList['name'] = $data['name'];
            $desktopList['name_eng'] = $data['name_eng'];
            $desktopList['id'] = $desktop_id;
            DesktopManageModel::create($desktopList);

            $desktopApp = [];
            if ($data['app_ids']) {
                foreach ($data['app_ids'] as $k => $app_id) {
                    $desktopApp[] = [
                        'app_id' => $app_id,
                        'desktop_id' => $desktop_id,
                        'disp_order' => ($k+1),
                    ];
                }
                $deskApp = new DesktopAppModel();
                $deskApp->insert($desktopApp);
            }

            $desktopRole = [];
            if ($data['role_ids']) {
                foreach ($data['role_ids'] as $k => $role_id) {
                    $desktopRole[] = [
                        'role_id' => $role_id,
                        'desktop_id' => $desktop_id,
                    ];
                }

                $deskRole = new DesktopRoleModel();
                $deskRole->insert($desktopRole);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '新增失败' . $e->getMessage(), 201);
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
        $info = DesktopManageModel::find($id);
        $roles = RoleModel::where('state', 0)
            ->pluck('name', 'id')->toArray();
        $deskRoles = DesktopRoleModel::where('desktop_id', $id)->pluck('role_id')->toArray();
        $deskApps = DesktopAppModel::from('t_sys_desktop_app as a')
            ->join('t_app_list as b', 'b.id', '=', 'a.app_id')
            ->where('desktop_id', $id)
            ->orderBy('a.disp_order')
            ->pluck('b.name', 'app_id')->toArray();
        $selectApp = [];
        if ($deskApps) {
            foreach ($deskApps as $k => $v) {
                $selectApp[] = [
                    'app_id' => (string)$k,
                    'app_name' => $v,
                ];
            }
        }

        return view('admin.desktop_manage.edit', [
            'info' => $info,
            'roles' => $roles,
            'deskRoles' => $deskRoles,
            'deskApps' => $deskApps,
            'selectApp' => json_encode($selectApp, 320)
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
        $data = $this->validation($request->all(), $id);
        DB::beginTransaction();
        try {
            $deskTop['name'] = $data['name'];
            $deskTop['name_eng'] = $data['name_eng'];
            DesktopManageModel::where('id', $id)->update($deskTop);

            $deskAppIds = DesktopAppModel::where('desktop_id', $id)->pluck('app_id')->toArray();
            $delAppIds = array_diff($deskAppIds, $data['app_ids']);
            if ($delAppIds) {
                DesktopAppModel::whereIn('app_id', $delAppIds)->where('desktop_id', $id)->delete();
            }
            if ($data['app_ids']) {
                foreach ($data['app_ids'] as $k => $app_id) {
                    DesktopAppModel::updateOrCreate([
                        'app_id' => $app_id,
                        'desktop_id' => $id,
                    ], [
                        'app_id' => $app_id,
                        'desktop_id' => $id,
                        'disp_order' => ($k+1)
                    ]);
                }
            }

            $deskRoleIds = DesktopRoleModel::where('desktop_id', $id)->pluck('role_id')->toArray();
            $delRoleIds = array_diff($deskRoleIds, $data['role_ids']);
            $addRoleIds = array_diff($data['role_ids'], $deskRoleIds);
            if ($delRoleIds) {
                DesktopRoleModel::whereIn('role_id', $delRoleIds)->where('desktop_id', $id)->delete();
            }

            if ($addRoleIds) {
                foreach ($addRoleIds as $addRoleId) {
                    $saveRoles[] = [
                        'role_id' => $addRoleId,
                        'desktop_id' => $id,
                    ];
                }
                DesktopRoleModel::insert($saveRoles);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '编辑失败' . $e->getMessage(), 201);
        }
        return $this->responseToJson([], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            DesktopManageModel::where('id', $id)->update(['state' => 1]);
            DesktopAppModel::where('desktop_id', $id)->delete();
            DesktopRoleModel::where('desktop_id', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '删除失败', 201);
        }
        return $this->responseToJson([], '删除成功');
    }

    public function getApplist(Request $request)
    {
        $apps = AppListModel::select('id', 'name')->get();
        return view('admin.desktop_manage.applist', [
            'apps' => $apps,
        ]);
    }

    //保存排序
    public function dispOrder(Request $request)
    {
        if ($request->app_ids) {
            $data = [];
            foreach ($request->app_ids as $k => $app_id) {
                $data[] = [
                    'id' => $app_id,
                    'disp_order' => ($k+1)
                ];
            }
            $res = $this->updateBatch('t_sys_desktop_list', $data);
            if ($res) {
                return $this->responseToJson([],'保存成功');
            }
        }
        return $this->responseToJson([], '保存失败', 201);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'name' => [
                'required',
                is_null($id) ? Rule::unique('t_sys_desktop_list') : Rule::unique('t_sys_desktop_list')->ignore($id),
            ],
        ];

        $msg = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已存在'
        ];

        $valid = Validator::make($rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        $data['app_ids'] = json_decode($data['app_ids'], true);
        return $data;
    }
}
