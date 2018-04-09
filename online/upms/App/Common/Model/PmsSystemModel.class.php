<?php
namespace Common\Model;
use Think\Model;
class PmsSystemModel extends Model {

	protected $insertFields = array('name','enalias','secret','url','sso_url','off_msg','isenable','createdtime');
	protected $updateFields = array('id','name','secret','enalias','url','sso_url','off_msg','isenable','updatedtime');
	
	protected $_auto = array (          
			   array('createdtime','time',1,'function'),       // 添加时录入创建时间
			   array('updatedtime','time',2,'function'),       // 修改时更改修改时间
        	   array('enalias','strtolower',3,'function') ,    // 添加修改时自动转小写
        	   array('dbname','strtolower',3,'function') ,     // 添加修改时自动转小写

	);


	// 添加系统时验证
	protected $_validate = array(
		// 验证时间  1 添加时验证 2：修改时 3：所有情况都验证
		// 验证条件  0 存在字段就验证（默认）   1 必须验证  2 值不为空的时候验证 
		array('name','/^([\xe4-\xe9][\x80-\xbf]{2}){2,8}$/','系统名称必须为2-8个中文字符', 1 , 'regex' , 3), 
		array('enalias','/^[a-zA-Z]{2,12}$/','英文别名APPID必须为2-12位纯英文字符', 1 , 'regex' , 3), 
		array('enalias','','APPID 已经存在,请保证唯一性',1,'unique',3),
		array('secret','/^\w{32}$/','密匙必须是32位数字字母组合', 1 , 'regex' , 3), 
		array('url' , 'isUrl' , 'url格式不正确' , 1 ,'function' , 3), 
		array('sso_url' , 'require' , 'SSO地址必须填写' , 1 ,'regex' , 3), 
	);


	/**
	 * [根据用户Id 查询拥有相关角色]
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	public function getHaveSystem($userid) {
		$usermodel = M('Useracc');
		$uinfo = $usermodel->where('id ='.$userid)->find();
		$level = $uinfo['sulevel'];
		if($level ==1) 
			$data['sys'] =  $this->ALIAS('s')
						   ->FIELD('s.name,(GROUP_CONCAT(r.rolename)) roles')
						   ->JOIN('__PMS_ROLE__ r on r.systemid = s.id')
						   ->GROUP('s.id asc')
						   ->SELECT();
		if($level ==2) 
			$data['sys'] = $this->ALIAS('s')
						   ->FIELD('s.name,(GROUP_CONCAT(r.rolename)) roles')
						   ->JOIN('__PMS_ROLE__ r on r.systemid = s.id')
						   ->WHERE('s.superuserid ='.$userid)
						   ->GROUP('s.id asc')
						   ->SELECT();

		if($level ==3) {
			$model = M('UserRole');
			$sql = "SELECT   ps.`name`,
			       			 (select GROUP_CONCAT(pr.rolename) 
			       			  FROM {$this->tablePrefix}pms_role pr 
			       			  WHERE (pr.systemid= ur.systemid AND pr.isdefault = 1) OR FIND_IN_SET(pr.id , ur.roleid)
			       		     )  roles
			        FROM {$this->tablePrefix}user_role ur 
			        LEFT JOIN {$this->tablePrefix}pms_system ps ON ps.id = ur.systemid 
			        WHERE ur.userid = {$userid}";
			$data['sys'] = $model->query($sql);
			// echo $sql;
		}



		$data['uinfo'] = $uinfo;

		return $data;
	}


	/**
	 * [系统新增后处理]
	 * @return [type] [description]
	 */
	public function _after_insert($data ,$option) {
		// 写入操作日志
		writeLog('ins' , '系统配置表' , "操作动作=新增系统 | 操作SQL={$this->getLastSql()}"); 
	}


	/**
	 * [修改系统配置后处理]
	 * @return [type]         [description]
	 */
	public function _after_update($data ,$option) {
		// 写入操作日志
		writeLog('edit' , '系统配置表' , "操作动作=修改系统配置 | 操作SQL={$this->getLastSql()}"); 
	}


	/**
	 * [获取所有的系统信息及其角色信息]
	 * @return [type] [description]
	 */
	public function getSystemRole() {
		$rolemodel = M('PmsRole');
		$sysdata  = $this->select();
		$roledata = $rolemodel->order('isdefault desc')->select();

		foreach ($sysdata as $k => $v) {
			foreach($roledata as $s => $i) {
				if($i['systemid'] == $v['id']){
					$sysdata[$k]['roledata'][] = $i ;
				}
			}
		}

		return $sysdata;
	}

}