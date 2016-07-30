<?php
namespace App\Libraries;

class WeimiSender
{
    public $url = 'http://api.weimi.cc/2/sms/send.html';
    public $uid = 'y6VIcXntpNHq';
    public $pas = '26y6pgdx';
    public $type = 'json';

    public function __construct($props = [])
    {
        if (count($props) > 0) {
            $this->initialize($props);
        }
    }

    /**
     * 初始化
     */
    public function initialize($props = [])
    {
        if (count($props) > 0) {
            foreach ($props as $key => $val) {
                if (in_array($key, ['url', 'uid', 'pas', 'type'])) {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
     * 判断手机号有效性
     */
    private function isMobile($str)
    {
        if (strlen($str) != 11 || !preg_match('/^1[3|4|5|7|8][0-9]\d{4,8}$/', $str)) {
            return false;
        }
        return true;
    }

    /**
     * 发送短信
     * @param  $mob    [手机号] 多手机号请使用Array
     * @param  $cid    [短信模板编号]
     * @param  $params [参数列表]
     * @return 成功返回 True, 失败返回错误信息
     */
    public function send($mob, $cid, $params = [])
    {
        $mobs = [];
        if (is_array($mob)) {
            foreach ($mob as $val) {
                if ($this->isMobile($val)) {
                    $mobs[] = $val;
                }
            }
        } else {
            if ($this->isMobile($mob)) {
                $mobs[] = $mob;
            }
        }
        if (empty($mobs)) {
            return '手机号参数不正确';
        }
        $mob = implode(',', $mobs);
        if (empty($cid)) {
            return '短信模板参数不正确';
        }
        $data = [
            'uid' => $this->uid,
            'pas' => $this->pas,
            'mob' => $mob,
            'cid' => $cid,
        ];
        if (is_array($params)) {
            foreach (array_values($params) as $k => $val) {
                $data['p' . ($k + 1)] = $val;
            }
        } else {
            $data['p1'] = strval($params);
        }
        $data['type'] = $this->type;
        $postfields = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        $res = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($res);
        if ($result->code == 0) {
            return true;
        } else {
            return $result->msg;
        }
    }
}
