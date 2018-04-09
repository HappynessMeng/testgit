<?php
namespace Common\Model;
use Think\Model;
class PmsNodeModel extends Model {	

	protected $insertFields = array('name','value','systemid','isenable','sort','pid');
	protected $updateFields = array('id','name','value','isenable','sort');


	// 添加系统时验证
	protected $_validate = array(
		// 验证时间  1 添加时验证 2：修改时 3：所有情况都验证
		// 验证条件  0 存在字段就验证（默认）   1 必须验证  2 值不为空的时候验证 
		array('name', 'require',  '权限名称必须填写', 1, 'regex', 3),
		array('value', 'require', '权值信息必须填写', 1, 'regex', 3),
		array('sort', 'require', '排序值必须填写', 1, 'regex', 3),

	);


	/**
	 * 权限排序
	 * @return [type] [description]
	 */
	public function sort($arr) {
		if(empty($arr))
			return false;
		foreach ($arr['id'] as $k => $v) {
			$data = array (
				'id'    => $arr['id'][$k],
				'sort'  => (int)$arr['sort'][$k],
			);
			$this->save($data);
		}
		return true;

	}


	/**
	 * [获取节点数据 排序后展示]
	 * @param  [type]  $sid  [系统编号]
	 * @param  boolean $type [是否树状展示]
	 * @return [type]        [排序后的数组]
	 */
	public function getTree($sid , $type = true)
	{
		$data = $this->where('systemid = ' . $sid)->order('sort asc')->select();
		if($type)
			return $this->tree($this->_channelList($data)); 
		return $this->_channelList($data);
	}


	/**
	 * [获取所有子栏目/ 按顺序组合 / 生成层次码 ]
	 * @param  [type]  $data      [数据数组资源]
	 * @param  integer $parent_id [起始父ID编号]
	 * @param  integer $level     [起始level编号]
	 * @param  string  $fcname    [父类ID 字段名]
	 * @param  string  $scname    [当前ID 字段名]
	 * @return [type]             [返回数组]
	 */
	private function _channelList($data, $pid=0, $level=1, $fcname = 'pid',$scname ='id')
	{
        if (empty($data))
            return array();
        $arr = array();
		foreach ($data as $v){
			if($v[$fcname] == $pid){
				$v['_level'] = $level;
				array_push($arr, $v);
				$tmp = $this->_channelList($data , $v[$scname] , $level+1 , $fcname , $scname);
				$arr = array_merge($arr, $tmp);
			}
		}
		return $arr;
	}



	/**
	 * [获得树状结构]
	 * @param  [type] $arr [拼接后的栏目]
	 * @return [type]      [返回树状结构数组]
	 */
    private function tree($arr) {
    	if(empty($arr))
    		return $arr ;
        foreach ($arr as $k => $v) {
            $str = "";
            if ($v['_level'] > 2) {
                for ($i = 1; $i < $v['_level'] - 1; $i++) {
                    $str .= "&emsp;│";
                }
            }
            if ($v['_level'] != 1) {
                if (isset($arr[$k + 1]) && $arr[$k + 1]['_level'] >= $arr[$k]['_level']) {
                    $arr[$k]['_html'] = $str . "&emsp;├&nbsp;─" ;
                } else {
                    $arr[$k]['_html'] = $str . "&emsp;└&nbsp;─" ;
                }
            } else {
                $arr[$k]['_html'] = '';
            }
        }
        return $arr;
    }


    /**
     * [添加节点后操作]
     * @return [type] [description]
     */
    public function _after_insert($data , $option) {
        writeLog('ins' , '节点表' , "操作动作=新增节点 | 关联系统ID={$data['systemid']} | 节点ID={$data['id']} | 父节点ID={$data['pid']} | 节点名称={$data['name']} | 权值={$data['value']}");
    }


    /**
     * [修改节点后操作]
     * @return [type] [description]
     */
    public function _after_update($data , $option) {
        writeLog('edit' , '节点表' , "操作动作=修改节点信息 | | 节点ID={$data['id']} | 父节点ID={$data['pid']} | 节点名称={$data['name']} | 权值={$data['value']}");
    }


    /**
     * [删除节点后操作]
     * @return [type] [description]
     */
    public function _after_delete($data , $option) {
		writeLog('delete' , '节点表' , "操作动作=删除节点 | 节点ID={$data['id']}");
    }

	
}