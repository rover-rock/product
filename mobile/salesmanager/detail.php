<?php
$title='订单详情';
//订单详情
$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
//进度图片
$status=1;
$orderBaseInfo;
$orderTraceInfo;
if ($order['detailstatus']==6 && $order['status']==1) {
	echo "此订单已拒绝通过";
	exit();
}
//显示订单的基本信息和追踪信息
include $this->template('detail');
?>