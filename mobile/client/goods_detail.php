<?php

	$goodsid=$_GPC['goodsid'];
	if ($_W['ispost']) {
		$data=['goodsid'=>$goodsid,
				'num'=>$_GPC['num'],
				'done_time'=>$_GPC['done_time'],
				'clientid'=>$client['id']
			];
		m('goods')->setCart($data);
		$url=$this->createMobileUrl('client',['tab'=>2]);
		header("location:".$url);
		exit();
	}


	$goods=m('goods')->getGoodsById($goodsid);
	
	include $this->template('client/goods_detail');