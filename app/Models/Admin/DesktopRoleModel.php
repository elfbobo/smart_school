<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DesktopRoleModel extends Model
{
    //
    protected $table = 't_sys_desktop_role';
    protected $fillable = ['desktop_id', 'role_id'];
}
