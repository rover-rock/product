<?php
	$title='生产人员';
	$notStartedOrder=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where status=1 and produce_user=:id',array(':id'=>$user['id']));
	foreach ($notStartedOrder as $key => $value) {
		$notStartedOrder[$key]['goodsname']=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',[':id'=>$value['goodsid']]);
	}
	$startedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where status=3 and detailstatus>3 and produce_user=:id',array(':id'=>$user['id']));
	$addOrder=pdo_fetchall('select * from ims_ly_product_manage_order where hasadditional=1 and ispassed=0 and produce_user=:id',array(':id'=>$user['id']));
	$addOrderPassed=pdo_fetchall('select * from ims_ly_product_manage_order where hasadditional=1 and ispassed=1 and produce_user=:id',array(':id'=>$user['id']));
	include $this->template('produceman/index');
?>