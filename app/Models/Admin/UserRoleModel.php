<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class UserRoleModel extends Model
{
    //
    protected $table = 't_user_role';
    protected $fillable = ['user_code', 'role_id'];
    public $timestamps = false;
}
