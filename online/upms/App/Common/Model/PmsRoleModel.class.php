<?php
namespace Common\Model;
use Think\Model;
class PmsRoleModel extends Model {

	protected $insertFields = array('rolename','systemid','description','createdtime','createduserid','isdefault');
	protected $updateFields = array('id','rolename','description','updatedtime','updateduserid','isdefault');
	
	 protected $_auto = array (          
			   array('createdtime','time',1,'function'),    // 添加时录入创建时间
			   array('updatedtime','time',2,'function'),    // 修改时更改修改时间
	 );


	// 添加系统时验证
	protected $_validate = array(
		// 验证时间  1 添加时验证 2：修改时 3：所有情况都验证
		// 验证条件  0 存在字段就验证（默认）   1 必须验证  2 值不为空的时候验证 
		array('rolename','/^([\xe4-\xe9][\x80-\xbf]{2}){2,8}$/','角色名称必须为2-8个中文字符', 1 , 'regex' , 3),
		array('description', 'require', '角色描述必须填写', 1, 'regex', 3),
	);


	/**
	 * [获取角色信息]
	 * @param  [type]  $sid  [系统编号]
	 * @param  integer $type [默认角色 类型 0 普通 1 默认]
	 * @return [type]        [description]
	 */
	public function getRole($sid , $type = null) {
		$sid   = intval($sid);
		$other = is_null($type) ? '' : ' and isdefault = '.$type ;
		$result = $this->where('systemid ='.intval($sid) . $other)
 				  ->order('id asc')
				  ->select();
		return  $result; 			
	}


	
	/**
	 * [角色录入后的操作]
	 * @param  [type] &$data  [description]
	 * @param  [type] $option [description]
	 * @return [type]         [description]
	 */
	protected function _after_insert($data , $option){

		// 添加新角色后录入角色权限关系
		$nodes = S('cknodes'); // 获取选中权限缓存
		$model = M('RoleNode');
		$arr = array(
			'roleid'  => $data['id'],
			'nodes'   => $nodes,
		);
		$model->add($arr);
		S('cknodes',null);

		// 日志操作
		writeLog('ins','角色表', "操作动作=新增角色 | 角色名称={$data['rolename']} | 角色ID={$data['id']} | 关联节点ID=({$nodes})");	
	}


	/**
	 * [角色基本信息修改后的操作]
	 * @return [type] [description]
	 */
	protected function _after_update(&$data , $option) {

		// 修改角色后更新 角色权限关系
		$nodes = S('cknodes');  // 获取选中权限缓存
		$model = M('RoleNode');
		$result = $model->where('roleid = '.$option['where']['id'])->setField('nodes',$nodes);
		S('cknodes',null);
		if(!$result) {
			$this->error = '异常的数据错误,角色修改失败' ;
			return FALSE;
		}

		// 日志操作
		writeLog('edit' , '角色表' , "操作动作=修改角色配置 | 角色名称={$data['rolename']} | 角色ID={$data['id']} | 关联节点ID=({$nodes})");
	}


	/**
     * [角色删除前的操作]
     * @return [type] [description]
     */
	protected function _before_delete($option){

		$model = M('UserRole');
		$roleid = $option['where']['id'];

		$count = $model->where('roleid = '.$roleid)->count();
		if($count){
			$this->error = '该角色下存在用户,无法删除';
			return FALSE;
		}

		// 删除角色权限信息
		$role_node_model = M('RoleNode');
		$role_node_model->where('roleid = '.$roleid)->delete();

		// 日志操作
		writeLog('delete' , '角色表' , "操作动作=删除角色信息 | 角色ID={$option['where']['id']}");
	}


}