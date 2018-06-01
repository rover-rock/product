<?php
/**
 * 生产管理系统模块微站定义
 *
 * @author 郝明轩
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
include "../addons/ly_product_manage/phpMailer/PHPMailer.php"; 
include "../addons/ly_product_manage/phpMailer/SMTP.php";
class Ly_product_manageModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		$this->route(false);
	}
	//ajax数据获取函数
	public function doMobileFetch()
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
			pdo_update('ly_product_manage_line',['status'=>1,'time'=>time()],['id'=>$_GPC['lineid']]);
			pdo_update('ly_product_manage_ordergoods',['line'=>$_GPC['lineid']],['id'=>$_GPC['ogid']]);
			return 'success';
		}
		if ($type=="producer") {
			$res=pdo_update('ly_product_manage_ordergoods',['produce_user'=>$_GPC['produce_userid']],['id'=>$_GPC['ogid']]);
			return $res;
		}
	}
	public function doWebAdd() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function route($isweb=true)
	{
		global $_GPC,$_W;
		load()->func('communication');
		$_W['openid']=5;
		if(!$isweb){
				//移动端入口
			$user=pdo_fetch('select * from ims_ly_product_manage_user where openid=:openid',array(':openid'=>$_W['openid']));

			if(!$user){

				include_once IA_ROOT .  '/addons/ly_product_manage/mobile/register.php';
				exit();
			}
			else{
				if($user['role']==0){
					echo "等待后台管理员绑定！";
					exit();
				}
				else{
					$r=$user['roledesc'];
					$action=$_GPC['action'];
					if (empty($action)) {
						$file='index';
					}
					else{
						$file=$action;
						if ($action=='personalcenter') {
							include IA_ROOT .  '/addons/ly_product_manage/mobile/'.$file.'.php';
							exit();
						}
						

					}

					include_once IA_ROOT .  '/addons/ly_product_manage/mobile/'.$r.'/'.$file.'.php';
				}
			}
			

		}
		else{
			//为后台操作
			

		}
	}
	public function doMobileMain()
	{
		include $this->template('main');
	}
	public function doMobileChoose()
	{
		include $this->template('choose');
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
	
	
	
}