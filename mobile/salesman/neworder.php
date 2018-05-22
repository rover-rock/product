<?php
$title='新建订单';
if ($_W['ispost']) {
	$client=array(
		'name'=>$_GPC['name'],
		'phone'=>$_GPC['phone'],
		'addr'=>$_GPC['addr']
		);
	pdo_insert('ly_product_manage_client',$client);
	$order['clientid']=pdo_insertid();
	$order['ordersn']=createOrdersn(1);
	$order['status']=1;//下单阶段
	$order['type']=2;//销售员个人订单
	$order['detailstatus']=4;//下单待审核
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
	include $this->template('salesman/neworderdone');
	exit();
}
$category1=pdo_fetchall('select * from ims_ly_product_manage_category1');

foreach ($category1 as $key => $value) {
	$cate1[$key]['value']=$value['id'];
	$cate1[$key]['text']=$value['name'];
}
$category1=json_encode($cate1);

include $this->template('salesman/neworder');

function createOrdersn($goodsid)
{
	$ordersn=time();
	return $ordersn;
}
?>