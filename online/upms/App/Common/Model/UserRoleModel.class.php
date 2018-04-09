<?php
namespace Common\Model;
use Think\Model;
class UserRoleModel extends Model {

	/**
	 * [获取用户在各个系统中拥有的角色]
	 * @param  [type] $uid      [用户ID]
	 * @param  [type] $systemid [查询哪套系统角色]
	 * @return [type]           [description]
	 */
	public function getUserRole($uid , $systemid = null) {
		$uid = intval($uid); 
		$other = is_null($systemid) ? '' : ' and systemid ='.$systemid;
		$result = $this->where('userid = '. $uid . $other)->select();
		return $result;
	}


	/**
	 * [获取用户关联系统及对应的角色]
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	public function getUallRole($userid) {

		$result = $this->where(array('userid'=>array('eq',$userid)))->select();
		if(!empty($result)) {
			foreach ($result as $k => $v) {
				$data[$v['systemid']] = explode(',' , $v['roleid']);
			}
		}

		return $data;
	}

}