<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class ProfessionalModel extends Model
{
    //
    protected $table = 't_sys_professional';
    protected $guarded = [];

    public static function getone($value, $isCode = false)
    {
        if ($isCode) {
            return self::where('name', $value)->value('code');
        } else {
            return self::where('code', $value)->value('name');
        }
    }
}
