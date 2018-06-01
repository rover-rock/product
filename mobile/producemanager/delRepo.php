<?php 
	$title='仓库管理';
	pdo_delete('ly_product_manage_repo',array('id'=>$_GPC['id']));
	header("location:".$this->createMobileUrl('index',array('action'=>'repoManage')));
	exit();
?>