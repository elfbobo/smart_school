<?php

namespace App\Http\Controllers\Admin;

use App\Libs\PHPTree;
use App\Models\Admin\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $menus = MenuModel::orderBy('sort')->get()->toArray();
        $tree = new PHPTree(['pid' => 'parent_id']);
        $menus = $tree::toList($menus);
        return view('admin.menu.index', [
            'menus' => $menus,
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
        $menus = MenuModel::orderBy('sort')
            ->where('status', 0)
            ->select('id', 'parent_id as pid', 'title')
            ->get()
            ->toArray();
        $menus = PHPTree::toList($menus);
        return view('admin.menu.create', [
            'menus' => $menus,
            'actions' => config('custom.actions')
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
        $sub = [];
        DB::beginTransaction();
        try {
            $res = MenuModel::create($data);
            if ($data['subs'] == 1 && isset($data['action'])) {
                $prefix = strstr($data['uri'], '.', true);
                foreach ($data['action'] as $k => $value) {
                    $actions = explode('-', $value);
                    $sub[] = [
                        'title' => $actions[1],
                        'parent_id' => $res->id,
                        'status' => 1,
                        'uri' => $prefix . '.' . $actions[0],
                        'sort' => $k+1,
                    ];
                }
                MenuModel::insert($sub);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '添加失败：【' . $e->getMessage() . '】', 201);
        }
        return $this->responseToJson([], '添加成功');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\MenuModel  $menuModel
     * @return \Illuminate\Http\Response
     */
    public function show(MenuModel $menuModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\MenuModel  $menuModel
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuModel $menuModel, $id)
    {
        //
        $info = MenuModel::find($id);
        $menus = MenuModel::orderBy('sort')
            ->where('status', 0)
            ->select('id', 'parent_id as pid', 'title')
            ->get()
            ->toArray();
        $menus = PHPTree::toList($menus);
        return view('admin.menu.edit', [
            'info' => $info,
            'menus' => $menus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\MenuModel  $menuModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuModel $menuModel, $id)
    {
        //
        $data = $this->validation($request->all());

        try{
            $menuModel->where('id', $id)->update($data);
        }catch (\Exception $e) {
            return $this->responseToJson([], '编辑失败：【' . $e->getMessage() . '】', 201);
        }
        return $this->responseToJson([], '编辑成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\MenuModel  $menuModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuModel $menuModel, $id)
    {
        //
        if (MenuModel::where('parent_id', $id)->value('id')) {
            return $this->responseToJson([], '该节点存在子节点，不允许删除', 201);
        }

        try{
            MenuModel::destroy($id);
        } catch (\Exception $e) {
            return $this->responseToJson([], '删除失败：【' . $e->getMessage() . '】');
        }
        return $this->responseToJson([], '删除成功');
    }

    private function validation($data)
    {
        $rule = [
            'title' => 'required|max:20',
        ];

        $msg = [
            'title.required' => '菜单名不能为空',
            'title.max' => '菜单名不超过20个字'
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }
}
