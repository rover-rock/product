<?php
	
	$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
$goods=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where orderid=:id',array(':id'=>$id));
foreach ($goods as $key => $value) {
	$data=pdo_fetch('select * from ims_ly_product_manage_goods where id=:id',array(':id'=>$value['goodsid']));
	$goods[$key]['category1'] =pdo_fetchcolumn('select name from ims_ly_product_manage_category1 where id=:id',array(':id'=>$data['category1']));
	$goods[$key]['category2']=pdo_fetchcolumn('select name from ims_ly_product_manage_category2 where id=:id',array(':id'=>$data['category2']));
	$goods[$key]['name']=$data['name'];
}

//进度图片
$status=1;
if ($order['pay_status']==1||$order['pay_status']==2) {
	//定金确认
	$title="定金确认";
	$type=$user['role']+1;
	if ($_GPC['op']==1) {
		include $this->template('finance/confirmDeposit1');
	}
	elseif ($_GPC['op']==2) {

		include $this->template('finance/confirmDeposit2');
	}
}
elseif ($order['pay_status']==3||$order['pay_status']==4) {
	//尾款确认
	$title="尾款确认";
	$type=$user['role']+3;
	if ($_GPC['op']==1) {
		include $this->template('finance/confirmRest1');
	}
	elseif ($_GPC['op']==2) {
		include $this->template('finance/confirmRest2');
	}
}
else{
	include $this->template('finance/detail');
}

?>