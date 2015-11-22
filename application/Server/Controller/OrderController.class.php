<?php
namespace Server\Controller;
use Common\Controller\MemberbaseController;

class OrderController extends MemberbaseController{


	private $uid = 0;

	function _initialize() {
		parent::_initialize();
		$this->order_model = D("Server/Order");
		$this -> uid = sp_get_current_userid();
		$this -> serversell_model = D("Server/ServersSell");
	}
	
	/****
	 * 用户订单列表
	 * 该页面提供删除，详细，上网，续费功能 为全部完成
	 * 2015-11-03 11:49:39 详细，删除完成
	 */
	function index(){
		$olist = $this -> order_model ->alias("o")
		-> where(array("o.status != 0","o.user_id"=> $this -> uid)) /*-> field("o.*","s.name","s.sign") */-> select();
		$pays = $this -> order_model -> payMethod;
		$this -> assign("olist",$olist);
		$this -> assign("pays",$pays);
		$this -> display();
		
	}
	
	function detail(){
		$orderId = intval(I("get.id"));
		if(!$orderId){
			$this -> error("数据格式错误",U("Server/Order/index"));
		}
		$order = $this -> order_model -> where(array(
			'id' => $orderId,
			'user_id' => $this -> uid,
			'status' => array('gt',0)
		)) -> find();
		
		if(!$order){
			$this -> error("订单不存在！");
		}
		$servers_model = D("Server/Servers");
		$ss_model = D("Server/Ss");
		
		$server = $servers_model -> where(array("id"=>$order['server_id'])) -> find();
		$ss = $ss_model -> where(array('order_id'=>$order['order_id'])) -> find();
		$server_sell = $this -> serversell_model -> where('id='.$order['month']) ->find();
		
		$this -> assign("server",$server);
		$this -> assign("ss",$ss);
		$this -> assign('server_sell',$server_sell);
		$this -> assign($order);
		$this -> display();
	}
	
	/****
	 * 用户删除自己的订单
	 * 2015-11-03 11:09:49
	 */
	function delete(){
		
		$orderId = intval(I("get.id"));
		if(!$orderId){
			$this -> error("数据格式错误");
		}
		
		$order = $this -> order_model -> where(array(
			'id' => $orderId,
			'user_id' => $this -> uid
		)) -> find();
		
		if(!$order){
			$this -> error("订单不存在！");
		}else if($order['status'] == 2){
			$this -> error ("订单已经支付，不可删除！");
		}else{
			if ($this->order_model->where("id=".$orderId)->delete()!==false) {
				$this->success("删除成功！");
			} else {
				$this->error("删除失败！");
			}
		}
	}
	
	
	/****
	 * 续费
	 * 2015-11-03 12:01:08
	 * 1.判断服务是否可用
	 */
	function payagint(){
		$this -> error("续费功能还没有做");
	}
}



?>