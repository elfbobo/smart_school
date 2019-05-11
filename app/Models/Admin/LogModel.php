<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Admin\LogModel
 *
 * @mixin \Eloquent
 */
class LogModel extends Model
{
    //
    protected $table = 't_sys_logs';

    protected $fillable = [
        'user_id',
        'client_ip',
        'type',
        'content',
        'browser',
        'os',
    ];

    /**
     * 操作日志写入
     * @param $content 详细操作内容
     * @param $type 操作类型 1-新增 2-编辑 3-删除
     */
    public static function writeLog($content, $type)
    {
        $browser = getBrowser();
        $data = [
            'user_id' => session('user_auth.id'),
            'client_ip' => request()->getClientIp(),
            'type' => $type,
            'browser' => isset($browser['browser']) ? $browser['browser'] : '',
            'os' => isset($browser['os']) ? $browser['os'] : '',
            'content' => json_encode($content, 320),
        ];

        try {
            self::create($data);
        } catch (\Exception $e) {
            Log::error('操作日志写入失败', $data);
        }
    }
}
