<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/Users/lijiafei/Documents/fanli/fanli/public/../application/admin/view/main/index.html";i:1522825174;}*/ ?>
﻿<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="renderer" content="webkit">
	<meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=no">
	<title>统计</title>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="/static/admin/css/base.css">
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="/static/admin/js/bootstrap.min.js"></script>
	<script src="/static/admin/js/Chart.min.js"></script>
</head>
<body>
<style>
	.view{padding:30px 0;background:#13cbae5;margin:10px 20px;color:#fff;text-align:center;}
	.view:hover{background:#133afd9;}
	.number{font-size:30px;}
</style>
<div class="well">
	<div class="col-sm-12 alert-success" style="font-size:16px;padding:10px 20px;margin-bottom:10px;">统计</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="order_number">#</em><div class="title">订单总数</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#5cb85c">
			<div class="inner">
				<em class="number" id="order_pay_number">#</em><div class="title">已支付订单</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="view" style="background:#f0ad4e">
			<div class="inner">
				<em class="number" id="order_type_number">#</em><div class="title">未发货订单</div>
			</div>
		</div>
	</div>



	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="order_type_number1">#</em><div class="title">未收货订单</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="order_type_number2">#</em><div class="title">已完成当订单</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="order_money">#</em><div class="title">订单总额</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="today_money">#</em><div class="title">今日订单总额</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="user_money">#</em><div class="title">用户余额</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="user_jifen">#</em><div class="title">用户积分总额</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="tixian_money">#</em><div class="title">提现金额</div>
			</div>
		</div>
	</div>


	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="tixian_success_money">#</em><div class="title">提现成功金额</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="tixian_fail_money">#</em><div class="title">提现失败金额</div>
			</div>
		</div>
	</div>

	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="alipay_money">#</em><div class="title">支付宝</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="view" style="background:#428bca">
			<div class="inner">
				<em class="number" id="weixin_money">#</em><div class="title">微信</div>
			</div>
		</div>
	</div>
</div>
</body>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script>
	$.ajax({
		url:"<?php echo url('index'); ?>",
		type:"post",
		dataType:"json",
		data:{
			type:1
		},
		success:function (data) {
			$("#order_number").text(data.order_number);
			$("#order_pay_number").text(data.order_pay_number)
			$("#order_type_number").text(data.order_type_number);
			$("#order_type_number1").text(data.order_type_number1);
			$("#order_type_number2").text(data.order_type_number2);
			$("#order_money").text(data.order_money);
			$("#today_money").text(data.today_money);
			setData2();
		}
	});
	function setData2(){
		$.ajax({
			url:"<?php echo url('index'); ?>",
			type:"post",
			dataType:"json",
			data:{
				type:2
			},
			success:function (data) {
				$("#user_money").text(data.user_money);
				$("#user_jifen").text(data.user_jifen)
				$("#tixian_money").text(data.tixian_money);
				$("#tixian_success_money").text(data.tixian_success_money);
				$("#tixian_fail_money").text(data.tixian_fail_money);
				setData3();
			}
		});
	}

	function setData3(){
		$.ajax({
			url:"<?php echo url('index'); ?>",
			type:"post",
			dataType:"json",
			data:{
				type:3
			},
			success:function (data) {
				$("#alipay_money").text(data.alipay_money);
				$("#weixin_money").text(data.weixin_money)
			}
		});
	}

</script>


</html>
