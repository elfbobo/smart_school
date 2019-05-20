<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AppFolderManageModel;
use App\Models\Admin\AppFolderModel;
use App\Models\Admin\AppListModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AppFolderManageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        $data = AppFolderManageModel::select('id', 'name')->where('state', 0)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->get();
        $apps = AppListModel::from('t_app_list as a')
            ->join('t_app_folder_app as b', 'b.app_id', '=', 'a.id')
            ->select('a.icon_url', 'a.name', 'b.folder_id')
            ->get();
        return view('admin.app_folder_manage.index', [
            'data' => $data,
            'apps' => $apps,
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
        $apps = AppListModel::pluck('name', 'id')->toArray();
        return view('admin.app_folder_manage.create', [
            'apps' => $apps,
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
        DB::beginTransaction();
        try {
            $appFolder['id'] = $this->getUUID();
            $appFolder['name'] = $data['name'];
            AppFolderManageModel::create($appFolder);

            if (isset($data['app_ids'])) {
                foreach ($data['app_ids'] as $app_id) {
                    $saved[] = [
                        'folder_id' => $appFolder['id'],
                        'app_id' => $app_id,
                    ];
                }

                AppFolderModel::insert($saved);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
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
        $info = AppFolderManageModel::find($id);
        $apps = AppListModel::pluck('name', 'id')->toArray();
        $appIds = AppFolderModel::where('folder_id', $id)->pluck('app_id')->toArray();
        return view('admin.app_folder_manage.edit', [
            'info' => $info,
            'apps' => $apps,
            'appIds' => $appIds,
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
        DB::beginTransaction();
        try {
            $folder['name'] = $data['name'];
            AppFolderManageModel::where('id', $id)->update($folder);

            if (isset($data['app_ids'])) {
                $appIds = AppFolderModel::where('folder_id', $id)->pluck('app_id')->toArray();
                $diffIds = array_diff($appIds, $data['app_ids']);
                if ($diffIds) {
                    AppFolderModel::where('folder_id', $id)->whereIn('app_id', $diffIds)->delete();
                }

                foreach ($data['app_ids'] as $app_id) {
                    AppFolderModel::updateOrCreate([
                        'app_id' => $app_id,
                        'folder_id' => $id,
                    ], [
                        'app_id' => $app_id,
                        'folder_id' => $id,
                    ]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function destroy($id)
    {
        //
        DB::beginTransaction();
        try {
            AppFolderManageModel::where('id', $id)->update(['state' => 1]);
            AppFolderModel::where('folder_id', $id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseToJson([], '删除失败', 201);
        }
        return $this->responseToJson([], '删除成功');
    }

    private function validation($data, $id = null)
    {
        $rule = [
            'name' => [
                'required',
                is_null($id) ? Rule::unique('t_app_folder_list') : Rule::unique('t_app_folder_list')->ignore($id),
            ],
        ];

        $msg = [
            'name.required' => '请输入文件夹名称',
            'name.unique' => '文件夹名称已存在',
        ];

        $valid = Validator::make($data, $rule, $msg);

        if ($valid->fails()) {
            exit($this->responseToJson([], $valid->errors()->first(), 201, false));
        }

        return $data;
    }
}
