<?php
	$title='申请补苗';
	$ogid=$_GPC['ogid'];
	$og=pdo_fetch('select * from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$ogid]);
	if ($_W['ispost']) {
		pdo_update('ly_product_manage_order',['hasadditional'=>1],['id'=>$og['orderid']]);
		pdo_insert('ly_product_manage_ordergoods',['type'=>2,'orderid'=>$og['orderid'],'goodsid'=>$og['goodsid'],'num'=>$_GPC['num'],'makeup_time'=>$_GPC['time'],'create_time'=>time(),'confirm_status'=>0,'status'=>1,'creator'=>$user['id']]);
		$url=$this->createMobileUrl('index');
		header("location:".$url);
		exit();
	}
	
	
	$name=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',[':id'=>$og['goodsid']]);

	include $this->template('produceman/addExtraOrder');