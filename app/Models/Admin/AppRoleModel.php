<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AppRoleModel extends Model
{
    //
    protected $table = 't_app_role';
    protected $fillable = ['app_id', 'role_id', 'state'];
}
