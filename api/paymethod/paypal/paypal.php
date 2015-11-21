<?php

class pp{
	///sandbox,live
	private $ppmode = "sandbox";
	
	private $url_live_account = "changjiang1988.hi@163.com";
	private $url_sandbox_account = "changjiang1988.hi-facilitator@163.com";
	
	private $url_live_code_url = "https://www.paypal.com/cgi-bin/webscr";
	private $url_sandbox_code_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	
	public $ordername = "";
	
	public function pp_get_code($order){
	
	$data_order_id      = $order['order_id'];
	$data_amount        = $order['price'];
	$return_url 		= U("Server/Index/paypalback",array("id"=>$order['id']),true,true);
	$data_return_url    =   $return_url;// return_url(basename(__FILE__, '.php'));
	$currency_code      = 'USD';
	$data_notify_url    = $return_url;// return_url(basename(__FILE__, '.php'));
	$cancel_return      = $return_url;//$GLOBALS['ecs']->url();
	$action_url			= "url_".$this -> ppmode."_code_url";
	$action_url			= $this->$action_url;
	$account			= 'url_'.$this -> ppmode."_account";
	$account			= $this->$account;
	
	$def_url  = '<br /><form style="text-align:center;" name="fomr" action="'.$action_url.'" method="post">' .   // 不能省略
	        "<input type='hidden' name='cmd' value='_xclick'>" .                             // 不能省略
	        "<input type='hidden' name='business' value='$account'>" .                 // 贝宝帐号
	        "<input type='hidden' name='item_name' value='$this->ordername'>" .                 // payment for
	        "<input type='hidden' name='amount' value='$data_amount'>" .                        // 订单金额
	        "<input type='hidden' name='currency_code' value='$currency_code'>" .            // 货币
	        "<input type='hidden' name='return' value='$data_return_url'>" .                    // 付款后页面
	        "<input type='hidden' name='invoice' value='$data_order_id'>" .                      // 订单号
	        "<input type='hidden' name='charset' value='utf-8'>" .                              // 字符集
	        "<input type='hidden' name='no_shipping' value='1'>" .                              // 不要求客户提供收货地址
	        "<input type='hidden' name='no_note' value=''>" .                                  // 付款说明
	        "<input type='hidden' name='notify_url' value='$data_notify_url'>" .
	        "<input type='hidden' name='rm' value='2'>" .
	        "<input type='hidden' name='cancel_return' value='$cancel_return'>" .
	        "<input type='submit' style='display:none;' value='" . "提交" . "'>" .                      // 按钮
	        "</form><br /><script type='text/javascript'>document.fomr.submit();</script>";
        return $def_url;
	}

	public function respond($post){
		$action_url			= "url_".$this -> ppmode."_code_url";
		$action_url			= $this->$action_url;
		$post['cmd'] = "_notify-validate";
		$info = $this->do_post_request($action_url, http_build_query($post));
		if(eregi("VERIFIED", $info)){
			return true;
		}else{
			return false;
		}
	}

	private function do_post_request($url, $data, $optional_headers = null){
		$params = array('http' => array(
		'method' => 'POST',
		'content' => $data
		));
		if ($optional_headers !== null) {
			$params['http']['header'] = $optional_headers;
		}
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
		if (!$fp) {
			throw new Exception("Problem with $url, $php_errormsg");
		}
		$response = @stream_get_contents($fp);
		if ($response === false) {
			throw new Exception("Problem reading data from $url, $php_errormsg");
		}
		return $response;
	}
	
}


?>