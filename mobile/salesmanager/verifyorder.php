<?php
$op=$_GPC['op'];
$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
if($_W['ispost']){
	//确认拒绝审核
	pdo_update('ly_product_manage_order',array('refuse_type'=>1,'refuse_reason'=>$_GPC['reason'],'detailstatus'=>6,'verify_time'=>time()),array('id'=>$id));
	echo "完成";
	exit();
}
if($op=='refuse'){
	$title='拒绝订单';
	$name=pdo_fetchcolumn('select name from ims_ly_product_manage_user where id=:id',[':id'=>$order['userid']]);
	include $this->template('salesmanager/refuseorder');
	exit();
}
else if($op=='pass'){
	$title='批准订单';
	include $this->template('salesmanager/passorder');
	exit();
}

$title='审批订单';


//进度图片
$status=1;
include $this->template('salesmanager/verifyorder');
?>