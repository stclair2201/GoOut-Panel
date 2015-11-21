<?php
namespace Server\Model;
use Common\Model\CommonModel;
class OrderModel extends CommonModel
{
	
	public  $payMethod = array(
		'paypal' => array(
			'img' => 'paypal.png',
			'account' => '',
			'key' => '',
			'text' => '全球众多用户使用的国际贸易支付工具',
			'isdefault'=> true,
			'class_url' => 'paypal/paypal.php'
		)
	);
	
	
	//用于获取时间，格式为2015-11-02 15:03:37,注意,方法不能为private
	public function mGetDate() {
		return date('Y-m-d H:i:s');
	}

	protected function _before_write(&$data) {
		parent::_before_write($data);
		$data['create_time'] = $this -> mGetDate();
	}
	
	public function CreateOrderId(){
		$order_sn = strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('d',rand(0,99));
		return date(Ymd).$order_sn;
	}
}