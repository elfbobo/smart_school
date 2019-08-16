<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\AppListModel;
use App\Models\Admin\EpisodeModel;
use App\Models\Admin\StudentModel;
use App\Models\Admin\TeacherModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //

    public function index()
    {
        $teacherCount = TeacherModel::count();
        $studentCount = StudentModel::count();
        $appCount = AppListModel::count();
        return view('admin.dashboard', [
            'teacherCount' => $teacherCount,
            'studentCount' => $studentCount,
            'appCount' => $appCount,
        ]);
    }
}
