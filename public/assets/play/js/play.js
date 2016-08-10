/*
*导播控制台js
 */
var ip=$('#player').data('ip');//ip地址

//调用编码器接口
var setEncoder=function(data,result){

	data.ip=ip;

	$.post('/api/encoder/set',data,result).fail(failure);
};
var failure = function(data) {
	var str = getStringByArray(data);
	dialog({
		content: '<i class="fa fa-info-circle"></i> ' + str,
		ok: true,
		zIndex: 2100
	}).showModal();
	return false;
};
var getStringByArray=function(data){
	if(typeof(data) == 'string'){
		return data;
	}
	if(typeof(data) == 'object'){
		for(i in data.responseJSON){

			return data.responseJSON[i][0];

		}
	}


}
/*****************************************分屏-S*******************************************/

//导播分频
$('#play-win li').click(function(){
	$(this).addClass("current").siblings('li').removeClass("current");
	var param=getWinSet($(this).index());
	setEncoder(param,'');
});
//分频配置
var getWinSet=function(type){
	var winSet={};
	winSet.action=2;//设置分屏信息
	winSet.win_type=type;
	switch (type){
		case 0:

			winSet.type=1;//单窗口
			winSet.window1=0;
			break;
		case 1:
			winSet.type=1;//单窗口
			winSet.window1=2;
			break;
		case 2:
			winSet.type=1;//单窗口
			winSet.window1=3;
			break;
		case 3:
			winSet.type=1;//单窗口
			winSet.window1=1;
			break;
		case 4:
			winSet.type=2;
			winSet.window1=0;
			winSet.window2=2;
			break;
		case 5:
			winSet.type=2;
			winSet.window1=0;
			winSet.window2=3;
			break;
		case 6:
			winSet.type=5;
			winSet.window1=0;
			winSet.window2=2;
			winSet.window3=3;
			break;
		case 7:
			winSet.type=6;
			winSet.window1=0;
			winSet.window2=2;
			winSet.window3=3;
			winSet.window4=1;
			break;
	}
	return winSet;
};

/*****************************************分屏-E*******************************************/
/*****************************************字幕-S*******************************************/

//字幕
$('form[name="subtitle"]').submit(function(){
	var enable=($(this).find('input[type="checkbox"]').prop("checked")==true)?1:0;
	var txt=$(this).find("textarea").val();//字幕内容
	if(enable==1&&!txt){
		failure("请先输入字幕内容！");
	}
	setEncoder($(this).serialize()+"&enable="+enable+"&action=3",'');
});
/*****************************************字幕-E*******************************************/
/*****************************************台标-S*******************************************/

//台标设置
$('#play-tb li').click(function(){
	$(this).addClass("current").siblings('li').removeClass("current");
	var param=getTbSet($(this).index());
	setEncoder(param,'');
});
var tbSet={};//台标配置
//台标预设配置
var getTbSet=function(type){

	tbSet.action=1;//设置台标屏信息
	switch (type){
		case 0:
			tbSet.enabled=0;//不启用台标
			tbSet.x=0;
			tbSet.y=0;
			break;
		case 1:
			tbSet.enabled=1;
			tbSet.x=0;
			tbSet.y=0;
			break;
		case 2:
			tbSet.enabled=1;
			tbSet.x=0;
			tbSet.y=800;
			break;
		case 3:
			tbSet.enabled=1;
			tbSet.x=1600;
			tbSet.y=0;
			break;
		case 4:
			tbSet.enabled=1;
			tbSet.x=1600;
			tbSet.y=800;
			break;

	}

	return tbSet;
};

//台标预设位置
$("#play-tb-detail").click(function(){
	$("#myEditModal").html(template('play-tb-detail-div')).modal('show');
	$('#tbSet-form').find('input[name="x"]').val(tbSet.x);//填充预设x、y值
	$('#tbSet-form').find('input[name="y"]').val(tbSet.y);

});
//台标弹窗提交
$('#myEditModal').delegate('.js-tb-sub','click',function(){
	setEncoder($('#tbSet-form').serialize(),'');
	$("#myEditModal").modal('hide');

});
/*****************************************台标-E*******************************************/
/*****************************************直播-S*******************************************/

//直播
$('#play-RTMP').click(function(){

	var $this=$(this);
	var enable=$this.data("enable");
	var d = dialog({
		title: '直播',
		content:(enable==1)?'确定打开直播功能！？':'确定关闭直播功能！？',
		ok: function () {
			var that = this;
			setEncoder({action:6,enable:enable},function(){
				$this.data("enable",(enable==1)?0:1);//重设置
				that.close().remove();
				$this.html((enable==1)?'直播：关':'直播：开');//重设置
			});
			return false;
		},
		cancel:true
	}).show();


});
/*****************************************直播-E*******************************************/
/*****************************************录制-S*******************************************/
var record_status=2;//录制状态 2默认值停止录制 1开始录制 3暂停录制 4继续录制
var record_time=parseInt($('#player').data('record'));//录制时长
var record_time_fromat='00:00:00';//录制格式化后
if(record_time>0){//如果正在录制
	recordTime=setInterval(setRecordTime,1000);
	record_time_fromat=get_format_time(record_time);
	$('#play-record-time').html("录制中 ["+record_time_fromat+"]");
}

var recordTime;//录制时间
//格式换秒数
function get_format_time(s) {
	var t;
	if(s > -1){
		hour = Math.floor(s/3600);
		min = Math.floor(s/60) % 60;
		sec = Math.floor(s % 60);
		day = parseInt(hour / 24);
		if(hour < 10){hour += "0";}
		if (day > 0) {
			hour = hour - 24 * day;
			t = day + "day " + hour + ":";
		}else{
			t = hour + ":";
		}

		if(min < 10){t += "0";}
		t += min + ":";
		if(sec < 10){t += "0";}
		t += sec;
	}
	return t;

}
function setRecordTime() {
	record_time++;
	record_time_fromat=get_format_time(record_time);
	$('#play-record-time').html("录制中 ["+record_time_fromat+"]");

}


//录制
$('#play-record').click(function(){
	var $this=$(this);
	switch(record_status){
		case 2:
			record_status=1;//开始录制
			setEncoder({action:7,status:record_status},function(){
				recordTime=setInterval(setRecordTime,1000);//开始计算录制时间
				$this.html("停止");//“录制”按钮内容改为 “停止”
			});
			break;
		case 1:case 3:case 4:
			record_status=2;//停止录制
			setEncoder({action:7,status:record_status},function(){
				$this.html("录制");//“停止”按钮内容改为 “录制”
				clearInterval(recordTime);//清除录制时间计算
				record_time=0;//重置录制时长
				record_time_fromat='00:00:00';//重置录制格式化后的时长
				$('#play-record-time').html("未录制 ["+record_time_fromat+"]");//更改录制显示
				$('#play-pause-record').html("暂停");//录制后面按钮内容 改为“暂停”
			});
			break;
	}


});
//录制暂停按钮
$('#play-pause-record').click(function(){
	var $this=$(this);
	switch(record_status){
		case 1:
			record_status=3;//暂停录制

			setEncoder({action:7,status:record_status},function(){
				clearInterval(recordTime);//清除录制时间计算
				$('#play-record-time').html("暂停中 ["+record_time_fromat+"]");//更改录制显示
				$this.html("恢复");
			});
			break;
		case 3:
			record_status=4;//继续录制

			setEncoder({action:7,status:record_status},function(){
				recordTime=setInterval(setRecordTime,1000);
				$this.html("暂停");
				$('#play-record-time').html("录制中 ["+record_time_fromat+"]");
			});
			break;
	}


});
/*****************************************录制-E*******************************************/
/*****************************************音量-S*******************************************/
var volume=($('#player').data('volume'))*5;//初始化音量volume范围相对于[0-20]变为[0-100]

//音量调节
scale = function (btn, bar) {
	this.btn = document.getElementById(btn);
	this.bar = document.getElementById(bar);
	this.step = this.bar.getElementsByTagName("DIV")[0];
	this.init();
};
scale.prototype = {
	init: function () {
		var f = this, g = document, b = window, m = Math;
		var to_init=volume*this.bar.offsetWidth/100;//初始化音量百分比
		this.step.style.width = to_init + 'px';
		this.btn.style.left = to_init + 'px';



		f.btn.onmousedown = function (e) {

			var x = (e || b.event).clientX;
			var l = this.offsetLeft;
			var max = f.bar.offsetWidth - this.offsetWidth;

			var pos=0;//整个长度是100 [0-100]表示位置
			g.onmousemove = function (e) {

				var thisX = (e || b.event).clientX;
				var to = m.min(max, m.max(-2, l + (thisX - x)));
				pos = m.round(m.max(0, to / max) * 100);

				f.btn.style.left = to + 'px';
				f.ondrag(pos, to);
				b.getSelection ? b.getSelection().removeAllRanges() : g.selection.empty();
			};
			//g.onmouseup = new Function('this.onmousemove=null');
			g.onmouseup =function(e){
				this.onmousemove=null;
				var volume=m.ceil(pos/5);//把范围值变为[0-20],上取整
				setEncoder({action:4,volume:volume},'');//鼠标确认点击后 调用时间设置api
			};
		};
	},
	ondrag: function (pos, x) {
		this.step.style.width = Math.max(0, x) + 'px';

	}
};
new scale('btn', 'bar');

/*****************************************音量-E*******************************************/
/*****************************************云台-S*******************************************/
var ctrl_addr=0;//选择的摄像头
//控制摄像头8个方位 控制摄像头放大放小
$('#play-ctrl-top a,#play-ctrl-bottom a').mousedown(function(){

	if(ctrl_addr==0){
		failure("请先选择摄像头！");return false;
	}
	var operation=$(this).data('operation');
	setEncoder({action:5,addr:ctrl_addr,operation:operation},'');//鼠标确认点击后 调用时间设置api

});

//松开鼠标后 摄像头停止移动
$('#play-ctrl-top a,#play-ctrl-bottom a').mouseup(function(){

	if(ctrl_addr==0){
		failure("请先选择摄像头！");return false;
	}
	setEncoder({action:5,addr:ctrl_addr,operation:10},'');

});
//选择摄像头
$('input[name="winMode"]').click(function(){
	ctrl_addr=$(this).val();

});
//预置位置
$('#play-ctrl-top li').click(function(){
	if(ctrl_addr==0){
		failure("请先选择摄像头！");return false;
	}
	setEncoder({action:5,addr:ctrl_addr,operation:14,num:$(this).index()+1},'');

});
/*****************************************云台-E*******************************************/
