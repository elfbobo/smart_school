<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\ProfessionalModel;
use App\Models\Admin\TeacherModel;
use App\Models\Admin\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ProfessionalController extends BaseController
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
        $data = ProfessionalModel::from('t_sys_professional as a')
            ->leftJoin('t_user_account as b', 'b.code', '=', 'a.leader_code')
            ->where(function ($query) use ( $search ) {
                if ($search) {
                    $query->where('a.code', 'like', '%' . $search . '%')
                        ->orWhere('a.name', 'like', '%' . $search . '%');
                }
            })
            ->select('a.*', 'b.name as leader')
            ->paginate($perPage);
        return view('admin.professional.index', [
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
        return view('admin.professional.create', [
            'users' => TeacherModel::pluck('name', 'union_id')->toArray(),
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
            ProfessionalModel::create($data);
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
        $info = ProfessionalModel::find($id);
        return view('admin.professional.edit', [
            'info' => $info,
            'users' => TeacherModel::pluck('name', 'union_id')->toArray(),
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
            ProfessionalModel::where('id', $id)->update($data);
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
            ProfessionalModel::destroy($id);
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
            Excel::load($file->getPathname(), function ($reader) {
                $header = [
                    "专业代码" => "code",
                    "专业名称" => "name",
                    "学制" => "schooling_length",
                    "专业负责人" => "leader_code",
                    "排序" => "sort",
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

                        if (!$item['code'] || !$item['name']) {
                            $errors[$key] = '第' . ($key+1) . '行，数据不完整';
                            continue;
                        }

                        if ($item['leader_code']) {
                            $user = UserModel::where('code', $item['leader_code'])
                                ->where('state', '<>', 2)
                                ->first();
                            if (!$user) {
                                $errors[$key] = '第' . ($key+1) . '行，负责人工号不存在';
                                continue;
                            }
                        }
                        $item['status'] = $item['status'] == '是' ? 1 : 0;

                        ProfessionalModel::updateOrCreate([
                            'code' => $item['code'],
                        ], $item);
                        $num++;
                    }
                }

                exit($this->responseToJson(['errors' => $errors ?? []], '本次导入成功' . $num . '条，导入失败' . ($count-$num) . '条', 200, false));
            });
        }
        return view('admin.import', [
            'export_url' => route('professional.import'),
            'template' => 'professional.xls'
        ]);
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'code' => [
                'regex:/^[a-z0-9]{1,}$/',
                is_null($id) ? Rule::unique('t_sys_professional') : Rule::unique('t_sys_professional')->ignore($id),
            ],
        ];
        $msg = [
            'code.regex' => '专业代码必须字母或数字组成',
            'code.unique' => '专业代码已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }
}
