<?php
namespace Common\Model;
use Think\Model;
class SystemlogModel extends Model {

	public function search($page ,$arr=array()) {

		$keys =  trim($arr['keys']) ? trim($arr['keys']) : null;
	    $sta  =  $arr['status'];
	    $stime = empty($arr['stime']) ? null : strtotime($arr['stime'].' 00:00:00');
	    $etime = empty($arr['etime']) ? null : strtotime($arr['etime'].' 23:59:59');

	    $where = ' userid > 0 ';

	    // 关键字匹配字段正则
	    $keysarr = array(
	        'userid' => '/^[1-9]\d{0,}$/',                      // 满足一个1开头的素子就匹配
	        'object' => '/^([\xe4-\xe9][\x80-\xbf]{2}){1,}$/',  // 满足一个汉字就匹配
	    );

	    if(!empty($keys)) {
	      foreach ($keysarr as $k => $v) {
	          if(preg_match($v,urldecode($keys))){
	              $where .= ' and '.$k. ' like \'%'.$keys.'%\'';
	              break;  // 匹配一次后自动跳出
	          }
	      }
	    }

	    if(!empty($sta)) $where .= " and action ='{$sta}'";

	    // 时间匹配
	    if(!empty($stime))  $where .= ' and createdtime >= '.$stime;
	    if(!empty($etime))  $where .= ' and createdtime <= '.$etime;


		$line  = $page; // 每页的行数
		$count = $this->where($where)->count();
		$spage = laypage($count , $line); // 翻页函数

		$line  = $page; // 每页的行数
		$count = $this->where($where)->count();
		$spage = laypage($count , $line); // 翻页函数

		$data = $this->where($where)
			    ->limit($spage['firstRow'],$line)
			    ->order('logid desc')
			    ->select();

	    $arr = array(
	      'data'   => $data ,
	      'spage'  => $spage ,
	    );

		return $arr;
	}


	/**
	 * [查看详细日志]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function detailed($id) {
		$id = intval(I('get.id'));
		$data = $this->ALIAS('l')
			    ->FIELD('l.*,u.realname')
		        ->JOIN('__USERACC__ u on l.userid = u.id','LEFT')
		        ->WHERE('l.logid ='.$id)
		        ->find();
		return $data;
	}

}