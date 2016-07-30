String.prototype.isMobile = function() {
  return /^0{0,1}1[0-9]{10}$/.test(this);
};

String.prototype.isEmail = function() {
  return /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(this);
};

String.prototype.isIDCard = function() {
  var iSum = 0;
  var sId = this;
  var aCity = {
    11: "北京",
    12: "天津",
    13: "河北",
    14: "山西",
    15: "内蒙古",
    21: "辽宁",
    22: "吉林",
    23: "黑龙江",
    31: "上海",
    32: "江苏",
    33: "浙江",
    34: "安徽",
    35: "福建",
    36: "江西",
    37: "山东",
    41: "河南",
    42: "湖北",
    43: "湖南",
    44: "广东",
    45: "广西",
    46: "海南",
    50: "重庆",
    51: "四川",
    52: "贵州",
    53: "云南",
    54: "西藏",
    61: "陕西",
    62: "甘肃",
    63: "青海",
    64: "宁夏",
    65: "新疆",
    71: "台湾",
    81: "香港",
    82: "澳门",
    91: "国外"
  };
  if (!/^\d{17}(\d|x)$/i.test(sId)) {
    return false;
  }
  sId = sId.replace(/x$/i, "a");
  //非法地区
  if (aCity[parseInt(sId.substr(0, 2))] == null) {
    return false;
  }
  var sBirthday = sId.substr(6, 4) + "-" + Number(sId.substr(10, 2)) + "-" + Number(sId.substr(12, 2));
  var d = new Date(sBirthday.replace(/-/g, "/"))

  //非法生日
  if (sBirthday != (d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate())) {
    return false;
  }
  for (var i = 17; i >= 0; i--) {
    iSum += (Math.pow(2, i) % 11) * parseInt(sId.charAt(17 - i), 11);
  }
  if (iSum % 11 != 1) {
    return false;
  }
  return true;
};

String.prototype.colorHex = function(){
  var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
  var that = this;
  if(/^(rgb|RGB)/.test(that)){
    var aColor = that.replace(/(?:\(|\)|rgb|RGB)*/g,"").split(",");
    var strHex = "#";
    for(var i=0; i<aColor.length; i++){
      var hex = Number(aColor[i]).toString(16);
      if(hex === "0"){
        hex += hex;
      }
      strHex += hex;
    }
    if(strHex.length !== 7){
      strHex = that;
    }
    return strHex;
  }else if(reg.test(that)){
    var aNum = that.replace(/#/,"").split("");
    if(aNum.length === 6){
      return that;
    }else if(aNum.length === 3){
      var numHex = "#";
      for(var i=0; i<aNum.length; i+=1){
        numHex += (aNum[i]+aNum[i]);
      }
      return numHex;
    }
  }else{
    return that;
  }
};