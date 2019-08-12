<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ClassModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DictModel;
use App\Models\Admin\ProfessionalModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\StudentModel;
use App\Models\Admin\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $params = $request->except('perpage');
        $perpage = $request->get('perpage', 20);
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
                        if ($column == 'search') {
                            $query->where('union_id', 'like', '%' . $value . '%')->orWhere('name', 'like', '%' . $value . '%');
                        } else {
                            $query->where($column, $value);
                        }
                    }
                }
            }
        })
            ->select('id','avatar', 'union_id', 'name', 'gender', 'grade', 'dept_name', 'course_name', 'class_name', 'in_registry', 'in_school')
            ->paginate($perpage);
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
                    "学号" => "union_id",
                    "姓名" => 'name',
                    "一卡通号" => "card_number",
                    "学籍号" => "school_roll_code",
                    "姓名拼音" => "name_py",
                    "曾用名" => "used_name",
                    "性别" => "gender",
                    "出生日期" => "birthday",
                    "证件类型" => "id_type",
                    "证件号码" => "id_card",
                    "民族" => "nation",
                    "政治面貌" => "politics_status",
                    "宗教信仰" => "religion",
                    "婚姻状况" => "marital_status",
                    "健康状况" => "health_status",
                    "来源国别" => "country",
                    "籍贯" => "birthplace",
                    "港澳台侨" => "gatq",
                    "户口性质" => "hkxz",
                    "户口所在地" => "hkszd",
                    "银行卡号" => "bank_card",
                    "户籍所在街镇名称" => "hjszjzmc",
                    "户籍所在居委会村委会名称" => "hjszcwhmc",
                    "血型" => "blood_type",
                    "身份标识" => "identity_type",
                    "身高cm" => "height",
                    "体重kg" => "weight",
                    "特长" => "specialty",
                    "学生类别" => "student_type",
                    "所在年级" => "grade",
                    "所在学部" => "dept_name",
                    "所在专业" => "course_name",
                    "所在班级" => "class_name",
                    "就读学历" => "education",
                    "学制" => "shooling_length",
                    "是否在籍" => "in_registry",
                    "是否在校" => "in_school",
                    "培养方式" => "train_type",
                    "入学日期" => "entry_date",
                    "何省市报考" => "enroll_province_city",
                    "生源地" => "student_source_place",
                    "考生号" => "candidate_number",
                    "准考证号" => "pass_number",
                    "入学前单位" => "entry_before_unit",
                    "入学年级" => "entry_grade",
                    "入学学部" => "entry_dept_name",
                    "入学专业" => "entry_course",
                    "入学方式" => "entry_type",
                    "学生来源" => "student_source",
                    "入学总成绩" => "total_score",
                    "预计毕业年份" => "graduate_year",
                    "实际毕业时间" => "graduate_date",
                    "毕业评语最多1000_字" => "graduate_comments",
                    "通讯地址" => "address",
                    "通讯邮编" => "zip_code",
                    "家庭地址" => "home_address",
                    "家庭邮编" => "home_zip_code",
                    "家庭电话" => "home_tel",
                    "个人邮箱" => "email",
                    "个人手机号" => "phone",
                    "联系电话" => "tel",
                    "qq" => "qq",
                    "msn" => "msn",
                    "个人主页" => "personal_home_page",
                    "备注最多300字" => "remark",
                ];

                $data = $reader->get()->toArray();
                $count = count($data);
                $num = 0;
                if ($data) {
                    foreach ($data as $key => $item) {
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
                        $num++;
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
