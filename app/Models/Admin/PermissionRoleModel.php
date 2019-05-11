<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class PermissionRoleModel extends Model
{
    //
    protected $table = 't_permission_role';
    protected $fillable = ['role_id', 'menu_id'];
    public $timestamps = false;
}
