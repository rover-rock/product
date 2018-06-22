<?php
	/**
	 * 商品操作
	 */
	class goods
	{
		function getCategory1(){
			$data=pdo_fetchall('select * from ims_ly_product_manage_category1');
			return $data;
		}
		function getCategory2($id)
		{
			$data=pdo_fetchall('select * from ims_ly_product_manage_category2 where id=:id',[':id'=>$id]);
			return $data;
		}
		function getGoodsByC2($id)
		{
			$data=pdo_fetchall('select * from ims_ly_product_manage_goods where category2=:id',[':id'=>$id]);
			return $data;
		}
		function getGoodsById($id)
		{
			$data=pdo_fetch('select * from ims_ly_product_manage_goods where id=:id',[':id'=>$id]);
			return $data;
		}
		function setCart($data){
		// ['clientid'=>$clientid,'goodsid'=>$goodsid,'num'=>$num,'done_time'=>$done_time];
			pdo_insert('ly_product_manage_cart',$data);
			return pdo_insertid();
		}
		function getCart($clientid)
		{
			$data=pdo_fetchall('select * from ims_ly_product_manage_cart where clientid=:id',[':id'=>$clientid]);
			return $data;
		}
		function getCategory1Pop()
		{
			$category1=pdo_fetchall('select * from ims_ly_product_manage_category1');

			foreach ($category1 as $key => $value) {
			$cate1[$key]['value']=$value['id'];
			$cate1[$key]['text']=$value['name'];
			}
			$category1=json_encode($cate1);
			return $category1;
		}
		function getClientPop($salerid)
		{
			$client=pdo_fetchall("select * from ims_ly_product_manage_client where salerid=:id",[':id'=>$salerid]);

			foreach ($client as $key => $value) {
			$cate1[$key]['value']=$value['id'];
			$cate1[$key]['text']=$value['name'];
			}
			$client=json_encode($cate1);
			return $client;
		}
		function addClient($data)
		{
			pdo_insert('ly_product_manage_client',$data);
		}
	}