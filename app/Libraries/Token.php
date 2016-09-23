<?php
namespace App\Libraries;

class Token
{
    public $token_auth;
    public $token_dir;
    public $token_ext;

    public function __construct($props = array())
    {
        if (is_array($props)) {
            $this->init($props);
        } else {
            $this->init();
        }
    }

    public function init($props = array())
    {
        $this->token_auth = array_key_exists('auth', $props) ? strval($props['auth']) : 'undefined';
        $token_path = storage_path() . '/token/' . $this->token_auth . '/';
        $this->token_dir = str_replace('/', DIRECTORY_SEPARATOR, $token_path);
        $this->token_ext = '.token';

        if (!is_dir($this->token_dir)) {
            if (!mkdir($this->token_dir, 0777, 1)) {
                exit('Failed to initialize Token.');
            }
        }
    }

    public function __destruct()
    {
        clearstatcache();
    }

    public function get($code)
    {
        if (empty($code)) {
            return false;
        }
        $file = $this->token_dir . $code . $this->token_ext;
        if (!file_exists($file)) {
            return false;
        }
        if (filemtime($file) < time()) {
            $this->del($code);
            return false;
        }
        $data = unserialize(file_get_contents($file));
        $expire = array_key_exists('expire', $data) ? intval($data['expire']) : 86500;
        touch($file, time() + $expire);
        return array_diff_key($data, array_flip(['create_time', 'expire']));
    }

    public function set($data, $expire = 86500)
    {
        if (!is_array($data)) {
            return false;
        }

        $data['create_time'] = time();
        $data['expire'] = $expire;

        $token_id = sha1(uniqid(mt_rand()));
        $token_file = $this->token_dir . $token_id . $this->token_ext;
        $token_data = serialize($data);

        if (!file_put_contents($token_file, $token_data)) {
            return false;
        }

        touch($token_file, time() + $expire);
        return $token_id;
    }

    public function edit($code, $data)
    {
        if (empty($code) || (!is_array($data))) {
            return false;
        }
        $token_file = $this->token_dir . $code . $this->token_ext;
        if (!file_exists($token_file)) {
            return false;
        }

        $token_data = array_merge(unserialize(file_get_contents($token_file)), $data);
        if (!file_put_contents($token_file, serialize($token_data))) {
            return false;
        }

        $expire = array_key_exists('expire', $token_data) ? intval($token_data['expire']) : 86500;
        touch($token_file, time() + $expire);
        return true;
    }

    public function del($token_code)
    {
        $token_file = $this->token_dir . $token_code . $this->token_ext;
        if (is_file($token_file)) {
            return unlink($token_file);
        } else {
            return true;
        }
    }

    public function clear()
    {
        $dh = opendir($this->token_dir);
        while ($file = readdir($dh)) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $file_ext = '.' . pathinfo($file, PATHINFO_EXTENSION);
            if ($file_ext == $this->token_ext) {
                $file = $this->token_dir . $file;
                if (filemtime($file) < time()) {
                    unlink($file);
                }
            }
        }
        closedir($dh);
        return true;
    }
}
