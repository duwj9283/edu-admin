<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EncoderController extends Controller
{

    /**
     * 设置高清编码器
     * @param action 操作类型 1设置台标
     * @date 2016.7.28
     */
    function postSet(Request $Request){
        $action=$Request->input('action');
        $to=0;//默认调用cs接口 为1调用des接口
        switch($action){
            case 1://设置台标信息
                $enabled=$Request->input('enabled',1);//是否启用台标 默认启用台标
                $x=$Request->input('x');//台标在画面的起始位置X坐标
                $y=$Request->input('y');//台标在画面的起始位置y坐标
                $alpha=$Request->input('alpha',128);//叠加到视频层上的整体透明度，值范围0-128，0表示透明，128表示100%不透明
                $buf = pack("CvCvvCC", config('encoder.SET_TB_CMD'), 7, $enabled, $x, $y,$alpha, 0);
                break;
            case 2://设置分屏信息
                $window_all=['3G-SDI1','3G-SDI2','3G-SDI3','DVI-IN'];//0-3对应板子的接口3G-SDI1,DVI-IN, 3G-SDI2,3G-SDI3，5表示IPC1输入，6表示IPC2。
                $type=$Request->input('type',1);//编码混屏类型1=>单窗口; 2=>两窗口; 3=>画外画; 4=>画中画;5=>1+2窗口;6=>4窗口; 7=>1+3窗口
                $window1=$Request->input('window1',255);//255表示无输入源,每个窗口输入通道号不能相同
                $window2=$Request->input('window2',255);
                $window3=$Request->input('window3',255);
                $window4=$Request->input('window4',255);
                $buf = pack("CvCCCCC", config('encoder.SET_COM_WIN_MODE'), 5, $type, $window1, $window2,$window3, $window4);
                break;
            case 3://设置字幕信息

                $enable=(int)$Request->input('enable',1);//是否启用字幕
                $fam_id=(int)$Request->input('fam_id',8);//字体ID
                //1华文楷体2华文仿宋3华文行楷4华文细黑5华文琥珀6华文彩云7华文新魏8黑体常规9隶书常规10幼圆常规11微软雅黑12方正舒体13方正姚体
                $alpha=$Request->input('alpha',128);//字幕整体透明度，值范围0-128。0表示透明，128表示100%不透明
                $bk_col=$Request->input('bk_col',0);//字幕整体背景色，以16进制表示的颜色，如0xFF0000表示红色。背景透明对应设置为#FFFFFF
                $txt_col=$Request->input('txt_col',hexdec('FF0000'));//字幕字体颜色，以16进制表示的颜色，如0xFF0000表示红色
                $stroke=$Request->input('stroke',0);//是否加上轮廓
                $str_col=$Request->input('str_col',0);//轮廓颜色，以16进制表示的颜色
                $strwidth=$Request->input('strwidth',1);//轮廓宽度
                $fontsize=$Request->input('fontsize',50);//文字大小30 40 50 60 70 80
                $txtx=$Request->input('txtx',0);//文字在字幕图片区域的坐标x
                $txty=$Request->input('txty',40);//文字在字幕图片区域的坐标y
                $txt=trim($Request->input('txt'));//字幕文字内容，utf-8编码，最大200字节，少于50个汉字
                $txtw=mb_strlen($txt,'utf8')*($fontsize);//字幕形成图片的宽度
                $txth=$fontsize+10;//字幕形成图片的高度
                $tit_txt_len=mb_strlen($txt,'utf8');
                $max_len=1920/$fontsize;
                if($tit_txt_len>$max_len){
                    $txt=mb_substr($txt,0,$max_len,'utf8');
                }

         
                $buf = pack("CvCCCVVCVCvvvvva".$tit_txt_len,  config('encoder.SET_SUBTITLE_PARAM'), 27+$tit_txt_len, $enable,
                    $fam_id, $alpha,0xFFFFFFFF,$txt_col, $stroke,$str_col,
                    $strwidth, $fontsize, $txtw, $txth, $txtx,
                    $txty,$txt);


                break;
            case 4://设置音频输入音量
                $pass=$Request->input('pass',0);//通道：支持2路输入， 0表示MIC输入，1表示LINE IN,  2表示混音后通道
                $volume=$Request->input('volume',0);//音量：支持10级可调分别为0-10,0表示原始音大小
                $buf = pack("CvCC", config('encoder.SET_AUDIO_INPUT_VOL'), 2,  $pass, $volume);
                break;
            case 5://云台控制请求
                $to=1;//调用des请求
                $addr=$Request->input('addr');//云台的485地址，范围0-255
                $type=$Request->input('type');//云台的协议类型0:visca协议1:pelco-d协议2:pelco-p协议
                //云台操作类型0：LEFT1：LEFTUP2：UP3：RIGHTUP4：RIGHT5：RIGHTDOWN6：DOWN7：LEFTDOWN8：ZOOMIN9：ZOOMOUT10:STOP
                $operation=$Request->input('operation');
                $speed=$Request->input('speed');//云台运行速度，VISCA协议值范围0-14，PELCO协议值范围0-63.
                //说明：0-9表示发起云台运行命令，在没有收到STOP命令前，动作会一直持续下去
                $buf = pack("CvCCCC", config('encoder.PTZ_CTRL_REQ'), 4,  $addr, $type,$operation,$speed);
                break;
        }
        if($to>0){
            $result=$this->SendSocketMsgDES($buf);

        }else{
            $result=$this->SendSocketMsgCS($buf);

        }
        if($result){
            return $this->response(true);
        }
        return $this->error(false);

    }

    /**
     * 底层音视频,流等的API接口服务
     * @param $msg
     * @return bool
     */
    function SendSocketMsgCS($msg)
    {
        $sock = @socket_create(AF_INET,SOCK_DGRAM,0);//创建一个套接字
        if (!$sock) {
            die("unable to create AF_INET socket");
        }
        $buf=$msg.md5($msg,TRUE);
        echo 111;
        if(!@socket_sendto($sock,$buf,strlen($buf),0,"192.168.1.98",36210)){
            socket_close($sock);
            return false;
        }
        socket_close($sock);
        return true;
    }

    /**
     * 上层逻辑相关的功能实现
     * @param $msg
     * @return bool
     */
    function SendSocketMsgDES($msg)
    {
        $sock = @socket_create(AF_INET,SOCK_DGRAM,0);
        if (!$sock) {
            die("unable to create AF_INET socket");
        }
        $buf=$msg.md5($msg,TRUE);
        //36211是des的监听端口
        if(!@socket_sendto($sock,$buf,strlen($buf),0,"192.168.1.98",36211)){
            socket_close($sock);
            return false;
        }
        socket_close($sock);
        return true;
    }

}
