<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
 <head>
  <title>上传头像组件</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script src="__RES__/js/jquery-1.12.3.min.js"></script>
 <script type="text/javascript">
   function uploadevent(data){
   	 var t = data.split('|');
   	 if(t[0] == 200) {
		 $('input[name=picture]', window.parent.document).val(t[1]);
		 $('#avapic', window.parent.document).attr('src','__ROOT__/'+t[1]);
		 var index = parent.layer.getFrameIndex(window.name);
		 parent.layer.close(index);	
	 } else{
		 alert('头像更新失败~');
		 location.reload();
	 }
   }
  </script>
 <style type="text/css">
 body {margin: 0; padding:0;}
 </style>
 </head>
<body>
    <div id="altContent">
        <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"
        WIDTH="650" HEIGHT="450" id="myMovieName">
        <PARAM NAME=movie VALUE="__RES__/lib/ava/flash/avatar.swf">
        <PARAM NAME=quality VALUE=high>
        <PARAM NAME=bgcolor VALUE=#FFFFFF>
        <param name="flashvars" value="imgUrl=/Uploads/picture/default/default.png&pData=240|240|180|180|60|60&uploadUrl={:U('avaUpload','',false)}/act/up" />
        <EMBED src="__RES__/lib/ava/flash/avatar.swf" quality=high bgcolor=#FFFFFF WIDTH="650" HEIGHT="450" wmode="transparent" flashVars="imgUrl=/Uploads/picture/default/default.png&pData=240|240|180|180|60|60&uploadUrl={:U('avaUpload','',false)}/act/up"
        NAME="myMovieName" ALIGN="" TYPE="application/x-shockwave-flash" allowScriptAccess="always"
        PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
        </EMBED>
        </OBJECT>
    </div>
</body>
</html>