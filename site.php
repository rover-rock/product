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
	}
	public function doWebAdd() {
		//这个操作被定义用来呈现 管理中心导航菜单
	}
	public function route($isweb=true)
	{
		global $_GPC,$_W;
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
	public function doMobileTest()
	{
		
	
	}
	function setTimer($value='')
	{
		$time=1;
		$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	//定时执行的任务
		$file = fopen("../addons/ly_product_manage/test.txt","a");
		fwrite($file, '1');
		fclose($file);
		sleep($time);
	//
		include "../addons/ly_product_manage/config.php";
		if(!$run) die('process abort');
		file_get_contents($url);
	}
	function mail()
	{
		$mail = new PHPMailer();  
		$mail->isSMTP();// 使用SMTP服务  
		$mail->SMTPDebug = 2;
$mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码  
$mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址  
$mail->SMTPAuth = true;// 是否使用身份验证  
$mail->Username = "362463215@qq.com";// 发送方的163邮箱用户名  
$mail->Password = "dlealxqqvtsebgch";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！  
$mail->SMTPSecure = "ssl";// 使用ssl协议方式 
$mail->Port = 465;// 163邮箱的ssl协议方式端口号是465/994  
$mail->From= "hao";  
$mail->Helo= "min";  
$mail->setFrom('362463215@qq.com', 'First Last');
//添加要发送的邮件地址
$mail->addAddress('rongyuzerenguojia@163.com', 'John Doe');
$mail->IsHTML(false);  
$mail->Subject = 'sub';// 邮件标题  
$mail->Body ='content' ;// 邮件正文  
if(!$mail->send()){// 发送邮件  
	echo "Message could not be sent.";  
  echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息  
}else{  
	echo 'Message has been sent.';  
}
}
}