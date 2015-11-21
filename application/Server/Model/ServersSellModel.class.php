<?php
namespace Server\Model;
use Common\Model\CommonModel;
class ServersSellModel extends CommonModel
{
	
	//用于获取时间，格式为2015-11-02 15:03:37,注意,方法不能为private
	public function mGetDate() {
		return date('Y-m-d H:i:s');
	}
	
	
	/***
	 * 获取POST里面信息处理服务销售价格
	 */
	public function doServerSell($serverId,$datas){
		$this -> doModeServerSell("A",$datas,$serverId);
		$this -> doModeServerSell("B",$datas,$serverId);
	}
	
	private function doModeServerSell($mods,$datas,$serverId){
		$ids = explode(",",$datas['sell_'.$mods]);
		$insertall = array();
		$delall = array();
		foreach($ids as $val){
			if(trim($val) == ""){
				continue;
			}
			$isnew = $_POST[$val.'_isnew'];
			$isdel = $_POST[$val.'_isdel'];
			$id = $_POST[$val.'_id'];
			$value = $_POST[$val];
			$price = $_POST[$val."_price"];
			////判断是否为删除
			if($isdel ==1){
				$delall[] = $id;
				continue;
			}
			////判断是否为删除
			if($isnew == 1 && ($value != 0 || $price != 0)){
				$insertall[] = array(
					'server_id'=>$serverId,
					'sell_mode'=>$mods,
					'value'=>$value,
					'price'=>$price
				);
				continue;
			}
			if($isnew == 0){
				$this->where("id=".$id)->save(array(
					'value'=>$value,
					'price'=>$price
				));
			}
		}
		$this -> addAll($insertall);
		$this -> delete(implode(',', $delall));
	}
}