<?php
namespace Server\Controller;
use Common\Controller\AdminbaseController;

class IndexadminController extends AdminbaseController{

	function _initialize() {
		parent::_initialize();
		$this->servers_model = D("Server/Servers");
		$this -> serversell_model = D("Server/ServersSell");
	}
	
	function index(){
		$count=$this->servers_model->where(array('isdel'=>'0'))->count();
		$page = $this->page($count, 20);
		$users = $this->servers_model -> where(array('isdel'=>'0'))
		->order("id DESC")
		->limit($page->firstRow . ',' . $page->listRows)
		->select();
		$this->assign("page", $page->show('Indexadmin'));
		$this->assign("users",$users);
		$this->display();
	}

	
	/**添加服务器**/
	function add(){
		$this -> assign('enmodes',$this -> servers_model -> getEnctyptionMode());
		$this -> assign("type","add_post");
		$this -> display();
	}

	function add_post(){
		if(IS_POST){
			if ($this->servers_model->create()) {
				$result=$this->servers_model->add();
				if ($result!==false) {
					$this->serversell_model ->doServerSell($result,$_POST);
					$this->success("添加成功！", U("Indexadmin/index"));
				} else {
					$this->error("添加失败！");
				}
			} else {
				$this->error($this->servers_model->getError());
			}
		}else{
			$this -> error("请求方式错误！");
		}
	}

	////禁用服务器
	function ban(){
		$id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->servers_model->where(array("id"=>$id))->setField('status','0');
    		if ($rst) {
    			$this->success("服务器禁用成功！", U("Indexadmin/index"));
    		} else {
    			$this->error('服务器禁用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
	}
	////启用服务器
	function cancelban(){
		$id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->servers_model->where(array("id"=>$id))->setField('status','1');
    		if ($rst) {
    			$this->success("服务器启用成功！", U("Indexadmin/index"));
    		} else {
    			$this->error('服务器启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
	}
	////删除服务器
	function delete(){
		$id=intval($_GET['id']);
    	if ($id) {
    		$rst = $this->servers_model->where(array("id"=>$id))->setField(array(
    			'isdel'=>'1',
    			'status'=>0
			));
    		if ($rst) {
    			$this->success("服务器启用成功！", U("Indexadmin/index"));
    		} else {
    			$this->error('服务器启用失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
	}

	///修改服务器
	function edit(){
		$id= intval(I("get.id"));
		$server=$this->servers_model->where(array("id"=>$id))->find();
		$this -> assign('enmodes',$this -> servers_model -> getEnctyptionMode());
		$this->assign($server);
		$sellList = $this -> serversell_model -> where(array("server_id"=>$id)) -> select();
		$this -> assign("sever_sells",json_encode($sellList));
		$this -> assign("type","edit_post");
		$this->display("add");
	}

	function edit_post(){
		if(IS_POST){
			if ($this->servers_model->create()) {
				$result=$this->servers_model->save();
				if ($result!==false) {
					$uid=intval($_POST['id']);
					$this-> serversell_model -> doServerSell($uid,$_POST);
					$this->success("保存成功！",U("Indexadmin/index"));
				} else {
					$this->error("保存失败！");
				}
			} else {
				$this->error($this->servers_model->getError());
			}
		}
	}
}



?>