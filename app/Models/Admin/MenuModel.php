<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\MenuModel
 *
 * @mixin \Eloquent
 */
class MenuModel extends Model
{
    //
    protected $table = 't_sys_menus';

    protected $fillable = ['title', 'parent_id', 'icon', 'sort', 'status', 'is_menu', 'uri'];
}
