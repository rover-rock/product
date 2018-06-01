<?php
	$ogid=$_GPC['id'];
	$og=pdo_fetch('select * from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$ogid]);
	include $this->template('produceman/ogDetail');