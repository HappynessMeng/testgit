<?php
require_once 'form_verify.php'; // 调用表单验证函数库
require_once 'func_data.php';  // 针对模型数据处理的函数库


/**
 * [基于layui构造的分页]
 * @param  array  $totalRows [数据总数]
 * @param  [type] $listRows  [列表每页显示行数]
 * @param  [type] $suffix    [是否开启页面后缀]
 * @param  string $p         [分页页码 参数]
 * @param  string $replace_str        [待替换 页码]
 * @param  array  $parameter  [传递的参数]
 * @return [type]             [array]
 */
function laypage($totalRows , $listRows = 20 ,$suffix = true, $p ='p' , $replaceStr = '[PAGE]' , $parameter = array()) {
    $nowPage   = empty($_GET[$p]) ? 1 : intval($_GET[$p]);    // 当前页码
    $nowPage   = $nowPage>0 ? $nowPage : 1;                  // 当前页码

    /* 计算分页信息 */
    $totalPages = ceil($totalRows / $listRows); //总页数
    if(!empty($totalPages) && $nowPage > $totalPages) {
         $nowPage = $totalPages;  // 超出的范围最大就是总页数
    }

    $firstRow  = $listRows * ($nowPage - 1);                // 翻页数据开始行
    $parameter = empty($parameter) ? $_GET : $parameter ;  //  分页跳转时要带的参数
    $parameter[$p] = $replaceStr;
    $url = $suffix ? U(ACTION_NAME, $parameter) : U(ACTION_NAME, $parameter ,false);

    $data = array(
        'nowPage'    => $nowPage,
        'firstRow'   => $firstRow,
        'totalPages' => $totalPages,
        'url'        => $url
    );

    return $data;
    
}


/**
 * ajax返回 json 信息 
 * @param  integer $code   [状态码]
 * @param  string  $notice [提示消息]
 * @return [type]          [json]
 */
function sendJson($code = 403 , $notice = '') {
	$arr = array (
		'code'    => $code ,
		'notice'  => $notice ,
	);
	header('Content-Type:text/html;charset=utf-8');
    exit(json_encode($arr));
}


/**
 * 强制关闭layer 弹层
 * @return [type] [description]
 */
function layerClose() {
	header('Content-Type:text/html; charset=utf-8');
	$close = <<<"JAVASCRIPT"
		    <script>
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
            </script>
JAVASCRIPT;
	echo $close;
	exit('Notice: Abnormal error data');
}


/**
 * [密码加密]
 * @param  [type] $pass [description]
 * @return [type]       [description]
 */
function pwdSave($pass) {
    $key = 'L.2-Wp%09=13.info';
    return md5(md5($key) . $pass . substr(crypt($pass,$key) , 5));      
}

/**
 * [生成指定长度的数字字母组合]
 * @param  integer $pw_length [密码长度]
 * @return [type]             [description]
 */
function randLenthStr( $length = 6 ) {
    // 字符集，可任意添加你需要的字符 
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $uname = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        // 这里提供两种字符获取方式
        // 第一种是使用substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组$chars 的任意元素
        // $uname .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $uname .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $uname;
}



/**  
 * 获取客户端浏览器信息 添加win10 edge浏览器判断  
 */  
function getBrowser(){  
     $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串  
     if (stripos($sys, "Firefox/") > 0) {  
         preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);  
         $exp[0] = "Firefox";  
         $exp[1] = $b[1];  //获取火狐浏览器的版本号  
     } elseif (stripos($sys, "Maxthon") > 0) {  
         preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);  
         $exp[0] = "傲游";  
         $exp[1] = $aoyou[1];  
     } elseif (stripos($sys, "MSIE") > 0) {  
         preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);  
         $exp[0] = "IE";  
         $exp[1] = $ie[1];  //获取IE的版本号  
     } elseif (stripos($sys, "OPR") > 0) {  
             preg_match("/OPR\/([\d\.]+)/", $sys, $opera);  
         $exp[0] = "Opera";  
         $exp[1] = $opera[1];    
     } elseif(stripos($sys, "Edge") > 0) {  
         //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配  
         preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);  
         $exp[0] = "Edge";  
         $exp[1] = $Edge[1];  
     } elseif (stripos($sys, "Chrome") > 0) {  
             preg_match("/Chrome\/([\d\.]+)/", $sys, $google);  
         $exp[0] = "Chrome";  
         $exp[1] = $google[1];  //获取google chrome的版本号  
     } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){  
         preg_match("/rv:([\d\.]+)/", $sys, $IE);  
             $exp[0] = "IE";  
         $exp[1] = $IE[1];  
     }else {  
        $exp[0] = "未知浏览器";  
        $exp[1] = "";   
     }  
     return $exp[0].'('.$exp[1].')';  
} 




/**  
 * 获取客户端操作系统信息包括win10  
 */  
function getOs(){  
$agent = $_SERVER['HTTP_USER_AGENT'];  
    $os = false;  
   
    if (preg_match('/win/i', $agent) && strpos($agent, '95'))  
    {  
      $os = 'Windows 95';  
    }  
    else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))  
    {  
      $os = 'Windows ME';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))  
    {  
      $os = 'Windows 98';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))  
    {  
      $os = 'Windows Vista';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))  
    {  
      $os = 'Windows 7';  
    }  
      else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))  
    {  
      $os = 'Windows 8';  
    }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))  
    {  
      $os = 'Windows 10';#添加win10判断  
    }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))  
    {  
      $os = 'Windows XP';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))  
    {  
      $os = 'Windows 2000';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))  
    {  
      $os = 'Windows NT';  
    }  
    else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))  
    {  
      $os = 'Windows 32';  
    }  
    else if (preg_match('/linux/i', $agent))  
    {  
      $os = 'Linux';  
    }  
    else if (preg_match('/unix/i', $agent))  
    {  
      $os = 'Unix';  
    }  
    else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'SunOS';  
    }  
    else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))  
    {  
      $os = 'IBM OS/2';  
    }  
    else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))  
    {  
      $os = 'Macintosh';  
    }  
    else if (preg_match('/PowerPC/i', $agent))  
    {  
      $os = 'PowerPC';  
    }  
    else if (preg_match('/AIX/i', $agent))  
    {  
      $os = 'AIX';  
    }  
    else if (preg_match('/HPUX/i', $agent))  
    {  
      $os = 'HPUX';  
    }  
    else if (preg_match('/NetBSD/i', $agent))  
    {  
      $os = 'NetBSD';  
    }  
    else if (preg_match('/BSD/i', $agent))  
    {  
      $os = 'BSD';  
    }  
    else if (preg_match('/OSF1/i', $agent))  
    {  
      $os = 'OSF1';  
    }  
    else if (preg_match('/IRIX/i', $agent))  
    {  
      $os = 'IRIX';  
    }  
    else if (preg_match('/FreeBSD/i', $agent))  
    {  
      $os = 'FreeBSD';  
    }  
    else if (preg_match('/teleport/i', $agent))  
    {  
      $os = 'teleport';  
    }  
    else if (preg_match('/flashget/i', $agent))  
    {  
      $os = 'flashget';  
    }  
    else if (preg_match('/webzip/i', $agent))  
    {  
      $os = 'webzip';  
    }  
    else if (preg_match('/offline/i', $agent))  
    {  
      $os = 'offline';  
    }  
    else  
    {  
      $os = '未知操作系统';  
    }  
    return $os;    
}  



/**
 * 远端根据IP 获取 city
 * @param  [type] $ip [description]
 * @return [type]     [description]
 */
function getIPLoc($queryIP){    
    $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;    
    $ch = curl_init($url);     
    curl_setopt($ch,CURLOPT_ENCODING ,'utf8');     
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
    $location = curl_exec($ch);    
    $location = json_decode($location);    
    curl_close($ch);         
    $loc = "";   
    if($location===FALSE) return "";     
    if (empty($location->desc)) {    
      $loc = $location->province.$location->city.$location->district.$location->isp;  
    }else{     
      $loc = $location->desc;    
    }    
    return $loc;
}

