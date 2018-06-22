<?php

 $title='销售经理';
 //员工类型订单
 $assignedOrder;
 //审批情况
 $verifiedOrder;
//经理个人订单
 $personalOrder;

$order['new_assigning']=m('dealOrder')->getSalesmanagerOrder('new_assigning');
$order['new_assigned']=m('dealOrder')->getSalesmanagerOrder('new_assigned');
$order['new_verifing']=m('dealOrder')->getSalesmanagerOrder('new_verifing');
$order['new_verified']=m('dealOrder')->getSalesmanagerOrder('new_verified');
$order['contractOrder']=m('dealOrder')->getSalesmanagerOrder('contractOrder');
$order['producingOrder']=m('dealOrder')->getSalesmanagerOrder('producingOrder');
$order['finishedOrder']=m('dealOrder')->getSalesmanagerOrder('finishedOrder');
include $this->template('salesmanager/index');

?>