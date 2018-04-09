<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>应用系统-添加</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css" >

</head>

<body>
<div style="max-width:450px;margin:10px 0 0 0">
<form action="" method="post" name="frmsys" class="layui-form ">
  <div class="layui-form-item">
    <label class="layui-form-label">系统名称</label>
    <div class="layui-input-block">
      <input name="name" type="text" class="layui-input" id="name" maxlength="30" lay-verify="required|chnname"  placeholder="请输入2-8位中文长度系统名称" autocomplete="off">  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">APPID</label>
    
    <div class="layui-input-block">
      <input name="enalias" type="text" class="layui-input" id="enalias" maxlength="12" lay-verify="required|eng" placeholder="请输入英文别名" autocomplete="off">  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">SECRET</label>
    <div class="layui-input-block">
      <input name="secret" type="text" class="layui-input" id="secret" maxlength="32" lay-verify="required|secret" placeholder="请输入数字英文密匙" value="{:randLenthStr(32)}" autocomplete="off">  
    </div>
  </div>
  
  <div class="layui-form-item">
    <label class="layui-form-label">系统地址</label>
    <div class="layui-input-block">
      <input name="url" type="text" class="layui-input" id="url" maxlength="50" lay-verify="required|url2ip" placeholder="请输入系统地址" autocomplete="off">  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">SSO_URL</label>
    <div class="layui-input-block">
      <input name="sso_url" type="text" class="layui-input" id="url" maxlength="50" lay-verify="required" placeholder="请输入SSO同步地址" autocomplete="off">  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">维护提示</label>
    <div class="layui-input-block">
      <input name="off_msg" type="text" class="layui-input" id="url" maxlength="50" lay-verify="required" placeholder="请输入关闭系统维护提示信息" autocomplete="off">  
    </div>
  </div>
      <div class="layui-form-item">
    <label class="layui-form-label">状 态</label>
    <div class="layui-input-block">
            <input name="isenable" lay-skin="switch" value="1" lay-text="启用|禁用" type="checkbox"  checked>
	</div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="subs">确认添加</button>
      <button type="reset" class="layui-btn layui-btn-primary">重 置</button>
    </div>
  </div>
</form>
</div>
<script src="__RES__/lib/layui/layui.js"></script>
<script src="__RES__/js/jquery-1.12.3.min.js"></script>

<script>

// 验证url 地址方法
function IsURL(str_url) {  
        var strRegex = "^((https|http|ftp|rtsp|mms)?://)"  
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@   
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184   
        + "|" // 允许IP和DOMAIN（域名）   
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.   
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名   
        + "[a-z]{2,6})" // first level domain- .com or .museum   
        + "(:[0-9]{1,4})?" // 端口- :80 <br>  
        + "((/?)|" // a slash isn't required if there is no file name   
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
        var re = new RegExp(strRegex);  
        if (re.test(str_url)) {  
            return (true);  
        } else {  
            return (false);  
        }  
} 

<!--layui 载入form START-->
layui.use('form', function(){	

  var s = layui.form();
  var layer = layui.layer;
  
  <!--自定义表单验证 START-->
  s.verify({ 
	  chnname: [
		/^[\u4E00-\u9FA5]{2,8}$/
		,'系统名称必须为2-8位的中文字符'
	  ] 
	  ,eng: [
		/^[a-zA-Z]{2,16}$/
		,'英文别名APPID必须为2-12位纯英文字符'
	  ]
	  ,secret: [
	  	/^\w{32}$/
		,'密匙必须是32位数字字母组合'
	  ]
	  , url2ip: function(value, item){ //value：表单的值、item：表单的DOM对象
			if(IsURL(value)== false ){
			  return '系统地址必须为 url 格式';
			}
	  }
  });
  <!--验证END-->  
  
  
  <!--表单提交监听事件 START--> 
  s.on('submit(subs)', function(data){
	  
		$.ajax({
		type : "post",
		data : $("form[name=frmsys]").serialize(),
		dataType: "json",
		url : "<?php echo U('ins','', FALSE); ?>",
		success : function(data) {
			if(data.code == 200) {
				layer.msg(
					data.notice
					,{icon: 1 , time:500 }
					,function(){
						parent.location.reload()
						parent.parent.location.reload();   
					}
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
    
</script>
</body>
</html>