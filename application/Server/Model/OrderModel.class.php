<?php
namespace Server\Model;
use Common\Model\CommonModel;
class OrderModel extends CommonModel
{
	
	public  $payMethod = array(
		'aliguarantee' => array(
			'title' => '支付宝担保',
			'img' => 'aliguarantee.png',
			'account' => '',
			'key'=>'',
			'text'=>'支付宝担保交易,就像是在淘宝购物一样',
			'isdefault'=>true,
			'class_url'=> 'alipay/guarantee/guarantee.php'
		),
		'paypal' => array(
			'title' => 'Paypal支付',
			'img' => 'paypal.png',
			'account' => '',
			'key' => '',
			'text' => '全球众多用户使用的国际贸易支付工具',
			'isdefault'=> false,
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