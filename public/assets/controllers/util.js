var $G = function(id) {
  return document.getElementById(id);
};

var required_check = function(str) {
  if (str.replace(/(^\s*)|(\s*$)/g, '') === '') {
    return false;
  } else {
    return true;
  };
};

var failure = function(data) {
  var str = typeof(data) == 'string' ? data : data.responseJSON;
  dialog({
    content: '<i class="fa fa-info-circle"></i> ' + str,
    ok: true
  }).showModal();
  return false;
};

var artInfo = function(data) {
  var str = typeof(data) == 'string' ? data : data.responseJSON;
  var d = dialog({
    title: false,
    content: str
  }).show();
  setTimeout(function() {
    d.close().remove();
  }, 1000);
};

var artTabs = function (bar, className, index) {
  var gid = function (id) {return document.getElementById(id)},
    buttons = bar.getElementsByTagName('a'),
    selectButton = buttons[index],
    showContent = gid(selectButton.href.split('#')[1]),
    target;
  bar.onclick = function (event) {
    event = event || window.event;
    target = event.target || event.srcElement;
    if (target.nodeName.toLowerCase() === 'a') {
      showContent.style.display = 'none';
      showContent = gid(target.href.split('#')[1]);
      showContent.style.display = 'block';
      selectButton.className = '';
      selectButton = target;
      target.className = className;
      return false;
    };
  };
};

var getGetArgs = function(key) {
  var args = {};
  var query = location.search.substring(1);
  var pairs = query.split('&');
  for (var i = 0; i < pairs.length; i++) {
    var pos = pairs[i].indexOf('=');
    if (pos == -1) continue;
    var argname = pairs[i].substring(0, pos);
    var value = pairs[i].substring(pos + 1);
    value = decodeURIComponent(value);
    args[argname] = value;
  }
  return key !== undefined ? args[key] : args;
};

var getRealSize = function(s) {
  var size = parseInt(s);
  var n, kb = 1024,
    mb = kb * 1024,
    gb = mb * 1024,
    tb = gb * 1024;
  if (size < kb) return size + " B";
  if (size < mb) return (size / kb).toFixed(2).replace(/(\.?0*$)/g, '') + " KB";
  if (size < gb) return (size / mb).toFixed(2).replace(/(\.?0*$)/g, '') + " MB";
  if (size < tb) return (size / gb).toFixed(2).replace(/(\.?0*$)/g, '') + " GB";
  return (size / tb).toFixed(2).replace(/(\.?0*$)/g, '') + " TB";
};

var page = function(page_count, total_rows, page_no) {
  if (page_count < 2) return '';
  page_no = parseInt(page_no);
  var page_str = '<ul class="pagination">';
  if (page_no > 1) {
    page_str += '<li><a href="javascript:;" rel="' + (page_no - 1) + '">« 上一页</a></li>';
  } else {
    page_str += '<li><span>« 上一页</a></span>';
  }
  var more = 4;
  var m = (page_no - Math.ceil(more / 2) > 0) ? (page_no - Math.ceil(more / 2)) : 1; //起始页码
  var n = (m + more < page_count) ? (m + more) : page_count; //终止页码
  m = ((n - m) < more) ? (n - more) : m;
  m = (m > 0) ? m : 1;
  if (m > 1) {
    page_str += '<li><a href="javascript:;" rel="1">1</a></li>';
    if (m > 2) {
      page_str += '<li><span>…</span></li>';
    }
  }
  for (i = m; i <= n; i++) {
    if (i == page_no) {
      page_str += '<li class="active"><span>' + i + '</span></li>';
    } else {
      page_str += '<li><a href="javascript:;" rel="' + i + '">' + i + '</a></li>';
    }
  }
  if (i <= page_count) {
    if (i != page_count) {
      page_str += '<li><span>…</span></li>';
    }
    page_str += '<li><a href="javascript:;" rel="' + page_count + '">' + page_count + '</a></li>';
  }
  if (page_no < page_count) {
    page_str += '<li><a href="javascript:;" rel="' + (page_no + 1) + '">下一页 »</a></li>';
  } else {
    page_str += '<li><span>下一页 »</span></li>';
  }
  return page_str + '</ul>';
};

seajs.config({
  base: '/assets/'
});