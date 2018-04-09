<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>登录</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css"></head>
<style>
@CHARSET "UTF-8";
body{ background-image:url(__RES__/images/loginbg.jpg);}
.login { padding-top: 15%; width: 26%;}
.btn-center{ text-center:center; margin:0 auto;}
</style>
<body>
    <div class="clear box layui-main login">
 
        <form class="layui-form layui-form-pane1" action="" name="frmsys" method="post">
            <div class="layui-form-item">
                <label class="layui-form-label">用户名：</label>
                <div class="layui-input-block">
                    <input type="text" name="u" lay-verify="required"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">密码：</label>
                <div class="layui-input-block">
                    <input type="password" name="p" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
            </div>
			<!--
            <div class="layui-form-item">
                <label class="layui-form-label">验证码：</label>
                <div class="layui-input-block">
                    <input type="text" name="code" placeholder="请输入验证码(调试阶段可不输入)" autocomplete="off" class="layui-input"><br>
                <a href="#"><img alt="验证码" src="/yzm"></a>
                </div>
            </div>
            -->
            <div class="layui-form-item">
            <label class="layui-form-label"></label>
   			  <button class="layui-btn" lay-submit lay-filter="subs">登 录</button>
            </div>
        </form>
    </div>
 
<script src="__RES__/lib/layui/layui.js"></script>
<script src="__RES__/js/jquery-1.12.3.min.js"></script>

<script>

<!--layui 载入form START-->
layui.use('form', function(){	

  var s = layui.form();
  var layer = layui.layer; 
  
  <!--表单提交监听事件 START--> 
  s.on('submit(subs)', function(data){	  	
		$.ajax({
		type : "post",
		data : $("form[name=frmsys]").serialize(),
		dataType: "json",
		url : "<?php echo U('index','', FALSE); ?>",
		success : function(data) {
			if(data.code == 200) {
				location.href = '{:U('Login/loding','',false)}';
      		}
			else {
				layer.msg(data.msg, {icon: 2});
			}
		},
		error : function(e) {
     		 layer.msg('网络不稳定,请刷新页面后重新尝试.....', {icon: 2});		  
		}

	  });
		
	  return false;

  });
   <!--监听END-->  
    
});
<!--载入form END-->
    
</script>
</body>
</html>