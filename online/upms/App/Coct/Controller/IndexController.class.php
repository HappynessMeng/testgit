<?php
namespace Coct\Controller;
use \Coct\Controller\IndexController;
class IndexController extends CoBaseController {

	/**
	 * [平台主框架页]
	 * @return [type] [description]
	 */
	public function index() {
		$systemid = session('su');
		$system_model = M('PmsSystem');
		$this->nav = $system_model->where('isenable = 1')->select();
		$this->display();
	}
	
	/**
	 * [欢迎页]
	 * @return [type] [description]
	 */
	public function welcome() {
		$this->display();	
	}


	/**
	 * [登录信息展示页]
	 * @return [type] [description]
	 */
	public function logininfo() {
		$sysmodel = D('PmsSystem');
		$uid  = session('su.id')  ;
		$data = $sysmodel->getHaveSystem($uid);
		$this->assign(array(
			'sys'   => $data['sys'],
			'uinfo' => $data['uinfo'],
		));
		$this->display();
	}

}