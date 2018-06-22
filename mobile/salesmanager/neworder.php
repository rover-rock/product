<?php
$title='新建订单';
if ($_W['ispost']) {
	$order['address']=$_GPC['address'];
	$order['clientid']=$_GPC['clientid'];
	$order['ordersn']=createOrdersn(1);
	$order['status']=1;//下单阶段
	$order['type']=3;//经理个人订单
	$order['detailstatus']=4;//下单待审核
	$order['create_time']=time();
	$order['userid']=$user['id'];
	pdo_insert('ly_product_manage_order',$order);
	$orderid=pdo_insertid();
	$totalprice=0;
	for ($i=0; $i <count($_GPC['goods']) ; $i++) { 
		$goodsdata[$i]['goodsid']=$_GPC['goods'][$i];
		$goodsdata[$i]['num']=$_GPC['nums'][$i];
		$goodsdata[$i]['done_time']=$_GPC['done_time'][$i];
		$goodsdata[$i]['orderid']=$orderid;
		$goodsdata[$i]['type']=1;
		pdo_insert('ly_product_manage_ordergoods',$goodsdata[$i]);
		$price=pdo_fetchcolumn('select price from ims_ly_product_manage_goods where id=:id',[':id'=>$$_GPC['goods'][$i]]);
		$totalprice+=$price*$_GPC['nums'][$i];
	}
	pdo_update('ly_product_manage_order',['price'=>$totalprice],['id'=>$orderid]);
	include $this->template('salesmanager/neworderdone');
	exit();

}

	//个人订单
	$category1=m('goods')->getCategory1Pop();

	//获取所属客户
	$client=m('goods')->getClientPop($user['id']);
	include $this->template('salesman/neworder');


?> 