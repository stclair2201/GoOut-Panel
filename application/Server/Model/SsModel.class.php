<?php
namespace Server\Model;
use Common\Model\CommonModel;
class SsModel extends CommonModel
{
	
	//用于获取时间，格式为2015-11-02 15:03:37,注意,方法不能为private
	public function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	/****
	 * 处理添加代理服务 
	 *****/
	public function addService($payInfo,$server,$server_sell){
		$data['uid'] = $payInfo['user_id'];
		$data['sspassword'] = $this -> getPassword();
		$data['port'] = $this -> getPort($server['sign'],$server);
		$data['sign'] = $server['sign'];
		$data['order_id'] = $payInfo['order_id'];
		$data['reg_date'] = $this -> mGetDate();
		$data['t'] = time();
		$data['u'] = $data['d'] = 0;
		$data['type'] = $server_sell['sell_mode'];   ///A代表流量套餐，B代表时间套餐
		$data['transfer_enable'] = $server_sell['sell_mode'] =="A"?($server_sell['value']*1024*1024):0; ///1024*1024=1MB.前台销售最小单位为1MB
		$data['overdue'] = $server_sell['sell_mode']=="B"?strtotime('+'.$server_sell['value'].' day'):time();
		return $this -> add($data);
	}
	
	/****
	 * 随机产生密码
	 */
	private function getPassword( $length = 6 ) 
	{
	    $str = substr(md5(time()), 0, $length);
	    return $str;
	}
	
	/***
	 * 获取该服务器的当前端口
	 */
	private function getPort($sign,$server){
		$result = $this -> where(array("sign"=>$sign)) -> max('port');
		if($result){
			return $result+1;
		}else{
			return $server['startport'];
		}
	}
	
	
}