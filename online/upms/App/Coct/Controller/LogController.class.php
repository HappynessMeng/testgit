<?php
namespace Coct\Controller;
use \Coct\Controller\CoBaseController;
class LogController extends CoBaseController {

	/**
	 * [日志展示]
	 * @return [type] [description]
	 */
	public function index() {
		$model = D('SystemLog');
		$data = $model->search(15 , I('get.'));
		$this->assign(array(
			'data'  => $data['data'],
			'spage' => $data['spage'],
			't'  => C('SYSTEM_LOG_TYPE'),
		));
		$this->display();
	}


	/**
	 * [查看操作日志详细]
	 * @return [type] [description]
	 */
	public function detailed() {
		$id = intval(I('get.id'));
		$model = D('SystemLog');
		$data = $model->detailed($id);
		if(empty($data))
			layerClose(); // 强制关闭弹窗*/
		$logtype = C('SYSTEM_LOG_TYPE');
		$this->assign(array(
			'v' => $data,
			't' => $logtype,
		));
		$this->display();
	}
	
}