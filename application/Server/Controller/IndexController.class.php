<?php
namespace Server\Controller;
use Common\Controller\HomeBaseController;

class IndexController extends HomeBaseController{

	function _initialize() {
		parent::_initialize();
		$this->servers_model = D("Server/Servers");
		$this->order_model = D("Server/Order");
		$this -> serversell_model = D("Server/ServersSell");
	}
	
	////服务列表
	function index(){
		$id = intval(I("get.id"));
		if($id){
			$rst = $this->servers_model->where(array("id"=>$id,"status"=>1,"isdel"=>0)) -> find();
			if($rst){
				$otherservers = $this -> servers_model -> where(array("status"=>1,"id" => array("NEQ",$id),"isdel"=>0)) ->select();
				$this -> assign("otherservers",$otherservers);
				
				$server_sell = $this -> serversell_model -> where(array(
					'server_id' => $id
				)) ->order("sell_mode asc") -> select();
				$this -> assign("server_sell",$server_sell);
				$this -> assign($rst);
			}else{
				$this -> error("暂未发现该服务器",U("/"));
			}
		} else {
    		$this->error('数据传入失败！',U("/"));
    	}
		$this -> display();
	}
	
	/****
	 * 用户下单
	 * 2015-11-02
	 */
	function order(){
		/***
		 * 1.判断服务器，是否存在。
		 * 2.判断用户是否登录。
		 * 3.判断购买的服务与价格是否存在
		 * 4.下单，再去支付页面
		 */
		$serverid = intval(I("post.server_id"));
		if($serverid){
			$rst = $this->servers_model->where(array("id"=>$serverid,"status"=>1,"isdel"=>0)) -> find();
			if(!$rst){
				$this -> error("暂未发现该服务器",U("/"));
			}
		} else {
    		$this->error('数据传入失败！',U("/"));
    	}
		
		if(!sp_is_user_login()){
			$_SESSION['login_http_referer'] = U("Server/Index/index",array("id"=>$serverid));
			$this->error('请先登录！',U("user/login/index"));
		}
		
		if(isset($_POST["timechange"])){
			$timeMode = $_POST["timechange"];
			$sell_re = $this->serversell_model->where(array("id"=>$timeMode)) -> find();
			if(!$sell_re || $sell_re['server_id'] != $serverid){
				$this -> error("暂未发现该服务器的价格",U("Server/index/index",'id='.$serverid));
			}
			
			////处理下单业务
			$OrderModel = array(
				"order_id" => $this -> order_model -> CreateOrderId(),
				"server_id" => $serverid,
				"user_id" => sp_get_current_userid(),
				"month" => $timeMode,
				"price" => $sell_re['price'],
				"status" => 1,
				"create_time" => date("Y-m-d H:i:s")
			);
			$result = $this -> order_model ->add($OrderModel);
			if ($result!==false) {
    			$this->success("下单成功,即将跳转到支付页面！",U("Server/Index/pay",array("id"=>$result)));
			}else{
				$this->error('数据传入失败！',U("/"));
			}
			
		}else{
			$this->error('数据传入失败！',U("/"));
		}
	}
	
	/*****
	 * 进行订单支付
	 * 2015-11-02 19:04:39
	 * 订单支付页面
	 */
	public function pay(){
		$payId = intval(I("get.id"));
		if(!$payId){
			$this->error('数据传入失败！');
		}
		if(!sp_is_user_login()){
			$_SESSION['login_http_referer'] = U("Server/Index/pay",array("id"=>$payId));
			$this->error('请先登录！',U("user/login/index"));
		}
		$payInfo = $this -> order_model -> where(array("id"=> $payId)) -> find();
		if(!$payInfo){
			$this->error('订单不存在！',U("/"));
		}
		
		$current_user = sp_get_current_user();
		if($payInfo['user_id'] != $current_user['id']){
			$this->error('只能支付自己的订单',U("Server/Order/index"));
		}
		
		$server = $this->servers_model -> where(array('id'=>$payInfo['server_id'],'status'=> 1,"isdel"=>0)) -> find();
		if(!$server){
			$this -> error("服务器不存在或已停运，不可购买！",U("Server/Order/index"));
		}
		
		if($payInfo["status"] == 0){
			$this->error('订单已经被删除！',U("/"));
		}else if($payInfo["status"] == 2){
			$this->error('订单已经支付！',U("/"));
		}
		
		$server_sell = $this -> serversell_model -> where('id='.$payInfo['month']) ->find();
		
		$this -> assign('payMethod',$this -> order_model -> payMethod); ////后期需要扩展支付接口地方
		$this -> assign("server",$server);
		$this -> assign("User",$current_user);
		$this -> assign('server_sell',$server_sell);
		$this -> assign($payInfo);
		$this -> display();
	}
	
	/****
	 * 订单支付Action ,表单提交
	 * 1.加载支付接口文件
	 * 2.验证订单，服务器
	 * 3.去支付
	 ***/
	public function pay_submit(){
		$paymethod = $_POST['paymethod'];
		include PAYMETHOD_ROOT.$this -> order_model -> payMethod[$paymethod]['class_url'];
		$id = intval(I('post.table_id'));
		$order_id = $_POST['order_id'];
		$payInfo = $this -> order_model -> where(array("id"=> $id,"order_id"=>$order_id)) -> find();
		if(!$payInfo){
			$this->error('订单不存在！',U("/"));
		}
		
		$server = $this->servers_model -> where(array('id'=>$payInfo['server_id'],'status'=> 1,"isdel"=>0)) -> find();
		if(!$server){
			$this -> error("服务器不存在或已停运，不可购买！",U("Server/Order/index"));
		}
		
		if($paymethod == 'paypal'){
			$pp = new \pp();
			$pp -> ordername = "购买-".$server['name'].'代理服务';
			echo $pp-> pp_get_code($payInfo);
			die;
		}elseif($paymethod == 'aliguarantee'){
			$aliguarantee = new \aliguarantee();
			$aliguarantee -> ordername = "购买-".$server['name'].'服务';
			echo $aliguarantee -> get_code($payInfo);
			die;
		}
		
	}

	/****
	 * paypal 支付回调接口
	 * 1.验证支付情况-主动发起验证
	 * 2.处理订单
	 * ***/
	public function paypalback(){
		include PAYMETHOD_ROOT.$this -> order_model -> payMethod['paypal']['class_url'];
		$pp = new \pp();
		if($pp -> respond($_POST)){
			$payInfo = $this -> order_model-> where(array("order_id"=>$_POST['invoice'])) -> find();
			if($this -> doPay($payInfo['id'],"paypal",json_encode($_POST))){
				$this -> success("支付成功！",U("Server/Order/detail",array("id"=>$payInfo['id'])));
			}
		}else{
			$this->redirect("Server/Order/index");
		}
	}
	
	/****
	 * aliguarantee 支付回调接口
	 * 1.验证支付情况-主动发起验证
	 * 2.处理订单
	 * ***/
	public function aliguaranteebackn(){
		include PAYMETHOD_ROOT.$this -> order_model -> payMethod['aliguarantee']['class_url'];
		$aliguarantee = new \aliguarantee();
		$result = $aliguarantee -> alipay_notify($_POST);
		$payInfo = $this -> order_model-> where(array("order_id"=>$_POST['out_trade_no'])) -> find();
		if($result =="error"){
			echo "fail";
			die;
		}else if($result == 'WAIT_BUYER_PAY') {
		}else if($result == 'WAIT_SELLER_SEND_GOODS') {
			/****
			 * 调用支付宝发货接口
			 * **/
			 $aliguarantee -> send($_POST);
		}else if($result == 'WAIT_BUYER_CONFIRM_GOODS') {
			/****
			 * 等待买家确认收货
			 * ****/
		}else if($result == 'TRADE_FINISHED') {
			/****
			 * 交易成功，进行业务处理
			 * ***/
			$this -> doPay($payInfo['id'],"aliguarantee",json_encode($_POST));
		}else if($result == "TRADE_CLOSED"){
			/****
			 * 交易失败，关闭本地订单
			 * ***/
			$payInfoNew['status'] = "0";
		}
		$payInfoNew['remark'] = $_POST['trade_no'];
		$payInfoNew['guarantee_status'] = $result;
		$payInfoNew['paymode'] = "aliguarantee";
		$this -> order_model-> where(array("order_id"=>$_POST['out_trade_no'])) -> save($payInfoNew);
		echo "success";
		die;
	}
	
	/****
	 * 处理用户支付后的方式
	 * 完整订单
	 */
	private function doPay($payId,$paymode="balance",$remark=""){
		$payInfo = $this -> order_model-> where(array("id"=>$payId)) -> find();
		if($payInfo['status'] == 2){return true;}
		$payInfoNew['status'] = 2;
		$payInfoNew['pay_time'] = $this -> order_model ->mGetDate();
		$payInfoNew['paymode'] = $paymode;
		$payInfoNew['remark'] = $remark;
		$result = $this -> order_model -> where(array("id"=>$payId)) -> save($payInfoNew);
		if($result !== FALSE){
			$payInfo = $this -> order_model -> where(array("id"=>$payId)) -> find();
			$server = $this -> servers_model -> where(array("id"=>$payInfo['server_id'])) -> find();
			$server_sell = $this -> serversell_model -> where('id='.$payInfo['month']) ->find();
			$Ss_model = D("Server/Ss");
			return $Ss_model -> addService($payInfo,$server,$server_sell);
		}
		return false;
		
	}
}



?>