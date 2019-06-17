<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DeptCateModel extends Model
{
    //
    protected $table = 't_department_category';

    protected $fillable = ['code', 'name', 'status', 'sort'];
}
