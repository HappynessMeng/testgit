<?php
return array(

/**本地*****************************************************************************************************************************/

    // UPMS 配置
    'DB_TYPE'   => 'mysql',
    'DB_HOST'   => '120.27.236.17',
    'DB_NAME'   => 'upms',
    'DB_USER'   => 'remote_user',
    'DB_PWD'    => 'bingxiang@201712',
    'DB_PORT'   =>  3306,
    'DB_PREFIX' => 'c_',
    'DB_CHARSET'=> 'utf8',
    'DB_DEBUG'  =>  TRUE,

    // CRM 数据库配置
    'DB_CRM' => array(
	    'DB_TYPE'   => 'mysql',
	    'DB_HOST'   => '120.27.236.17',
	    'DB_NAME'   => 'zoga_crm_risk',
	    'DB_USER'   => 'remote_user',
	    'DB_PWD'    => 'bingxiang@201712',
	    'DB_PORT'   =>  3306,
	    'DB_PREFIX' => 'crm_',
	    'DB_CHARSET'=> 'utf8',
	    'DB_DEBUG'  =>  TRUE,
    ),
	
	
/**远端*****************************************************************************************************************************/

    // UPMS 配置
	/*
    'DB_TYPE'   => 'mysql',
    'DB_HOST'   => '120.27.236.17',
    'DB_NAME'   => 'upms',
    'DB_USER'   => 'erpdemo',
    'DB_PWD'    => '123456',
    'DB_PORT'   =>  3306,
    'DB_PREFIX' => 'c_',
    'DB_CHARSET'=> 'utf8',
    'DB_DEBUG'  =>  TRUE,

    // CRM 数据库配置
    'DB_CRM' => array(
		'DB_TYPE'   => 'mysql',
        'DB_HOST'   => '120.27.236.17',
        'DB_NAME'   => 'zoga_crm_risk',
        'DB_USER'   => 'erpdemo',
        'DB_PWD'    => '123456',
		'DB_PORT'   =>  3306,
		'DB_PREFIX' => 'crm_',
		'DB_CHARSET'=> 'utf8',
		'DB_DEBUG'  =>  TRUE,
    ),	
	*/
);
