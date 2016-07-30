Date.prototype.Format = function(fmt) {
  var o = {
    'm+': this.getMonth() + 1, //月份
    'd+': this.getDate(), //日
    'h+': this.getHours(), //小时
    'i+': this.getMinutes(), //分
    's+': this.getSeconds(), //秒
    'q+': Math.floor((this.getMonth() + 3) / 3), //季度
    'S': this.getMilliseconds() //毫秒
  };
  if (/(y+)/.test(fmt))
    fmt = fmt.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
  for (var k in o)
    if (new RegExp('(' + k + ')').test(fmt))
      fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (('00' + o[k]).substr(('' + o[k]).length)));
  return fmt;
};
Date.prototype.getWeekStartDate = function(fmt) {
  this.setDate(this.getDate() - this.getDay());
  return this.Format(fmt);
};
Date.prototype.getWeekEndDate = function(fmt) {
  this.setDate(this.getDate() + (6 - this.getDay()));
  return this.Format(fmt);
};
Date.prototype.getMonthStartDate = function(fmt) {
  this.setDate(1);
  return this.Format(fmt);
};
Date.prototype.getMonthEndDate = function(fmt) {
  var d1 = this, d2 = this;
  d1.setMonth(d1.getMonth() + 1);
  d1.setDate(1);
  d2.setDate(1);
  this.setDate((d1 - d2) / 86400000);
  return this.Format(fmt);
};