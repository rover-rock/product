<?php
	$title='生产经理';
	$notStartedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where status=3 and detailstatus=1 ');

	$startedOrder=pdo_fetchall('select * from ims_ly_product_manage_order where status=3 and detailstatus>1 ');
	$addOrder=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where type=2');
	

	include $this->template('producemanager/index');
?> 