<?php
namespace Coct\Controller;
use \Coct\Controller\CoBaseController;
class SystemController extends CoBaseController {


	/**
	 * 应用系统内容显示
	 * @return [type] [description]
	 */
	public function index() {
		$system_model = M('PmsSystem');
		$data  = $system_model->select();
		$this->assign(array(
			'data' => $data
		));
		$this->display();
	}


	/**
	 * 添加应用系统
	 * @return [type] [description]
	 */
	public function ins() {
		if(IS_AJAX) {
			$system_model = D('PmsSystem');
			if($system_model->create(I('post.'),1)) {
				$system_model->isenable = $system_model->isenable ? 1 : 0 ; //开启状态更改
				$system_model->url = rtrim($system_model->url,'/');         //去掉末端 /
                $system_model->add() ? sendJson(200 , '应用添加成功') : sendJson(403 , '异常的数据处理错误');
			} 
			sendJson(403 , $system_model->getError());
		}
		$this->display();
	}


	/**
	 * 修改应用系统
	 * @return [type] [description]
	 */
	public function edit() {
		$system_model = D('PmsSystem');
		if(IS_AJAX) {
			if($system_model->create(I('post.'),2)) {
				$system_model->isenable = $system_model->isenable ? 1 : 0 ; //开启状态更改
				$system_model->url = rtrim($system_model->url,'/');         //去掉末端 /
				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {      
                    $sta = $system_model->save();
                    flock($fp,LOCK_UN);      
                }
                fclose($fp);
				$sta !== false ? sendJson(200 , '应用修改成功') : sendJson(403 , '异常的数据处理错误');
			}
			sendJson(403 , $system_model->getError());

		}
		$sid = intval(I('get.sid',0));
		$data = $system_model->find($sid);
		
		if($data){
			$this->assign('data' , $data);
			$this->display();
		}
		else {
			layerClose();  // 关闭弹层
		}
	}

	
	
	/**
	 * 删除系统[暂不开通]
	 * @return [type] [description]
	 */
/*	public function del() {
		$sid = (int) I('get.sid');
		$model = D('PmsSystem');
		$model->delete($sid) ? sendJson(200 , '系统删除成功') : sendJson(403 , '系统删除失败');
	}*/
	

}