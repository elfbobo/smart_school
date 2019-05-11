<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\EpisodeModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //

    public function index()
    {
        $start = date('Y-m-01 00:00:00');
        $end = date('Y-m-d 23:59:59');

        return view('admin.dashboard', [
            'data' => [],
        ]);
    }
}
