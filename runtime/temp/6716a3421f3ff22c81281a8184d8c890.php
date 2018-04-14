<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/msg.html";i:1522286340;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<style>
	.table tr td img{height:40px;cursor:hand;}
</style>
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>信息管理--添加信息</div>
	<div class="main-content">
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">信息参数</a></li>
	  </ul>
	  <form class="form-horizontal" action="msg" method="post" onsubmit="return check();" enctype="multipart/form-data" >
	  <div class="tab-content well" style="margin-top:30px;border:1px solid #dddddd;padding:10px 2%;">
		<div role="tabpanel" class="tab-pane active" id="home">
			
			 <div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">key</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  id="key" name="key" value="<?php echo $info['key']; ?>" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">模板id</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control"  id="tel_id" name="tel_id" value="<?php echo $info['tel_id']; ?>" placeholder="">
				</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $info['id']; ?>"/>
			<div style="clear:both"></div>
			<div style="margin-top:30px"><button type="submit" class="btn btn-success">保存信息信息</button></div>
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
	if($('#key').val()==""){layer.msg("key不能为空");return false;}
	if($('#tel_id').val()==""){layer.msg("模板id不能为空");return false;}
	if($('#money').val()==0){layer.msg("金额不能为空");return false;}
}
</script>