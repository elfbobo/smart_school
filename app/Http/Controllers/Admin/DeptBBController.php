<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\DeptBBModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DeptBBController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.dept-bb.create');
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
        $data = $this->validation($request->post());

        try {
            DeptBBModel::create($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '新增失败', 201);
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
        $info = DeptBBModel::find($id);
        return view('admin.dept-bb.edit', [
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
        $data = $this->validation($request->post(), $id);

        try {
            DeptBBModel::where('id', $id)->update($data);
        } catch (\Exception $e) {
            return $this->responseToJson([], '编辑失败', 201);
        }

        return $this->responseToJson([], '编辑成功');
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
        try {
            DeptBBModel::destroy($id);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败', 201);
        }

        return $this->responseToJson([], '删除成功');
    }

    public function sortable(Request $request)
    {
        $ids = $request->post('ids');
        $data = [];
        foreach ($ids as $k => $id) {
            $data[] = [
                'id' => $id,
                'sort' => ($k+1)
            ];
        }

        $res = $this->updateBatch('t_department_bb', $data);
        if (!$res) {
            return $this->responseToJson([], '保存失败', 201);
        }
        return $this->responseToJson([], '保存成功');
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'code' => [
                'regex:/^[a-zA-Z0-9]{1,}+$/',
                is_null($id) ? Rule::unique('t_department_bb') : Rule::unique('t_department_bb')->ignore($id),
            ],
            'name' => [
                is_null($id) ? Rule::unique('t_department_bb') : Rule::unique('t_department_bb')->ignore($id),
            ],
        ];

        $msg = [
            'code.regex' => '代码必须是数字或字母组成',
            'code.unique' => '代码已存在',
            'name.unique' => '名称已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }
}
