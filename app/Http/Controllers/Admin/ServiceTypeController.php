<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\ServiceTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ServiceTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $types = $types = ServiceTypeModel::where('state', 0)
            ->select('id', 'parent_id as pid', 'name as title')
            ->orderBy('disp_order')
            ->get()
            ->toArray();
        return view('admin.service_type.index', [
            'items' => !empty($types) ? $this->getTreeData($types) : null,
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
        $types = ServiceTypeModel::where('state', 0)
            ->select('id', 'parent_id as pid', 'name as title')
            ->get()
            ->toArray();
        $types = PHPTree::toList($types);
        return view('admin.service_type.create', [
            'types' => $types,
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
        $data = $this->validation($request->post());

        try {
            ServiceTypeModel::create($data);
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
        $info = ServiceTypeModel::find($id);
        $ids = ServiceTypeModel::where('parent_id', $id)->pluck('id')->toArray();
        $ids = array_merge([$id], $ids);
        $types = ServiceTypeModel::where('state', 0)
            ->whereNotIn('id', $ids)
            ->select('id', 'parent_id as pid', 'name as title')
            ->get()
            ->toArray();
        $types = PHPTree::toList($types);
        return view('admin.service_type.edit', [
           'info' => $info,
           'types' => $types,
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
            ServiceTypeModel::where('id', $id)->update($data);
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
        //找出分类下的所有子分类
        $ids = ServiceTypeModel::where('parent_id', $id)->pluck('id')->toArray();
        $ids = array_merge($ids, [$id]);
        try {
            ServiceTypeModel::whereIn('id', $ids)->update(['state' => 1]);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败', 201);
        }

        return $this->responseToJson([], '删除成功');
    }

    /**
     * 保存节点排序
     * @param Request $request
     */
    public function saveNode(Request $request)
    {
        $data = $this->getOrderList($request->post('ids'));
        if (!$this->updateBatch('t_service_type', $data)) {
            return $this->responseToJson([], '保存失败', 201);
        }

        return $this->responseToJson([], '保存成功');
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'parent_id' => 'required',
            'name' => [
                'required',
                'max:20',
                !is_null($id) ? Rule::unique('t_service_type')
                    ->ignore($id)
                    ->where('state', 1) : Rule::unique('t_service_type')->where('state', 1),
            ],
        ];

        $msg = [
            'parent_id.required' => '请选择上级类别',
            'name.required' => '请输入类别名称',
            'name.max' => '类别名称最多不超过20个字符',
            'name.unique' => '类别名称已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }

    private function getTreeData($data, $pid = 0, $level = 1)
    {
        $html = '';
        foreach ($data as $item) {
            if ($item['pid'] == $pid) {
                $html .= '<li class="dd-item" data-id="' . $item['id'] . '">
                                <div class="dd-handle">' . $item['title'] . '</div>
                                <a class="dd-button" href="javaScript:;" onclick="openIframe(\'编辑\', \'' . route('service_type.edit', ['id' => $item['id']]) . '\')" style="font-size: 18px;">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                <a class="dd-button1" href="javascript:;" onclick="removeOne(\'' . route('service_type.destroy', ['id' => $item['id']]) . '\')" style="font-size: 18px;">
                                    <i class="fa fa-trash"></i>
                                </a>';

                $child = $this->getTreeData($data, $item['id']);
                if ($child != '') {
                    $html .= '<ol class="dd-list">' . $child . '</ol>';
                }

                $html .= '</li>';
            }
        }
        return $html;
    }
}
