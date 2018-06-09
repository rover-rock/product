<?php

	

	$category1=m('goods')->getCategory1();

	$tab=$_GPC['tab'];
	if (!isset($tab)) {
		$tab=0;
	}
	//购物车
	$cart=m('goods')->getCart($client['id']);
	foreach ($cart as $key => $value) {
		$cart[$key]['name']=m('goods')->getGoodsById($value['goodsid'])['name'];
	}
	//客户信息是否完善
	if ($client['name']==null || empty($client['phone']) ) {
		$completed=false;
	}
	else{
		$completed=true;
	}
	//是否首次订单
	if (empty($client['salerid']) ) {
		$salername="";
	}
	else{
		$salername = pdo_fetchcolumn('select name from ims_ly_product_manage_user where id=:id',[':id'=>$client['salerid']]);

	}

	$salers=m('user')->getSalersPop();
	include $this->template('client/index');