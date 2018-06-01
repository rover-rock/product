<?php
//所有进入订单的操作逻辑入口
$title='订单详情';
$id=$_GPC['id'];
$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
$goods=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where orderid=:id',array(':id'=>$id));
foreach ($goods as $key1 => $value1) {
	$goods[$key1]['name']=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',array(":id"=>$value1['goodsid']));
		if ($value1['line']!=0) {
			$goods[$key1]['line']=pdo_fetchcolumn('select name from ims_ly_product_manage_line where id=:id',array(':id'=>$value1['line']));
		}
		else{
			$goods[$key1]['line']='';
		}
	
}
if ($_W['ispost']) {
	$title='订单分配完成';
	pdo_update('ly_product_manage_order',array('detailstatus'=>2),array('id'=>$id));
	$name=pdo_fetchcolumn('select name from ims_ly_product_manage_user where id=:id',array(':id'=>$_GPC['produceman']));
	include $this->template('producemanager/done');
	exit();
}

$client=pdo_fetch('select * from ims_ly_product_manage_client where id=:id',array(':id'=>$order['clientid']));
$category1=pdo_fetchall('select * from ims_ly_product_manage_category1');

$produceman=pdo_fetchall('select * from ims_ly_product_manage_user where boss=:id',array(':id'=>$user['id']));
foreach ($produceman as $key => $value) {
	$sal[$key]['text']=$value['name'];
	$sal[$key]['value']=$value['id'];
}
$produceman=json_encode($sal);

//生产线


//进度图片显示生产阶段
$status=5;
include $this->template('detail');