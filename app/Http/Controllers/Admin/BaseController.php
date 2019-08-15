<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\RoleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    protected $category = '01';
    //
    /**
     * 返回json格式数据 主要用于ajax请求
     * @param array $data
     * @param string $msg
     * @param int $code
     * @param bool $default json处理方式
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseToJson($data = [], $msg = 'ok', $code = 200, $default = true) {
        if ($default) {
            return response()->json([
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
            ]);
        }
        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取uuid
     * @return mixed
     */
    public function getUUID()
    {
        $query = DB::select('select uuid_short() as id');
        return $query[0]->id;
    }

    /**
     * 获得排序后的数组
     * @param $data
     * @param int $pid
     * @return array
     */
    protected function getOrderList($data, $pid = 0)
    {
        $newArray = [];
        foreach ($data as $k => $item) {
            $newArray[] = [
              'id' =>  $item['id'],
              'parent_id' => $pid,
              'disp_order' => ($k+1),
            ];

            if (isset($item['children'])) {
                $newArray = array_merge($newArray, $this->getOrderList($item['children'], $item['id']));
            }
        }

        return $newArray;
    }

    /**
     * 批量更新
     * @param array $multipleData
     * @param $tableName
     * @return bool|int
     */
    public function updateBatch($tableName = "", $multipleData = array()){

        if( $tableName && !empty($multipleData) ) {

            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  '`' . $uColumn . '`' . " = CASE ";

                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = ".$data[$referenceColumn]." THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ". '`' . $uColumn . '`' . " END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";

            // Update
            return DB::update(DB::raw($q));

        } else {
            return false;
        }

    }

    //获取字典项的代码和名称
    protected function getCodeName($str)
    {
        $arr = explode('|', $str);
        return [
            'code' => $arr[0] ?? '',
            'name' => $arr[1] ?? '',
        ];
    }

    protected function getRoleId($roleCode)
    {
        return RoleModel::where('code', $roleCode)->value('id');
    }
}
