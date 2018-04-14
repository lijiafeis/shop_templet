<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/shop/category.html";i:1522286340;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<link href="/static/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<style>
.btn-default{background:#44b549;color:#fff;}
.form-group1:hover{background:#fff;}
 .table tr td img{height:30px;cursor:hand;}
.code:hover{cursor:hand;}
</style>
<div class="container-fluid main">
	<div class="main-top"><span  aria-hidden="true"></span>商品分类</div>
		<div class="main-content">
		  <ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">商品分类</a></li>
		  </ul>

		  <div class="tab-content well" style="margin-top:20px;">
			<a href="<?php echo url('add_category'); ?>"><button class="btn btn-warning" style="margin:10px;">添加新分类</button></a>
			<div role="tabpanel" class="tab-pane active" id="home">

				<table class="table table-striped" style="font-size:14px;">
				<th>分类名称</th>
				<th>分类层级</th>
				<th>缩略图</th>
				<th>排序</th>
				<th>操作</th>
				<?php if(is_array($pid_info) || $pid_info instanceof \think\Collection || $pid_info instanceof \think\Paginator): $kk = 0; $__LIST__ = $pid_info;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?>
				  <tr>
					<td><?php echo $vv['cate_name']; ?></td>
					<td style="font-size:12px;"><?php if($vv['pid'] == '0'): ?>顶级分类<?php else: ?>二级分类<?php endif; ?></td>
					<td ><img src="<?php echo $vv['pic_url']; ?>"></td>
					<td><?php echo $vv['code']; ?></td>
					<td>
					<a href="<?php echo url('add_category'); ?>?cate_id=<?php echo $vv['cate_id']; ?>"><button class="btn btn-default btn-sm">修改</button></a>
					<button class="btn btn-danger btn-sm" onclick="del(this,'<?php echo $vv['cate_id']; ?>')">删除</button>
					</td>
				  </tr>
				  <?php if(is_array($vv['children']) || $vv['children'] instanceof \think\Collection || $vv['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vv['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
					  <tr style="color:#999;">
						<td style="font-size:12px;">　———<?php echo $v['cate_name']; ?></td>
						<td style="font-size:12px;"><?php if($v['pid'] == '0'): ?>二级分类<?php endif; ?></td>
						<td ><img src="<?php echo $v['pic_url']; ?>"></td>
						<td><?php echo $v['code']; ?></td>
						<td>
						<a href="<?php echo url('add_category'); ?>?cate_id=<?php echo $v['cate_id']; ?>"><button class="btn btn-default btn-sm">修改</button></a>
						<button class="btn btn-danger btn-sm" onclick="del(this,'<?php echo $v['cate_id']; ?>')">删除</button>
						</td>
					  </tr>
					<?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "$empty" ;endif; ?>
				</table>
				  <div style="clear:both"></div>
			</div>
		  </div>
		</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
function del(obj,id){
    layer.confirm('确定删除当前的记录？', {
        btn: ['是，确认','否，再看看'] //按钮
    }, function(){
        var index = layer.load(2,{
            shade:[0.6,"#000"]
        });
        $.ajax({
            type:'post',
            url:"<?php echo url('del_shop_categrey'); ?>",
            dataType:'json',
            data:{'id':id},
            success:function(){
                layer.close(index);
                layer.msg('删除成功', {icon: 1});
                $(obj).parent().parent().remove();
            },
            error:function(){
                layer.close(index);
                layer.msg('通信通道发生错误！刷新页面重试！');
            }
        });
    }, function(){

    });

}
</script>