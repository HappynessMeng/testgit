<?php
namespace Coct\Controller;
use Think\Controller;
class CoBaseController extends Controller {

	public function __construct() {
		parent::__construct();

		// 登录验证
		session('[start]');
		if(session('?su') === false){
			$url = U('Login/index');
			echo "<script>parent.location.href='{$url}';</script>";
			exit(-1);
		} 
		
		$su         = session('su');
		$sulevel    = intval($su['sulevel']);
		$userid     = $su['id'];

		$controller = strtolower(CONTROLLER_NAME);
		$action     = strtolower(ACTION_NAME);


		// 多人登录判断和控制
		$userinfo = M('Useracc')->where('id ='.$userid)->find();
		$newcurrtime = intval($userinfo['currenttime']);
		unset($userinfo);
		if(intval($su['currenttime']) < $newcurrtime && empty($su['ismulti'])) {
			$this->alert('您的账号已在别处登录，当前登录已失效!' , U('Login/loding','sta=exit'));
		}


		/* 普通用户权限 */
		$default = array(
			'useracc' => array(
				'ucenter'       => '用户中心' ,
				'avaupload'     => '上传头像' ,
			),
			'index' => array(
				'index'         => '平台首页',
				'logininfo'     => '登录信息',
				'welcome'       => '欢迎页面',
			),
		);

		/* UPMS 超管权限 */
		$sonsu_noallow = array(
			'system' => array(
				'index'         => '系统列表' ,
				'ins'           => '系统添加' ,
				'edit'          => '系统修改' ,
			),
			'useracc' => array(
				'commons'       => '所有用户',
				'allot'         => '系统分配',
			),
			'log' => array(
				'index'         => '日志列表',
				'detailed'      => '日志详细',
			),		
		);

		// 用户级别信息错误
		if($sulevel<=0 || $sulevel>3)  
			$this->alert('异常的用户登录,请联系开发者!');

		// 超管权限判定
		if($sulevel == 3 && isset($default[$controller][$action]) == false) 
			$this->alert('您当前的权限级别无法访问该内容!');

		// 分系统管理员权限判断
		if($sulevel == 2) {
			if(isset($sonsu_noallow[$controller][$action])) 
				$this->alert('您当前的权限级别无法访问该内容!');	

			$urlsystem = intval(I('request.systemid'));
			$psmodel = M('PmsSystem');
			$data = $psmodel->where('superuserid ='.$userid)->find();
			unset($psmodel);

			if(intval($data['id']) != $urlsystem && empty($urlsystem) == false)
				 $this->alert('您当前的权限级别无法访问该内容!');
		}

	}

	/**
	 * [空操作方法]
	 * @return [type] [description]
	 */
	public function _empty(){
        $this->alert('糟糕，访问的资源未找到 !');
    }

	
	/**
	 * [alert 消息提示跳转页面]
	 * @param  [type] $notice [提示语]
	 * @param  [type] $url    [跳转地址]
	 * @return [type]         [description]
	 */
	public function alert($notice , $url = null) {
		$this->assign(array(
			'notice' => $notice,
			'url'    => $url,
		));
		$this->display('Notice/layer');
		exit(-1);
	}



}