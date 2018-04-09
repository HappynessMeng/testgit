<?php
return array(
	'CURR_SYSTEM_ID' => 1000 ,

	// SSO 访问地址配置
	'SSO_CONFIG' => array(
		'SECRET' => '',	    // 密钥
		'SSO_CHECK_URL' => 'http://zk.dai1yi.com/index.php/Coct/Sso/islogin' ,  // 校验地址
		'SSO_LOGIN_URL' => 'http://zk.dai1yi.com/index.php/Coct/Sso/pclogin' ,  // 登录地址
		'SSO_P3P' => 'http://zk.dai1yi.com/index.php/Coct/Sso/p3psave' ,        // p3p共享cookie地址
		'SYSTEM_STATUS_CHECK_URL' => 'http://zk.dai1yi.com/index.php/Coct/Sso/systemstatus' ,  // 系统状态校验地址
	),
);