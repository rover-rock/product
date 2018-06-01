<?php
	$title='管理生产线';
	$repos=pdo_fetchall('select * from ims_ly_product_manage_repo');

	include $this->template('producemanager/repoManage');
?>