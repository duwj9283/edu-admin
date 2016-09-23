<?php
/**
 * Content Delivery Network
 */
if (!function_exists('cdn1')) {
    function cdn1($url)
    {
        return '/' . $url;
    }
}

/**
 * Uniform Resource Identifier
 */
if (!function_exists('uri')) {
    function uri($url = '', $spare = 'assets/images/blank.gif')
    {
        return empty($url) ? asset($spare) : asset($url);
    }
}
/**
 * 检查远程文件是否存在
 */
if (!function_exists('remote_file_exists')) {
    function remote_file_exists($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        $is_exists = false;
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $is_exists = true;
            } elseif ($statusCode == 304) {
                $is_exists = true;
            }
        }
        curl_close($curl);
        return $is_exists;
    }
}
/**
 * 检查远程文件是否存在
 */
if (!function_exists('remoteFileExists')) {
    function remoteFileExists($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        $is_exists = false;
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) {
                $is_exists = true;
            } elseif ($statusCode == 304) {
                $is_exists = true;
            }
        }
        curl_close($curl);
        return $is_exists;
    }
}

/**
 * 获取用户目录
 */
if (!function_exists('getUserDir')) {
    function getUserDir($uid)
    {
        return 'upload/u' . base_convert(ceil($uid / 2000), 10, 36) . '/' . base_convert($uid, 10, 36) . '/';
    }
}

/**
 * 获取用户照片
 */
if (!function_exists('getPhoto')) {
    function getPhoto($uid)
    {
        return getAvatar($uid);
    }
}

/**
 * 获取用户头像
 */
if (!function_exists('getAvatar')) {
    function getAvatar($uid)
    {
        $face = 'upload/avatar/' . $uid . '.jpg';
        if (file_exists(public_path() . DIRECTORY_SEPARATOR . $face)) {
            return asset($face);
        }
        return asset('assets/avatar/' . strval($uid % 40) . '.jpg');
    }
}
