<?php
$title='新建订单';
if ($_W['ispost']) {
	if($_GPC['type']==3){
		$client=array(
			'name'=>$_GPC['name'],
			'phone'=>$_GPC['phone'],
			'addr'=>$_GPC['addr']
		);
		pdo_insert('ly_product_manage_client',$client);
		$order['clientid']=pdo_insertid();
		$order['ordersn']=createOrdersn(1);
	$order['status']=2;//下单阶段
	$order['type']=3;//经理个人订单
	$order['create_time']=time();
	$order['userid']=$user['id'];
	pdo_insert('ly_product_manage_order',$order);
	$orderid=pdo_insertid();
	for ($i=0; $i <count($_GPC['goods']) ; $i++) { 
		$goodsdata[$i]['goodsid']=$_GPC['goods'][$i];
		$goodsdata[$i]['num']=$_GPC['nums'][$i];
		$goodsdata[$i]['orderid']=$orderid;
		pdo_insert('ly_product_manage_ordergoods',$goodsdata[$i]);
	}
	include $this->template('salesmanager/neworderdone');
	exit();
}
else if($_GPC['type']==1){
	$client=array(
		'name'=>$_GPC['name'],
		'phone'=>$_GPC['phone'],
		'addr'=>$_GPC['addr']
	);
	pdo_insert('ly_product_manage_client',$client);
	$order['clientid']=pdo_insertid();
	$order['ordersn']=createOrdersn(1);
		$order['status']=1;//下单阶段
	$order['detailstatus']=1;//意向状态未分配
	$order['type']=1;//分配订单
	$order['create_time']=time();
	$order['managerid']=$user['id'];
	pdo_insert('ly_product_manage_order',$order);
	include $this->template('salesmanager/neworderdone');
	exit();
}

}
if($_GPC['type']==3){
	//个人订单
	$category1=pdo_fetchall('select * from ims_ly_product_manage_category1');

	foreach ($category1 as $key => $value) {
		$cate1[$key]['value']=$value['id'];
		$cate1[$key]['text']=$value['name'];
	}
	$category1=json_encode($cate1);

	include $this->template('salesman/neworder');

}
else if($_GPC['type']==1){
	//意向订单
	include $this->template('salesmanager/newmeantorder');
}



function createOrdersn($goodsid)
{
	$ordersn=time();
	return $ordersn;
}
?>