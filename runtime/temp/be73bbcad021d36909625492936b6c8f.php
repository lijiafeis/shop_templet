<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:94:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/subscribe.html";i:1522286340;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>微信管理</div>
	<div class="main-content well">
	<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">当用户首次关注时，回复内容</h5>
		<form class="form-horizontal" action="<?php echo url('subscribe'); ?>" method="post" onsubmit="return check()">
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 control-label">文本内容</label>
				<div class="col-sm-7">
					<span id="" class="help-block" style="color:red">默认回复：感谢您成为N号会员，平台全体家人将竭诚为您服务！下方可加入文字小尾巴</span>
					<?php if($info != ''): ?>
					<textarea class="form-control" rows="7" name="content" id="content"><?php echo $info[0]['content']; ?></textarea>
					<?php else: ?>
					<textarea class="form-control" rows="7" name="content" id="content"></textarea>
					<?php endif; ?>
				</div>
			</div>
			<?php if($info != ''): ?>
			 <input type="hidden" class="form-control" id="" name="id" value="<?php echo $info[0]['id']; ?>" placeholder="">

			<?php endif; ?>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				   <button type="submit" class="btn btn-default">确认保存</button>
				</div>
			 </div>
		</form>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>

<script src="/static/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/static/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
	function check(){		
		//if($('#content').val()==""){
			//layer.msg("回复内容不能为空");
			//return false;
		//}
	}

</script>