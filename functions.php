<?php
	 function m($name)
	{
		static $model=array();
		if (isset($model[$name])) {
			return $model[$name];
		}
		require_once IA_ROOT."/addons/ly_product_manage/model/".$name.".php";
		$model[$name]=new $name;
		return $model[$name];
	}
	function createOrdersn($goodsid)
	{
		$ordersn=time();
		return $ordersn;
	}
	function getOrderStatus($id)
	{
		$order=pdo_fetch('select * from ims_ly_product_manage_order where id=:id',array(':id'=>$id));
		$status=$order['status'];
		$detailstatus=$order['detailstatus'];
		switch ($status) {
			case 1:
				switch ($detailstatus) {
					case 1:
						$res='未分配';
						break;
					case 2:
						$res='已分配未接受';
						break;
					case 3:
						$res='已接受未下单';
						break;
					case 4:
						$res='下单待审核';
						break;
					case 5:
						$res='审核通过';
						break;
					case 6:
						$res='审核未通过';
						break;
					default:
						# code...
						break;
				}
				break;
			case 2:
				switch ($order['pay_status']) {
					case 1:
						$res='签约未支付';
						break;
					case 2:
						$res='定金已支付';
						break;
					case 3:
						$res='定金已确认';
						break;
					case 4:
						$res='尾款已支付';
						break;
					case 5:
						$res='尾款已确认';
						break;
					default:
						# code...
						break;
				}
				break;
			case 3:
				switch ($order['detailstatus']) {
					case 1:
						$res='未开始未分配';
						break;
					case 2:
						$res='未开始已分配';
						break;
					case 3:
						$res='已播种';
						break;
					case 4:
						$res='已移苗';
						break;
					default:
						# code...
						break;
				}
				break;
			default:
				# code...
				break;
		}
		return $res;
	}
	function fetchAjaxData()
	{
		global $_W,$_GPC;
		$type=$_GPC['type'];
		
		if($type==1){
			//获取c2类数据

			$category2=pdo_fetchall('select * from ims_ly_product_manage_category2 where category1=:c1',array(':c1'=>$_GPC['c1']));
			foreach ($category2 as $key => $value) {
				$cate2[$key]['text']=$value['name'];
				$cate2[$key]['value']=$value['id'];
			}
			return json_encode($cate2);
		}
		else if($type==2){
			$goods=pdo_fetchall('select * from ims_ly_product_manage_goods where category2=:c2',array(':c2'=>$_GPC['c2']));
			foreach ($goods as $key => $value) {
				$g[$key]['text']=$value['name'];
				$g[$key]['value']=$value['id'];
			}
			return json_encode($g);
		}
		else if($type==3){
			//接受订单
			pdo_update('ly_product_manage_order',array('detailstatus'=>3),array('id'=>$_GPC['id']));
			return 'success';
		}
		elseif ($type==4) {
			//财务人员确认定金支付
			pdo_update('ly_product_manage_order',array('pay_status'=>2,'pay_time1'=>time(),'finance_user'=>$_GPC['user']),array('id'=>$_GPC['id']));
			return 'success';
		}
		elseif ($type==5) {
			//财务经理确认定金支付
			pdo_update('ly_product_manage_order',array('pay_status'=>3,'status'=>3,'detailstatus'=>1 ,'finance_manager'=>$_GPC['user']),array('id'=>$_GPC['id']));
			return 'success';
		}
		elseif ($type==6) {
			//财务人员确认尾款支付
			pdo_update('ly_product_manage_order',array('pay_status'=>4,'pay_time2'=>time()),array('id'=>$_GPC['id']));
			return 'success';
		}
		elseif ($type==7) {
			//财务经理确认尾款支付
			pdo_update('ly_product_manage_order',array('pay_status'=>5),array('id'=>$_GPC['id']));
			return 'success';
		}
		if ($type=="line") {
			//获取生产线数据
			$lines=pdo_fetchall('select * from ims_ly_product_manage_line');
			foreach ($lines as $key => $value) {
			$data[$key]['text']=$value['name'].($value['status']==0?'-未使用':'-被占用');
			$data[$key]['value']=$value['id'];
			}
			return json_encode($data);
		}
		elseif ($type=="uline") {
			//更新生产线
			pdo_update('ly_product_manage_line',['status'=>1,'time'=>time()],['id'=>$_GPC['lineid']]);
			pdo_update('ly_product_manage_ordergoods',['line'=>$_GPC['lineid']],['id'=>$_GPC['ogid']]);
			return 'success';
		}
		if ($type=="producer") {
			//更新分配的生产员
			$res=pdo_update('ly_product_manage_ordergoods',['produce_user'=>$_GPC['produce_userid']],['id'=>$_GPC['ogid']]);
			return $res;
		}
		if ($type=="confirmOg") {
			//审批补苗订单
			
			$res=pdo_update('ly_product_manage_ordergoods',['confirm_status'=>$_GPC['status']],['id'=>$_GPC['ogid']]);

			return 'success';
		}
		if ($type=="goods") {
			$data=pdo_fetchall('select * from ims_ly_product_manage_goods where category2=:c2',array(':c2'=>$_GPC['c2']));
			return json_encode($data);
		}
	}
	function setAjaxData()
	{
		global $_GPC,$_W;
		$type=$_GPC['type'];
		switch ($type) {
			case "dealOrder":
				$goods=json_decode($_GPC['goods']) ;
				$data=[
					'userid'=>$_GPC['salerid'],
					'address'=>$_GPC['address'],
					'goods'=>$goods
				];

				m('dealOrder')->createClientOrder($data);
				m('dealOrder')->removeGoodsFromCart($goods);
				break;
			case 'stopOrder':
				pdo_update('ly_product_manage_order',['status'=>-1],['id'=>$_GPC['id']]);
				break;
			case 'sendMessage':
				sendMessage(); 
				break;
			case 'confirmContract':
				pdo_update('ly_product_manage_order',array('status'=>2,'pay_status'=>1,'contact_time'=>time()),array('id'=>$_GPC['id']));
				break;
			case 'payPartDeposit':
				$deposit=pdo_fetchcolumn('select pay_log from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$_GPC['id']]);
				pdo_update('ly_product_manage_ordergoods',['pay_log'=>$deposit+intval($_GPC['money'])],['id'=>$_GPC['id']]);
				break;
			case 'payAllDeposit':
				pdo_update('ly_product_manage_ordergoods',['pay_status'=>2],['id'=>$_GPC['id']]);
				$orderid=pdo_fetchcolumn('select orderid from ims_ly_product_manage_ordergoods where id=:id',[':id'=>$_GPC['id']]);
				pdo_update('ly_product_manage_order',['pay_status'=>4],['id'=>$orderid]);
				break;
			default:
				
				break;
		}
		return "success";
	}
	function sendMessage()
	{
		//TODO:发送模板消息
	}