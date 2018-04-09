<?php
namespace Coct\Controller;
use \Coct\Controller\CoBaseController;
class NodeController extends CoBaseController { 

	/**
	 * 权限列表展示
	 * @return [type] [description]
	 */
	public function index() {
		$sid = intval(I('get.systemid'));
		$model = D('PmsNode');
		$base = M('PmsSystem')->find($sid);
		if(empty($base)) 
        	$this->alert('糟糕，访问的资源未找到 !');
		$data = $model->getTree($sid);
        $this->assign(array(
        	'sys' => $base ,
            'data' => $data ,
        ));
        $this->display();
	}


	/**
	 * 添加新权限
	 * @return [type] [description]
	 */
	public function ins() {
		$note_model = D('PmsNode');
		if(IS_AJAX) {
			if($note_model->create(I('post.'),1)) {
				$note_model->isenable = $note_model->isenable ? 1 : 0 ; //开启状态更改
				$note_model->add() ? sendJson(200 , '权限添加成功') : sendJson(403 , '异常的数据处理错误');
			} 
			sendJson(403 , $note_model->getError());
		}
		$this->display();
	}


	/**
	 * 权限删除
	 * @return [type] [description]
	 */
	public function del() {
		$noteid = intval(I('get.noteid'));
		$model = D('PmsNode');
		if($model->where('pid = '.$noteid)->find()) 
			sendJson(403 , '该权限配置下存在子权限,无法删除');
		$model->delete($noteid) ? sendJson(200 , '系统删除成功') : sendJson(403 , '系统删除失败');
	}


	/**
	 * 修改权限
	 * @return [type] [description]
	 */
	public function edit() {

		$note_model = D('PmsNode');
		if(IS_AJAX) {
			// 排序处理
			if(I('get.act') == 'sort') {
				$arr = array(
					'id'    => I('post.noteid'),
					'sort'  => I('post.sort'),
				);
				if($note_model->sort($arr)) 
				$note_model->sort($arr) ? sendJson(200 , '排序更新成功') : 	sendJson(403 , '排序更新失败');
			}

			if($note_model->create(I('post.'),2)) {
				$note_model->isenable = $note_model->isenable ? 1 : 0 ; //开启状态更改
				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {      
                    $sta = $note_model->save();
                    flock($fp,LOCK_UN);      
                }
                fclose($fp);
				$sta !== false ? sendJson(200 , '权限配置修改成功') : sendJson(403 , '异常的数据处理错误');
			}
			sendJson(403 , $note_model->getError());
		}

		$noteid = intval(I('get.noteid'));
		$data = $note_model->find($noteid);
		if($data){  		// 防恶意修改参数
			$this->assign('data',$data);
			$this->display();
		}else{
			layerClose();  // 关闭弹层
		}
	}


}