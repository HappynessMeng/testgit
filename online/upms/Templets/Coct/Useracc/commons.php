<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户管理</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css">
</head>

<body>
<div class="bednav">
<i class="layui-icon">&#xe62e;</i>   
<span class="layui-breadcrumb" lay-separator=">" >
    <a href="{:U('Index/welcome','',false)}">首页</a>
    <a href="">所有用户</a>
</span>
</div>


<div class="main_cont">

<form class="layui-form" action="{:U('commons','',false)}"  name="schfrm" id="schfrm">
<!--Search From Start-->
<input name="systemid" type="hidden" value="" />
 <div class="layui-form-pane">
    <div class="layui-inline">
        <label class="layui-form-label">关键字查找</label>
        <div class="layui-input-inline" style="width: 300px;">
          <input type="text" name="keys" placeholder="支持姓名、别名、手机号、邮箱查找" value="{$Think.get.keys}" autocomplete="off"  class="layui-input">
        </div>
    </div>
    
    <div class="layui-inline">
        <label class="layui-form-label">状 态</label>
        <div class="layui-input-inline" >
            <select name="status" lay-verify="">
            <option value="0">请选择用户状态</option>
            <option value="1" <if condition="$Think.get.status eq 1 ">selected</if> >正 常</option>
            <option value="2" <if condition="$Think.get.status eq 2 ">selected</if> >停 用</option>
            <option value="3" <if condition="$Think.get.status eq 3 ">selected</if> >永久删除</option>
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
    <label class="layui-form-label">注册时间</label>
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
    <th>编号</th>
    <th>登录别名</th>
    <th>姓名</th>
    <th>性别</th>
    <th>电话</th>
    <th>邮箱</th>
    <th>关联系统</th>
    <th>录入时间</th>
    <th>修改时间</th>
    <th>状态</th>
    <th>操作</th>
  </tr>
  </thead>
    <tbody>
   <?php foreach($data as $k => $v) : ?>
   <tr id="systr_{$v.id}">
    <td>{$v.id}</td>
    <td>{$v.alias}<?php if($v['sulevel'] == 2):?><span class="sign">(系统)</span><?php endif;?></td>
    <td>{$v.realname}</td>
    <td><if condition="$v['sex'] gt 1 ">女<else />男</if></td>
    <td>{$v.phone}</td>
    <td>{$v.email}</td>
    <td>{$v.system_name}</td>
    <td>{$v.createdtime|date='Y-m-d H:i:s',###}</td>
    <td><empty name="v.updatedtime"> - <else />{$v.updatedtime|date='Y-m-d H:i:s',###}</empty></td>
    <td>
	<?php
    	if($v['isdeleted']==1)
			echo '永久删除' ;
		else
			echo $v['isenable'] > 0 ? '正 常' : '停 用' ;
	?></td>
    <td>  
    	<button class="layui-btn layui-btn-normal layui-btn-small <?php if($v['sulevel']<=2 || $v['isdeleted']==1 ) echo 'layui-btn-disabled'; ?>" name="oper_set" id="{$v.id}" >
        <i class="layui-icon">&#xe614;</i> 配置
        </button>
    </td>
  </tr>
   <?php endforeach;?>
  <empty name="data"><tr><td colspan="11"><div class="nodatanotice"></div></td></tr></empty>
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

layui.use('form', function(){
	var form = layui.form();
});

layui.use('element', function(){
  var element = layui.element();
});

layui.use('layer');


$('button[name=oper_set]').on('click', function(){
	var id = $(this).attr('id');
	if($(this).is('.layui-btn-disabled')){
		return false;	
	}
	
	layer.open({
	  type: 2,
	 // offset: '10px' ,         // 坐标位置
	  title: '用户系统分配-用户ID（'+id+'）',    //标题
	  resize: false ,     // 是否允许拉伸
	  anim:1,            // 弹出动画类型
	  shadeClose: true, // 是否点击遮罩关闭
	  shade: [0.8, '#333333'], // 遮罩层
      area: ['80%', '80%'],
	  scrollbar : true,
	  maxmin: false,   
	  content: ['{:U('allot','',false)}/userid/'+id,'no'],	  
	}); 
});

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

  
});


</script>
</body>
</html>