<?php

/**
 * [日志写入函数]
 * @return [type] [description]
 */
function writeLog($action , $object , $description) {
		$model = M('SystemLog');
		$client = '操作系统-'.getOs().'--浏览器'.getBrowser();
		$ip = ip2long($_SERVER["REMOTE_ADDR"]);
		$userid = session('su.id') ;  // 获取sessionid
		$data = array(
			'action'      => $action,
			'description' => $description,
			'ip'          => $ip,
			'createdtime'  => time(),
			'client'      => $client,
			'object'      => $object,
			'userid'      => $userid,
		);

	   return $model->add($data) ? true : false;
}