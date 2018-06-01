<?php
	$title='生产经理';
	$notStartedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where status=3 and detailstatus=1 ');

	$startedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where status=3 and detailstatus>1 ');
	$addOrder=pdo_fetchall('select * from ims_ly_product_manage_order where hasadditional=1 and ispassed=0 and produce_user=:id',array(':id'=>$user['id']));
	$addOrderPassed=pdo_fetchall('select * from ims_ly_product_manage_order where hasadditional=1 and ispassed=1 and produce_user=:id',array(':id'=>$user['id']));

	include $this->template('producemanager/index');
?> 