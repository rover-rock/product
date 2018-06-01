<?php
	$title='生产线管理';
	$lines=pdo_fetchall('select * from ims_ly_product_manage_line where repo=:repo',array(':repo'=>$_GPC['id']));
	$repoid=$_GPC['id'];
	$repo=pdo_fetchcolumn('select name from ims_ly_product_manage_repo where id=:id',array(':id'=>$repoid));
		
		include $this->template('producemanager/lineManage');
		exit();
?>