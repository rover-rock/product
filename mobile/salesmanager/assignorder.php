<?php
$title='分配订单';
//订单id
$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
if($_W['ispost']){
	$name=pdo_fetchcolumn('select * from ims_ly_product_manage_user where id=:id',[':id'=>$_GPC['salesman']]);
	pdo_update('ly_product_manage_order',['userid'=>$_GPC['salesman'],'assign_time'=>time(),'detailstatus'=>2],['id'=>$id]);
	include $this->template('salesmanager/assigndone');
	exit();
}

//销售人员列表
$salesman=pdo_fetchall('select * from ims_ly_product_manage_user where boss=:boss',[':boss'=>$user['id']]);
foreach ($salesman as $key => $value) {
	$sal[$key]['text']=$value['name'];
	$sal[$key]['value']=$value['id'];
}
$salesman=json_encode($sal);
//意向订单信息
$client=pdo_fetch('select * from ims_ly_product_manage_client where id=:id',[':id'=>$order['clientid']]);

//若为修改分配人员
if($order['detailstatus']==2){
	$assignedman=pdo_fetchcolumn('select name from ims_ly_product_manage_user where id=:id',array(':id'=>$order['userid']));
	
}
include $this->template('salesmanager/assignorder');

?>