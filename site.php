<?php
/**
 * 生产管理系统模块微站定义
 *
 * @author 郝明轩
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
include "../addons/ly_product_manage/mobile/tempmessage.php"; 
require_once '../addons/ly_product_manage/functions.php';


class Ly_product_manageModuleSite extends WeModuleSite {

	public function doMobileIndex() {
		$this->route(false);
	}
	public function doMobileClient()
	{
		global $_GPC,$_W;
		$action=$_GPC['action'];
		$_W['openid']=10;
		$client=pdo_fetch('select * from ims_ly_product_manage_client where openid=:openid',[':openid'=>$_W['openid']]);
		if (!$client) {
			pdo_insert('ly_product_manage_client',['openid'=>$_W['openid']]);
			$client=pdo_fetch('select * from ims_ly_product_manage_client where openid=:openid',[':openid'=>$_W['openid']]);
		}
		if (empty($action)) {
						$file='index';
			}
			else{
				$file=$action;
			}
		include_once IA_ROOT .  '/addons/ly_product_manage/mobile/client/'.$file.'.php';
		exit();
	}
	//ajax数据获取函数
	public function doMobileFetch()
	{
		global $_GPC;
		if ($_GPC['action']=="setdata") {
			return setAjaxData();
		}
		return fetchAjaxData();
	}
	public function doWebAdd() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function route($isweb=true)
	{
		global $_GPC,$_W;
		load()->func('communication');
		$_W['openid']=1;
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
	
	
	public function doMobileSendmessage()
	{
		global $_GPC,$_W;
		$tplmes=new templatemessage();
		$arr['openid']='ohKkv0rx-rLmJZ5u9_HDIKLt6Fbc';
		$arr['mid1']="jaXxAstSFsvLvXXI5uRZAn4dgeB-YeMNNGCW_3v7Puc";
		$arr['url']="http://www.baidu.com";
		//$tplmes->pay_sucess($_GPC['content'],$_GPC['create_time'],$arr);
		$tplmes->task_alert('客户邮箱更改','2018-6-6',$arr);
		
	}
	
}