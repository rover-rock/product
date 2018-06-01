<?php
	if($_W['ispost']){
			$data=array(
			'repo'=>$_GPC['repoid'],
			'name'=>$_GPC['name'],
			);
			if($_GPC['id']==''){

				pdo_insert('ly_product_manage_line',$data);
			}
			else{
				pdo_update('ly_product_manage_line',$data,array('id'=>$_GPC['id']));
			}
			header("location:".$this->createMobileUrl('index',array('action'=>'lineManage','id'=>$_GPC['repoid'])));
			exit();
			}
		if(!is_null($_GPC['id'])){
			$data=pdo_fetch('select * from ims_ly_product_manage_line where id=:id',array(':id'=>$_GPC['id']));
			
		}
		$repoid=$_GPC['repoid'];
		include $this->template('producemanager/addLine');
?>