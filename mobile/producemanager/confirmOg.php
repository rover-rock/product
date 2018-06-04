<?php
	$title='审批补苗订单';
	$ogid=$_GPC['id'];
	$og=pdo_fetch('select * from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$ogid]);
	$name=pdo_fetchcolumn('select name from ims_ly_product_manage_goods where id=:id',[':id'=>$og['goodsid']]);
	$produceman=pdo_fetchall('select * from ims_ly_product_manage_user where boss=:id',[':id'=>$user['id']]);
	foreach ($produceman as $key => $value) {
			$data[$key]['text']=$value['name'];
			$data[$key]['value']=$value['id'];
			}

	include $this->template('producemanager/confirmOg');
