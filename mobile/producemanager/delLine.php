<?php 
	pdo_delete('ly_product_manage_line',array('id'=>$_GPC['id']));
	header("location:".$this->createMobileUrl('index',array('action'=>'lineManage','id'=>$_GPC['repoid'])));
		$this->doWebShopmanage();

 ?>