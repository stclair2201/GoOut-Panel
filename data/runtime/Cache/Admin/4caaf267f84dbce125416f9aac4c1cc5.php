<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/goout/statics/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/goout/statics/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/goout/statics/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/goout/statics/simpleboot/font-awesome/4.2.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/goout/statics/simpleboot/font-awesome/4.2.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/goout/",
    JS_ROOT: "statics/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/goout/statics/js/jquery.js"></script>
    <script src="/goout/statics/js/wind.js"></script>
    <script src="/goout/statics/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
<div class="wrap">
  <div id="error_tips">
    <h2>缓存已更新！</h2>
    <div class="error_cont">
      <ul>
        <li>缓存已更新！</li>
      </ul>
      <div class="error_return"><a href="javascript:close_app();" class="btn">关闭</a></div>
    </div>
  </div>
</div>
<script src="/goout/statics/js/common.js"></script>
<script>
var close_timeout=setTimeout(function(){
	parent.close_current_app();
},3000);

function close_app(){
	clearTimeout(close_timeout);
	parent.close_current_app();
}
</script>
</body>
</html>