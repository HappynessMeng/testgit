<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>应用系统</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">
</head>

<body>

<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="{:U('Index/welcome','',false)}">首页</a>
    <a href="">应用系统</a>
</span>
</div>


<div class="main_cont">
	<div>
	<button class="layui-btn layui-btn-danger" name="oper_ins">
      <i class="layui-icon">&#xe608;</i> 添加
    </button>
    <button class="layui-btn" name="oper_flush">
     <i class="layui-icon">&#x1002;</i>  刷新
    </button>
</div>
<table class="layui-table" lay-even="">
    <colgroup>
    <col width="70">
    <col width="200">
    <col width="150">
    <col width="320">
    <col>
    </colgroup>
     <thead>
  <tr>
    <th>编号</th>
    <th>应用系统名称</th>
    <th>APPID</th>
    <th>SECRET</th>
    <th>URL</th>
    <th>状态</th>
    <th>操作</th>
  </tr>
  </thead>
    <tbody>
   <?php foreach($data as $k => $v) : ?>
   <tr id="systr_{$v.id}">
    <td>{$v.id}</td>
    <td>{$v.name}</td>
    <td>{$v.enalias}</td>
    <td>{$v.secret}</td>
    <td><a href="{$v.url}" target="_blank">{$v.url}</a></td>
    <td>
		<?php if($v['isenable']):?>
    		<span class="layui-btn layui-btn-normal layui-btn-small">正 常</span>
		<?php else:?>
       	 <span class="layui-btn layui-btn-warm layui-btn-small">停 用</span>
		<?php endif;?>
    </td>
    <td>  
    	<button class="layui-btn layui-btn-primary layui-btn-small" name="oper_edit" id="{$v.id}">
        <i class="layui-icon">&#xe642;</i>
        </button>
        <!--删除按钮
        <button class="layui-btn layui-btn-primary layui-btn-small" name="oper_del"  id="{$v.id}">
        <i class="layui-icon">&#xe640;</i>
        </button>
        -->
    </td>
  </tr>
   <?php endforeach;?>
  <empty name="data"><tr><td colspan="9"><div class="nodatanotice"></div></td></tr></empty>
  </tbody>
</table>
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
	$('button[name=oper_ins').on('click', function(){
		layer.open({
		  type: 2,
		  offset: '10px' ,         // 坐标位置
		  title: '新增应用系统', //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['480px', '540px'],
		  scrollbar : true,
		  content: ['{:U('ins','',false)}','no'],
		}); 
	});
	
	// 修改事件
	$('button[name=oper_edit').on('click', function(e){
		var system_name = $(this).parent().parent().find('td').eq(1).html() + '（修改）';
		var systemid = $(this).attr('id');
		layer.open({
		  type: 2,
		  offset: '10px' ,         // 坐标位置
		  title: system_name,    //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['480px', '540px'],
		  scrollbar : true,
		  content: ['{:U('edit','',false)}/sid/'+systemid,'no'],
		}); 
	});
	
	// 刷新事件
	$('button[name=oper_flush').on('click', function(){
		location.reload();
	});
	
	// 删除事件 [暂不开通]
	/* $('button[name=oper_del').on('click', function(e){
		var id = $(this).attr('id');
		layer.confirm(
			'确定要删除该应用系统吗 ?'
			, {icon: 3, title:'提 示'}
			, function(index){
			    layer.close(index);
	 	 		$.ajax({
					type : "get",
					dataType: "json",
					url : "<?php echo U('del','', FALSE); ?>/sid/"+systemid,
					success : function(data) {
						if(data.code == 200) {
							layer.msg(
								data.notice
								,{icon: 1 , time:500 }
								,function(){
									$('#systr_'+id).remove(); 
									parent.location.reload();
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
				})
			}
		);
	});
	*/
	
  
});


</script>
</body>
</html>