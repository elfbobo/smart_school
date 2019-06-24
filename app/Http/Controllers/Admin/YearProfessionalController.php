<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\DepartmentModel;
use App\Models\Admin\ProfessionalModel;
use App\Models\Admin\YearProfessionalModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class YearProfessionalController extends BaseController
{
    private $category = '01';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //$search = $request->get('search', null);
        $params = [];
        $deptCode = $request->get('dept_code', null);
        $courseCode = $request->get('course_code', null);
        $grade = $request->get('grade', null);
        $perPage = $request->get('perpage', 20);
        !is_null($deptCode) ? $params['dept_code'] = $deptCode : null;
        !is_null($courseCode) ? $params['course_code'] = $courseCode : null;
        !is_null($grade) ? $params['grade'] = $grade : null;
        $data = YearProfessionalModel::where(function ($query) use ($deptCode, $courseCode, $grade) {
            if ($deptCode) {
                $query->where('dept_code', $deptCode);
            }

            if ($courseCode) {
                $query->where('course_code', $courseCode);
            }

            if ($grade) {
                $query->where('grade', $grade);
            }
        })
            ->paginate($perPage);
        return view('admin.pro-year.index', [
            'params' => $params ? json_encode($params, 320) : '{}',
            'data' => $data,
            'pagelist' => $data->appends($params)->links(),
            'dept' => DepartmentModel::where('category', $this->category)
                ->pluck('name', 'code')
                ->toArray(),
            'course' => ProfessionalModel::pluck('name', 'code')->toArray(),
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
            ->pluck('name', 'code')
            ->toArray();
        return view('admin.pro-year.create', [
            'dept' => $dept,
            'course' => ProfessionalModel::pluck('name', 'code')->toArray(),
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
            YearProfessionalModel::create($data);
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
        $info = YearProfessionalModel::find($id);
        $dept = DepartmentModel::where('category', $this->category)
            ->pluck('name', 'code')
            ->toArray();
        $info->dept_code = $info->dept_code . '|' . $info->dept_name;
        $info->course_code = $info->course_code . '|' . $info->course_name;
        return view('admin.pro-year.edit', [
            'dept' => $dept,
            'course' => ProfessionalModel::pluck('name', 'code')->toArray(),
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
            YearProfessionalModel::where('id', $id)->update($data);
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
            YearProfessionalModel::destroy($id);
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
                    "年级" => "grade",
                    "系部代码" => "dept_code",
                    "系部名称" => "dept_name",
                    "专业代码" => "course_code",
                    "专业名称" => "course_name",
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

                        if (!$item['grade'] || !$item['dept_code'] || !$item['course_code']) {
                            $errors[$key] = '第' . ($key+1) . '行，数据不完整';
                            continue;
                        }

                        $dept = DepartmentModel::where('code', $item['dept_code'])->first();
                        if (!$dept) {
                            $errors[$key] = '第' . ($key+1) . '行，系部代码不存在';
                            continue;
                        }
                        $item['dept_name'] = $dept->name;

                        $course = ProfessionalModel::where('code', $item['course_code'])->first();
                        if (!$course) {
                            $errors[$key] = '第' . ($key+1) . '行，专业代码不存在';
                            continue;
                        }
                        $item['course_name'] = $course->name;

                        YearProfessionalModel::updateOrCreate([
                            'dept_code' => $item['dept_code'],
                            'course_code' => $item['course_code'],
                            'grade' => $item['grade'],
                        ], $item);
                        $num++;
                    }
                }

                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count-$num) . '条', 200, false));
            });
        }
        return view('admin.import', [
            'export_url' => route('year-professional.import'),
            'template' => '[导入模板]学部年度专业信息.xls'
        ]);
    }

    private function validation($data, $id = null)
    {
        $arr = explode('|', $data['dept_code']);
        $data['dept_code'] = $arr[0];
        $data['dept_name'] = $arr[1];
        $arr = explode('|', $data['course_code']);
        $data['course_code'] = $arr[0];
        $data['course_name'] = $arr[1];
        $res = YearProfessionalModel::where('grade', $data['grade'])
            ->where('dept_code', $data['dept_code'])
            ->where('course_code', $data['course_code'])
            ->when($id, function ($query) use ( $id ) {
                $query->where('id', '<>', $id);
            })
            ->first();

        if ($res) {
            exit($this->responseToJson([], '专业已存在', 201, false));
        }

        return $data;
    }
}
