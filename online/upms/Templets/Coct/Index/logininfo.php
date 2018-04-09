<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>登录信息</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">

<style type="text/css">
body {margin:0 auto; padding:0px;font-size:13px;}
#avapic { cursor:pointer;}
</style>
</head>

<body>

<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="{:U('Index/welcome','',false)}">首页</a>
    <a href="#">登录信息</a>
</span>
</div>


<div class="main_cont" style="width:98%">
  <form action="" method="post" name="frmsys" class="layui-form ">
    
    <blockquote class="layui-elem-quote   layui-quote-nm">
    	<span class="item">尊敬的用户：</span>
    	<p>为了帐号的安全，如下面的登录情况与实际情况不符，我们建立以马上修改密码～</p>
    </blockquote>  
    <notempty name="sys">  
    <div class="layui-collapse">
    	<?php foreach($sys as $k => $s):?>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">系统栏目：{$s.name}</h2>
            <div class="layui-colla-content layui-show">拥有角色：{$s.roles}</div>
        </div>
    	<?php endforeach;?>
    </div>
    </notempty>
    
    <div class="layui-form-item" style="margin-top:20px;">
      <blockquote class="layui-elem-quote  layui-quote-nm">
          	<span class="item">登录安全信息：</span>
            <p>本次登录IP：{$uinfo.currentip|long2ip=###} {$uinfo.currentip|long2ip|getIPLoc=###}</p>
            <p>本次登录时间：{$uinfo.currenttime|date='Y-m-d H:i:s',###}</p>
            <p>上次登录IP：<empty name="uinfo['lastip']"> First login <else/>{$uinfo.lastip|long2ip=###} {$uinfo.lastip|long2ip|getIPLoc=###}</empty></p>
            <p>上次登录时间：<empty name="uinfo['lastip']"> First login <else/>{$uinfo.lasttime|date='Y-m-d H:i:s',###}</empty></p>
        </blockquote>
    </div>
  </form>
</div> 


<script src="__RES__/js/jquery-1.12.3.min.js"></script>
<script src="__RES__/lib/layui/layui.js"></script>

<script>
$(document).ready(function() { 
 	
layui.use('element', function(){
  var element = layui.element();
});
	

});
</script>
</body>
</html>