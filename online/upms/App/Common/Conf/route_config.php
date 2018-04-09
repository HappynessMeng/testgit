<?php
return array(
	
	'DOMAIN_URL' => 'http://zk.dai1yi.com' ,  // 必须http:// 或 https:// 格式 不能已 /结尾

	// 开启路由
	'URL_ROUTER_ON'   => true , 

	// URL映射定义规则 //访问前面地址,映射后面的内容
	'URL_MAP_RULES'=>array(
		 'upms'=> 'coct/login/index' ,               
	),

	'URL_CASE_INSENSITIVE'  =>  true,   // 不区分大小写


);