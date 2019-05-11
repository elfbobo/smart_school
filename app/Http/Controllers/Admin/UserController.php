<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ColumnModel;
use App\Models\Admin\RoleModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->get('perpage', config('perPage'));
        $search = $request->get('search', null);
        $data = UserModel::where('code', '<>', config('custom.super_manager'))
            ->where('state', 0)
            ->where(function($query) use ($search) {
                if ($search) {
                    $query->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                }
            })
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        !is_null($search) ? $params['search'] = $search : '';
        $perPage != config('perPage') ? $params['perpage'] = $perPage : '';
        return view('admin.user.index', [
            'data' => $data,
            'params' => isset($params) ? $params : [],
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
        return view('admin.user.create', [

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
        $data['id'] = $this->getUUID();
        try {
            UserModel::create($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '新增失败：【' . $e->getMessage() . '】');
        }
        return $this->responseToJson([], '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\UserModel  $userModel
     * @return \Illuminate\Http\Response
     */
    public function show(UserModel $userModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\UserModel  $userModel
     * @return \Illuminate\Http\Response
     */
    public function edit(UserModel $userModel, $id)
    {
        //
        $info = UserModel::findOrFail($id);
        return view('admin.user.edit', [
            'info' => $info,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\UserModel  $userModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserModel $userModel, $id)
    {
        //
        $data = $this->validation($request->all(), $id);

        try {
            UserModel::where('code', $id)->update($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '编辑失败：【' . $e->getMessage() .'】', 201);
        }
        return $this->responseToJson([], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\UserModel  $userModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserModel $userModel, Request $request)
    {
        //
        $id = $request->get('id');
        try{
            if (is_array($id)) {
                UserModel::whereIn('code', $id)->update(['state' => 2]);
            } else {
                UserModel::where('code', $id)->update(['state' => 2]);
            }
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败：【' . $e->getMessage() . '】', 201);
        }
        return $this->responseToJson([], '删除成功');
    }

    /**
     * 批量导入
     */
    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            $file = $request->file('file');
            Excel::load($file->getPathname(), function ($reader) {
                $reader = $reader->getSheet(0);
                $results = $reader->toArray();
                unset($results[0]);
                $errors = [];
                $num = 0;
                foreach ($results as $k => $result) {
                    if ($result[0] == '' || $result[1] == '' || $result[2] == '') {
                        $errors[$k] = '第' . $k . '行，账号或姓名或性别为空，请填写完整';
                        continue;
                    }

                    $id = $this->getUUID();
                    $saveData = [
                        'id' => $id,
                        'name' => trimall($result[1]),
                        'sex' => trimall($result[2]) == '男' ? 1 : 2,
                        'password' => md5('123456'),
                    ];

                    $code = trimall($result[0]);
                    if (!preg_match('/^\w{4,20}$/', $code)) {
                        $errors[$k] = '第' . $k . '行，账号不合法，必须是字母或数字组成4-20位';
                        continue;
                    }

                    $saveData['code'] = $code;
                    if ($phone = trimall($result[3])) {
                        if (!preg_match('/^1[34578]\d{9}$/', $phone)) {
                            $errors[$k] = '第' . $k . '行，手机号不合法，必须是有效的11位';
                            continue;
                        }
                        $saveData['phone'] = $phone;
                    }
                    if ($column = trimall($result[4])) {
                        $column_id = ColumnModel::where('title', $column)->value('id');
                        if ($column_id) {
                            $saveData['column_id'] = $column_id;
                        }
                    }
                    if ($role = trimall($result[5])) {
                        $role_id = RoleModel::where('name', $role)->value('id');
                        if ($role_id) {
                            $userRole = [
                              'user_id' => $id,
                              'role_id' => $role_id,
                            ];
                        }
                    }
                    try {
                        if ($pk = UserModel::where('code', $code)->where('state', 'A')->value('id')) {
                            unset($saveData['id']);
                            unset($saveData['password']);
                            UserModel::where('id', $pk)->update($saveData);
                        } else {
                            UserModel::create($saveData);
                        }
                        if (isset($userRole)) {
                            if ($pk) {
                                $userRole['user_id'] = $pk;
                            }
                            UserRoleModel::updateOrCreate([
                                'user_id' => $userRole['user_id'],
                                'role_id' => $userRole['role_id'],
                            ], $userRole);
                        }
                        $num ++;
                    } catch (\Exception $e) {
                        $errors[$k] = '第' . $k . '行，导入失败：【' . $e->getMessage() . '】';
                        continue;
                    }
                }
                exit($this->responseToJson(['errors' => $errors], '导入成功' . $num . '条，导入失败' . (count($results)-$num). '条.', 200, false));
            });
        }
        $template = 'user.xlsx';
        $tips = '<li>所属栏目、角色必须和系统里保持一致，不能有任何偏差（此两项非必填项）</li>';
        return view('admin.common.import', [
            'template' => $template,
            'tips' => $tips,
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'code' => [
                'required',
                'regex:/^\w{4,20}$/',
                $id ? Rule::unique('t_users')->ignore($id) : 'unique:t_users',
            ],
            'name' => 'required',
            'password' => 'nullable|regex:/^\w{6,18}$/',
            'phone' => [
                'nullable',
                'regex:/^1[34578]\d{9}/',
            ],
            'telephone' => [
                'nullable',
                'regex:/(^(\d{3,4}-)?\d{7,8})$|(13[0-9]{9})/'
            ]
        ];

        $msg = [
            'code.required' => '人员编号不能为空',
            'code.unique' => '人员编号已存在',
            'code.regex' => '人员编号必须是字母或数字组成，6-20位',
            'name.required' => '姓名不能为空',
            'password.regex' => '密码必须是字母或数字或特殊字符组成，6-18位',
            'phone.regex' => '手机号必须是合法的11位数字',
            'telephone.regex' => '固话格式不正确',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        if ($data['password'] != '') {
            $data['password'] = md5($data['password']);
        } else {
            if ($id == null) {
                $data['password'] = md5('123456');
            } else {
                unset($data['password']);
            }
        }
        return $data;
    }
}
