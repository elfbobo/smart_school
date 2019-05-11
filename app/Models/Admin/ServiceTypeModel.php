<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ServiceTypeModel extends Model
{
    //
    protected $table = 't_service_type';

    protected $fillable = ['parent_id', 'name', 'state', 'order'];
}
