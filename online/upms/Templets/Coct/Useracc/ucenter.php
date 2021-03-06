<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改用户</title>
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
    <a href="#">个人信息设置</a>
</span>
</div>


<div class="main_cont" style="width:65%;">
	<form action="" method="post" name="frmsys" class="layui-form ">
    
    <div class="layui-form-item">
    <blockquote class="layui-elem-quote">基本资料</blockquote>
    </div>
    
   <div style="width:60%">
    <div class="layui-form-item">
    <label class="layui-form-label">用户头像</label>
    <div class="layui-input-block">
      <div style="width:162px;height:162px;border-radius:50%; overflow:hidden;">
        <img src="__ROOT__/{$uinfo.picture}" width="162" height="162" title="点击图片上传" id="avapic">
        <input type="hidden" name="picture" id="picture" value="{$uinfo.picture}" />
      </div>  
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">默认账号设置</label>
      <div class="layui-input-block">
        <input type="radio" name="logintype" value="1" title="用手机作为账号"   <?php if($uinfo['logintype'] ==1 ) echo 'checked' ; ?> >
        <input type="radio" name="logintype" value="2" title="用邮箱作为账号"   <?php if($uinfo['logintype'] ==2 ) echo 'checked' ; ?> >
  </div>
  </div>
  
    
  <div class="layui-form-item">
    <label class="layui-form-label">辅助登录别名</label>
    <div class="layui-input-block">
      <input name="alias" type="text" class="layui-input" maxlength="20" lay-verify="required|alias" placeholder="别名为字母开头6-16位字母数字" autocomplete="off" id="alias" value="{$uinfo.alias}" >
    </div>
  </div>  
  
  
  <div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
      <input name="password" type="password" class="layui-input" maxlength="20" lay-verify="password" placeholder="不修改则留空" autocomplete="off" id="password">
      <div id="ckpwd"></div>  
    </div>
  </div>  
  
  <div class="layui-form-item">
    <label class="layui-form-label">确定密码</label>
    <div class="layui-input-block">
      <input name="cpassword" type="password" class="layui-input" maxlength="20" lay-verify="cpwd" placeholder="请保持和密码输入一致" autocomplete="off" id="cpassword">  
    </div>
  </div>
  
    <div class="layui-form-item">
    <label class="layui-form-label">姓名</label>
    <div class="layui-input-block">
      <input name="realname" type="text" class="layui-input" id="realname" maxlength="50" lay-verify="required|realname"  placeholder="请输入有效姓名2-8字符中文" value="{$uinfo.realname}" autocomplete="off">  
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">性别</label>
    <div class="layui-input-block">
      <input type="radio" name="sex" value="1" title="先生"  <?php if($uinfo['sex'] ==1 ) echo 'checked' ; ?> >
      <input type="radio" name="sex" value="2" title="女士"  <?php if($uinfo['sex'] ==2 ) echo 'checked' ; ?> >  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">手机号码</label>
    <div class="layui-input-block">
      <input name="phone" type="text" class="layui-input" maxlength="11" lay-verify="phone" placeholder="请输入正确手机号码格式" autocomplete="off" value="{$uinfo.phone}" id="phone">  
    </div>
  </div>
  
    <div class="layui-form-item">
    <label class="layui-form-label">安全邮箱</label>
    <div class="layui-input-block">
      <input name="email" type="text" class="layui-input" maxlength="20" lay-verify="email" placeholder="请输入正确的邮箱格式" value="{$uinfo.email}" autocomplete="off" id="email">  
      <input type="hidden" name="id" id="id" value="{$uinfo.id}" >
    </div>
  </div>
   </div>
   
   
   <div class="layui-form-item" style="margin-top:20px;">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="subs">修 改</button>
      <button type="reset" class="layui-btn layui-btn-primary">重 置</button>
    </div>
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
	
<!--layui 载入form START-->
layui.use('form', function(){	

  var s = layui.form();
  var layer = layui.layer; 
  
  $('#avapic').click(function(){
	  layer.open({
		  type: 2,
		  offset: '0' ,         // 坐标位置
		  title: '头像上传', //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['640px', '510px'],
		  scrollbar : true,
		  content: ['{:U('avaupload','',false)}/userid/{$uinfo.id}','no'],
		}); 
  });
  
 <!--自定义表单验证 START-->
  s.verify({ 
	  alias: [
		/^[a-zA-Z]\w{5,16}$/
		,'别名为字母开头6-16位字母数字组合'
	  ] 
	  ,password: function(value , item){
		  var upwd =/((?=.*\d)(?=.*\D)|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^.{6,}$/ ;
		if(value.trim()!= '' && !upwd.test(value)) {
			return '密码必须至少包含数字、字母、字符其中两种不少于6位' ;
		}
	  }  
	  , cpwd: function(value, item){ //value：表单的值、item：表单的DOM对象
			if(value!= $('input[name=password]').val()){
			  return '两次密码输入不一致！';
			}
	  }
	  ,realname:[
	  	/^[\u4E00-\u9FA5]{2,8}$/
		,'请输入有效的真实姓名'
	  ]
  });
  <!--验证END-->  
  
  <!--表单提交监听事件 START--> 
  s.on('submit(subs)', function(data){
	  
		$.ajax({
		type : "post",
		data : $("form[name=frmsys]").serialize(),
		dataType: "json",
		url : "<?php echo U('ucenter','', FALSE); ?>",
		success : function(data) {
			if(data.code == 200) {
				layer.msg(
					data.notice
					,{icon: 1 , time:500 }
					,function(){location.href='{:U('ucenter','',false)}'}
				);
      		}
			else {
				layer.msg(data.notice, {icon: 2});
			}
		},
		error : function(e) {
     		 layer.msg('网络不稳定,异步数据处理延时.....', {icon: 2});		  
		}

	  });
		
	  return false;

  });
   <!--监听END-->  
    
});
<!--载入form END-->




});
</script>
</body>
</html>