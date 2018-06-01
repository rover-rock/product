<?php
	$ogid=$_GPC['ogid'];
	$og=pdo_fetch('select * from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$ogid]);
	switch ($og['status']) {
		case 1:
			$data['seeding_time']=time();
			break;
		case 2:
			$data['bedout_time']=time();
			break;
		case 3:
			$data['choose_time']=time();
			break;
		default:
			break;
	}
	$data['status']=$og['status']+1;
	
	pdo_update('ly_product_manage_ordergoods',$data,['id'=>$ogid]);
	$url=$this->createMobileUrl('index',['action'=>'detail','id'=>$og['orderid']]);
	header('location:'.$url);
	exit();