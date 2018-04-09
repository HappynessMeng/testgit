<?php
namespace Coct\Controller;
use Think\Controller;
class LoginController extends Controller {
	
	private $secret;                       // 密钥
	private $sso_check_url;                // 校验地址
	private $sso_login_url;                // 登录地址
	private $sso_p3p;                      // p3p共享cookie地址
	
	
	/**
	 * [__construct 重写构造]
	 */
	public function __construct() {
		parent::__construct();
		$this->_init();
	}


	/**
	 * [_init 初始化配置信息]
	 * @return [type] [description]
	 */
	public function _init(){
		$sso_config = C('SSO_CONFIG');
		$this->secret = $sso_config['SECRET'];	
		$this->sso_check_url = $sso_config['SSO_CHECK_URL'];
		$this->sso_login_url = $sso_config['SSO_LOGIN_URL'];
		$this->sso_p3p       = $sso_config['SSO_P3P'];
		$this->system_status_check_url = $sso_config['SYSTEM_STATUS_CHECK_URL'];
	}
	

	/**
	 * [index 通用登录模块]
	 * @return [type] [description]
	 */
	public function index() {	
		session('[start]');
		// 本地登录
		if(IS_AJAX){  
			$var = ['u' => I('post.u') , 'p' => I('post.p') , 'secret' => $this->secret ];
			// 基础校验
			$data = json_decode($this->curl_request($this->sso_login_url, $var) , ture);

			if($data['code'] == 200) {
				cookie('temp_sessionid', $data['sess_id']);
				session('su' , $data['uinfo']); // 本地程序使用
				// 其他条件的校验
				$this->ajaxReturn(['code' => 200 , 'msg' => 'success']);
			}

			// 其他code 结果
			$this->ajaxReturn(['code' => 403 , 'msg' => $data['msg']]);
		}

		// 已经在本地登录则直接跳转
		if(session('?su')) $this->_goIndex();
		
		$temp_sessionid = cookie('temp_sessionid');
		// 存储临时远端的 session_id 是否失效
		if(empty($temp_sessionid)) {
			$this->display() ;     // 显示登录页面
			exit();
		} else{
			$url = $this->sso_check_url.'/sessid/'.$temp_sessionid ; // 远端校验地址
			$data = json_decode($this->curl_request($url) , true); // 校验登录状态
			if($data['code'] == 200){ // 校验成功
				session('su' , $data['uinfo']); // 本地程序使用
				$this->_goIndex();
			}else{
				cookie('temp_sessionid',null);
				$this->display();       // 显示登录界面	
				exit();
			}
			
		}
		
	}



	/**
	 * [loding 登录退出过渡页面]
	 * @return [type] [description]
	 */
	public function loding(){
		session('[start]');
		// 无session登录标识直接退出
		if(!session('?su')) {
			exit("<script>parent.location.href='".U('Login/index')."';</script>") ;	
		}

		$sta = I('get.sta');
		if($sta == 'exit'){  // 登出处理
			$url = $this->sso_p3p.'/status/0' ;
			$html = $this->curl_request($url);
			echo $html;
			session('su' , null);
			cookie('temp_sessionid' , null);
			echo "<script>parent.location.href='".U('Login/index')."';</script>" ;	
		}else{
			echo 'loding ......';
			// 登录处理
			$temp_sessionid = cookie('temp_sessionid');
			$url = $this->sso_p3p.'/status/1/sessid/'.$temp_sessionid;
			$html = $this->curl_request($url);
			echo $html;
			echo "<script>parent.location.href='".U('Index/index')."';</script>" ;	
		}
	}


	
	/**
	 * [_goIndex 父框架跳转到首页]
	 * @return [type] [description]
	 */
	private function _goIndex() {
		$url = U('Index/index');
		exit("<script>parent.location.href='{$url}';</script>");
	}


	/**
	 * [ssop3p 调用SSO P3P处理]
	 * @return [type] [description]
	 */
	public function ssop3p() {
		session('[start]');
		$status = intval(I('get.status')) === 1 ? 1 : 0 ; 
		if($status) {  // 登录状态下
			$temp_sessionid = I('get.sessid');
			cookie('temp_sessionid' , $temp_sessionid);
		}else {
			session('su' , null);     // 销毁本地
			cookie('temp_sessionid',null);
		}
	}
	

	
	/**
	 * [curl_request Curl 访问]
	 * @param  [type]  $url          [访问的url]
	 * @param  string  $post         [提交的post 数据 不填写则为GET]
	 * @param  string  $cookie       [提交的$cookies]
	 * @param  integer $returnCookie [是否返回$cookies]
	 * @return [type]                [description]
	 */
 	public function curl_request($url , $post='',$cookie='', $returnCookie = 0){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $_SERVER['SERVER_NAME']);
        if($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        }else{
            return $data;
        }

	}

	/**
	 * [没有继承 空方法的处理]
	 * @return [type] [description]
	 */
	public function _empty() {
		exit('404');
	}
}