<?php
/**
 * Created by PhpStorm.
 * User: 59222
 * Date: 2018/10/27
 * Time: 18:45
 */

if (!function_exists('getTopMenu')) {
    /**
     * 顶部菜单栏
     * @param array $data
     * @return string
     */
    function getTopMenu($data = [])
    {
        $html = '';
        foreach ($data as $item) {
            if ($item['uri'] != '') {
                $html .= '<li class="has-submenu">
                        <a href="' . route($item['uri']) . '"><i class="' . $item['icon'] . '"></i>' . $item['title'] . '</a>
                        ';
            } else {
                $html .= '<li class="has-submenu">
                        <a href="javascript:;"><i class="' . $item['icon'] . '"></i>' . $item['title'] . '</a>
                        ';
            }

            if (isset($item['child'])) {
                $html .= getChildMenu($item['child']);
            }

            $html .= '</li>';
        }
        return $html;
    }
}


if (!function_exists('getChildMenu')) {
    /**
     * 子菜单
     * @param $data
     * @return string
     */
    function getChildMenu($data)
    {
        $html = '<ul class="submenu">';
        foreach ($data as $item) {
            $html .= '<li><a href="' . route($item['uri']) . '">' . $item['title'] . '</a></li>';
            if (isset($item['child'])) {
                $html .= getChildMenu($data);
            }
        }
        $html .= '</ul>';
        return $html;
    }
}

if (!function_exists('getLocation')) {

    function getLocation($data = [], $curren_id = null)
    {
        $location = [];
        foreach ($data as $item) {
            if ($item['id'] == $curren_id) {
                $location[] = $item;
                $location = array_merge(getLocation($data, $item['parent_id']), $location);
            }
        }
        return $location;
    }
}


if (!function_exists('getBrowser')) {
    function getBrowser(){
        $flag = $_SERVER['HTTP_USER_AGENT'];
        $para = [];
        // 检查操作系统
        if(preg_match('/Windows[\d\. \w]*/',$flag, $match)) $para['os']=$match[0];

        if(preg_match('/Chrome\/[\d\.\w]*/',$flag, $match)){
            // 检查Chrome
            $para['browser']=$match[0];
        }elseif(preg_match('/Safari\/[\d\.\w]*/',$flag, $match)){
            // 检查Safari
            $para['browser']=$match[0];
        }elseif(preg_match('/Mozilla\/5.0 [\d\.\w]*/',$flag, $match)){
            // IE
            $para['browser']=$match[0];
        }elseif(preg_match('/Opera\/[\d\.\w]*/',$flag, $match)){
            // opera
            $para['browser']=$match[0];
        }elseif(preg_match('/Firefox\/[\d\.\w]*/',$flag, $match)){
            // Firefox
            $para['browser']=$match[0];
        }elseif(preg_match('/OmniWeb\/(v*)([^\s|;]+)/i',$flag, $match)){
            //OmniWeb
            $para['browser']=$match[2];
        }elseif(preg_match('/Netscape([\d]*)\/([^\s]+)/i',$flag, $match)){
            //Netscape
            $para['browser']=$match[2];
        }elseif(preg_match('/Lynx\/([^\s]+)/i',$flag, $match)){
            //Lynx
            $para['browser']=$match[1];
        }elseif(preg_match('/360SE/i',$flag, $match)){
            //360SE
            $para['browser']='360安全浏览器';
        }elseif(preg_match('/SE 2.x/i',$flag, $match)) {
            //搜狗
            $para['browser']='搜狗浏览器';
        }else{
            $para['browser']='unkown';
        }
        return $para;
    }
}

if (!function_exists('getZTreeData')) {
    function getZTreeData($data, $config = [])
    {
        $config = array_merge([
            'id' => 'id',
            'pid' => 'parent_id',
            'name' => 'name'
        ], $config);

        $trees = [];
        foreach ($data as $item) {
            $trees[] = [
                'id' => $item[$config['id']],
                'pId' => $item[$config['pid']],
                'name' => $item[$config['name']],
            ];
        }
        return $trees;
    }
}

if (!function_exists('responseToJson')) {
    /**
     * 返回json格式提示信息
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return false|string
     */
    function responseToJson($data = [], $msg = 'ok', $code = 200)
    {
        return json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ], 320);
    }
}

if (!function_exists('getGreetings')) {
    /**
     * 问候语
     * @return string
     */
    function getGreetings()
    {
        $g = date('G');
        if ($g >= 3 && $g < 6) {
            $tips = '凌晨好！';
        } else if ($g >= 6 && $g < 9) {
            $tips = '早上好！';
        } else if ($g >= 9 && $g < 11) {
            $tips = '上午好！';
        } else if ($g >= 11 && $g < 13) {
            $tips = '中午好！';
        } else if ($g >= 13 && $g < 17) {
            $tips = '下午好！';
        } else if ($g >= 17 && $g < 19) {
            $tips = '傍晚好！';
        } else if ($g >= 19 && $g < 23) {
            $tips = '晚上好！';
        } else {
            $tips = '夜已深，注意休息！';
        }
        return $tips;
    }
}

if (!function_exists('trimall')) {
    /**
     * 去掉所有空格
     * @param $str
     * @return mixed
     */
    function trimall($str)
    {
        $limit=array(" ","　","\t","\n","\r");
        $rep=array("","","","","");
        return str_replace($limit,$rep,$str);
    }
}

if (!function_exists('secToTime')) {
    /**
     *      把秒数转换为时分秒的格式
     *      @param Int $times 时间，单位 秒
     *      @return String
     */
    function secToTime($times){
        $result = 0;
        if ($times > 0) {
            $hour = floor($times/3600);
            $minute = floor(($times-3600 * $hour)/60);
            $second = floor((($times-3600 * $hour) - 60 * $minute) % 60);
            $result = $hour > 0 ? $hour . '时' : '';
            $result .= $minute > 0 ? $minute . '分' : '';
            $result .= $second . '秒';
        }
        return $result;
    }
}

if (!function_exists('randColor')) {
    function randColor()
    {
        $colors = [];
        for($i = 0;$i<6;$i++) {
            $colors[] = dechex(rand(0,15));
        }
        return implode('',$colors);
    }
}

if (!function_exists('msubstr')) {
    /**
     *+----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
     *+----------------------------------------------------------
     * @static
     * @access public
     *+----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     *+----------------------------------------------------------
     * @return string
     *+----------------------------------------------------------
     */
    function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true){
        if(function_exists("mb_substr")){
            if($suffix){
                if(strlen($str)>$length)
                    return mb_substr($str, $start, $length, $charset)."...";
                else
                    return mb_substr($str, $start, $length, $charset);
            }else{
                return mb_substr($str, $start, $length, $charset);
            }
        }elseif(function_exists('iconv_substr')) {
            if($suffix){
                return iconv_substr($str,$start,$length,$charset);
            }else{
                return iconv_substr($str,$start,$length,$charset);
            }
        }
    }
}

if (!function_exists('getYear')) {
    function getYear($prev = 10, $next = 5)
    {
        $years = [];
        for ($i = $prev; $i > 0; $i--) {
            $years[] = date('Y', strtotime('-' . $i . ' year'));
        }

        for ($j = 0; $j <= $next; $j++) {
            $years[] = date('Y', strtotime('+' . $j . ' year'));
        }

        return $years;
    }
}