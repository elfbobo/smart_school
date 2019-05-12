<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AppServiceModel extends Model
{
    //
    protected $table = 't_app_service_app';

    protected $fillable = ['app_id', 'service_type_id', 'state'];
}
