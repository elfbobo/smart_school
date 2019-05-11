<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class AppListModel extends Model
{
    //
    protected $table = 't_app_list';
    protected $fillable = [
        'name',
        'icon_url',
        'description',
        'business_code',
        'is_pc_app',
        'is_mobile_app',
        'has_pc_card',
        'has_mobile_card',
        'is_new',
        'is_recommended',
        'online_status',
        'online_status_time',
        'is_cycle',
        'cycle_begin_time',
        'cycle_end_time',
        'vender',
        'category',
        'entry_url',
        'version_code',
        'version_name',
    ];
}
