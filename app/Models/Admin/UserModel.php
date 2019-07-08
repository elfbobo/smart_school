<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\UserModel
 *
 * @mixin \Eloquent
 */
class UserModel extends Model
{
    //
    protected $table = 't_user_account';
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'type',
        'password',
        'token',
        'state',
    ];
}
