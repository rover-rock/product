<?php
	/**
	 * 业务订单操作
	 */
	class dealOrder
	{
		
		function createClientOrder($data)
		{
			$clientid=pdo_fetchcolumn('select clientid from ims_ly_product_manage_cart where id=:id',[':id'=>$data['goods'][0]]);
			$totalprice=0;
			foreach ($data['goods'] as $key => $value) {
					$res=pdo_fetch('select * from ims_ly_product_manage_cart where id=:id',[':id'=>$value]);
					$price=pdo_fetchcolumn('select price from ims_ly_product_manage_goods where id=:id',[':id'=>$res['goodsid']]);
					$totalprice+=$price*$res['num'];
					
				}
				$order=[
					'ordersn'=>createOrdersn(1),
					'clientid'=>$clientid,
					'type'=>1,
					'status'=>1,
					'userid'=>$data['userid'],
					'address'=>$data['address'],
					'create_time'=>time(),
					'price'=>$totalprice
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

		function completeClientOrder()
		{
			# code...
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
		function getOrderById($orderid)
		{
			$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$orderid));
			return $order;
		}
		function getOrdergoodsByOrderid($orderid)
		{
			$og=pdo_fetchall('select * from ims_ly_product_manage_ordergoods where orderid=:orderid',[ ':orderid'=>$orderid]);
			foreach ($og as $key => $value) {
				$og[$key]['goodsname']=m('goods')->getGoodsById($value['goodsid'])['name'];
			}
			return $og;
		}
		function removeGoodsFromCart($goods)
		{
			foreach ($goods as $key => $value) {
				pdo_delete('ly_product_manage_cart',['id'=>$value]);
			}
		}
		function getSalerOrder($salerid,$type)
		{
			$data=[];
			switch ($type) {
				case 'newOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and status=1 and detailstatus<5 and userid=:userid',array(':userid'=>$salerid));
					break;
				case 'verifiedOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and userid=:userid and status=1 and detailstatus in (5,6) ',array(':userid'=>$salerid));
					break;
				case 'contractOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and status=2 and userid=:userid',array(':userid'=>$salerid));
					break;
				case 'producingOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and status=3 and userid=:userid',array(':userid'=>$salerid));
					break;
				case 'finishedOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type<3 and status>3 and userid=:userid',array(':userid'=>$salerid));
					break;
				default:
					
					break;
			}
			return $data;
		}
		function getSalesmanagerOrder($type)
		{
			$data=[];
			switch ($type) {
				case 'new_assigning':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type=1 and detailstatus=1 and status=1');
					break;
				case 'new_assigned':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where type=1 and status=1 and (detailstatus=2 or detailstatus=3)');
					break;
				case 'new_verifing':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where   detailstatus=4 and status=1');
					break;
				case 'new_verified':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where  detailstatus>4 and status=1');
					break;
				case 'contractOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where status=2 ');
					break;
				case 'producingOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where  status=3 ');
					break;
				case 'finishedOrder':
					$data=pdo_fetchall('select * from ims_ly_product_manage_order where  status>3 ');
					break;
				default:
					
					break;
			}
			foreach ($data as $key => $value) {
				$data[$key]['clientname']=pdo_fetchcolumn('select name from ims_ly_product_manage_client where id=:id',[':id'=>$value['clientid']]);
				$data[$key]['ordergoods']=$this->getOrderDetail($value['id']);
			}
			
			return $data;
			
		}
		function getOrderDetail($orderid)
		{
			$data=pdo_fetchall("select * from ims_ly_product_manage_ordergoods where type=1 and orderid=:id",[':id'=>$orderid]);
			foreach ($data as $key => $value) {
				$res=pdo_fetch('select * from ims_ly_product_manage_goods where id=:id',[":id"=>$value['goodsid']]);
				$data[$key]['goodsname']=$res['name'];
			}
			
			return $data;
		}
		//分配客户订单给业务员
		function assignSaler($data)
		{
			$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id ',[':id' => $data['id']]);

			pdo_update('ly_product_manage_order',['userid'=>$data['salesman'],'assign_time'=>time(),'detailstatus'=>3],['clientid'=>$order['clientid'],'userid'=>0]);
			pdo_update('ly_product_manage_client',['salerid'=>$data['salesman']],["id"=>$order['clientid']]);

		}
	}