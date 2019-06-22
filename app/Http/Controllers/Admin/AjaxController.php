<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\DepartmentModel;
use App\Models\Admin\EpisodeModel;
use App\Models\Admin\GroupMemberModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\TeamGroupModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    //部门节点排序
    public function saveDeptNodes(Request $request)
    {
        $id = $request->get('id');
        $parent_id = $request->get('parent_id');
        $parent_id = $parent_id == null ? 0 : $parent_id;
        $targetId = $request->get('targetId');
        $moveType = $request->get('moveType');

        try {
            if ($moveType == 'prev') {
                DepartmentModel::where('id', $id)->update([
                    'sort' => DB::raw('sort-1'),
                    'parent_id' => $parent_id,
                ]);
            } else {
                DepartmentModel::where('id', $id)->update([
                    'sort' => DB::raw('sort+1'),
                    'parent_id' => $parent_id,
                ]);
            }
        } catch (\Exception $e) {
            return $this->toJson([], 201, '保存失败：【' . $e->getMessage() . '】');
        }
        return $this->toJson([], 200, '保存成功');
    }


    public function getMenus(Request $request)
    {
        $role_id = $request->get('role_id', null);
        $menus = MenuModel::from('t_sys_menus as a')
            ->join('t_permission_role as b', function ($join) use ($role_id) {
                $join->on('b.menu_id', '=', 'a.id')
                    ->where('b.role_id', $role_id);
            }, '', '', 'left')
            ->select('a.id', 'a.parent_id as pId', 'a.title as name', 'b.menu_id')
            ->orderBy('a.sort')
            ->get()
            ->toArray();
        return $this->toJson($menus);
    }

    public function getTeamGroupOption(Request $request)
    {
        $team_id = $request->get('team_id', null);
        $group_id = $request->get('group_id', null);
        $groups  = TeamGroupModel::when($team_id, function ($query) use ($team_id) {
            $query->where('team_id', $team_id);
        })
            ->where('state', 'A')
            ->get();

        $options = '';
        if ($groups) {
            foreach ($groups as $group) {
                $options .= '<option value="' . $group->id . '" ' . ($group_id == $group->id ? 'selected' : '') . '>' . $group->name . '</option>';
            }
        }
        return $this->toJson(['options' => $options]);
    }

    public function getDataStatistics()
    {
        $start = date('Y-m-01 00:00:00');
        $end = date('Y-m-d 23:59:59');
        /*$data = EpisodeModel::from('t_tv_episode as a')
            ->leftJoin('t_tv_column as b', 'b.id', '=', 'a.column_id')
            ->whereBetween('a.created_at', [$start, $end])
            ->groupBy('a.column_id')
            ->select('column_id', 'b.title',
                DB::raw('count(a.id) as tvs'),
                DB::raw('sum(duration) as total_duration'))
            ->get();
        $pieChat = [];
        $donutChart = [];
        if ($data) {
            foreach ($data as $item) {
                $labels[] = $item->title;
                $dataTvs[] = $item->tvs;
                $dataDurations[] = $item->total_duration;
                $colors[] = '#' . randColor();
                $pieChat = [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'data' => $dataTvs,
                            'backgroundColor' => $colors,
                            'hoverBackgroundColor' => $colors,
                            'hoverBorderColor' => ['#fff'],
                        ]
                    ]
                ];

                $donutChart = [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'data' => $dataDurations,
                            'backgroundColor' => $colors,
                            'hoverBackgroundColor' => $colors,
                            'hoverBorderColor' => ['#fff'],
                        ]
                    ]
                ];
            }
            //dd($pieChat);
        }

        //记者得分
        $horizontalBarData = [];
        $barData = EpisodeModel::whereBetween('created_at', [$start, $end])
            ->orderBy('total_score', 'desc')
            ->groupBy('director_id')
            ->select('director_name', 'total_score')
            ->take(10)
            ->get();

        if ($barData->isNotEmpty()) {
            foreach ($barData as $item) {
                $label[] = $item->director_name;
                $arr[] = $item->total_score;
                $horizontalBarData = [
                    'labels' => $label,
                    'datasets' => [
                        [
                            'label' => '总分',
                            'backgroundColor' => 'rgba(2, 192, 206, 1)',
                            'borderColor' => '#02c0ce',
                            'borderWidth' => 2,
                            'hoverBackgroundColor' => 'rgba(2, 192, 206, 1.5)',
                            'hoverBorderColor' => '#02c0ce',
                            'data' => $arr,
                        ],
                    ]
                ];
            }
        }

        //团队得分
        $barData = [];
        $sub1 = DB::table('t_director_score_month')
            ->where('year', date('Y'))
            ->where('month', date('n'))
            ->select('user_id', DB::raw('sum(score) as score'))
            ->groupBy('user_id');
        $groupData = TeamGroupModel::from('t_group as a')
            ->join('t_group_member as b', 'b.group_id', '=', 'a.id')
            ->leftJoin(DB::raw("({$sub1->toSql()}) as c"), 'c.user_id', '=', 'b.user_id')
            ->mergeBindings($sub1)
            ->join('t_group_score as d', function ($join) {
                $join->on('d.group_id', '=', 'a.id')
                    ->where('d.year', date('Y'))
                    ->where('d.month', date('n'));
            })
            ->groupBy('a.id')
            ->orderBy('a.id')
            ->select(
                'a.name',
                DB::raw('(improve_score+award_score+(plan_members*plan_score)-punish_score) as final_score')
            )
            ->get();
        if ($groupData) {
            foreach ($groupData as $item) {
                $label1[] = $item->name;
                $arr1[] = $item->final_score;
                $barData = [
                    'labels' => $label1,
                    'datasets' => [
                        [
                            'label' => '最终分',
                            'backgroundColor' => 'rgba(255, 99, 132, 1)',
                            //'borderColor' => '#02c0ce',
                            'borderWidth' => 2,
                            'hoverBackgroundColor' => 'rgba(255, 99, 132, 2)',
                            //'hoverBorderColor' => '#02c0ce',
                            'data' => $arr1,
                        ],
                    ]
                ];
            }
        }*/
        return $this->toJson([
            'pieChart' => [],
            'donutChart' => [],
            'horizontalBarData' => [],
            'BasicBarData' => [],
        ]);
    }

    public function getRegion(Request $request)
    {
        $parentCode = $request->get('parent_code');
        $parentCode = strstr($parentCode, '|', true);
        $data = RegionModel::where('parent_code', $parentCode)
            ->pluck('name', 'code')
            ->toArray();

        $options = '<option></option>';
        if ($data) {
            foreach ($data as $code => $name) {
                $options .= '<option value="' . $code . '|' . $name  . '">[' . $code . ']' . $name . '</option>';
            }
        }

        return $this->toJson(['options' => $options]);
    }

    private function toJson($data = [], $code = 200, $msg = 'ok')
    {
        return response()->json([
            'code' => $code,
            'msg' =>$msg,
            'data' => $data,
        ]);
    }
}
