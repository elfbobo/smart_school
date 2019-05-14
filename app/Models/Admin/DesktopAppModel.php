<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DesktopAppModel extends Model
{
    //
    protected $table = 't_desktop_app';
    protected $fillable = ['user_code', 'app_id', 'desktop_id', 'disp_order'];
}
