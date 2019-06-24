<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ClassModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\ProfessionalModel;
use Illuminate\Http\Request;
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
        $params = [];
        !is_null($search) ? $params['search'] = $search : null;
        $data = ClassModel::from('t_sys_class as a')
            //->leftJoin('t_user_account as b', 'b.code', '=', 'a.leader_code')
            ->where(function ($query) use ( $search ) {
                if ($search) {
                    $query->where('a.code', 'like', '%' . $search . '%')
                        ->orWhere('a.name', 'like', '%' . $search . '%');
                }
            })
            ->select('a.*')
            ->paginate($perPage);
        return view('admin.class.index', [
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
}
