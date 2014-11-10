<html lang="zh-CN"><head>
    
<meta charset="utf-8">
<title>title</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<meta name="robots" content="index,follow">
<meta name="application-name" content="">

<!-- Site CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/site.min.css?v3" rel="stylesheet">

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="js/html5shiv.min.js?v=e179cfe103"></script>
  <script src="js/respond.min.js?v=e179cfe103"></script>
<![endif]-->

<!-- Favicons -->
<!-- 可以在iPhone/iPod Touch上将网页"添加至主屏幕"时，去掉icon上那恶心的透明层 -->
<link rel="apple-touch-icon-precomposed" href="http://static.bootcss.com/www/assets/ico/apple-touch-icon-precomposed.png">
<link rel="shortcut icon" href="http://static.bootcss.com/www/assets/ico/favicon.png">

<script>
  var _hmt = _hmt || [];
</script>
</head>
<body class="home-template">

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- title -->
      <a class="navbar-brand hidden-sm" href="/">title</a>
    </div>
    <div class="navbar-collapse collapse" role="navigation">
      <ul class="nav navbar-nav">
        <!-- 左边菜单 -->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <!-- 右边菜单 -->
        @if(Auth::check())
        <li><a href="">{{Auth::user()->username}}</a></li>
        <li><a href="/signout">退出</a></li>
        @else
        <li><a href="/signin">登陆</a></li>
        <li><a href="/signup">注册</a></li>
        @endif
      </ul>
    </div>
  </div>
</div>

<!-- 内容 -->
<div class="container" style="margin-top:50px;">
    @yield('content')
</div><!-- /.container -->

<footer class="footer ">
  
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.unveil.min.js"></script>
<script src="js/jquery.scrollUp.min.js"></script>
<script src="js/toc.min.js"></script>
<script src="js/site.min.js"></script>
  
<!-- 返回顶部 -->
<a id="scrollUp" href="#top" style="position: fixed; z-index: 2147483647; display: none;"><i class="fa fa-angle-up"></i></a>
<div id="GWDANG_HAS_BUILT"></div>
</body>
</html>