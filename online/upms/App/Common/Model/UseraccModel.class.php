<?php
namespace Common\Model;
use Think\Model;
class UseraccModel extends Model {

	// 允许写入字段
	protected $insertFields  = array('alias','password','cpassword','sex','realname','phone','email','ismulti','sulevel','createdtime','createduserid','isenable','isdeleted','logintype','picture');

	// 允许修改字段
	protected $updateFields = array('id','alias','password','cpassword','sex','realname','phone','email','ismulti','updatedtime','updateduserid','isenable','isdeleted','logintype','picture');
	

	// 自动验证
	protected $_validate = array(

		// 验证条件  0 存在字段就验证（默认）   1 必须验证  2 值不为空的时候验证 
		// 验证时间  1 添加时验证 2：修改时 3：所有情况都验证
	
		array('alias', '/^[a-zA-Z]\w{5,16}$/', '用户名为字母开头6-16位字母数字组合', 1, 'regex', 3),	
		// 添加验证[pass]
		array('password', '/((?=.*\d)(?=.*\D)|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^.{6,16}$/', '密码必须至少包含数字、字母、字符其中两种长度为6-16位', 1, 'regex', 1),	
		// 修改验证[pass]
		array('password', '/((?=.*\d)(?=.*\D)|(?=.*[a-zA-Z])(?=.*[^a-zA-Z]))^.{6,16}$/', '密码必须至少包含数字、字母、字符其中两种长度为6-16位', 2, 'regex', 2),

		array('cpassword', 'password', '两次密码输入不一致！', 1, 'confirm', 3),
		array('realname', '/^([\xe4-\xe9][\x80-\xbf]{2}){2,8}$/', '请输入有效的真实姓名', 1, 'regex', 3),
		array('phone','/^1[34578]\d{9}$/','请输入正确的手机', 1 , 'regex' , 3), 
		array('email','/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/','请输入正确的邮箱', 1 , 'regex' , 3), 
		array('alias','','别名已经被占用,请尝试其他别名',1,'unique',3),
		array('phone','','手机号码已注册过',1,'unique',3),
		array('email','','邮箱已注册过',1,'unique',3),
	);
	

	// 自动完成
	protected $_auto = array (          
			   array('createdtime','time',1,'function') ,    // 添加时录入创建时间
         array('password','pwdSave',1,'function') ,    // 添加时录入创建时间
			   array('isdeleted' ,0) ,                       // 新增时写入
			   array('updatedtime','time',2,'function'),     // 对update_time字段在更新的时候写入当前时间戳
	);


  /**
   * [登录判断]
   * @param  [type] $u [description]
   * @param  [type] $p [description]
   * @return [type]    [description]
   */
  public function pcLogin($u , $p ) {

        if(empty($u) || empty($p)){
          $this->error = '请输入合法的登录帐号和密码。';
          return false;
        }

        $u = addslashes($u);
        $p = pwdSave(addslashes($p));

        // 手机登录条件
        $where['phone'] = array(
            'phone'     => array('eq' ,$u),
            'password'  => array('eq' ,$p),
            'logintype' => array('eq' ,1),
        );

        // 别名登录条件
        $where['email'] = array(
            'email'     => array('eq' ,$u),
            'password'  => array('eq' ,$p),
            'logintype' => array('eq' ,2),
        );

        $where['alias'] = array(
            'alias'    => array('eq' ,$u),
            'password' => array('eq' ,$p),
        );

        // 关键字匹配字段正则
        $keysarr = array(
            'phone' => '/^1[34578]\d{9}$/',      // 手机正则
            'alias' => '/^[a-zA-Z]\w{5,16}$/',   // 别名正则
            'email' => '/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',  // 邮箱正则
        );

        foreach ($keysarr as $k => $v) {
            if(preg_match($v , $u)){
                $fieldname = $k ;
                break;
            }
        }

        if(empty($fieldname)) {
          $this->error = '请输入合法的登录帐号和密码。';
          return false;
        }

        $data = $this->where($where[$fieldname])->find();

        if(empty($data)) {
            $this->error = '帐号不存在或密码错误。';
            return false;
        } else{

            // 已被禁用的
            if(empty($data['isenable']) && $data['sulevel']!=1){
                $this->error = '当前帐号已经被禁用，如有疑问请联系管理员。';
                return false;
            }

            // 已被删除
            if(!empty($data['isdeleted']) && $data['sulevel']!=1){
                $this->error = '登录帐号不存在。';
                return false;
            }
			
			// 更新登录信息
			$arr = array(
				'id'         => $data['id'] ,
				'currentip'  => ip2long($_SERVER["REMOTE_ADDR"]),
				'lastip'     => $data['currentip'],
				'currenttime' => time(),
				'lasttime'    => $data['currenttime'],
			);
			if($this->save($arr) === false){
				$this->error = '登录信息更新失败。';
				return false;	
			}

            return $this->find($data['id']);
			
        }

  }


  /**
   * [判断一个系统下是否存在超管用户]
   * @param  [type]  $systemid [description]
   * @return boolean           [description]
   */
  public function isExistsSuUser($systemid) {
    $sql = "SELECT u.id FROM {$this->tablePrefix}useracc u 
            LEFT JOIN {$this->tablePrefix}user_system us ON us.userid = u.id
            WHERE us.systemid ={$systemid} AND u.sulevel = 2 
            LIMIT 1";

    /* 这样处理的目的，避免更新时插入组件错误，因为存在缓存*/
    $result = $this->query($sql);
    return $result;

  }
	
	
	/**
	 * [用户管理信息展示]
	 * @param  [type] $sid [description]
	 * @return [type]      [description]
	 */
	public function search($page = 20 , $sid , $loginuserlevel ,$arr) {

    $keys  = trim($arr['keys']) ? trim($arr['keys']) : null;
    $sta   = intval($arr['status']);
    $stime = empty($arr['stime']) ? null : strtotime($arr['stime'].' 00:00:00');
    $etime = empty($arr['etime']) ? null : strtotime($arr['etime'].' 23:59:59');

    // 获取表前缀
    $tb_prefix = $this->tablePrefix;
    

    // 拼接用户关联的系统名
    $sql = 'SELECT GROUP_CONCAT(s.name)  
           FROM '.$tb_prefix.'user_system us 
           LEFT  JOIN '.$tb_prefix.'pms_system s on us.systemid = s.id 
           WHERE us.userid = u.id 
           GROUP BY us.userid asc';

    // 默认查询字段 u为用户表
    $selfield ='u.* , ('.$sql.') system_name' ; 
        
    // 如果存在指定系统ID
    if(intval($sid)) {
        $all_userid .='select userid from  ' . $tb_prefix .'user_system where systemid  ='.$sid ;
        $selfield ='u.*' ; 

        // 如果是子系统管理 则只显示普通用户         
        if($loginuserlevel ==2) $level .= ' and u.sulevel = 3'; 

        // 获取当前系统下存在的用户ID 
        $where = 'id in ('.$all_userid.') '.$level; 

    } else{
          $where = ' u.sulevel !=1 ';
    }

    
		
    // 关键字匹配字段正则
    $keysarr = array(
        'phone' => '/^1[34578]\d{0,9}$/',    // 满足手机开头就匹配
        'alias' => '/^[a-zA-Z]\w{0,16}$/',   // 满足一个字符就匹配
        'email' => '/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',  // 满足邮箱格式就匹配
        'realname' => '/^([\xe4-\xe9][\x80-\xbf]{2}){1,8}$/',  // 满足一个汉字就匹配
    );

    if(!empty($keys)) {
      foreach ($keysarr as $k => $v) {
          if(preg_match($v,urldecode($keys))){
              $where .= ' and '.$k. ' like \'%'.$keys.'%\'';
              break;  // 匹配一次后自动跳出
          }
      }
    }

    // 状态匹配条件
    $statusFiled = array(
         1 => ' and isenable = 1 and isdeleted = 0'  ,
         2 => ' and isenable = 0 and isdeleted = 0'  ,
         3 => ' and isdeleted = 1' ,
    );

    if(array_key_exists($sta , $statusFiled)) 
       $where .= $statusFiled[$sta]; 

    // 时间匹配
    if(!empty($stime))  $where .= ' and createdtime >= '.$stime;
    if(!empty($etime))  $where .= ' and createdtime <= '.$etime;


		$line  = $page; // 每页的行数
		$count = $this->alias('u')->where($where)->count();
		$spage = laypage($count , $line); // 翻页函数

    $data = $this->alias('u')
           ->field($selfield)
           ->where($where)
           ->ORDER('u.id asc')
           ->limit($spage['firstRow'].','.$line)
           ->select();

    $arr = array(
      'data'   => $data ,
      'spage'  => $spage ,
    );

		return $arr;
	}



	/**
	 * [用户录入后操作]
	 * @return [type] [description]
	 */
    protected function _after_insert($data , $option){ 
      $systemid = S('usysid');
      $roleids  = S('uroles');
     	$userid   = $data['id'] ;
      $sulevel  = $data['sulevel'] ;

      // 操作日志记录            
      writeLog('ins' , '用户表' , "操作动作=新增用户 | 关联系统ID={$systemid} | 操作SQL={$this->getLastSql()}");

    	// 关联用户及系统
    	$usersystem_model = M('UserSystem') ;
      $usarrdata = array(
          'systemid'       => $systemid,
          'userid'         => $userid,
          'createduserid'  => $data['createduserid'],
          'createdtime'    => time(),
      );
      $usersystem_model->add($usarrdata);
      unset($usersystem_model); // 销毁模型


      if($sulevel == 2) { // 更新系统表对应的超管用户
          $system_model = M('PmsSystem');
          $sta = $system_model->where('id ='.$systemid)->setField('superuserid',$userid);
          writeLog('edit' , '系统配置表' , "操作动作=系统关联超管用户 | 关联系统ID={$systemid} | 系统超管用户ID={$userid}");
      } else {
      	   // 关联用户和角色
        	$userrole_model = M('UserRole') ;
        	$urarr = array(
        		'systemid'    => $systemid,
        		'userid'      => $userid,
        		'roleid'      => $roleids,
        	);
        	$userrole_model->add($urarr);
      }

    	// 销毁缓存资源
    	S('usysid', null) ;
    	S('uroles', null) ;
    }


    /**
     * [用户基本信息修改前操作]
     * @return [type]         [description]
     */
    protected function _before_update(&$data , $option) {
        // 修改密码的过程中,如密码为空则删除该字段
        if(empty($data['password'])) {
              unset($data['password']);
        } else { 
              $data['password'] = pwdSave($data['password']);
        }

    }


    /**
     * [用户基本信息修改完后的操作]
     * @return [type]         [description]
     */
    protected function _after_update($data , $option) {

      $controller = strtolower(CONTROLLER_NAME);
      $action     = strtolower(ACTION_NAME);

      // 该控制器和方法下不触发 角色更新
      $noallow = array(
        'useracc' => array(
                    'ucenter' => '个人中心',
                    'del'     => '删除用户',
        ),
        'login'   => array('index'   => '登录'),
      );

      $sulevel = S('sulevel');

      if(!isset($noallow[$controller][$action]) && $sulevel == 3) {

            	// 关联用户和角色信息修改
            	$userid         = $option['where']['id'];
            	$systemid       = S('usysid');
            	$roleids        = S('uroles');
            	$userrole_model = M('UserRole') ;

          		$where = array(
          			 'systemid' => $systemid ,
          			 'userid'   => $userid ,
          		);
            	$userrole_model->where($where)->setField('roleid',$roleids);

              // 操作日志记录
              writeLog('edit' , '用户表' , "操作动作=修改用户基本信息 | 关联系统ID={$systemid} | 操作SQL={$this->getLastSql()}");
            	// 销毁缓存资源
            	S('usysid', null) ;
            	S('uroles', null) ;
      }

      S('sulevel',null);
    }


    /**
     * [更改用户为删除状态]
     * @return boolean [description]
     */
    public function isDel($uid , $opuserid) {

      $data = $this->find($uid);

      $userrole_model   = M('UserRole');
      $usersystem_model = M('UserSystem');
 
      if(empty($data)) {
          $this->error = '指定删除的用户不存在';
          return false;
      } else{
            if($data['sulevel'] <=2){
                $this->error = '系统管理员无法删除';
                return false;
            }

            $arr = array(
                'isdeleted'      => 1,
                'updatedtime'    => time(),
                'updateduserid'  => $opuserid,
            );
            
            // 账户改成删除模式
            $result = $this->where('id ='.$uid)->setField($arr);

            // 删除系统和角色关联
            //$usersystem_model->where('userid ='.$uid)->delete();
            //$userrole_model->where('userid ='.$uid)->delete();

            // 操作日志记录            
            writeLog('edit' , '用户表' , "操作动作=修改用户状态为删除 | 涉及用户ID={$uid}");
            return true;
      }

    }

}