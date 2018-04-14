<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/config.html";i:1522317599;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<style>.btn-default{background:#44b549;color:#fff;}</style>
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>微信参数设置</div>
	<div class="main-content well"  style="margin-top:30px;border:1px solid #dddddd;padding:10px 2%;">
		<form class="form-horizontal" action="<?php echo url('config'); ?>" method="post" enctype="multipart/form-data" >
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">微信名称</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="wxname" value="<?php echo $info['wxname']; ?>" id="" placeholder="">
			</div>
			
		 </div>
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">微信号</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="wxid" value="<?php echo $info['wxid']; ?>" id="" placeholder="">
			</div>
			
		 </div>
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">微信APPID</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="appid" value="<?php echo $info['appid']; ?>" id="" placeholder="">
			</div>
			
		 </div>
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">微信APPSECRET</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="appsecret" value="<?php echo $info['appsecret']; ?>" id="" placeholder="">
			</div>
			
		 </div>
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">微信商户平台ID</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="machid" value="<?php echo $info['machid']; ?>" id="" placeholder="">
			</div>
			
		 </div>
		 <div class="form-group">
			<label class="col-sm-2   col-lg-2 control-label" for="exampleInputEmail1">商户平台密钥mkey</label>
			<div class="col-sm-6 col-lg-4">
				<input type="text" class="form-control" name="mkey" value="<?php echo $info['mkey']; ?>" id="" placeholder="">
			</div>
			
		 </div>
			<input type="hidden" name="app_id" value="<?php echo $info['id']; ?>"/>
		<div style="clear:both"></div>
		  <button type="submit" class="btn btn-default">保存</button>
		</form>
	</div>
</div>
<style>
.ccca{font-size: 18px;color:#00a2e8}
</style>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
