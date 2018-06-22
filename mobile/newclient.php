<?php
	if ($_W['ispost']) {
		 $data=[
		 	'name'=>$_GPC['name'],
		 	'phone'=>$_GPC['phone'],
		 	'salerid'=>$user['id']
		 ];
		 m('goods')->addClient($data);
		 $url=$this->createMobileUrl('index',['action'=>'neworder']);
		 header('location:'.$url);
	}
	include $this->template('newclient');