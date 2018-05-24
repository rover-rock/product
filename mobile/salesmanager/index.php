<?php

 $title='销售经理';
 //员工类型订单
 $assignedOrder;
 //审批情况
 $verifiedOrder;
//经理个人订单
 $personalOrder;

$order['new_assigning']=pdo_fetchall('select * from ims_ly_product_manage_order where type=1 and detailstatus=1 and status=1');
$order['new_assigned']=pdo_fetchall('select * from ims_ly_product_manage_order where type=1 and status=1 and (detailstatus=2 or detailstatus=3)');
$order['new_verifing']=pdo_fetchall('select * from ims_ly_product_manage_order where (type=1 or type=2) and detailstatus=4 and status=1');
$order['new_verified']=pdo_fetchall('select * from ims_ly_product_manage_order where (type=1 or type=2) and detailstatus>4 and status=1');
$order['assigntype']=pdo_fetchall('select * from ims_ly_product_manage_order where type=1 ');
$order['personaltype']=pdo_fetchall('select * from ims_ly_product_manage_order where  type=3');

include $this->template('salesmanager/index');

?>