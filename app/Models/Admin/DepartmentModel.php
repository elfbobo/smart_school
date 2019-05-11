<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\DepartmentModel
 *
 * @mixin \Eloquent
 */
class DepartmentModel extends Model
{
    //
    protected $table = 't_department';

    protected $fillable = ['code', 'name', 'parent_id', 'status'];
}
