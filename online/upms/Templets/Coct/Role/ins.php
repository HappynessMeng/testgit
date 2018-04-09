<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>角色添加</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css" >
<link rel="stylesheet" href="__RES__/lib/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<style>
.divtree blockquote{ height:230px; width:360px;overflow-y:scroll; margin:0;padding:5px 0px 0 10px;}
.divtree .ztree { padding:0px; margin:0px;}
.divtree .ztree li { margin:5px 0;}
</style>
</head>

<body>

<div style="margin:0 auto;width:95%;">
<form action="" method="post" name="frmsys" class="layui-form ">

<div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief">
    <ul class="layui-tab-title">
    	<li class="layui-this">角色信息</li>
    	<li>权限分配</li>
    </ul>            
  
 	  <div class="layui-tab-content">    
     	  <!--Role Info Start-->
          <div style="max-width:400px;margin:10px 0 0 0" class="layui-tab-item layui-show">
                  <div class="layui-form-item">
                    <label class="layui-form-label">角色名称</label>
                    <div class="layui-input-block">
                      <input name="rolename" type="text" class="layui-input" id="rolename" maxlength="50" lay-verify="required"  placeholder="请输入角色名称" autocomplete="off">  
                    </div>
                  </div>  
                    <div class="layui-form-item">
                    <label class="layui-form-label">角色描述</label>
                    <div class="layui-input-block">
                    <textarea name="description" placeholder="请输入描述内容" class="layui-textarea" lay-verify="required" ></textarea>
                    <input type="hidden" name="systemid" value="{$Think.get.systemid}" >
                    <input type="hidden" name="nodes" id="nodes" />
                    </div>
                    </div>
                  <div class="layui-form-item">
                        <label class="layui-form-label">是否默认</label>
                        <div class="layui-input-block">
                        <input type="checkbox" name="isdefault" title="默 认" value="1">
                        </div>
                  </div>
        </div>
    	<!--Role Info End-->
    
    
   		 <!--权限展示START-->
    	<div class="layui-tab-item divtree">
			<blockquote class="layui-elem-quote layui-quote-nm">
            <ul id="treeRole" class="ztree"></ul>
        	</blockquote>
            </fieldset>
        </div>
   		 <!--权限展示END-->
    
    
    </div>
    
    
     <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="subs">确认添加</button>
          <button type="reset" class="layui-btn layui-btn-primary">重 置</button>
        </div>
 	 </div>
    
</div>      
</form>
</div>

<script src="__RES__/lib/layui/layui.js"></script>
<script src="__RES__/js/jquery-1.12.3.min.js"></script>

<!--Tree Start-->
<!--
<script type="text/javascript" src="__RES__/lib/ztree/js/jquery-1.4.4.min.js"></script>
低版本文件删除
-->
<script type="text/javascript" src="__RES__/lib/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="__RES__/lib/ztree/js/jquery.ztree.excheck.js"></script>
<SCRIPT type="text/javascript">

<!--  Tree 配置
var setting = {
	check: {
		enable: true
	},
	data: {
		simpleData: {
			enable: true
		}
	}
};

var zNodes =[
	<?php foreach($notearr as $k => $v):?>
	{ id:{$v.id}, pId:{$v.pid}, name:"{$v.name}", open:<?php echo $v['_level']>2 ? 'false' : 'true' ;?>},
	<?php endforeach;?>
];

var code;

function setCheck() {
	var zTree = $.fn.zTree.getZTreeObj("treeRole"),
	py = $("#py").attr("checked")? "p":"",
	sy = $("#sy").attr("checked")? "s":"",
	pn = $("#pn").attr("checked")? "p":"",
	sn = $("#sn").attr("checked")? "s":"",
	type = { "Y":py + sy, "N":pn + sn};
	//zTree.setting.check.chkboxType = type;
	zTree.setting.check.chkboxType = { "Y" : "ps", "N" : "ps" };

	
	showCode('setting.check.chkboxType = { "Y" : "' + type.Y + '", "N" : "' + type.N + '" };');
}
function showCode(str) {
	if (!code) code = $("#code");
	code.empty();
	code.append("<li>"+str+"</li>");
}

$(document).ready(function(){
	$.fn.zTree.init($("#treeRole"), setting, zNodes);
	setCheck();
	$("#py").bind("change", setCheck);
	$("#sy").bind("change", setCheck);
	$("#pn").bind("change", setCheck);
	$("#sn").bind("change", setCheck);
});

<!--Tree End-->


//选项卡加载
layui.use('element', function(){
  var element = layui.element();
});

<!--layui 载入form START-->
layui.use('form', function(){	

  var s = layui.form();
  var layer = layui.layer; 
  
  <!--表单提交监听事件 START--> 
  s.on('submit(subs)', function(data){
	  
		 // 获取树状结构中选中的数据
		 var nodes=$.fn.zTree.getZTreeObj("treeRole").getCheckedNodes(true)
		 var checkNodesId = '';
		 for(var i=0;i<nodes.length;i++){
			 checkNodesId += nodes[i].id+','; 
		 }
		 checkNodesId = checkNodesId.substring(0,checkNodesId.length-1);
		 $('input[name=nodes]').val(checkNodesId);
		 
		           
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