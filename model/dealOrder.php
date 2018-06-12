<?php
	/**
	 * 业务订单操作
	 */
	class dealOrder
	{
		
		function createClientOrder($data)
		{
			$clientid=pdo_fetchcolumn('select clientid from ims_ly_product_manage_cart where id=:id',[':id'=>$data['goods'][0]]);
				$order=[
					'ordersn'=>createOrdersn(1),
					'clientid'=>$clientid,
					'type'=>1,
					'status'=>1,
					'userid'=>$data['userid'],
					'address'=>$data['address'],
					'create_time'=>time()
				];
				if ($data['userid']>0) {
					$order['detailstatus']=3;
				}
				else{
					$order['detailstatus']=1;
				}
				pdo_insert('ly_product_manage_order',$order);
				$orderid=pdo_insertid();
				foreach ($data['goods'] as $key => $value) {
					$res=pdo_fetch('select * from ims_ly_product_manage_cart where id=:id',[':id'=>$value]);
					pdo_insert('ly_product_manage_ordergoods',['orderid'=>$orderid,'type'=>1,'goodsid'=>$res['goodsid'],'num'=>$res['num'],'done_time'=>$res['done_time']]);
				}
	
		} 
		function getOrdersByClientid($clientid)
		{
			$orders=pdo_fetchall('select * from ims_ly_product_manage_order where clientid=:clientid',[':clientid'=>$clientid]);
			foreach ($orders as $key => $order) {
				$ogs=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where orderid=:orderid',[':orderid'=>$order['id']]);
				foreach ($ogs as $k => $og) {
					$ogs[$k]['goods']=m('goods')->getGoodsById($og['goodsid']);

				}
				$orders[$key]['ogs']=$ogs;
			}
			return $orders;
		}
		function createSalerPersonalOrder()
		{
			
		}
		function removeGoodsFromCart($goods)
		{
			foreach ($goods as $key => $value) {
				pdo_delete('ly_product_manage_cart',['id'=>$value]);
			}
		}
	}