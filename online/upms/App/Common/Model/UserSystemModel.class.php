<?php
namespace Common\Model;
use Think\Model;
class UserSystemModel extends Model {

	/**
	 * [用户关联信息初始化]
	 * @param  [type] $userid [description]
	 * @param  [type] $data   [description]
	 * @return [type]         [description]
	 */
	public function userInit($userid , $data  , $createduserid){
		$userrole = M('UserRole');
		$this->where('userid ='.$userid)->delete();
		$userrole->where('userid ='.$userid)->delete();
		
		if(!empty($data)) {
			foreach ($data as $k => $v) {
				$tmparr = array(
					'systemid' => $v['systemid'] ,
					'userid'   => $userid ,
					'createduserid'  => $createduserid ,
					'createdtime'    => time(),
				);

				$roletmparr = array(
					'systemid' => $v['systemid'] ,
					'userid'   => $userid,
					'roleid'   => $v['roles'],
				);

				$str .= "( 系统ID={$v['systemid']} 角色ID={$v['roles']} ) / " ;

				$this->add($tmparr);
				$userrole->add($roletmparr); // 录入用户系统和用户匹配
			}

		// 日志操作
		writeLog('ins','用户系统表 - 用户角色表', "操作动作 = 分配系统及角色 | 系统角色对应关系 {$str}");	
		}

		return true;
	}

}