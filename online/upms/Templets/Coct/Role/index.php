<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>角色管理</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">
</head>

<body>

<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="{:U('Index/welcome','',false)}">首页</a>
    <a href="">{$sys.name}</a>
    <a href="">角色管理</a>
</span>
</div>


<div class="main_cont">
	<div>
	<button class="layui-btn layui-btn-danger" name="oper_ins"  id="0" type="button" >
      <i class="layui-icon">&#xe608;</i> 添加
    </button>
    <button class="layui-btn" name="oper_flush" type="button" >
     <i class="layui-icon">&#x1002;</i>  刷新
    </button>
</div>
<form action="" method="post" name="frmnote">
<table class="layui-table" lay-even="">
    <colgroup>
    <col width="240">
    <col width="100">
    <col width="360">
    <col>
    </colgroup>
     <thead>
  <tr>
    <th>角色名称</th>
    <th>是否默认</th>
    <th>角色描述</th>
    <th>创建时间</th>
    <th>最后操作时间</th>
    <th>操作</th>
  </tr>
  </thead>
    <tbody style="max-height:500px; overflow-y:scroll;">
   <?php foreach($data as $k => $v) : ?>
   <tr id="systr_{$v.id}">
    <td>{$v.rolename}</td>
    <td>
		<?php if($v['isdefault']):?>
    		<span class="layui-btn layui-btn-normal layui-btn-small">Yes</span>
		<?php else:?>
       	 <span class="layui-btn layui-btn-warm layui-btn-small">N o</span>
		<?php endif;?>
    </td>
    <td>{$v.description}</td>
    <td>{$v.createdtime|date='Y-m-d H:i:s',###}</td>
    <td><empty name="v['updatedtime']"> - <else />{$v.updatedtime|date='Y-m-d H:i:s',###}</empty></td>
    <td>  
        <button type="button" class="layui-btn layui-btn-primary layui-btn-small" name="oper_edit" id="{$v.id}">
        <i class="layui-icon">&#xe642;</i>
        </button>
        <button type="button" class="layui-btn layui-btn-primary layui-btn-small" name="oper_del" id="{$v.id}" >
        <i class="layui-icon">&#xe640;</i>
        </button>
    </td>
  </tr>
   <?php endforeach;?>
   <empty name="data"><tr><td colspan="6"><div class="nodatanotice"></div></td></tr></empty>
  </tbody>
</table>
</form>
</div>
<script src="__RES__/js/jquery-1.12.3.min.js"></script>
<script src="__RES__/lib/layui/layui.js"></script>

<script>
$(document).ready(function() {  	
	layui.use('element', function(){
	  var element = layui.element();
	});
	
	layui.use('layer');
	
	// 添加事件
	$('button[name=oper_ins]').on('click', function(){
		var id = $(this).attr('id');
		
		layer.open({
		  type: 2,
		  offset: '10px' ,         // 坐标位置
		  title: '新增角色', //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['460px', '420px'],
		  scrollbar : true,
		  content: ['{:U('ins','',false)}/systemid/{$Think.get.systemid}','no'],
		}); 
	
	});
	
	// 修改事件
	$('button[name=oper_edit]').on('click', function(e){
		var id = $(this).attr('id');
		layer.open({
		  type: 2,
		  offset: '10px' ,         // 坐标位置
		  title: '角色修改',    //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['460px', '420px'],
		  scrollbar : true,
		  content: ['{:U('edit','',false)}/systemid/{$Think.get.systemid}/roleid/'+id,'no'],
		}); 
	});
	
	
	// 刷新事件
	$('button[name=oper_flush]').on('click', function(){
		location.reload();
	});

	
	// 删除事件
	$('button[name=oper_del]').on('click', function(e){
		var id = $(this).attr('id');
		layer.confirm(
			'确定要删除该角色吗 ?'
			, {icon: 3, title:'提 示'}
			, function(index){
			    layer.close(index);
	 	 		$.ajax({
					type : "get",
					dataType: "json",
					url : "<?php echo U('del','', FALSE); ?>/roleid/"+id,
					success : function(data) {
						if(data.code == 200) {
							layer.msg(
								data.notice
								,{icon: 1 , time:500 }
								,function(){location.reload();}
							);
						}
						else {
							layer.msg(data.notice, {icon: 2});
						}
					},
					error : function(e) {
						layer.msg('网络不稳定,异步数据处理延时.....', {icon: 2});		  
					}
				})
			}
		);
	});
	
  
});


</script>
</body>
</html>