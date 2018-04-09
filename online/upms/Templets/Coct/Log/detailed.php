<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>应用系统-添加</title>
<link rel="stylesheet" href="__RES__/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__RES__/css/base.css" >

</head>

<body>
<div style="max-width:450px;margin:10px auto 0">
<form action="" method="post" name="frmsys" class="layui-form layui-form-pane">
  <div class="layui-form-item ">
    <label class="layui-form-label">操作者</label>
    <div class="layui-input-block">
      <input type="text" class="layui-input" maxlength="30" lay-verify="required|chnname"  value="姓名【{$v.realname}】&nbsp;ID【{$v.userid}】" autocomplete="off" readonly>  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">时间</label>
    
    <div class="layui-input-block">
      <input type="text" class="layui-input" maxlength="12" lay-verify="required|eng" value="{$v.createdtime|date='Y-m-d H:i:s',###}" autocomplete="off" readonly>  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">行为</label>
    <div class="layui-input-block">
      <input type="text" class="layui-input" maxlength="32" lay-verify="required|secret" value="{$t[$v['action']]}" autocomplete="off" readonly>  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">操作对象</label>
    <div class="layui-input-block">
      <input type="text" class="layui-input" maxlength="16" lay-verify="required" value="{$v.object}" autocomplete="off" readonly>  
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">IP地址</label>
    <div class="layui-input-block">
      <input type="text" class="layui-input" maxlength="50" lay-verify="required|url2ip" value="{$v.ip|long2ip=###}" autocomplete="off" readonly>  
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">终端设备</label>
    <div class="layui-input-block">
      <input type="text" class="layui-input"  maxlength="4" value="{$v.client}"  autocomplete="off" readonly>  
    </div>
    </div>
     <div class="layui-form-item layui-form-text">
      <label class="layui-form-label">操作详细</label>
      <div class="layui-input-block">
        <textarea name="desc" class="layui-textarea" readonly>{$v.description}</textarea>
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
    
});
<!--载入form END-->
    
</script>
</body>
</html>