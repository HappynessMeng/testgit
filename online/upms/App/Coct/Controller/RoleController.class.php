<?php
namespace Coct\Controller;
use \Coct\Controller\CoBaseController;
class RoleController extends CoBaseController { 

	// 角色内容展示
	public function index() {
		$sid = intval(I('get.systemid'));
		$model = M('PmsRole');
		$base  = M('PmsSystem')->find($sid);
		
		if(empty($base)) 
			 $this->alert('糟糕，访问的资源未找到 !');

		$data = $model->where('systemid = '.$sid)->select();
        $this->assign(array(
        	'sys'  => $base ,
            'data' => $data ,
        ));
        $this->display();
	}



	/**
	 * 角色添加
	 * @return [type] [description]
	 */
	public function ins() {
		$role_model = D('PmsRole');
		$node_model = D('PmsNode');
		if(IS_AJAX) {
			if($role_model->create(I('post.'),1)) {
				$role_model->isdefault = $role_model->isdefault ? 1 : 0 ; //开启状态更改
				$role_model->createduserid = session('su.id');
				S('cknodes' , trim(I('post.nodes',null)) , 10);  // 缓存选择权限 - 方便模型处理
                $role_model->add() ? sendJson(200 , '角色添加成功') : sendJson(403 , '异常的数据处理错误');
			} 
			sendJson(403 , $role_model->getError());
		}

		$sid = intval(I('get.systemid'));
		$notearr = $node_model->getTree($sid ,false); // 树状结构权限显示
		$this->assign(array(
			'notearr' => $notearr,
		));
		$this->display();
	}



	/**
	 * 角色修改
	 * @return [type] [description]
	 */
	public function edit() {
		$role_model = D('PmsRole');
		$node_model = D('PmsNode');
		$rolenode_model = M('RoleNode');
		if(IS_AJAX) {
			if($role_model->create(I('post.'),2)) {
				$role_model->isdefault = $role_model->isdefault ? 1 : 0 ; //开启状态更改
				$role_model->updateduserid = session('su.id');
				S('cknodes' , trim(I('post.nodes',null)) , 10);  // 缓存选择权限 - 方便模型处理
				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {      
                   $sta = $role_model->save();  // 触发模型操作
                   flock($fp,LOCK_UN);      
               }
               fclose($fp);
			   $sta !== false ? sendJson(200 , '角色修改成功') : sendJson(403 , '异常的数据处理错误');
			} 
			sendJson(403 , $role_model->getError());
		}

		$sid = intval(I('get.systemid'));
		$roleid = intval(I('get.roleid'));
		$data = $role_model->find($roleid);

		$benodes = $rolenode_model->field('nodes')->where('roleid = '.$roleid)->find();   // 查询当前角色拥有全部权限节点
		$benodes = explode(',',$benodes['nodes']);     // 分割成单个ID 数组

		$notearr = $node_model->getTree($sid ,false);  // 树状结构权限显示
		$this->assign(array(
			'notearr' => $notearr,
			'data'    => $data,
			'benodes' => $benodes,
		));
		$this->display();
	
	}



	/**
	 * 角色删除
	 * @return [type] [description]
	 */
	public function del() {
		if(IS_AJAX) {
			$roleid = intval(I('get.roleid'));
			$model = D('PmsRole');
			$model->delete($roleid) ? sendJson(200 , '角色删除成功') : sendJson(403 , $model->getError());
		}
	}


}