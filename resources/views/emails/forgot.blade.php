<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>找回登录密码</title>
  </head>
  <body>
    <div>
      <p>您好：</p>
      <p>您的找回密码验证码是： <code>{{$code}}</code> </p>
      <p>请在 10 分钟内使用，过期失效</p>
      <p>安徽魅课信息科技有限公司</p>
      <p>{{date('Y-m-d H:i:s', time())}}</p>
    </div>
  </body>
</html>
