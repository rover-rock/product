<?php
	$title='生产人员';
	$notStartedOrder=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where status=1 and produce_user=:id',array(':id'=>$user['id']));
	foreach ($notStartedOrder as $key => $value) {
		$notStartedOrder[$key]['goodsname']=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',[':id'=>$value['goodsid']]);
	}
	$startedOrder=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where status>1 and produce_user=:id',array(':id'=>$user['id']));
	foreach ($startedOrder as $key => $value) {
		$startedOrder[$key]['goodsname']=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',[':id'=>$value['goodsid']]);
	}
	//自己所提交的补苗订单
	$addOrder=pdo_fetchall('select * from ims_ly_product_manage_ordergoods  where creator=:id',array(':id'=>$user['id']));
	
	include $this->template('produceman/index');
?>