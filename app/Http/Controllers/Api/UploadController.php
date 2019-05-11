<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends ApiController
{
    protected static $allowExt = []; //允许上传的文件类型
    protected static $maxSize; //允许上传文件大小，单位：M
    //
    public function upload(Request $request)
    {
        if (false === $request->hasFile('files')) {
            return $this->responseToJson([], 1001, '缺少key：files');
        }
        $allowExts = config('custom.allow_ext');
        $files = $request->file('files');
        $ext = strtolower($files->getClientOriginalExtension()); //文件后缀名
        $size = $files->getClientSize(); //文件大小，字节
        $mime = $files->getClientMimeType(); //获取mime
        $type = strstr($mime, '/', true); //获取文件类型
        $dir = $request->get('dirname', $type) . '/' . date('Ymd'); //上传目录，有指定目录时则使用指定目录，否则使用默认
        switch ($type) {
            //图片类型
            case 'image':
                self::$allowExt = $allowExts['image'];
                self::$maxSize = 3;
                break;
            //视频类型
            case 'video':
                self::$allowExt = $allowExts['video'];
                self::$maxSize = 30;
                break;
            //音频类型
            case 'audio':
                self::$allowExt = $allowExts['audio'];
                self::$maxSize = 10;
                break;
            //默认
            default:
                self::$allowExt = ['txt', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'zip', 'rar', '7z'];
                self::$maxSize = 30;
                break;
        }
        $unique = $unique = md5(uniqid(md5(microtime(true)),true));
        $realname = $files->getClientOriginalName();
        $filename = date('Ymd') . '-' . $unique . '.' . $ext; //重新命名文件
        $filepath = '/' . $dir . '/' . $filename;

        if (!in_array($ext, self::$allowExt)) {
            return $this->responseToJson([], 1001, '不支持次文件类型，仅支持' . implode(',', self::$allowExt));
        }

        if ($size/1024/1024 > self::$maxSize) {
            return $this->responseToJson([], 1001, '超过文件大小限制' . self::$maxSize . 'M');
        }

        $dir = public_path('uploads/' . $dir);
        if (!$files->move($dir, $filename)) {
            return $this->responseToJson([], 1001, '上传错误');
        }
        $filepath = env('APP_URL', 'http://127.0.0.1') . '/uploads'. $filepath;

        return $this->responseToJson([
            'name' => $realname,
            'ext'  => $ext,
            'size' => $size,
            'code' => $unique,
            'filepath' => $filepath,
        ]);
    }
}
