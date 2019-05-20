<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AppFolderModel extends Model
{
    //
    public $timestamps = false;
    protected $table = 't_app_folder_app';
    protected $fillable = ['folder_id', 'app_id'];
}
