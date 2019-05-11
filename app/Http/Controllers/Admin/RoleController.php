<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\LogModel;
use App\Models\Admin\PermissionRoleModel;
use App\Models\Admin\RoleModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->get('perpage', config('custom.perPage'));
        $data = RoleModel::paginate($perPage);
        return view('admin.role.index', [
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
        $users = UserModel::where('state', 0)
            ->where('code', '<>', config('custom.super_manager'))
            ->get();
        return view('admin.role.create', [
            'users' => $users,
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
            $res = RoleModel::create($data);
            if (isset($data['users'])) {
                $users = [];
                foreach ($data['users'] as $user) {
                    $users[] = [
                        'user_code' => $user,
                        'role_id' => $res->id,
                    ];
                }
                UserRoleModel::insert($users);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '新增失败：【' . $e->getMessage() . '】', 201);
        }
        LogModel::writeLog('添加角色：【' . $data['name'] . '】', 1);
        return $this->responseToJson([], '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\RoleModel  $roleModel
     * @return \Illuminate\Http\Response
     */
    public function show(RoleModel $roleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\RoleModel  $roleModel
     * @return \Illuminate\Http\Response
     */
    public function edit(RoleModel $roleModel, $id)
    {
        //
        $users = UserModel::from('t_user_account as a')
            //->leftJoin('t_user_role as b', 'b.user_id', '=', 'a.id')
            ->join('t_user_role as b', function($join) use ($id) {
                $join->on('b.user_code', '=', 'a.code')
                    ->where('b.role_id', $id);
            }, '', '', 'left')
            ->where('a.state', 0)
            ->where('a.code', '<>', config('custom.super_manager'))
            ->select('a.*', 'b.user_code')
            ->get();
        $info = RoleModel::findOrFail($id);
        return view('admin.role.edit', [
            'info' => $info,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\RoleModel  $roleModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoleModel $roleModel, $id)
    {
        //
        $data = $this->validation($request->all(), $id);
        DB::beginTransaction();
        try{
            UserRoleModel::where('role_id', $id)->delete();
            if (isset($data['users'])) {
                $users = [];
                foreach ($data['users'] as $user) {
                    $users[] = [
                        'user_code' => $user,
                        'role_id' => $id
                    ];
                }
                UserRoleModel::insert($users);
                unset($data['users']);
            }
            RoleModel::where('id', $id)->update($data);
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '编辑失败：【' . $e->getMessage() . '】', 201);
        }
        LogModel::writeLog('修改角色：【' . $data['name'] . '】', 2);
        return $this->responseToJson([], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\RoleModel  $roleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleModel $roleModel, Request $request)
    {
        //
        $id = $request->get('id');
        if (is_array($id)) { //如果是批量删除，过滤系统角色
            $ids = RoleModel::where('is_sys_role', 1)
                ->pluck('id')
                ->toArray();
            $id = array_diff($id, $ids);
        }

        try{
            RoleModel::destroy($id);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败：【' . $e->getMessage() . '】', 201);
        }
        LogModel::writeLog('删除角色', 3);
        return $this->responseToJson([], '删除成功');
    }

    /**
     * 分配节点权限
     */
    public function auth(Request $request)
    {
        $role_id = $request->get('role_id');
        if ($request->isMethod('post')) {
            $ids = $request->get('ids');
            $saveAll = [];
            foreach ($ids as $id) {
                $saveAll[] = [
                    'role_id' => $role_id,
                    'menu_id' => $id,
                ];
            }
            DB::beginTransaction();
            try {
                PermissionRoleModel::where('role_id', $role_id)->delete(); //先删除
                PermissionRoleModel::insert($saveAll);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->responseToJson([], '操作失败：【' . $e->getMessage() . '】', 201);
            }
            return $this->responseToJson([], '操作成功');
        }
        return view('admin.role.auth', [
            'role_id' => $role_id,
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'name' => [
                'required',
                'max:20',
                $id ? Rule::unique('t_sys_role')->ignore($id) : 'unique:t_sys_role',
            ],
            'description' => 'nullable|max:200'
        ];

        $msg = [
            'name.required' => '角色名称不能为空',
            'name.max' => '角色名称不能超过20个字',
            'name.unique' => '角色名称必须唯一',
            'description.max' => '角色描述不能超过200字',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }
        return $data;
    }
}
