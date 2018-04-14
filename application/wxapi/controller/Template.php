<?php

namespace app\wxapi\controller;

use think\Controller;

class Template extends Controller{
	function __construct(){
		parent::__construct();
	}
    public function sendTemplateTijian($openid,$url,$tel){
        $tem_data = '{
		   "touser":"'.$openid.'",
		   "template_id":"vGoDGSBhBpqeg5_7i7Ut1fA4PeAXhvyMlHIy_RmThuA",
		   "url":"'.$url.'",
		   "data":{
				   "first": {
					   "value":"你的体检报告生成成功",
					   "color":""
				   },
				   "keyword1":{
					   "value":"'.$tel.'",
					   "color":""
				   },
				   "keyword2": {
					   "value":"健康专家",
					   "color":""
				   },
				   "keyword3": {
					   "value":"'.date('Y-m-d H:i:s',time()).'",
					   "color":""
				   },
				   "remark":{
					   "value":"点击可查看体检报告详情",
					   "color":""
				   }
		   }
	   }';
        $weixin = controller("wxapi/Weixin");
        $weixin ->send_template($openid,$tem_data);
    }
}