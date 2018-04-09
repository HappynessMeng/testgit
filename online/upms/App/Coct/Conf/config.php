<?php
return array(

	'LOAD_EXT_CONFIG' => 'db', 

	'TMPL_CACHE_ON'    => false,            //禁止模板编译缓存
	'HTML_CACHE_ON'    => false,            //禁止静态缓存 
	'ACTION_CACHE_ON'  => false,           // 默认关闭Action 缓存
	'DB_FIELDS_CACHE'  => false ,           // 关闭字段缓存 

	// 视图模板显示
	//'DEFAULT_V_LAYER'          =>   'Tpl'  ,  // 首页已经定义了
    'TMPL_TEMPLATE_SUFFIX'     =>   '.php' ,


    'TMPL_PARSE_STRING'  =>array(
         '__RES__' => '/Res',              // 增加路径规则
    ),

    'DEFAULT_FILTER'        => 'trim,htmlspecialchars',   // 默认过滤机制

    'DEFAULT_PURL'          => "Uploads/picture/" , // 默认头像上传地址

    // 操作日志类型
    'SYSTEM_LOG_TYPE'  => array(
		'ins'     => '新建',
		'edit'    => '修改',
		'delete'  => '删除',
		'ohter'   => '其他',
	),

);