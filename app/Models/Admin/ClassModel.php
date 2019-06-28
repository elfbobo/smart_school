<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    //
    protected $table = 't_sys_class';
    protected $guarded = [];

    public static function getone($value, $isCode = false)
    {
        if ($isCode) {
            return self::where('class_name', $value)->value('class_code');
        } else {
            return self::where('class_code', $value)->value('class_name');
        }
    }
}
