<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AppFolderManageModel extends Model
{
    //
    protected $table = 't_app_folder_list';
    protected $fillable = ['id', 'name', 'state'];
}
