<?php
	/**
	 * 用户操作
	 */
	class user
	{
		
		function getSalersPop()
		{
			$salers=pdo_fetchall('select * from ims_ly_product_manage_user where role=1');
			foreach ($salers as $key => $value) {
				$data[$key]['text']=$value['name'];
				$data[$key]['value']=$value['id'];
			}
			return json_encode($data);
		}
	}