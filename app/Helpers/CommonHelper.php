<?php
if (!function_exists('idump')) {
    function idump($var, $echo = true, $label = null, $strict = true)
    {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . ' : ' . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo ($output);
            return null;
        } else {
            return $output;
        }
    }
}

if (!function_exists('arrayTrim')) {
    function arrayTrim($arr)
    {
        return array_filter($arr, create_function('$v', 'return ! empty($v);'));
    }
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @param string $len 长度
 * @param string $type 字串类型
 * @param string $addChars 额外字符
 * @return string
 */
if (!function_exists('randStr')) {
    function randStr($len = 6, $type = 0, $addChars = '')
    {
        $str = '';
        switch ($type) {
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 3:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 4:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 5:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $addChars;
                break;
            default: // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) {
            //位数过长重复字符串一定次数
            $chars = ($type == 1) ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
        return $str;
    }
}

/**
 * 10进制转2、8、16、36、62进制
 * @param int $num 要转换的数字
 * @param int $hex 要转换的进制(2,8,16,36,62)
 * @param int $length 字符宽度
 * @return string
 */
if (!function_exists('hexTrans')) {
    function hexTrans($num, $hex = 62, $length = 0)
    {
        if (!in_array($hex, array(2, 8, 16, 36, 62))) {
            return (string) $num;
        }
        if ($hex == 2) {
            return decbin($num);
        }
        if ($hex == 8) {
            return decoct($num);
        }
        if ($hex == 16) {
            return dechex($num);
        }
        if ($hex == 36) {
            return base_convert($num, 10, 36);
        }

        $str = '';
        while ($num != 0) {
            $n = $num % $hex;
            switch ($hex) {
                case 62:
                    if (($n >= 10) && ($n <= 35)) {
                        $str .= chr($n + 55);
                        break;
                    }
                    if (($n >= 36) && ($n <= 61)) {
                        $str .= chr($n + 61);
                        break;
                    }
                    $str .= $n;
                    break;
            }
            $num = intval($num / $hex);
        }
        return strrev(str_pad($str, $length, '0', STR_PAD_RIGHT));
    }
}

if (!function_exists('padLeft')) {
    function padLeft($str, $length)
    {
        return str_pad($str, $length, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('padRight')) {
    function padRight($str, $length)
    {
        return str_pad($str, $length, '0', STR_PAD_RIGHT);
    }
}

if (!function_exists('getFileExt')) {
    function getFileExt($file = '')
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}

if (!function_exists('getThumb')) {
    function getThumb($file = '')
    {
        if (empty($file)) {
            return;
        }
        extract(pathinfo($file));
        return $dirname . '/' . $filename . '_thumb.' . $extension;
    }
}

if (!function_exists('cutStr')) {
    function cutStr($str, $len)
    {
        $str = strip_tags($str);
        for ($i = 0; $i < $len; $i++) {
            $temp_str = substr($str, 0, 1);
            if (ord($temp_str) > 127) {
                $i++;
                if ($i < $len) {
                    $new_str[] = substr($str, 0, 3);
                    $str = substr($str, 3);
                }
            } else {
                $new_str[] = substr($str, 0, 1);
                $str = substr($str, 1);
            }
        }
        return htmlspecialchars(join($new_str), ENT_QUOTES);
    }
}

if (!function_exists('cutStr2')) {
    function cutStr2($str, $len)
    {
        $len1 = strlen($str);
        $str = strip_tags($str);
        for ($i = 0; $i < $len; $i++) {
            $temp_str = substr($str, 0, 1);
            if (ord($temp_str) > 127) {
                $i++;
                if ($i < $len) {
                    $new_str[] = substr($str, 0, 3);
                    $str = substr($str, 3);
                }
            } else {
                $new_str[] = substr($str, 0, 1);
                $str = substr($str, 1);
            }
        }

        $new_str = join($new_str);
        if (strlen($new_str) < $len1) {
            $new_str .= '…';
        }
        return htmlspecialchars($new_str, ENT_QUOTES);
    }
}

if (!function_exists('tt')) {
    function tt($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }
}

if (!function_exists('safe')) {
    function safe($str, $length = 100)
    {
        return mb_substr(trim($str), 0, $length, 'utf-8');
    }
}

if (!function_exists('safe2')) {
    function safe2($str)
    {
        return htmlFilter($str);
    }
}

if (!function_exists('htmlFilter')) {
    function htmlFilter($str)
    {
        $search = array("'<script[^>]*?>(.*?)</script>'i",
            "'(javascript|jscript|vbscript|vbs):'i",
            "'<iframe(.*)>.*</iframe>'i",
            "'<frameset(.*)>.*</frameset>'i",
            "'on(load|exit|error|mouse|key|click)'i");
        $replace = array("\\1", "", "", "", "");
        return preg_replace($search, $replace, $str);
    }
}

if (!function_exists('toLimitLng')) {
    function toLimitLng($num, $min = 0, $max = PHP_INT_MAX)
    {
        $num = intval($num);
        $min = intval($min);
        $max = intval($max);
        if ($num < $min) {
            return $min;
        }
        if ($num > $max) {
            return $max;
        }
        return $num;
    }
}

if (!function_exists('toDate')) {
    function toDate($time, $format = 'Y-m-d H:i:s')
    {
        if (empty($time)) {
            return '';
        }
        return date($format, $time);
    }
}

if (!function_exists('percent')) {
    function percent($p, $t)
    {
        return sprintf('%.2f%%', $p / $t * 100);
    }
}

if (!function_exists('postData')) {
    function postData($host, $port, $page, $data)
    {
        $sock = fsockopen($host, $port, $errno, $errstr, 30);
        if (!$sock) {
            return '';
        }

        fwrite($sock, 'POST ' . ($page ? $page . ' ' : '') . "HTTP/1.0\r\n");
        fwrite($sock, 'Host: ' . $host . "\r\n");
        fwrite($sock, "Content-type: application/x-www-form-urlencoded\r\n");
        fwrite($sock, "Content-length: " . strlen($data) . "\r\n");
        fwrite($sock, "Accept: */*\r\n");
        fwrite($sock, "\r\n");
        fwrite($sock, $data);
        $headers = '';
        while ($str = trim(fgets($sock, 4096))) {
            $headers .= $str . "\n";
        }

        $body = '';
        while (!feof($sock)) {
            $body .= fgets($sock, 4096);
        }

        fclose($sock);
        return $body;
    }
}

if (!function_exists('curlPost')) {
    function curlPost($url,$method='POST', $data = '', $header = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        switch($method){
            case 'GET':
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            print curl_error($ch);
        } else {
            curl_close($ch);
            return $result;
        }
    }
}

if (!function_exists('realPath')) {
    function realPath($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}

if (!function_exists('uploadPath')) {
    function uploadPath()
    {
        $dir = public_path() . DIRECTORY_SEPARATOR . 'upload';
        return (is_dir($dir) or mkdir($dir, 0777, true)) ? realpath($dir) : false;
    }
}

if (!function_exists('filesizeFormat')) {
    function filesizeFormat($size)
    {
        $units = array(' B', ' KB', ' MB', ' GB', ' TB');
        for ($i = 0; $size >= 1024 && $i < 4; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $units[$i];
    }
}

if (!function_exists('timelongFormat')) {
    function timelongFormat($seconds)
    {
        return gmstrftime('%H:%M:%S', $seconds);
    }
}

if (!function_exists('deleteDir')) {
    function deleteDir($dir)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != '.' and $file != '..') {
                $fullpath = $dir . DIRECTORY_SEPARATOR . $file;
                is_dir($fullpath) ? deleteDir($fullpath) : unlink($fullpath);
            }
        }
        closedir($dh);
        return rmdir($dir);
    }
}

if (!function_exists('xCopy')) {
    function xCopy($source, $destination, $child)
    {
        if (!is_dir($source)) {
            return false;
        }
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $handle = dir($source);
        while ($entry = $handle->read()) {
            if ($entry != '.' and $entry != '..') {
                if (is_dir($source . '/' . $entry)) {
                    if ($child) {
                        xCopy($source . '/' . $entry, $destination . '/' . $entry, $child);
                    }
                } else {
                    copy($source . '/' . $entry, $destination . '/' . $entry);
                }
            }
        }
        return true;
    }
}

if (!function_exists('ipQuery')) {
    function ipQuery($ip)
    {
        if (is_ip($ip) == false) {
            $ip = long2ip($ip);
        }

        $url = 'http://whois.pconline.com.cn/ip.jsp?ip=' . $ip;
        $str = @file_get_contents($url);
        if ($str !== false) {
            $str = iconv('gb2312', 'utf-8', $str);
            return $str;
        }

        $url = 'http://sucha.cwaic.com/service/query.asmx/GetIpInfo?ip=' . $ip;
        $xml = @simplexml_load_file($url);
        if ($xml !== false) {
            $data = json_decode($xml);
            $str = strtr($data[0]->Country . $data[0]->City, array('CZ88.NET' => ''));
            return $str;
        }

        $url = 'http://ipquery.sdo.com/getipinfo.php?ip=' . $ip;
        $xml = @simplexml_load_file($url);
        if ($xml !== false) {
            $str = $xml->country . $xml->city . $xml->sp;
            return $str;
        }
        return $ip . ' - Unknown Address';
    }
}

if (!function_exists('isMobile')) {
    function isMobile($str)
    {
        if (strlen($str) != 11 || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', $str)) {
            return false;
        }
        return true;
    }
}

if (!function_exists('isEmail')) {
    function isEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isCardid')) {
    function isCardid($vStr)
    {
        $vCity = [
            '11', '12', '13', '14', '15', '21', '22', '23',
            '31', '32', '33', '34', '35', '36', '37',
            '41', '42', '43', '44', '45', '46', '50', '51', '52', '53', '54',
            '61', '62', '63', '64', '65', '71', '81', '82', '91',
        ];
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) {
            return false;
        }
        if (!in_array(substr($vStr, 0, 2), $vCity)) {
            return false;
        }
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) {
            return false;
        }
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) {
                return false;
            }
        }
        return true;
    }
}

/**
 * @brief 文件大小展示计算
 * @param $size
 * @param string $unit
 * @param int $decimals
 * @param string $targetUnit
 * @return string
 */
if (!function_exists('fileSizeConv')) {
    function fileSizeConv($size, $unit = 'B', $decimals = 1, $targetUnit = 'auto')
    {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
    $theUnit = array_search(strtoupper($unit), $units); //初始单位是哪个
    //判断是否自动计算，
    if ($targetUnit != 'auto') {
        $targetUnit = array_search(strtoupper($targetUnit), $units);
    }
    //循环计算
    while ($size >= 1024) {
        $size /= 1024;
        $theUnit++;
        if ($theUnit == $targetUnit) //已符合给定则退出循环吧！
        {
            break;
        }
    }
    return sprintf("%1\$.{$decimals}f", $size) . $units[$theUnit];
    }
}
