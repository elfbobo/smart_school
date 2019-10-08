<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ClassModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DictModel;
use App\Models\Admin\ProfessionalModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\StudentModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\UserRoleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends BaseController
{
    private $roleCode = 'student';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $params = $request->except(['perpage', 'page', 'search']);
        $perpage = $request->get('perpage', 20);
        $search = $request->get('search', null);
        if (!empty($params)) {
            foreach ($params as $column => $value) {
                if (!strlen($value)) {
                    unset($params[$column]);
                }
            }
        }

        $data = StudentModel::where(function ($query) use ($params) {
            if (!empty($params)) {
                foreach ($params as $column => $value) {
                    if (strlen($value)) {
                        $query->where($column, $value);
                    }
                }
            }
        })
            ->where(function ($query) use ($search) {
                if ($search) {
                    $query->where('union_id', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                }
            })
            ->select('id', 'avatar', 'union_id', 'name', 'gender', 'grade', 'dept_name', 'course_name', 'class_name', 'in_registry', 'in_school')
            ->orderBy('updated_at', 'desc')
            ->paginate($perpage);

        !is_null($search) ? $params['search'] = $search : null;
        return view('admin.student.index', [
            'params' => $params ? json_encode($params, 320) : '{}',
            'data' => $data,
            'pagelist' => $data->appends($params)->links(),
            'dept' => DepartmentModel::where('category', $this->category)->pluck('name', 'code')->toArray(),
            'class' => ClassModel::where('status', 1)->pluck('class_name', 'class_code')->toArray(),
            'course' => ProfessionalModel::orderBy('sort')->pluck('name', 'code')->toArray(),
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
        return view('admin.student.create', [
            'id_type' => DictModel::getData('id_type'),
            'nation' => DictModel::getData('nation'),
            'politics_status' => DictModel::getData('politics_status'),
            'religion' => DictModel::getData('religion'),
            'marital_status' => DictModel::getData('marital_status'),
            'health_status' => DictModel::getData('health_status'),
            'country' => DictModel::getData('country'),
            'gatq' => DictModel::getData('gatq'),
            'blood_type' => DictModel::getData('blood_type'),
            'identity_type' => DictModel::getData('identity_type'),
            'student_type' => DictModel::getData('student_type'),
            'birthplace' => RegionModel::where('type', 'area')->pluck('name', 'code')->toArray(),
            'dept' => DepartmentModel::where('category', $this->category)->pluck('name', 'code')->toArray(),
            'course' => ProfessionalModel::orderBy('sort')->pluck('name', 'code')->toArray(),
            'class' => ClassModel::where('status', 1)->pluck('class_name', 'class_code')->toArray(),
            'education' => DictModel::getData('education'),
            'train_type' => DictModel::getData('train_type'),
            'entry_type' => DictModel::getData('entry_type'),
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
        DB::beginTransaction();
        try {
            StudentModel::create($data);
            $account['code'] = $data['union_id'];
            $account['name'] = $data['name'];
            $account['type'] = 0;
            $account['password'] = $data['phone'] ? md5(substr($data['phone'], -6)) : md5('123456');
            UserModel::create($account);

            UserRoleModel::create([
                'user_code' => $data['union_id'],
                'role_id' => $this->getRoleId($this->roleCode),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '新增失败：【' . $e->getMessage() . '】', 201);
        }

        return $this->responseToJson(['url' => route('student.index')], '新增成功');
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
        $info = StudentModel::find($id);
        return view('admin.student.edit', [
            'info' => $info,
            'id_type' => DictModel::getData('id_type'),
            'nation' => DictModel::getData('nation'),
            'politics_status' => DictModel::getData('politics_status'),
            'religion' => DictModel::getData('religion'),
            'marital_status' => DictModel::getData('marital_status'),
            'health_status' => DictModel::getData('health_status'),
            'country' => DictModel::getData('country'),
            'gatq' => DictModel::getData('gatq'),
            'blood_type' => DictModel::getData('blood_type'),
            'identity_type' => DictModel::getData('identity_type'),
            'student_type' => DictModel::getData('student_type'),
            'birthplace' => RegionModel::where('type', 'area')->pluck('name', 'code')->toArray(),
            'dept' => DepartmentModel::where('category', $this->category)->pluck('name', 'code')->toArray(),
            'course' => ProfessionalModel::orderBy('sort')->pluck('name', 'code')->toArray(),
            'class' => ClassModel::where('status', 1)->pluck('class_name', 'class_code')->toArray(),
            'education' => DictModel::getData('education'),
            'train_type' => DictModel::getData('train_type'),
            'entry_type' => DictModel::getData('entry_type'),
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
            StudentModel::where('id', $id)->update($data);
            $account['code'] = $data['union_id'];
            $account['name'] = $data['name'];
            $account['type'] = 0;
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
            return $this->responseToJson([], '编辑失败：【' . $e->getMessage() . '】', 201);
        }

        return $this->responseToJson(['url' => route('student.index')], '编辑成功');
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
        $users = StudentModel::whereIn('id', $id)->pluck('union_id')->toArray();
        DB::beginTransaction();
        try {
            StudentModel::destroy($id);
            UserModel::whereIn('code', $users)->update(['state' => 2]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '删除失败：【' . $e->getMessage() . '】', 201);
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
                    "union_id",
                    'name',
                    "card_number",
                    "school_roll_code",
                    "name_py",
                    "used_name",
                    "gender",
                    "birthday",
                    "id_type",
                    "id_card",
                    "nation",
                    "politics_status",
                    "religion",
                    "marital_status",
                    "health_status",
                    "country",
                    "birthplace",
                    "gatq",
                    "hkxz",
                    "hkszd",
                    "bank_card",
                    "hjszjzmc",
                    "hjszcwhmc",
                    "blood_type",
                    "identity_type",
                    "height",
                    "weight",
                    "specialty",
                    "student_type",
                    "grade",
                    "dept_name",
                    "course_name",
                    "class_name",
                    "education",
                    "shooling_length",
                    "in_registry",
                    "in_school",
                    "train_type",
                    "entry_date",
                    "enroll_province_city",
                    "student_source_place",
                    "candidate_number",
                    "pass_number",
                    "entry_before_unit",
                    "entry_grade",
                    "entry_dept_name",
                    "entry_course",
                    "entry_type",
                    "student_source",
                    "total_score",
                    "graduate_year",
                    "graduate_date",
                    "graduate_comments",
                    "address",
                    "zip_code",
                    "home_address",
                    "home_zip_code",
                    "home_tel",
                    "email",
                    "phone",
                    "tel",
                    "qq",
                    "msn",
                    "personal_home_page",
                    "remark",
                ];

                $data = $reader->get()->toArray();
                $count = count($data)-1;
                $num = 0;
                if ($data) {
                    foreach ($data as $key => $item) {
                        if ($key == 0) {
                            continue;
                        }
                        DB::beginTransaction();
                        try {
                            foreach ($item as $k => $v) {
                                $item[$header[$k]] = $v;
                                unset($item[$k]);
                            }

                            if (!$item['union_id'] || !$item['name']) {
                                $errors[$key] = '第' . ($key + 1) . '行，数据不完整';
                                continue;
                            }

                            StudentModel::updateOrCreate([
                                'union_id' => $item['union_id'],
                            ], $item);

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
                            DB::rollBack();
                            $errors[$key] = '第' . ($key + 1) . '行，导入失败：' . $e->getMessage();
                        }
                    }
                }
                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count - $num) . '条', 200, false));
            });
        }

        $tips = '<li>学号和姓名为必填项</li>';
        return view('admin.import', [
            'export_url' => route('student.import'),
            'template' => 'student.xls',
            'tips' => $tips,
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'union_id' => [
                is_null($id) ? Rule::unique('t_sys_student') : Rule::unique('t_sys_student')->ignore($id),
            ],
        ];

        $msg = [
            'union_id.unique' => '学号已存在'
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            return $this->responseToJson([], $valid->errors()->first(), 201, false);
        }

        if ($data['id_type']) {
            $arr = $this->getCodeName($data['id_type']);
            $data['id_type_code'] = $arr['code'];
            $data['id_type'] = $arr['name'];
        }

        if ($data['nation']) {
            $arr = $this->getCodeName($data['nation']);
            $data['nation_code'] = $arr['code'];
            $data['nation'] = $arr['name'];
        }

        if ($data['politics_status']) {
            $arr = $this->getCodeName($data['politics_status']);
            $data['politics_status_code'] = $arr['code'];
            $data['politics_status'] = $arr['name'];
        }

        if ($data['religion']) {
            $arr = $this->getCodeName($data['religion']);
            $data['religion_code'] = $arr['code'];
            $data['religion'] = $arr['name'];
        }

        if ($data['marital_status']) {
            $arr = $this->getCodeName($data['marital_status']);
            $data['marital_status_code'] = $arr['code'];
            $data['marital_status'] = $arr['name'];
        }

        if ($data['health_status']) {
            $arr = $this->getCodeName($data['health_status']);
            $data['health_status_code'] = $arr['code'];
            $data['health_status'] = $arr['name'];
        }

        if ($data['country']) {
            $arr = $this->getCodeName($data['country']);
            $data['country_code'] = $arr['code'];
            $data['country'] = $arr['name'];
        }

        if ($data['gatq']) {
            $arr = $this->getCodeName($data['gatq']);
            $data['gatq_code'] = $arr['code'];
            $data['gatq'] = $arr['name'];
        }

        if ($data['blood_type']) {
            $arr = $this->getCodeName($data['blood_type']);
            $data['blood_type_code'] = $arr['code'];
            $data['blood_type'] = $arr['name'];
        }

        if ($data['identity_type']) {
            $arr = $this->getCodeName($data['identity_type']);
            $data['identity_type_code'] = $arr['code'];
            $data['identity_type'] = $arr['name'];
        }

        if ($data['student_type']) {
            $arr = $this->getCodeName($data['student_type']);
            $data['student_type_code'] = $arr['code'];
            $data['student_type'] = $arr['name'];
        }

        if ($data['dept_code']) {
            $arr = $this->getCodeName($data['dept_code']);
            $data['dept_code'] = $arr['code'];
            $data['dept_name'] = $arr['name'];
        }

        if ($data['course_code']) {
            $arr = $this->getCodeName($data['course_code']);
            $data['course_code'] = $arr['code'];
            $data['course_name'] = $arr['name'];
        }

        if ($data['class_code']) {
            $arr = $this->getCodeName($data['class_code']);
            $data['class_code'] = $arr['code'];
            $data['class_name'] = $arr['name'];
        }

        if ($data['education_code']) {
            $arr = $this->getCodeName($data['education_code']);
            $data['education_code'] = $arr['code'];
            $data['education'] = $arr['name'];
        }

        if ($data['train_type']) {
            $arr = $this->getCodeName($data['train_type']);
            $data['train_type_code'] = $arr['code'];
            $data['train_type'] = $arr['name'];
        }

        if ($data['entry_type']) {
            $arr = $this->getCodeName($data['entry_type']);
            $data['entry_type_code'] = $arr['code'];
            $data['entry_type'] = $arr['name'];
        }

        return $data;
    }
}
