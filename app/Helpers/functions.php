<?php
/**
 * 通用辅助函数
 */

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



/**
 * 格式化特殊字符串等
 * @param  $strParam         需要格式化的参数
 * @return string            格式化结果
 */

function replaceSpecialChar($strParam)
{
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex, '', $strParam);

}

function set_chars(){
    return IN_CHARSET == 'gbk' ? 'GB2312' : 'UTF-8';
}

function convert_charset($str, $type=0){
    if($type == 1){
        return IN_CHARSET == 'gbk' ? iconv('UTF-8', 'GBK//IGNORE', $str) : $str;
    }else{
        return IN_CHARSET == 'gbk' ? iconv('GBK', 'UTF-8//IGNORE', $str) : $str;
    }
}

function formatsize($size){
    $prec = 3;
    $size = round(abs($size));
    $units = array(0 => " B", 1 => " KB", 2 => " MB", 3 => " GB", 4 => " TB");
    if($size == 0){
        return str_repeat(" ", $prec)."0".$units[0];
    }
    $unit = min(4, floor(log($size) / log(2) / 10));
    $size = $size * pow(2, -10 * $unit);
    $digi = $prec - 1 - floor(log($size) / log(10));
    $size = round($size * pow(10, $digi)) * pow(10, -$digi);
    return $size.$units[$unit];
}

function checkmobile(){
    $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $touchbrowser_list = array('ipod', 'ipad', 'iphone', 'android', 'phone', 'mobile', 'wap', 'netfront', 'java', 'opera mobi', 'opera mini', 'ucweb', 'windows ce', 'symbian', 'series', 'webos', 'sony', 'blackberry', 'dopod', 'nokia', 'samsung', 'palmsource', 'xda', 'pieplus', 'meizu', 'midp', 'cldc', 'motorola', 'foma', 'docomo', 'up.browser', 'up.link', 'blazer', 'helio', 'hosin', 'huawei', 'novarra', 'coolpad', 'webos', 'techfaith', 'palmsource', 'alcatel', 'amoi', 'ktouch', 'nexian', 'ericsson', 'philips', 'sagem', 'wellcom', 'bunjalloo', 'maui', 'smartphone', 'iemobile', 'spice', 'bird', 'zte-', 'longcos', 'pantech', 'gionee', 'portalmmm', 'jig browser', 'hiptop', 'benq', 'haier', '^lct', '320x320', '240x320', '176x220', 'windows phone');
    $wmlbrowser_list = array('cect', 'compal', 'ctl', 'lg', 'nec', 'tcl', 'alcatel', 'ericsson', 'bird', 'daxian', 'dbtel', 'eastcom', 'pantech', 'dopod', 'philips', 'haier', 'konka', 'kejian', 'lenovo', 'benq', 'mot', 'soutec', 'nokia', 'sagem', 'sgh', 'sed', 'capitel', 'panasonic', 'sonyericsson', 'sharp', 'amoi', 'panda', 'zte');
    $brower = array('mozilla', 'chrome', 'safari', 'opera', 'm3gate', 'winwap', 'openwave', 'myop');
    if(dstrpos($useragent, $touchbrowser_list)){
        return true;
    }elseif(dstrpos($useragent, $wmlbrowser_list)){
        return true;
    }elseif(dstrpos($useragent, $brower)){
        return false;
    }else{
        return false;
    }
}

function dstrpos($string, $arr){
    if(empty($string)){return false;}
    foreach((array)$arr as $v){
        if(strpos($string, $v) !== false){
            return true;
        }
    }
    return false;
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function get_byte($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function get_random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 *  作用：将xml转为array
 */
function xmlToArray($xml) {
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}


/**
 * 反字符 去标签 自动加点 去换行 截取字符串
 */
function cutstr ($data, $no, $le = '') {
    $data = strip_tags(htmlspecialchars_decode($data));
    $data = str_replace(array("\r\n", "\n\n", "\r\r", "\n", "\r"), '', $data);
    $datal = strlen($data);
    $str = msubstr($data, 0, $no);
    $datae = strlen($str);
    if ($datal > $datae)
        $str .= $le;
    return $str;
}


/**
 * [字符串截取]
 * @param  [type]  $Str    [字符串]
 * @param  [type]  $Length [长度]
 * @param  boolean $more   [模型]
 * @return [type]          [截取后的字符串]
 */
function cut($Str, $Length,$more=true) {//$Str为截取字符串，$Length为需要截取的长度

    global $s;
    $i = 0;
    $l = 0;
    $ll = strlen($Str);
    $s = $Str;
    $f = true;

    while ($i <= $ll) {
        if (ord($Str{$i}) < 0x80) {
            $l++; $i++;
        } else if (ord($Str{$i}) < 0xe0) {
            $l++; $i += 2;
        } else if (ord($Str{$i}) < 0xf0) {
            $l += 2; $i += 3;
        } else if (ord($Str{$i}) < 0xf8) {
            $l += 1; $i += 4;
        } else if (ord($Str{$i}) < 0xfc) {
            $l += 1; $i += 5;
        } else if (ord($Str{$i}) < 0xfe) {
            $l += 1; $i += 6;
        }

        if (($l >= $Length - 1) && $f) {
            $s = substr($Str, 0, $i);
            $f = false;
        }

        if (($l > $Length) && ($i < $ll) && $more) {
            $s = $s . '...'; break; //如果进行了截取，字符串末尾加省略符号“...”
        }
    }
    return $s;
}

/**
 * 将一个字符串转换成数组，支持中文
 * @param string    $string   待转换成数组的字符串
 * @return string   转换后的数组
 */
function strToArray($string) {
    $strlen = mb_strlen($string);
    while ($strlen) {
        $array[] = mb_substr($string, 0, 1, "utf8");
        $string = mb_substr($string, 1, $strlen, "utf8");
        $strlen = mb_strlen($string);
    }
    return $array;
}




/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
    if(is_array($list)){
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
            $refer[$i] = &$data[$field];
        switch ($sortby) {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc':// 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ( $refer as $key=> $val)
            $resultSet[] = &$list[$key];
        return $resultSet;
    }
    return false;
}


// 格式化api返回值 0=成功 其他=失败
if (!function_exists('formatResponse')) {
    function formatResponse($code, $msg, &$data = null)
    {
        if (empty($data)) $data = (object)[];
        return response()->json(['code' => $code, 'msg' => $msg, 'data' => $data]);
    }
}


// 获取客户端IP
if (!function_exists('getIp')) {
    function getIp($ip2long = false)
    {
        $ip = '';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if (!empty($ip) && $ip2long) $ip = bindec(decbin(ip2long($ip)));
        return $ip;
    }
}


// curl 请求
if (!function_exists('curlRequest')) {
    function curlRequest($url, $data = [], $headers = [], $timeout = 10, $method = 'GET')
    {
        $curl = curl_init();
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data) && 'GET' == $method) $method = 'POST';

        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'PUT':
                curl_setopt($curl, CURLOPT_PUT, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);exit;
        }
        curl_close($curl);

        return $output;
    }
}


if(!function_exists('postText')) {
    function postText($url, $data_string)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $post_result = curl_exec($ch);

        if (curl_errno($ch)) {

            //print curl_error($ch);

        }

        curl_close($ch);

        return $post_result;
    }
}

if(!function_exists('postJson')) {
    function postJson($url, $data_string)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            'Content-Type: application/json',

            'Content-Length: ' . strlen($data_string)

        ));
        $post_result = curl_exec($ch);
        if (curl_errno($ch)) {

            //print curl_error($ch);

        }

        curl_close($ch);

        return $post_result;
    }
}

// 生成订单号
if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber($prefix = 'S')
    {
        $yCode = getCharacters();
        $index = intval(date('Y')) - 2019;
        if (1 == $index && 'S' == $prefix) $index = 2; // 过滤SB
        return $prefix . $yCode[$index] . dechex(date('m')) . date('d') . substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8) . rand(100, 999);
    }
}

// 获取大/小写英文字符
if (!function_exists('getCharacters')) {
    function getCharacters($type = 0)
    {
        if (0 == $type) {
            return ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        } else {
            return ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
        }
    }
}

// 获取远程图片大小
if (!function_exists('getRemoteImageSize')) {
    function getRemoteImageSize($url, &$base64Data = null)
    {
        getFileDataByCurl($url, $base64Data);

        return getimagesize('data://image/jpeg;base64,' . $base64Data);
    }
}

// 获取文件数据流
if (!function_exists('getFileDataByCurl')) {
    function getFileDataByCurl($url, &$data, $type = 0)
    {
        $ch = curl_init($url);
        // 超时设置
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // 取前面 1000 个字符 若获取不到数据可适当加大数值
        //curl_setopt($ch, CURLOPT_RANGE, '0-1000');

        // 跟踪301跳转
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        // 返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        if (0 == $type) $data = base64_encode($data);
    }
}

// 根据图片路径获取图片的尺寸(宽度&高度)
if (!function_exists('getImageSizeByPath')) {
    function getImageSizeByPath($imagePath)
    {
        $flag = 0;
        $sizeInfo = ['width' => 0, 'height' => 0];
        if (!empty($imagePath)) {
            if (false === strpos($imagePath, '_')) $flag = 1;
            else {
                $imagePathArr1 = explode('_', $imagePath);
                if (false === strpos($imagePathArr1[1], '.')) $flag = 1;
                else {
                    $imagePathArr2 = explode('.', $imagePathArr1[1]);
                    if (false === strpos($imagePathArr2[0], 'x')) $flag = 1;
                    else {
                        $imagePathArr3 = explode('x', $imagePathArr2[0]);
                        $sizeInfo['width'] = $imagePathArr3[0];
                        $sizeInfo['height'] = $imagePathArr3[1];
                    }
                }
            }
        }

        if (1 == $flag) {
            //list($width, $height) = getimagesize($imagePath);
            $imageSize = getRemoteImageSize($imagePath);
            $sizeInfo['width'] = $imageSize[0];
            $sizeInfo['height'] = $imageSize[1];
        }

        return $sizeInfo;
    }
}

// 请求频率控制
if (!function_exists('requestRateControl')) {
    function requestRateControl($key, $seconds = 5)
    {
        $num = Redis::incr($key);
        if (3 == $num) {
            Redis::expire($key, $seconds);
            return true;
        }

        return false;
    }
}
// 请求次数控制
if (!function_exists('requestNumControl')) {
    function requestNumControl($key, $seconds = 3600)
    {
        $num = Redis::incr($key);
        if (30 == $num) {
            Redis::expire($key, $seconds);
            return true;
        }

        return false;
    }
}
// 版本比较
if (!function_exists('versionCompare')) {
    function versionCompare($version1, $version2)
    {
        // $version1 < $version2返回 -1
        // $version1 = $version2返回 0
        // $version1 > $version2返回 1
        return version_compare($version1, $version2);
    }
}

if(!function_exists('successMessage')) {
    function successMessage($msg, $data=null) {
        return response()->json(['status' => 'success', 'msg' => $msg, 'data' => $data]);
    }
}

if(!function_exists('errorMessage')) {
    function errorMessage($msg) {
        return response()->json(['status' => 'error', 'msg' => $msg]);
    }
}



/**
 * PHP 非递归实现查询该目录下所有文件
 * @param unknown $dir
 * @return multitype:|multitype:string
 */
function scanfiles($dir) {
    if (! is_dir ( $dir )) return array ();

    // 兼容各操作系统
    $dir = rtrim ( str_replace ( '\\', '/', $dir ), '/' ) . '/';

    // 栈，默认值为传入的目录
    $dirs = array ( $dir );

    // 放置所有文件的容器
    $rt = array ();
    do {
        // 弹栈
        $dir = array_pop ( $dirs );
        // 扫描该目录
        $tmp = scandir ( $dir );
        foreach ( $tmp as $f ) {

            // 过滤. ..
            if ($f == '.' || $f == '..')
                continue;

            // 组合当前绝对路径
            $path = $dir . $f;

            // 如果是目录，压栈。
            if (is_dir ( $path )) {
                array_push ( $dirs, $path . '/' );
            } else if (is_file ( $path )) { // 如果是文件，放入容器中
                $rt [] = $path;
            }
        }
    } while ( $dirs ); // 直到栈中没有目录
    return $rt;
}


/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, $sortby='asc');
    }
    return $list;
}

//生成手机验证码，并保存

function generateSmsCode($phone)
{
    $code='';
    for($i=0;$i<4;$i++){
        $code .= rand(0,9);
    }
    session()->put('code', $code);
    $ip = getIp();
    $smsCode = \App\Model\SmsCode::where('phone', $phone)->where('is_use', 0)->first();
    if($smsCode) {
        $smsCode->code = $code;
        $smsCode->save();
    } else {
        $smsCode = new \App\Model\SmsCode();
        $smsCode->phone = $phone;
        $smsCode->code = $code;
        $smsCode->ip = $ip;
        $smsCode->save();
    }
    return $code;
}

/**
 * @param $url 请求网址
 * @param bool $params 请求参数
 * @param int $ispost 请求方式
 * @param int $https https协议
 * @return bool|mixed
 */
function curl($url, $params = false, $ispost = 0, $https = 0)
{
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if ($https) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    }
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        if ($params) {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
        }
    }

    $response = curl_exec($ch);
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
    curl_close($ch);
    return $response;
}

/**
 * 设置缓存
 * @param $key
 * @param $val
 */
function setRedis($key, array $val, $ex=60)
{
    Redis::setex($key,$ex, serialize($val));
}

/**
 * 获取缓存
 * @param $key
 * @return mixed
 */
function getRedis($key)
{
   return unserialize(Redis::get($key));
}

/**
 * 判断缓存是否存在
 * @param $key
 * @return mixed
 */
function hasRedis($key)
{
    return Redis::exists($key);
}

/**
 * 对图片进行Base64位转换
 */
if(!function_exists('base64EncodeImage')) {
    function base64EncodeImage ($image_file) {
        $base64_image = '';
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), getRemoteImageSize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }
}

function getMarket($key)
{
    if(hasRedis("market_".$key)) {
        return unserialize(getRedis("market_".$key));
    } else {
        $market = \App\Model\Market\Market::where('market_code', $key)->first()->toArray();
        setRedis("market_".$key, $market);
        return $market;

    }
}

function detect_encoding($str){
    $chars = NULL;
    $list = array('GBK', 'UTF-8');
    foreach($list as $item){
        $tmp = mb_convert_encoding($str, $item, $item);
        if(md5($tmp) == md5($str)){
            $chars = $item;
        }
    }
    return strtolower($chars) !== IN_CHARSET ? iconv($chars, strtoupper(IN_CHARSET).'//IGNORE', $str) : $str;
}
/**
 * 上传文件
 * @param $file
 * @return string
 */
function uploadFile($file)
{
    $extension = $file->getClientOriginalExtension(); //获取文件扩展名
    $destinationPath='/'.$extension; //定义目录
    $fileName =str_random(40);                //定义文件名称
    $fileNameFull=$fileName.'@.'.$extension;
    $fullPath = $destinationPath.'/'.$fileNameFull; //文件路劲
    Storage::put($fullPath, file_get_contents($file->getRealPath()));  //存储文件
    return $fullPath;
}


