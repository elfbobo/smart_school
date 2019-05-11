<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\RoleModel
 *
 * @mixin \Eloquent
 */
class RoleModel extends Model
{
    //
    protected $table = 't_sys_role';

    protected $fillable = ['name', 'description', 'state', 'code', 'is_sys_role'];
}
