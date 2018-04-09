<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>日志管理</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">
</head>

<body>

<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="{:U('Index/welcome','',false)}">首页</a>
    <a href="">日志管理</a>
</span>
</div>


<div class="main_cont">


<form action="{:U('index','',false)}" method="get" name="schfrm" class="layui-form" id="schfrm">
<!--Search From Start-->
 <div class="layui-form-pane">
    <div class="layui-inline">
        <label class="layui-form-label">关键字查找</label>
        <div class="layui-input-inline" style="width: 300px;">
          <input type="text" name="keys" placeholder="操作者ID、操作对象" value="{$Think.get.keys}" autocomplete="off"  class="layui-input">
        </div>
    </div>
    
    <div class="layui-inline">
        <label class="layui-form-label">行为</label>
        <div class="layui-input-inline" >
            <select name="status" lay-verify="">
            <option value="0">请选择行为模式</option>
            <option value="ins" <if condition="$Think.get.status eq ins ">selected</if> >新建</option>
            <option value="edit" <if condition="$Think.get.status eq edit ">selected</if> >修改</option>
            <option value="delete" <if condition="$Think.get.status eq delete ">selected</if> >删除</option>
            <option value="other" <if condition="$Think.get.status eq other ">selected</if> >其他</option>
            </select>  
        </div>
    </div>
    
    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal" type="submit">
        <i class="layui-icon">&#xe609;</i>  搜索
        </button>
        
        <button class="layui-btn layui-btn-danger" type="button" name="emptyform" style="margin-left:50px;">
        <i class="layui-icon">&#xe63c;</i>  清空搜索条件
        </button>
    </div>
    
 </div>
 
 <div class="layui-form-pane" style=" margin-top:15px;">
    <label class="layui-form-label">操作时间</label>
    <div class="layui-input-inline dateinput">
      <input class="layui-input" name="stime" placeholder="开始日" id="LAY_demorange_s" value="{$Think.get.stime}" >
    </div>
    <div class="layui-input-inline"> - </div>
    <div class="layui-input-inline dateinput">
      <input class="layui-input" name="etime" placeholder="截止日" id="LAY_demorange_e" value="{$Think.get.etime}" >
    </div>
    
 </div>

<!--Search Form End-->

</form>	

<table class="layui-table" lay-even="">
     <thead>     
  <tr>
    <th>操作者ID</th>
    <th>操作时间</th>
    <th>源IP</th>
    <th>行为</th>
    <th>对象类型</th>
    <th>终端内核</th>
    <th>操作</th>
  </tr>
  </thead>
    <tbody>
   <?php foreach($data as $k => $v) : ?>
   <tr>
    <td>{$v.userid}</td>
    <td>{$v.createdtime|date='Y-m-d H:i:s',###}</td>
    <td>{$v.ip|long2ip=###}</td>
    <td><?php echo $t[$v['action']];?></td>
    <td>{$v.object}</td>
    <td>{$v.client}</td>
    <td>  
    	<button class="layui-btn layui-btn-normal layui-btn-small" name="oper_look" id="{$v.logid}" type="button">
        <i class="layui-icon">&#xe62c;</i>详细
        </button>
    </td>
  </tr>
   <?php endforeach;?>
   <empty name="data"><tr><td colspan="10"><div class="nodatanotice"></div></td></tr></empty>
  </tbody>
</table>

<noempty name="data">
<div class="spages">
	<div id="layuipages"></div>
</div>
</noempty>

</div>
<script src="__RES__/js/jquery-1.12.3.min.js"></script>
<script src="__RES__/lib/layui/layui.js"></script>

<script>
$(document).ready(function() {  
	// 清空搜索数据
	$('button[name=emptyform]').on('click', function(){
	$(':input','#schfrm')
	.not(':button, :submit, :reset, :hidden') 
	.val('') 
	.removeAttr('checked') 
	.removeAttr('selected');
	});

	// 分页插件
	layui.use(['laypage', 'layer'], function(){
	  var laypage = layui.laypage
	  laypage({
		 cont: 'layuipages'          // 容器id
		,curr:{$spage.nowPage}      // 默认当前页
		,groups: 5                 // 连续分页数
		,pages: {$spage.totalPages}    // 分页总数
		,skin: '#4BB2FF'              // 样式
		,skip: true                // 是否显示跳转
		,first:'首页'              // 控制首页 Number/String/Boolean
		,last:'末页'                // 控制尾页 Number/String/Boolean
		,prev: '<em>«</em>'        // 上一页样式 Number/String/Boolean
		,next: '<em>»</em>'        // 下一页样式 Number/String/Boolean
	   // ,curr: location.hash.replace('#!page=', '') //获取hash值为fenye的当前页
	   // ,hash: 'page' //自定义hash值
		,jump: function(obj, first){
			var curr = obj.curr;
			str = '{$spage.url}';  // 跳转带参数
			tourl = str.replace(/%5BPAGE%5D/,curr);
			if(!first) 
				location.href = tourl ;
		}
		});
	});
	// 分页插件结束
	
	
	
	// 日期选择 START
	layui.use('laydate', function(){
	  var laydate = layui.laydate;
	  
	  var start = {
		 min: '2017-01-01 00:00:00'
		,max: '2099-06-16 23:59:59'
		,istoday: false
		,choose: function(datas){
		  end.min = datas; //开始日选好后，重置结束日的最小日期
		  end.start = datas //将结束日的初始值设定为开始日
		}
	  };
	  
	  var end = {
		min: laydate.now()
		,max: '2099-06-16 23:59:59'
		,istoday: false
		,choose: function(datas){
		  start.max = datas; //结束日选好后，重置开始日的最大日期
		}
	  };
	  
	  document.getElementById('LAY_demorange_s').onclick = function(){
		start.elem = this;
		laydate(start);
	  }
	  document.getElementById('LAY_demorange_e').onclick = function(){
		end.elem = this
		laydate(end);
	  }
	  
	});
	// Date End
	
	
	layui.use('element', function(){
	  	var element = layui.element();
	});
	
	layui.use('layer');	
   
   
   layui.use('form', function(){
		var form = layui.form();
   });

   	// 查看事件
	$('button[name=oper_look').on('click', function(){
		var id = $(this).attr('id');		
		layer.open({
		  type: 2,
		  //offset: '10px' ,         // 坐标位置
		  title: '日志详细', //标题
		  resize: false ,     // 是否允许拉伸
		  anim:1,            // 弹出动画类型
		  shadeClose: true, // 是否点击遮罩关闭
		  shade: [0.8, '#333333'], // 遮罩层
		  area: ['480px', '520px'],
		  scrollbar : true,
		  content: ['{:U('detailed','',false)}/id/'+id,'no'],
		}); 
	});
  
});


</script>
</body>
</html>