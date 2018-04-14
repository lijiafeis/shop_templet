<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/Users/lijiafei/Documents/fanli/fanli/public/../application/admin/view/config/config.html";i:1523180269;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<style>
	.table tr td img{height:40px;cursor:hand;}
</style>
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>配置管理--添加配置</div>
	<div class="main-content">
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">配置参数</a></li>
	  </ul>
	  <form class="form-horizontal" action="config" method="post" onsubmit="return check();" enctype="multipart/form-data" >
	  <div class="tab-content well" style="margin-top:30px;border:1px solid #dddddd;padding:10px 2%;">
		<div role="tabpanel" class="tab-pane active" id="home">
			 <div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">当天提现多少次</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="withdraw_number" value="<?php echo $info['withdraw_number']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">当天最低提现多少钱</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  id="withdraw_min_money" name="withdraw_min_money" value="<?php echo $info['withdraw_min_money']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">小V享受团队销售额百分比</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="xv_bili" value="<?php echo $info['xv_bili']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">大V享受团队销售额百分比</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="dv_bili" value="<?php echo $info['dv_bili']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">官方合伙人享受团队销售额百分比</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="gf_bili" value="<?php echo $info['gf_bili']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">联合创始人</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control" name="lh_bili" value="<?php echo $info['lh_bili']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">一级高管薪酬的多少分润</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control" name="one_bili" value="<?php echo $info['one_bili']; ?>" placeholder="">
				</div>
			</div><div class="form-group">
			<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">二级高管薪酬的多少分润</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control"  name="two_bili" value="<?php echo $info['two_bili']; ?>" placeholder="">
			</div>
		</div>
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">三级高管薪酬的多少分润</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control"  name="three_bili" value="<?php echo $info['three_bili']; ?>" placeholder="">
			</div>
		</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">秒杀商品的运费</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="yunfei" value="<?php echo $info['yunfei']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">转盘中奖几率</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="lottery_win_bili" value="<?php echo $info['lottery_win_bili']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">转盘多少积分可玩</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  name="lottery_jifen" value="<?php echo $info['lottery_jifen']; ?>" placeholder="">
				</div>
			</div>

			<input type="hidden" name="id" value="<?php echo $info['id']; ?>"/>
			<div style="clear:both"></div>
			<div style="margin-top:30px"><button type="submit" class="btn btn-success">保存配置信息</button></div>
		</div>
	  </div>
	</form>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
function check(){
	if($('#name').val()==""){layer.msg("配置名称不能为空");return false;}
	if($('#money').val()==""){layer.msg("配置价格不能为空");return false;}
	if($('#bili').val()==""){layer.msg("配置比例不能为空");return false;}
}
</script>