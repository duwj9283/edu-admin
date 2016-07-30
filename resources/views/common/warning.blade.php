<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warning</title>
    <link rel="stylesheet" href="{{cdn1('assets/bootstrap/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{cdn1('assets/font-awesome/css/font-awesome.min.css')}}" />
    <style>
        body {margin:0;padding:0;width:100%;height:100%;display:table;font-family:'Microsoft Yahei';}
        a { text-decoration: none; color: #036;}
        a:hover { text-decoration: none; color: #F60; }
        a:focus { outline: none; }
        .container {text-align: center;display: table-cell; vertical-align: middle; }
        .panel-body{padding:2em 0 2em 2em;}
        .panel-body i{vertical-align: middle; margin-right: 20px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="row text-left">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h5>警告信息</h5>
                    </div>
                    <div class="panel-body">
                        <p> <i class="fa fa-info-circle fa-4x text-danger"></i>
                            {{$msg}}
                        </p>
                    </div>
                    <div class="panel-footer text-center">
                        <a href="{{$url}}">请点击这里继续</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
