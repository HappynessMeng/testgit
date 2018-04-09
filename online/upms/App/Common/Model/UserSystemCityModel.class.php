<?php
namespace Common\Model;
use Think\Model;
class UserSystemCityModel extends Model {

	/**
	 * [getUserSystemCityInfo 获取用户子系统城市配置信息]
	 * @param  [type] $systemid [系统编号]
	 * @param  [type] $userid   [用户编号]
	 * @return [type]           [description]
	 */
	public function getUserSystemCityInfo($systemid , $userid) {
		$data = $this->FIELD('citys')
				->WHERE('systemid ='.$systemid.'  and userid ='.$userid)
				->FIND();
		return $data;
	}

	/**
	 * [ins description]
	 * @param  [type] $systemid [系统编号]
	 * @param  [type] $userid   [用户编号]
	 * @param  [type] $citys    [城市编号]
	 * @return [type]           [description]
	 */
	public function ins($systemid , $userid , $citys){
		$this->startTrans();
		$userdata = M('Useracc')->find($userid);
		if(empty($userdata)) {
			$this->rollback();
			$this->error = '用户信息不存在!';
			return false;
		}

		if($userdata['sulevel'] != 3) {
			$this->rollback();
			$this->error = '只能对普通会员进行分配城市设置';
			return false;
		}

		$system_data = M('UserSystem')->WHERE('systemid ='.$systemid.' and userid ='.$userid)->FIND();

		if(empty($system_data)) {
			$this->rollback();
			$this->error = '提示：你没有分配该系统模块';
			return false;
		}

		$del = $this->where('systemid ='.$systemid.' and userid ='.$userid)->delete();
		if($del !== false) {
			$arr = array(
				'systemid' => $systemid ,
				'userid'   => $userid ,
				'citys'    => $citys ,
			);
			if($this->add($arr)) {
				$this->commit();
				return true;
			}else{
				$this->rollback();
				$this->error = '设置失败,请刷新页面后重新尝试';
				return false;
			}
		} else{
			$this->rollback();
			$this->error = '提示：城市分配设置失败';
			return false;
		}


	}

}