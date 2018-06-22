<?php
	class templatemessage{
		
		function __construct(){
			load()->func('logging');
			logging_run("进templatemessage了");
		}
						//活动结束通知评分
		function overmes($arr){
			$_tdata = array(
			'first'=>array('value'=>$arr['first'],'color'=>'#d35400'),
			'keyword1'=>array('value'=>$arr['k1'],'color'=>'#16a085'),
			'keyword2'=>array('value'=>$arr['k2'],'color'=>'#16a085'),
			'keyword3'=>array('value'=>$arr['k3'],'color'=>'#16a085'),
			'remark'=>array('value'=>$arr['rem'],'color'=>'#95a5a6')
			);
			logging_run("_tdata==>".json_encode($_tdata));
			return $this->sendTemplate_common($arr['openid'],$arr['mid1'],$arr['url'],$_tdata);
		}				
		
		//支付成功	
			
		function task_alert($content,$create_time,$trace_time,$arr){						
			$_tdata = array(			
				'first'=>array('value'=>"待办事项",'color'=>'#d35400'),			
				'keyword1'=>array('value'=>$content,'color'=>'#16a085'),			
				'keyword2'=>array('value'=>$create_time,'color'=>'#16a085'),			
				'remark'=>array('value'=>"设定时间为:".$trace_time,'color'=>'#95a5a6')		
			);			
			logging_run("_tdata==>".json_encode($_tdata));			
			return $this->sendTemplate_common($arr['openid'],$arr['mid1'],$arr['url'],$_tdata);		
		}				
			//支付失败
					
		function pay_false($money,$arr){						
			$_tdata = array(			
			'first'=>array('value'=>"您好,您的账单支付失败",'color'=>'#d35400'),			
			'keyword1'=>array('value'=>"报名费",'color'=>'#16a085'),						
			'keyword2'=>array('value'=>$money,'color'=>'#16a085'),						
			'keyword3'=>array('value'=>date('y-m-d',time()),'color'=>'#16a085'),			
			'remark'=>array('value'=>"您好，您的订单支付失败",'color'=>'#95a5a6')			
			);			
			logging_run("_tdata==>".json_encode($_tdata));			
			return $this->sendTemplate_common($arr['openid'],$arr['mid1'],$arr['url'],$_tdata);		
		}	
		
		//退款成功
		function refunds_success($arr,$money){
			$_tdata = array(			
			'first'=>array('value'=>"您好,您参加的全城来电活动已经成功退款",'color'=>'#d35400'),			
			'keyword1'=>array('value'=>"全程来电",'color'=>'#16a085'),						
			'keyword2'=>array('value'=>$money,'color'=>'#16a085'),						
			'keyword3'=>array('value'=>"已退款",'color'=>'#16a085'),			
			'remark'=>array('value'=>"全城来电会在这里一直陪着您！",'color'=>'#95a5a6')			
			);
			logging_run("_tdata==>".json_encode($_tdata));			
			return $this->sendTemplate_common($arr['openid'],$arr['mid1'],$arr['url'],$_tdata);
		}
				
		function sendTemplate_common($touser,$template_id,$url,$data){
			global $_W; 
			$weid = $_W['acid'];  
			load()->classs('weixin.account');
			$accObj= WeixinAccount::create($weid);
			$ret=$accObj->sendTplNotice($touser, $template_id, $data, $url);
			logging_run('ret==>'.json_encode($ret));
			return $ret;
		}
		
		
	}
