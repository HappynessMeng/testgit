<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>权限管理</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">
</head>

<body>

<div class="main_cont" style="width:570px">
	
<form action="" method="post" name="frmsys" class="layui-form ">
<div>
	<button class="layui-btn" lay-submit lay-filter="subs">
      <i class="layui-icon">&#xe608;</i> 配置
    </button>
</div>
<input type="hidden" value="{$Think.get.systemid}" name="systemid" />
<input type="hidden" value="{$Think.get.id}" name="userid" />
<table class="layui-table" lay-even="">
  <colgroup>
    <col width="260">
    <col>
    </colgroup>
     <thead>
  <tr>
    <th>城市分配</th>
    </tr>
  </thead>
    <tbody>
   <tr style=" height:45px;">
    <td>
    <?php foreach($city_data as $k => $v):?>
    <input name="cityid[]" type="checkbox" title="{$v.name}" <?php if(in_array($v['city_id'] , $citys)) {echo 'checked' ; }?> value="{$v.city_id}" lay-skin="primary" >
    <?php endforeach;?>
      </td>
    </tr>
  </tbody>
</table>
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
		url : "<?php echo U('cityconfig','', FALSE); ?>",
		success : function(data) {
			if(data.code == 200) {
				layer.msg(
					'设置成功'
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