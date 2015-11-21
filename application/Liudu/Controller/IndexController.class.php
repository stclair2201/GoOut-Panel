<?php
namespace Liudu\Controller;
use Common\Controller\HomeBaseController;

class IndexController extends HomeBaseController{

	function _initialize() {
		parent::_initialize();
		$this -> LiuduOne_model = D("Liudu/LiuduOne");
	}
	
	function index(){
		$list = $this -> LiuduOne_model -> where(array()) -> select();
		$this->assign("list",$list);
		$this -> display();
	}
	
	function add_post(){
		if(IS_POST){
			$num = I("post.number");
			
			$qq = I("post.qq");
			$res = $this -> LiuduOne_model -> checkqq($qq);
			if($res != 2){
				$this->error($res);
			}

			$res = $this -> LiuduOne_model -> checknum($num);
			if($res !=2 ){
				$this->error($res);
			}
			if ($this->LiuduOne_model->create()) {
				$result=$this->LiuduOne_model->add();
				if ($result!==false) {
					$this->success("你的抽奖信息已经保存，等待开奖后，奖在群里通知你", U("Index/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->LiuduOne_model->getError());
			}
		}else{
			$this -> error("请求方式错误！");
		}
	}
}



?>