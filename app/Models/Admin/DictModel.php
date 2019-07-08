<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DictModel extends Model
{
    //
    protected $table = 't_sys_dict';

    public static function getData($category)
    {
        return self::where('category', $category)
            ->orderBy('order')
            ->pluck('name', 'code')
            ->toArray();
    }

    public static function getOne($category, $name, $isCode = false)
    {
        return self::where('category', $category)
            ->where(function ($query) use ($name, $isCode) {
                if ($isCode) {
                    $query->where('code', $name);
                } else {
                    $query->where('name', $name);
                }
            })
            ->first();
    }
}
