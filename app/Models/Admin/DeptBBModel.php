<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DeptBBModel extends Model
{
    //
    protected $table = 't_department_bb';
    protected $fillable = ['code', 'name', 'status', 'sort'];
}
