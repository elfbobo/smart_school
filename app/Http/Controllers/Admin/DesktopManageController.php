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
        $sub = DesktopAppModel::select('desktop_id', DB::raw('count(*) as total'))
            ->groupBy('desktop_id');
        $data = DesktopManageModel::from('t_desktop_list as a')
            ->leftJoin(DB::raw("({$sub->toSql()}) as b"), 'b.desktop_id', 'a.id')
            ->where('state', 0)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('name_eng', 'like', '%' . $search . '%');
            })
            ->select('a.*', 'b.total')
            ->orderBy('disp_order')
            ->get();
        return view('admin.desktop_manage.index', [
            'data' => $data,
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

    private function validation($data, $id = null)
    {
        $rule = [
            'name' => [
                'required',
                is_null($id) ? Rule::unique('t_desktop_list') : Rule::unique('t_desktop_list')->ignore($id),
            ],
        ];

        $msg = [
            'name.required' => '名称不能为空',
            'name.unique' => '名称已存在'
        ];

        $valid = Validator::make($rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201));
        }

        $data['app_ids'] = json_decode($data['app_ids'], true);
        return $data;
    }
}
