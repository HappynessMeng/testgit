<?php
namespace Coct\Controller;
use \Coct\Controller\CoBaseController;
class UseraccController extends CoBaseController {

	/**
	 * [系统用户展示]
	 * @return [type] [description]
	 */
	public function index() {
		$user_model = D('Useracc');
		$system_model = M('PmsSystem');
		$systemid = intval(I('get.systemid'));
		$sys = $system_model->find($systemid);
		if(empty($sys)) 
        	$this->alert('糟糕，访问的资源未找到 !');
		$sys  = $system_model->find($systemid);
		$loginuserlevel = session('su.sulevel');
		$data = $user_model->search(15 , $systemid , $loginuserlevel , I('get.'));
		$this->assign(array(
			'data'  => $data['data'],
			'spage' => $data['spage'],
			'sys'   => $sys,
		));
		$this->display();
	}


	/**
	 * [所有用户管理]
	 * @return [type] [description]
	 */
	public function commons() {
		$user_model = D('Useracc');
		$loginuserlevel = session('su.sulevel');
		$data = $user_model->search(15 , '' , $loginuserlevel , I('get.'));
		$this->assign(array(
			'data'  => $data['data'],
			'spage' => $data['spage'],
		));
		$this->display();
	}


	/**
	 * [超级系统分配]
	 * @return [type] [description]
	 */
	public function allot() {
		$system_model     = D('PmsSystem');
		$userrole_model   = D('UserRole');
		$usersystem_model = D('UserSystem');
		$userid = I('request.userid');

		// 更新数据
		if(IS_POST) {
			$sysarr = I('post.selsystem');
			$newdata = array();
			if(!empty($sysarr)) {
				foreach($sysarr as $k => $v) {
					$newdata[$k]['systemid'] = $v;
					$newdata[$k]['roles']    = implode(',',I('post.selrole_'.$v));
				}
			}
			$createduserid = session('su.id');

			$sta = $usersystem_model->userInit($userid , $newdata  , $createduserid);
			$sta !== false ? sendJson(200 ,'ok') : sendJson(403 ,'error');
		}

		$uallrole = $userrole_model->getUallRole($userid);
		$sysdata  = $system_model->getSystemRole();
		$this->assign(array(
			'uallrole'   => $uallrole ,
			'sysdata'    => $sysdata ,
		));
		$this->display();
	}

	
	/**
	 * [用户中心]
	 * @return [type] [description]
	 */
	public function ucenter() {
		$userid = session('su.id') ;
		$model  = D('Useracc');
		$uinfo  = $model->find($userid);
		if(IS_AJAX) {
			if($model->create(I('post.'),2)) {
				$model->updateduserid = session('su.id'); 
				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {      
                    $sta = $model->save();
                    flock($fp,LOCK_UN);      
                }
                fclose($fp);
                $sta ? sendJson(200 , '个人资料更新成功') :  sendJson(403 , '资料未更新');
			} 
			sendJson(403 , $model->getError());
		}

		$this->assign('uinfo',$uinfo);
		$this->display();
	}



	/**
	 * [添加用户]
	 * @return [type] [description]
	 */
	public function ins() {
		$user_model   = D('Useracc');
		$system_model = M('PmsSystem');
		$role_model   = D('PmsRole');
		if(IS_AJAX) {
			if($user_model->create(I('post.'),1)) {

				$user_model->isenable = $user_model->isenable ? 1 : 0 ;  //用户状态
				$user_model->ismulti  = $user_model->ismulti  ? 1 : 0 ;  //是否允许多人登录
				$user_model->createduserid = session('su.id');    

				$uroles = I('post.roleids',null);
				if(!empty($uroles)) $uroles = implode(',',$uroles);

				$systemid = intval(I('post.systemid'));
				S('usysid' , $systemid , 10);
				S('uroles' , $uroles , 10);

				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {  
                	$issu = $user_model->isExistsSuUser($systemid); 
   					$user_model->sulevel = $issu ? 3 : 2 ;
					$sta = $user_model->add();
                    flock($fp,LOCK_UN);      
                }
                fclose($fp);

                $sta !== false ? sendJson(200 , '用户添加成功') : sendJson(403 , '异常的数据处理错误');
			
			} 
			sendJson(403 , $user_model->getError());
		}

		$systemid = intval(I('get.systemid'));
		$sys = $system_model->find($systemid);
		
		$issu = $user_model->isExistsSuUser($systemid); // 是否添加超级管理员

		if(empty($sys)) 
       		 $this->alert('糟糕，访问的资源未找到 !');
		$roledata = $role_model->getRole($systemid , 0); // 获取相关系统的角色数据

		$this->assign(array(
			'sys'       => $sys ,
			'roledata'  => $roledata ,
			'issu'      => $issu ,
		));

		$this->display();
	}


	/**
	 * [修改用户]
	 * @return [type] [description]
	 */
	public function edit() {
		$user_model = D('Useracc');
		$system_model = M('PmsSystem');
		$role_model = D('PmsRole');
		$userrole_model = D('UserRole');
		
		if(IS_AJAX) {
			if($user_model->create(I('post.'),2)) {
				$user_model->isenable = $user_model->isenable ? 1 : 0 ;  //用户状态
				$user_model->ismulti  = $user_model->ismulti  ? 1 : 0 ;  //是否允许多人登录
				$user_model->updateduserid = session('su.id');           // (待完善)修改此用户人ID
				$uroles = I('post.roleids',null);
				
				if(!empty($uroles)) $uroles = implode(',',$uroles);

				S('usysid' , I('post.systemid',null) , 10);
				S('uroles' , $uroles , 10);
				S('sulevel' , intval(I('post.sulevel')) ,10);
				$fp = fopen("dblock", "r"); 
                if(flock($fp,LOCK_EX)) {      
                    $sta = $user_model->save();
                    flock($fp,LOCK_UN);      
                }
                fclose($fp);
                $sta !== false ? sendJson(200 , '用户修改成功') :  sendJson(403 , '异常的数据处理错误');
			} 
			sendJson(403 , $user_model->getError());
		}


		$systemid = intval(I('get.systemid'));
		$userid   = intval(I('get.id'));
		$sys      = $system_model->find($systemid);    // 查询系统基本信息
		$uinfo    = $user_model->find($userid);        // 查询用户基本信息
	
		if(empty($sys) || empty($uinfo) || $uinfo['isdeleted'] == 1) 
        	$this->alert('糟糕，访问的资源未找到 !');
				
		$roledata = $role_model->getRole($systemid , 0); // 获取相关系统的角色数据
		$ckrole = $userrole_model->getUserRole($userid,$systemid); 
		$ckrole = empty($ckrole) ? array() : explode(',', $ckrole[0]['roleid']);

		$this->assign(array(
			'sys'       => $sys ,
			'roledata'  => $roledata ,
			'ckrole'    => $ckrole ,
			'uinfo'     => $uinfo ,
		));

		$this->display();
	}


	/**
	 * [删除用户]
	 * @return [type] [description]
	 */
	public function del() {
		$id = intval(I('get.id'));
		$opuserid = session('su.id');
		$model = D('Useracc');
		$model->isDel($id ,$opuserid) ? sendJson(200 , '用户删除成功!') : sendJson(403 , $model->getError());
	}


	/**
	 * [Flash 头像裁剪上传]
	 * @return [type] [description]
	 */
	public function avaupload() {
		if(I('get.act') == 'up') {
			$userid = intval(I('get.usreid'));
			$path = C('DEFAULT_PURL');
			$start  = uniqid();
	       	$prefix = date('YmdHis').'_'.$start;
	       	$new_picurl = $path.$prefix.'_162.jpg';
			$sta = file_put_contents($new_picurl,base64_decode($_POST['pic1']));

			if(empty($userid)) {
				$model = M('Useracc');
				$udata = $model->where('id ='.$userid)->find();
				@unlink('/'.$udata['picture']);
			}

			if($sta) {
					$status = array(
						'status'   => '200|'.$new_picurl ,
					);
			} else {
					$status = array(
						'status'   => '403|error.jpg' ,
					);
			}
			echo json_encode($status);
	    }
		
		$this->display('Public/ava');		
	}


	
	/**
	 * [cityconfig 分配城市]
	 * @return [type] [description]
	 */
	public function cityconfig(){

		$config_model = D('UserSystemCity');

		// 分配城市
		if(IS_AJAX){
			$citys  =   implode(',',I('post.cityid')) ? implode(',',I('post.cityid')) : '' ;
			$userid =   intval(I('post.userid'));
			$systemid = intval(I('post.systemid'));			
			if(empty($userid) || empty($systemid)) 
				sendJson(403 , '网络延时，请关闭窗口后重新打开!');
			if($config_model->ins($systemid , $userid ,$citys)) {
				sendJson(200 , 'success');
			}else{
				sendJson(400 , $config_model->getError());
			}
		}

		$userid   = I('get.id');
		$systemid = I('get.systemid');
		if($systemid != 1000 && $systemid!= 1001)  die('error');

		$city_model = M('city','crm_','DB_CRM'); 
		$city_data = $city_model->select();

		$data =  $config_model->getUserSystemCityInfo($systemid , $userid);

		$citys = explode(',' , $data['citys']);

		$this->assign(array(
			'city_data' => $city_data , 
			'citys'     => $citys ,
		));
		$this->display('cityconfig');

	}

}