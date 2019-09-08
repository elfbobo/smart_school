<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\DepartmentModel
 *
 * @mixin \Eloquent
 */
class DepartmentModel extends Model
{
    //
    protected $table = 't_department';

    protected $fillable = ['code', 'name', 'parent_id', 'status', 'category', 'bbdm', 'leader', 'principal'];

    public static function getone($value, $isCode = false)
    {
        if ($isCode) {
            return self::where('name', $value)->value('code');
        } else {
            return self::where('code', $value)->value('name');
        }
    }
}
