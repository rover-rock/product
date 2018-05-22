<?php
if ($_W['ispost']) {
	$data=array(
		'openid'=>$_W['openid'],
		'name'=>$_GPC['name'],
		'phone'=>$_GPC['phone']
	);
	pdo_insert('ly_product_manage_user',$data);
}
$title='填写个人信息';
var_dump($_W['openid']);
include $this->template('register');

?>