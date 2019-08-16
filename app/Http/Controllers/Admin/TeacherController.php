<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DictModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\TeacherModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\UserRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends BaseController
{
    private $roleCode = 'teacher';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $gender = $request->get('gender', null);
        $status = $request->get('status', null);
        $is_prepare = $request->get('is_prepare', null);
        $dept_id = $request->get('dept_id', null);
        $search = $request->get('search', null);
        $perPage = $request->get('perpage', 20);
        $params = [];
        !is_null($gender) ? $params['gender'] = $gender : null;
        !is_null($status) ? $params['status'] = $status : null;
        !is_null($is_prepare) ? $params['is_prepare'] = $is_prepare : null;
        !is_null($dept_id) ? $params['dept_id'] = $dept_id : null;
        !is_null($search) ? $params['search'] = $search : null;

        $data = TeacherModel::from('t_sys_teacher as a')
            ->leftJoin('t_department as b', 'b.id', '=', 'a.dept_id')
            ->where(function ($query) use ($params) {
                isset($params['search']) ?
                    $query->where('union_id', 'like', '%' . $params['search'] . '%')
                        ->orWhere('a.name', 'like', '%' . $params['search'] . '%')
                    : null;
                isset($params['gender']) ? $query->where('gender', $params['gender']) : null;
                isset($params['status']) ? $query->where('a.status', $params['status']) : null;
                isset($params['is_prepare']) ? $query->where('is_prepare', $params['is_prepare']) : null;
                isset($params['dept_id']) ? $query->where('a.dept_id', $params['dept_id']) : null;

            })
            ->select('a.*', 'b.name as dept_name')
            ->orderBy('a.updated_at', 'desc')
            ->paginate($perPage);
        return view('admin.teacher.index', [
            'data' => $data,
            'buildParams' => json_encode($params, 320),
            'params' => $params,
            'dept' => DepartmentModel::orderBy('sort')->pluck('name', 'id'),
            'status' => DictModel::getData('current_status'),
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
        $dept = DepartmentModel::orderBy('sort')
            ->select('id', 'parent_id', 'name')
            ->get()
            ->toArray();
        PHPTree::config(['pid' => 'parent_id', 'title' => 'name']);
        $dept = PHPTree::toList($dept);
        $region = RegionModel::where('type', 'area')
            ->pluck('name', 'code')
            ->toArray();
        return view('admin.teacher.create', [
            'dept' => $dept,
            'politics_status' => DictModel::getData('politics_status'),
            'id_type' => DictModel::getData('id_type'),
            'degree' => DictModel::getData('degree'),
            'education' => DictModel::getData('education'),
            'titles' => DictModel::getData('titles'),
            'qualification' => DictModel::getData('qualification'),
            'status' => DictModel::getData('current_status'),
            'region' => $region,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $this->validation($request->all());
        $data['id'] = $this->getUUID();
        DB::beginTransaction();
        try {
            TeacherModel::create($data);
            $account['code'] = $data['union_id'];
            $account['name'] = $data['name'];
            $account['type'] = 1;
            $account['password'] = $data['phone'] ? md5(substr($data['phone'], -6)) : md5('123456');
            UserModel::create($account);

            UserRoleModel::create([
                'user_code' => $data['union_id'],
                'role_id' => $this->getRoleId($this->roleCode),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '新增失败' . $e->getMessage(), 201);
        }

        return $this->responseToJson(['url' => route('teacher.index')], '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $info = TeacherModel::find($id);

        $dept = DepartmentModel::orderBy('sort')
            ->select('id', 'parent_id', 'name')
            ->get()
            ->toArray();
        PHPTree::config(['pid' => 'parent_id', 'title' => 'name']);
        $dept = PHPTree::toList($dept);
        $region = RegionModel::where('type', 'area')
            ->pluck('name', 'code')
            ->toArray();
        return view('admin.teacher.edit', [
            'dept' => $dept,
            'politics_status' => DictModel::getData('politics_status'),
            'id_type' => DictModel::getData('id_type'),
            'degree' => DictModel::getData('degree'),
            'education' => DictModel::getData('education'),
            'titles' => DictModel::getData('titles'),
            'qualification' => DictModel::getData('qualification'),
            'status' => DictModel::getData('current_status'),
            'region' => $region,
            'info' => $info,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $this->validation($request->all(), $id);
        DB::beginTransaction();
        try {
            TeacherModel::where('id', $id)->update($data);
            $account['code'] = $data['union_id'];
            $account['name'] = $data['name'];
            $account['type'] = 1;
            $account['password'] = $data['phone'] ? md5(substr($data['phone'], -6)) : md5('123456');

            $user = UserModel::where('code', $data['union_id'])->where('state', '<>', 2)->first();
            if ($user) {
                UserModel::where('code', $user->code)->update(['name' => $data['name']]);
            } else {
                UserModel::create($account);
            }

            UserRoleModel::updateOrCreate([
                'user_code' => $data['union_id'],
                'role_id' => $this->getRoleId($this->roleCode),
            ], [
                'user_code' => $data['union_id'],
                'role_id' => $this->getRoleId($this->roleCode),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '编辑失败' . $e->getMessage(), 201);
        }

        return $this->responseToJson(['url' => route('teacher.index')], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        if ($id == 'delete') {
            $id = $request->get('id');
        }
        $id = is_string($id) ? [$id] : $id;

        $users = TeacherModel::whereIn('id', $id)->pluck('union_id')->toArray();
        DB::beginTransaction();
        try {
            TeacherModel::destroy($id);
            UserModel::whereIn('code', $users)->update(['state' => 2]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '删除失败', 201);
        }

        return $this->responseToJson([], '删除成功');
    }

    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            set_time_limit(0);
            $file = $request->file('files');
            Excel::selectSheetsByIndex(0)->load($file->getPathname(), function ($reader) {
                $header = [
                    "职工号" => "union_id",
                    "姓名" => "name",
                    "性别" => "gender",
                    "手机号" => "phone",
                    "出生日期" => "birthday",
                    "籍贯" => "native_place",
                    "当前状态" => "status_desc",
                    "编制属性" => "is_prepare",
                    "所属部门学部" => "dept_id",
                    "所属二级部门科室" => "dept_sec_id",
                    "政治面貌" => "politics_status_desc",
                    "任职资格名称" => "qualification_desc",
                    "职称级别" => "titles_desc",
                    "最高学历" => "education_desc",
                    "最高学位" => "degree_desc",
                    "身份证件类型" => "id_type_desc",
                    "证件号码" => "id_card",
                ];

                $data = $reader->get()->toArray();
                $count = count($data);
                $num = 0;
                if ($data) {
                    $dic = $this->getDic();
                    foreach ($data as $key => $item) {
                        DB::beginTransaction();
                        try {
                            foreach ($item as $k => $v) {
                                $item[$header[$k]] = $v;
                                unset($item[$k]);
                            }

                            if (!$item['union_id'] || !$item['name'] || !$item['gender'] || !$item['phone']) {
                                $errors[$key] = '第' . ($key + 1) . '行，数据不完整！';
                                continue;
                            }

                            if ($item['dept_id']) {
                                $dept = DepartmentModel::where('name', $item['dept_id'])->first();
                                if ($dept) {
                                    $item['dept_id'] = $dept->id;
                                }
                            }

                            if ($item['dept_sec_id']) {
                                $dept = DepartmentModel::where('name', $item['dept_sec_id'])->first();
                                if ($dept) {
                                    $item['dept_sec_id'] = $dept->id;
                                }
                            }


                            $item['gender'] = $item['gender'] == '男' ? 1 : 2;
                            $item['is_prepare'] = $item['is_prepare'] == '在编' ? 1 : 0;
                            $status = $this->getCode($dic, 'current_status', $item['status_desc']);
                            $status ? $item['status'] = $status : null;
                            $politics_status = $this->getCode($dic, 'politics_status', $item['politics_status_desc']);
                            $politics_status ? $item['politics_status'] = $politics_status : null;

                            $qualification = $this->getCode($dic, 'qualification', $item['qualification_desc']);
                            $qualification ? $item['qualification'] = $qualification : null;

                            $titles = $this->getCode($dic, 'titles', $item['titles_desc']);
                            $titles ? $item['titles'] = $titles : null;

                            $education = $this->getCode($dic, 'education', $item['education_desc']);
                            $education ? $item['education'] = $education : null;

                            $degree = $this->getCode($dic, 'degree', $item['degree_desc']);
                            $degree ? $item['degree'] = $degree : null;

                            $id_type = $this->getCode($dic, 'id_type', $item['id_type_desc']);
                            $id_type ? $item['id_type'] = $id_type : null;
                            $item['id'] = $this->getUUID();
                            TeacherModel::updateOrCreate([
                                'union_id' => $item['union_id'],
                            ], $item);

                            //写入账号表
                            $user = UserModel::where('code', $item['union_id'])->where('state', '<>', 2)->first();
                            if ($user) {
                                UserModel::where('code', $user->code)->update(['name' => $item['name']]);
                            } else {
                                $account = [
                                    'code' => $item['union_id'],
                                    'name' => $item['name'],
                                    'type' => 1,
                                    'password' => $item['phone'] ? md5(substr($item['phone'], -6)) : md5('123456')
                                ];
                                UserModel::create($account);
                            }

                            //加入教师角色关联表
                            UserRoleModel::updateOrCreate([
                                'user_code' => $item['union_id'],
                                'role_id' => $this->getRoleId($this->roleCode),
                            ], [
                                'user_code' => $item['union_id'],
                                'role_id' => $this->getRoleId($this->roleCode),
                            ]);
                            $num++;
                            DB::commit();
                        } catch (\Exception $e) {
                            $errors[$key] = '第' . ($key + 1) . '行，' . $e->getMessage();
                            DB::rollBack();
                        }
                    }
                }

                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count - $num) . '条', 200, false));
            });
        }

        $tips = '<li>职工号|姓名|性别|手机号均为必填项</li>';
        $tips .= '<li>职工号初始密码为手机号后6位</li>';
        return view('admin.import', [
            'export_url' => route('teacher.import'),
            'template' => 'teacher.xls',
            'tips' => $tips,
        ]);
    }

    private function getDic()
    {
        $data = DictModel::where('category', 'status')
            ->orWhere('category', 'politics_status')
            ->orWhere('category', 'qualification')
            ->orWhere('category', 'titles')
            ->orWhere('category', 'education')
            ->orWhere('category', 'degree')
            ->orWhere('category', 'id_type')
            ->select('category', 'code', 'name')
            ->get()
            ->toArray();

        $newData = [];
        if ($data) {
            foreach ($data as $item) {
                $newData[$item['category']] = array_merge($newData[$item['category']] ?? [], [
                    $item['name'] => $item['code'],
                ]);
            }
        }
        return $newData;
    }

    private function getCode($data, $cate, $name)
    {
        return $data[$cate][$name] ?? null;
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'union_id' => [
                'regex:/^[a-z0-9]{4,}$/',
                is_null($id) ? Rule::unique('t_sys_teacher') : Rule::unique('t_sys_teacher')->ignore($id),
            ],
            'phone' => [
                'nullable',
                'regex:/^1[3456789]\d{9}$/',
                is_null($id) ? Rule::unique('t_sys_teacher') : Rule::unique('t_sys_teacher')->ignore($id),
            ],
            'id_card' => [
                'nullable',
                'regex:/^[a-z0-9]{6,}$/',
                is_null($id) ? Rule::unique('t_sys_teacher') : Rule::unique('t_sys_teacher')->ignore($id),
            ],
        ];

        $msg = [
            'union_id.regex' => '职工号必须是字母或数字组成',
            'union_id.unique' => '职工号已存在',
            'phone.regex' => '手机号格式不正确',
            'phone.unique' => '手机号已存在',
            'id_card.regex' => '证件号必须是字母或数字组成',
            'id_card.unique' => '证件号码已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        if ($data['politics_status']) {
            $arr = explode('|', $data['politics_status']);
            $data['politics_status'] = $arr[0];
            $data['politics_status_desc'] = $arr[1];
        }

        if ($data['id_type']) {
            $arr = explode('|', $data['id_type']);
            $data['id_type'] = $arr[0];
            $data['id_type_desc'] = $arr[1];
        }

        if ($data['degree']) {
            $arr = explode('|', $data['degree']);
            $data['degree'] = $arr[0];
            $data['degree_desc'] = $arr[1];
        }

        if ($data['education']) {
            $arr = explode('|', $data['education']);
            $data['education'] = $arr[0];
            $data['education_desc'] = $arr[1];
        }

        if ($data['titles']) {
            $arr = explode('|', $data['titles']);
            $data['titles'] = $arr[0];
            $data['titles_desc'] = $arr[1];
        }

        if ($data['qualification']) {
            $arr = explode('|', $data['qualification']);
            $data['qualification'] = $arr[0];
            $data['qualification_desc'] = $arr[1];
        }

        if ($data['status']) {
            $arr = explode('|', $data['status']);
            $data['status'] = $arr[0];
            $data['status_desc'] = $arr[1];
        }

        return $data;
    }
}
