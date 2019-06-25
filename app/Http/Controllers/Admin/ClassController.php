<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ClassModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\ProfessionalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ClassController extends BaseController
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
        $grade = $request->get('grade', null);
        $deptCode = $request->get('dept_code', null);
        $courseCode = $request->get('course_code', null);
        $inSchool = $request->get('in_school', null);
        $params = [];
        !is_null($search) ? $params['search'] = $search : null;
        !is_null($grade) ? $params['grade'] = $grade : null;
        !is_null($deptCode) ? $params['dept_code'] = $deptCode : null;
        !is_null($courseCode) ? $params['course_code'] = $courseCode : null;
        !is_null($inSchool) ? $params['in_school'] = $inSchool : null;
        $data = ClassModel::from('t_sys_class as a')
            //->leftJoin('t_user_account as b', 'b.code', '=', 'a.leader_code')
            ->where(function ($query) use ( $search,$grade,$deptCode,$courseCode,$inSchool ) {
                if ($search) {
                    $query->where('a.code', 'like', '%' . $search . '%')
                        ->orWhere('a.name', 'like', '%' . $search . '%');
                }

                if ($grade) {
                    $query->where('grade', $grade);
                }

                if ($deptCode) {
                    $query->where('dept_code', $deptCode);
                }

                if ($courseCode) {
                    $query->where('course_code', $courseCode);
                }

                if (!is_null($inSchool)) {
                    $query->where('in_school', $inSchool);
                }
            })
            ->select('a.*')
            ->paginate($perPage);
        return view('admin.class.index', [
            'params' => $params ? json_encode($params, 320) : '{}',
            'data' => $data,
            'pagelist' => $data->appends($params)->links(),
            'dept' => DepartmentModel::where('category', $this->category)
                ->pluck('name', 'code')->toArray(),
            'course' => $course = ProfessionalModel::pluck('name', 'code')->toArray(),
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
        $dept = DepartmentModel::where('category', $this->category)
            ->pluck('name', 'code')->toArray();

        $course = ProfessionalModel::pluck('name', 'code')->toArray();
        return view('admin.class.create', [
            'dept' => $dept,
            'course' => $course,
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
        try {
            ClassModel::create($data);
        } catch (\Exception $e) {
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
        $info = ClassModel::find($id);
        $dept = DepartmentModel::where('category', $this->category)
            ->pluck('name', 'code')->toArray();

        $course = ProfessionalModel::pluck('name', 'code')->toArray();
        return view('admin.class.edit', [
            'dept' => $dept,
            'course' => $course,
            'info' => $info,
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
        try {
            ClassModel::where('id', $id)->update($data);
        } catch (\Exception $e) {
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
    public function destroy(Request $request, $id)
    {
        //
        if ($id == 'delete') {
            $id = $request->get('id');
        }

        try {
            ClassModel::destroy($id);
        } catch (\Exception $e) {
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
                    "班级代码" => "class_code",
                    "班级名称" => "class_name",
                    "系部代码" => "dept_code",
                    "系部名称" => "dept_name",
                    "专业代码" => "course_code",
                    "专业名称" => "course_name",
                    "所属年级" => "grade",
                    "是否在校" => "in_school",
                    "是否使用" => "status",
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

                        if (!$item['class_code'] || !$item['class_name'] || !$item['grade']) {
                            $errors[$key] = '第' . ($key+1) . '行，数据不完整';
                            continue;
                        }

                        if ($item['dept_code']) {
                            $dept = DepartmentModel::where('code', $item['dept_code'])
                                ->first();
                            if (!$dept) {
                                $errors[$key] = '第' . ($key+1) . '行，系部代码不存在';
                                continue;
                            }
                            $item['dept_name'] = $dept->name;
                        }

                        if ($item['course_code']) {
                            $course = ProfessionalModel::where('code', $item['course_code'])
                                ->first();
                            if (!$course) {
                                $errors[$key] = '第' . ($key+1) . '行，专业代码不存在';
                                continue;
                            }
                            $item['course_name'] = $course->name;
                        }
                        $item['status'] = $item['status'] == '是' ? 1 : 0;
                        $item['in_school'] = $item['in_school'] == '是' ? 1 : 0;

                        ClassModel::updateOrCreate([
                            'class_code' => $item['class_code'],
                            'grade' => $item['grade'],
                        ], $item);
                        $num++;
                    }
                }

                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count-$num) . '条', 200, false));
            });
        }
        return view('admin.import', [
            'export_url' => route('class.import'),
            'template' => 'class.xls'
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'class_code' => [
                'regex:/^[a-z0-9]{2,}$/',
                is_null($id) ? Rule::unique('t_sys_class') : Rule::unique('t_sys_class')->ignore($id),
            ],
        ];

        $msg = [
            'class_code.regex' => '班级代码必须是字母或数字组成',
            'class_code.unique' => '班级代码已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        $arr = explode('|', $data['dept_code']);
        $data['dept_code'] = $arr[0];
        $data['dept_name'] = $arr[1];

        $arr = explode('|', $data['course_code']);
        $data['course_code'] = $arr[0];
        $data['course_name'] = $arr[1];
        return $data;
    }
}
