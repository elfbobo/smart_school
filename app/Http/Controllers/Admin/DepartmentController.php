<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DeptBBModel;
use App\Models\Admin\DeptCateModel;
use App\Models\Admin\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends BaseController
{
    private $tab = 'dept';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DepartmentModel::from('t_department as a')
            ->leftJoin('t_department_category as b', 'b.code', '=', 'a.category')
            ->select('a.id', 'a.parent_id', 'a.code', 'a.name as title', 'b.name as dept_name')
            ->orderBy('a.sort')
            ->get()
            ->toArray();
        if ($data) {
            foreach ($data as $k => $item) {
                $data[$k]['title'] = '[' . $item['dept_name'] . ']' . '[' . $item['code'] . ']'  . $item['title'];
            }
        }
        $tree = new PHPTree(['pid' => 'parent_id']);
        $dpts = $tree::toList($data);
        $category = DeptCateModel::orderBy('sort')->get();
        $bb = DeptBBModel::orderBy('sort')->get();
        return view('admin.department.index', [
            'dpts' => $dpts,
            'data' => $data ? json_encode(getZTreeData($data, ['name' => 'title'])) : "{}",
            'category' => $category,
            'bb' => $bb,
            'users' => UserModel::pluck('name', 'code')->toArray(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $pid = $request->get('pid', null);
        $dpts = DepartmentModel::select('id', 'parent_id as pid', 'code', 'name as title')
            ->orderBy('sort')
            ->get()
            ->toArray();
        $dpts = PHPTree::toList($dpts);
        $cateogry = DeptCateModel::orderBy('sort')
            ->pluck('name', 'id')
            ->toArray();
        $bb = DeptBBModel::orderBy('sort')->pluck('name', 'id')
            ->toArray();
        return view('admin.department.create', [
            'dpts' => $dpts,
            'pid' => $pid,
            'category' => $cateogry,
            'bb' => $bb,
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

        try{
            DepartmentModel::create($data);
        }catch (\Exception $e) {
            return $this->responseToJson([], '新增失败：【' . $e->getMessage() . '】', 201);
        }
        return $this->responseToJson(['url' => route('department.index') . '?tab=' . $this->tab], '新增成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentModel $departmentModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function edit(DepartmentModel $departmentModel, $id)
    {
        //
        $info = DepartmentModel::findOrFail($id);
        $dpts = DepartmentModel::select('id', 'parent_id as pid', 'code', 'name as title')
            ->orderBy('sort')
            ->get()
            ->toArray();
        $dpts = PHPTree::toList($dpts);
        $category = DeptCateModel::orderBy('sort')->get();
        $bb = DeptBBModel::orderBy('sort')->get();
        return view('admin.department.edit', [
            'info' => $info,
            'dpts' => $dpts,
            'category' => $category,
            'bb' => $bb,
            'users' => UserModel::pluck('name', 'code')->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepartmentModel $departmentModel, $id)
    {
        //
        $data = $this->validation($request->all(), $id);

        try {
            DepartmentModel::where('id', $id)->update($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '编辑失败：【' . $e->getMessage() . '】', 201);
        }
        return $this->responseToJson(['url' => route('department.index') . '?tab=' . $this->tab], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepartmentModel $departmentModel, $id)
    {
        //
        try {
            $ids = DepartmentModel::where('parent_id', $id)
                ->pluck('id')
                ->toArray();
            $ids = array_merge([(int)$id], $ids);
            DepartmentModel::destroy($ids);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败：【' . $e->getMessage() . '】', 201);
        }

        return $this->responseToJson(['url' => route('department.index') . '?tab=' . $this->tab], '删除成功');
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'code' => [
                'required',
                'max:10',
                'regex:/^\w{3,10}$/',
                $id ? Rule::unique('t_department')->ignore($id) : 'unique:t_department',
            ],
            'name' => 'required|max:30',
        ];

        $msg = [
            'code.required' => '部门编号不能为空',
            'code.max' => '部门编号不超过10位',
            'code.unique' => '部门编号已存在',
            'code.regex' => '部门编号必须是字母或数字组成，3-10位',
            'name.required' => '部门名称不能为空',
            'name.max' => '部门名称不超过30位',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }
}
