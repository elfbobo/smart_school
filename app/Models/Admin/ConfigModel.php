<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
    //
    protected $table = 't_sys_config';
    public $timestamps = false;
    protected $fillable = ['code', 'value'];
}
