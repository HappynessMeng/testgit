<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎</title>
<link rel="stylesheet" href="/Res/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/Res/css/base.css" >
</head>

<body>
<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="">首页</a>
    <a href="">欢迎中心</a>
</span>
</div>

<div class="welcome_txt">Welcome  back </div>


<script src="/Res/lib/layui/layui.js"></script>
<script>
layui.use('element', function(){
  var element = layui.element();
});
</script>
</body>
</html>