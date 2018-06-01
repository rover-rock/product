<?php
if ($user['role']==3) {
	$title='财务人员';
	$deposit_unpaid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=1 and status=2');
	$deposit_paid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=2 and status=2 ');
	$restmoney_unpaid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=3 ');
	$restmoney_paid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=4 ');
	include $this->template('finance/index');
}
elseif ($user['role']==4) {
	$title='财务经理';
	$deposit_unpaid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=2 and status=2');
	$deposit_paid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=3 and status=3 ');
	$restmoney_unpaid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=4 ');
	$restmoney_paid=pdo_fetchall('select * from ims_ly_product_manage_order where pay_status=5 ');
	include $this->template('finance/index');
}
	
?>