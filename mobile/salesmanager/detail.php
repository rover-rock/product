<?php
$title='订单详情';
//订单详情
$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
//进度图片
$status=1;
$orderBaseInfo;
$orderTraceInfo;
if ($order['detailstatus']==5) {
	//进入签约页
	pdo_update('ly_product_manage_order',array('status'=>2,'pay_status'=>1,'contact_time'=>time()),array('id'=>$id));
	include $this->template('salesmanager/confirmContract');
	exit();
}
elseif ($order['detailstatus']==6) {
	echo "此订单已拒绝通过";
	exit();
}
//显示订单的基本信息和追踪信息
include $this->template('detail');
?>