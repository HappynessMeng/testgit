<?php
namespace Coct\Controller;
use Think\Controller;
class SsoController extends Controller {


	/**
	 * [isLogin 校验当前登录状态]
	 * @return boolean [description]
	 */
	public function islogin() {
		if(IS_GET) {
			$session_id = I('get.sessid');
			if(empty($session_id)) $this->ajaxReturn(['code'=> 403 , 'msg'=> 'no val']);
			session(array('id' => $session_id));
			session('[start]');

			// 如果存在session 信息
			if(session('?sso_center_info')){
				$this->ajaxReturn(['code'=> 200 , 'msg' => 'success' , 'uinfo' => session('sso_center_info')]);
			}else{
				$this->ajaxReturn(['code'=> 403 , 'msg'=> 'no val']);
			}
		}

	}
	
	public function systemstatus(){
		if(IS_POST){
			$secret = I('post.secret');	
			$pms_model = M('PmsSystem');
			$where['secret'] = $secret ;
			$result = $pms_model->where($where)->find();
			if(empty($result)){
				$this->ajaxReturn(['code'=> 403 , 'msg'=> 'Notice: system key error' ]);
			} else{
				if($result['isenable'] == 1){
					$this->ajaxReturn(['code'=> 200]);
				}else{
					$this->ajaxReturn(['code'=> 403 , 'msg'=> $result['off_msg']]);
				}
			}
		}	
	}


	/**
	 * [pclogin pc 端登录]
	 * @return [type] [description]
	 */
	public function pclogin() {
		if(IS_POST){
			$u = I('post.u');
			$p = I('post.p');
			$secret = I('post.secret'); 
			$user_model = D('Useracc');
			if($result = $user_model->pcLogin($u , $p)){
				session('[regenerate]');              // 产生新的session_id
				session('[start]');                   // 启动session
				session('sso_center_info' , $result); // 保存用户信息
				$temp_sessid = session_id();

				$return_arr = ['code' => 200 , 'msg'=> 'success' , 'uinfo' => $result , 'sess_id' => $temp_sessid];
			}else{
				$return_arr = ['code' => 403 , 'msg'=> $user_model->getError() ,];
			}
			header('Content-Type:text/html;charset=utf-8');
			exit(json_encode($return_arr));
		}
		
	}


    /**
     * [p3plogin p3p登录]
     * @return [type] [description]
     */
    public function p3psave(){
    	header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

    	$status = intval(I('get.status')) === 1 ? 1 : 0 ; 
    	if($status === 1) { // 登录处理
    		$session_id = I('get.sessid');
    		$var = '?status=1&sessid='.$session_id;
    	}else{ // 退出处理
			session(null);
			session('[destroy]');
			cookie(null);
    		$var = '?status=0';
    	}

		$pms_model  = M('PmsSystem');
		//$data = $pms_model->where('isenable = 1')->select();
		$data = $pms_model->select();
		foreach($data as $k => $v) {  
			if($v['url'] != ''){
				$response .= "<script type='text/javascript' src='{$v['sso_url']}/{$var}' reload='1'></script>";                   
			}
		}

		$upms_url = C('DOMAIN_URL');
		$response .= "<script type='text/javascript' src='{$upms_url}/Coct/login/ssop3p/{$var}' reload='1'></script>";         

		echo $response;
    }
	
	
	/**
	 * [没有继承 空方法的处理]
	 * @return [type] [description]
	 */
	public function _empty() {
		exit('404');
	}


}