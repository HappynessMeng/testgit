<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限修改</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css" >

</head>

<body>
<div style="max-width:300px;margin:10px 0 0 0">
<form action="" method="post" name="frmsys" class="layui-form ">
  <div class="layui-form-item">
    <label class="layui-form-label">权限名称</label>
    <div class="layui-input-block">
      <input name="name" type="text" class="layui-input" value="{$data.name}" id="name" maxlength="50" lay-verify="required"  placeholder="请输入权限名称" autocomplete="off">  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">权值</label>
    <div class="layui-input-block">
      <input name="value" type="text" class="layui-input" value="{$data.value}" maxlength="50" lay-verify="required" placeholder="请输入权值信息" autocomplete="off">  
    </div>
  </div>
  
    <div class="layui-form-item">
    <label class="layui-form-label">排序</label>
    <div class="layui-input-block" style="width:50px;">
      <input name="sort" type="text" class="layui-input" id="sort" value="{$data.sort}" maxlength="2" lay-verify="required|number" autocomplete="off" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" >
      <input type="hidden" name="id" id="hiddenField" value="{$Think.get.noteid}" />
    </div>
    </div>
  <div class="layui-form-item">
    <label class="layui-form-label">状 态</label>
    <div class="layui-input-block">
        <input name="isenable" lay-skin="switch" value="1" lay-text="启用|禁用" type="checkbox"  <?php echo $data['isenable'] ? 'checked' : '' ;?>>
	</div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="subs">确定修改</button>
      <button type="reset" class="layui-btn layui-btn-primary">重 置</button>
    </div>
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
		url : "<?php echo U('edit','', FALSE); ?>",
		success : function(data) {
			if(data.code == 200) {
				layer.msg(
					data.notice
					,{icon: 1 , time:500 }
					,function(){parent.location.reload()}
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