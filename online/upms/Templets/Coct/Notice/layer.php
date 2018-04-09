<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
</head>
<body>

</body>
<script src="__RES__/lib/layui/layui.js"></script>
<script>
	layui.use('layer', function(){
		  layer.alert('<i class="layui-icon">&#xe63a;</i>&nbsp;&nbsp;{$notice}', {
			skin: 'layui-layer-lan'
			,title: '提示'
			,closeBtn: 0
			,anim: 4 //动画类型
		    }
			<notempty name="url">
			,function(index){
				parent.location.href = '{$url}';
			}
			</notempty>
			);
	});              
</script>
</html>