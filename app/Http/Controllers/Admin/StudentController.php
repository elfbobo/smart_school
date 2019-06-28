<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\StudentModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $search = $request->get('search', null);
        $perPage = $request->get('perpage', 20);
        $params = [];
        !is_null($search) ? $params['search'] = $search : null;
        $data = StudentModel::where(function ($query) {

        })
            ->select('avatar', 'union_id', 'name', 'gender', 'grade', 'dept_name', 'course_name', 'class_name', 'in_registry', 'in_school')
            ->paginate($perPage);
        return view('admin.student.index', [
            'params' => $params ? json_encode($params, 320) : '{}',
            'data' => $data,
            'pagelist' => $data->appends($params)->links(),
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
        return view('admin.student.create');
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
        $data = $request->all();
        StudentModel::create($data);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
                        $num++;
                    }
                }

                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count - $num) . '条', 200, false));
            });
        }
        return view('admin.import', [
            'export_url' => route('student.import'),
            'template' => 'student.xls'
        ]);
    }
}
