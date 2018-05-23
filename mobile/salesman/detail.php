<?php
//所有进入订单的操作逻辑入口
$title='订单详情';
$id=$_GPC['id'];
if($_W['ispost']){
	
	$order['detailstatus']=4;//下单待审核
	pdo_update('ly_product_manage_order',$order,array('id'=>$id));
	for ($i=0; $i <count($_GPC['goods']) ; $i++) { 
		$goodsdata[$i]['goodsid']=$_GPC['goods'][$i];
		$goodsdata[$i]['num']=$_GPC['nums'][$i];
		$goodsdata[$i]['orderid']=$id;
		pdo_insert('ly_product_manage_ordergoods',$goodsdata[$i]);
	}
	include $this->template('salesman/neworderdone');
	exit();
}
//订单详情

$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
$client=pdo_fetch('select * from ims_ly_product_manage_client where id=:id',array(':id'=>$order['clientid']));
$category1=pdo_fetchall('select * from ims_ly_product_manage_category1');

foreach ($category1 as $key => $value) {
	$cate1[$key]['value']=$value['id'];
	$cate1[$key]['text']=$value['name'];
}
$category1=json_encode($cate1);
//进度图片
$status=1;
//未接受订单的处理
if ($order['detailstatus']==2) {
	$title='接受订单';
	include $this->template('salesman/acceptorder');
	exit();
}
elseif($order['detailstatus']==3){
	
	include $this->template('salesman/neworder');
	exit();
}


  
$orderBaseInfo;
$orderTraceInfo;

//显示订单的基本信息和追踪信息
include $this->template('detail');
?>