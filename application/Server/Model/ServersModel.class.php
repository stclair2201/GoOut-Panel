<?php
namespace Server\Model;
use Common\Model\CommonModel;
class ServersModel extends CommonModel
{
	private $enctyption_mode = array('table','rc4-md5','salsa20','chacha20','aes-256-cfb','aes-128-cfb','aes-192-cfb','rc4');
	private $time_mode = array("m1","m3","m6","m12");
	
	protected $_validate = array(
		array('name', 'require', '服务器名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH  ),
		array('domain', 'require', '转发域名不能为空！', 1, 'regex', CommonModel:: MODEL_BOTH ),
		array('ip', 'require', 'IP地址不能为空！', 0, 'regex', CommonModel:: MODEL_BOTH  ),
		array('enctyption_mode', 'require', '加密方式不能为空！', 0, 'regex', CommonModel:: MODEL_BOTH  ),
		array('sign','','唯一标识已经存在',0,'unique',CommonModel:: MODEL_BOTH ) // 验证user_login字段是否唯一
	);


	public function getEnctyptionMode(){
		return $this -> enctyption_mode;
	}
	public function getTimeMode(){
		return $this -> time_mode;
	}

	
	//用于获取时间，格式为2012-02-03 12:12:12,注意,方法不能为private
	public function mGetDate() {
		return date('Y-m-d H:i:s');
	}

	protected function _before_write(&$data) {
		parent::_before_write($data);
		$data['create_time'] = $this -> mGetDate();
	}
}