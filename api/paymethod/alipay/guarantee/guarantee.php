<?php
class aliguarantee{
	public $ordername = "";
	public $cacert = "";
	
	function __construct() {
		$this -> cacert = getcwd().'\\api\\paymethod\\alipay\\guarantee\\cacert.pem';
   }
	
	public $alipay_config = array(
		'partner' => '2088602932132140',
		'seller_email' => '15321315407',
		'key' => 'abd1aqakclmji7ytgggrgtddss4d4zv8t',
		'sign_type' => "MD5",
		'input_charset' => "utf-8",
		'transport' => 'http'
	);
	//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
	
	
	public function get_code($order){
		require_once("lib/alipay_submit.class.php");
		$notify_url = U("Server/Index/aliguaranteebackn",array("id"=>$order['id']),true,true);
		$return_url = U("Server/Order/detail",array("id"=>$order['id']),true,true);
        $payment_type = "1";
        $out_trade_no = $order['order_id'];
        $subject = $this -> ordername;
        $price = $order['price'];
        $quantity = "1";
        $logistics_fee = "0.00";
        $logistics_type = "EXPRESS";
        $logistics_payment = "SELLER_PAY";
        $body = $this -> ordername;
        $show_url = "";
        $receive_name = "";
        $receive_address = "";
        $receive_zip = "";
        $receive_phone = "";
        $receive_mobile = "";
		$parameter = array(
				"service" => "create_partner_trade_by_buyer",
				"partner" => trim($this -> alipay_config['partner']),
				"seller_email" => trim($this -> alipay_config['seller_email']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"price"	=> $price,
				"quantity"	=> $quantity,
				"logistics_fee"	=> $logistics_fee,
				"logistics_type"	=> $logistics_type,
				"logistics_payment"	=> $logistics_payment,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"receive_name"	=> $receive_name,
				"receive_address"	=> $receive_address,
				"receive_zip"	=> $receive_zip,
				"receive_phone"	=> $receive_phone,
				"receive_mobile"	=> $receive_mobile,
				"_input_charset"	=> trim(strtolower($this -> alipay_config['input_charset']))
		);
		$this -> alipay_config['cacert'] = $this -> cacert;
		$alipaySubmit = new AlipaySubmit($this -> alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		return $html_text;
	}
	
	/***
	 * 验证通知接口
	 */
	public function alipay_notify($post){
		require_once("lib/alipay_notify.class.php");
		$this -> alipay_config['cacert'] = $this -> cacert;
		$alipayNotify = new AlipayNotify($this -> alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
		if($verify_result){
			return $post['trade_status'];
		}else{
			return "error";
		}
		
	}
	
	/****
	 * 发货接口
	 * sign,sign_type
	 */
	public function send($post){
		require_once("lib/alipay_submit.class.php");
		$parameter = array(
				"service" => "send_goods_confirm_by_platform",
				"partner" => trim($this -> alipay_config['partner']),
				"_input_charset"	=> trim(strtolower($this -> alipay_config['input_charset'])),
				"trade_no" => $post['trade_no'],
				"logistics_name"=> "无需物流",
				"invoice_no" => "",
				"transport_type" => "DIRECT",
				"create_transport_type" =>  "DIRECT",
				"seller_ip"=>""
		);
		$this -> alipay_config['cacert'] = $this -> cacert;
		$alipaySubmit = new AlipaySubmit($this -> alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($parameter);
		return $html_text;
	}

	
}

?>