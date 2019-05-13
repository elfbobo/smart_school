<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DesktopManageModel extends Model
{
    //
    protected $table = 't_desktop_list';
    protected $fillable = ['name', 'name_eng', 'state'];
}
