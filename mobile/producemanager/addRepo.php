<?php 
	$title='仓库管理';
	if($_W['ispost']){
			$data=array(
			'name'=>$_GPC['name'],
			
			);
			if($_GPC['id']==''){

				pdo_insert('ly_product_manage_repo',$data);
			}
			else{
				pdo_update('ly_product_manage_repo',$data,array('id'=>$_GPC['id']));
			}
			header("location:".$this->createMobileUrl('index',array('action'=>'repoManage')));
			exit();
		}
		if(!is_null($_GPC['id'])){
			$data=pdo_fetch('select * from ims_ly_product_manage_repo where id=:id',array(':id'=>$_GPC['id']));
			
		}
		include $this->template('producemanager/addRepo');
 ?>