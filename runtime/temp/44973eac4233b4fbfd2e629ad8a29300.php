<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:94:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/config/img_list.html";i:1522317514;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>商城管理--商城设置</div>
	<div class="main-content">
  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">首页轮播图</a></li>
	  </ul>
		<table class="table table-striped" style="font-size:14px;">
			<th>编号</th>
			<th>缩略图</th>
			<th>url</th>
			<th>操作</th>
			<?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
			  <tr>
				<td><?php echo $vv['id']; ?></td>
				<td ><img style="width: 200px;" src="<?php echo $vv['path']; ?>"></td>
				  <th><?php echo $vv['url']; ?></th>
				<td><button class="btn btn-danger btn-sm" onclick="del(this,<?php echo $vv['id']; ?>)">删除</button>
				</td>
			  </tr>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			</table>
			<form action="img_list" method="post"  enctype="multipart/form-data">
				<input type="file" id="path" name="path"/><br/>
				<input style="'width:50%" class="form-control"  type="text" id="url" name="url" placeholder="url地址"/>
			 <div style="margin-top:30px"><button type="submit" class="btn btn-success">保存</button></div>
			 </form>
	</div>
			  
	</div>
</div>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
    function del(obj, id) {
        layer.confirm('确定要删除这条数据吗？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.ajax({
                type: 'post',
                url: "<?php echo url('del_shop_bannar'); ?>",
                dataType: 'json',
                data: {'id': id},
                success: function () {
                    layer.msg('删除成功', {icon: 1});
                    $(obj).parent().parent().remove();
                },
                error: function () {
                    layer.msg('通信通道发生错误！刷新页面重试！');
                }
            });
        }, function () {

        });
    }
</script>