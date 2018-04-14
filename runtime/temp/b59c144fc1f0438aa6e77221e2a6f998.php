<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/menu.html";i:1522286340;}*/ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/weui.min.css"/>
	<link rel="stylesheet" href="/static/admin/css/base.css" />
	<title>账号绑定</title>
	<style>
		.menu-view{width:100%;height:380px;background:url(/static/admin/image/menu.png) no-repeat;position:relative;}
		.menuall{position: absolute;bottom:0px;height:60px;left:51px;line-height: 60px;font-size: 16px;font-weight: bold; width:400px;color:#534b4b;}
		.menu3{width:115px;display: inline-block;text-align: center;border-right:1px solid #ccc;overflow:hidden;height:60px;}
		.menu2{width:173px;display: inline-block;text-align: center;border-right:1px solid #ccc;overflow:hidden;height:60px;}
		.menu1{width:347px;display: inline-block;text-align: center;border-right:1px solid #ccc;overflow:hidden;height:60px;}
		.list-menu{position: absolute; bottom:70px;left:51px;line-height: 50px;font-size: 14px;color:#534b4b;text-align: center;}
		.list-menu .ul3{list-style: none;padding:0;margin:0;display: inline-block;width:110px;background:#FAFAFA;border-radius: 10px;margin-right: 5px;margin-left: 2px;}
		.list-menu .ul2{list-style: none;padding:0;margin:0;display: inline-block;width:110px;background:#FAFAFA;border-radius: 10px;margin-left: 30px;margin-right: 30px;}
		.list-menu .ul1{list-style: none;padding:0;margin:0;display: inline-block;width:110px;background:#FAFAFA;border-radius: 10px;margin-left: 118px;}
		.list-menu ul li{height:50px;border-bottom: 1px solid #ccc;padding:0;margin:0;overflow:hidden;padding:0px 5px;}
		.list-menu2{left:80px;}
		.list-menu1{left:167px;}
		.list-menu3{left:51px;}
		.list-menu4{left:253px;}
		.change:hover{color:red;font-size:18px;font-weight:bold;}
	</style>
	<script> 
		function setChange() { 
			if (document.f.type.value == "click") { 
				document.all.tb1.style.display = "block"; 
			}else { 
				document.all.tb1.style.display = "none"; 
			} 
			if (document.f.type.value == "view") { 
				document.all.tb2.style.display = "block"; 
			} else { 
				document.all.tb2.style.display = "none"; 
			} 
		}
	
	</script> 
</head>
<body>
	<div class="container-fluid">
		<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>自定义菜单管理</div>
		<div class="main-content">
		<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;color:#000;border:1px solid #ccc;">更改已创建菜单请直接在下方视图内点击进行操作</h5>
		<div class="menu-view">
			<div class="menuall">
				 <?php if($ress == 'null'): ?>
				 	<span class="menu1">你还没有创建菜单哦！</span>
				 <?php else: if(is_array($ress) || $ress instanceof \think\Collection || $ress instanceof \think\Paginator): $k = 0; $__LIST__ = $ress;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($k % 2 );++$k;?>
				 	<div class="menu<?php echo $num; ?> change" style="cursor:pointer" onclick="abc(<?php echo $vv['id']; ?>)" title="点击更改" ><?php echo $vv['title']; ?></div>

					<?php endforeach; endif; else: echo "" ;endif; endif; ?>
			</div>			
					<div class="list-menu">
						<?php if(is_array($ress) || $ress instanceof \think\Collection || $ress instanceof \think\Paginator): $i = 0; $__LIST__ = $ress;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
						<ul class="ul<?php echo $num; ?> hand">
							<?php if(is_array($vv['list']) || $vv['list'] instanceof \think\Collection || $vv['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vv['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kk): $mod = ($i % 2 );++$i;?>
							<li class="change" style="cursor:pointer" onclick="abc(<?php echo $kk['id']; ?>)" title="点击更改"><?php echo $kk['title']; ?></li>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					<?php endforeach; endif; else: echo "" ;endif; ?>						
					</div>
			<div style="clear:both"></div>
			<div style="margin-left:420px;width:50%;border:1px dashed #333;padding:10px 2%;box-shadow:0 0 2px 2px #999;">
			<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;background:#44b549;color:#fff;">添加新菜单</h5>
			<form class="form-horizontal" action="<?php echo url('menuadd'); ?>" onsubmit="return check()" method="post" name="f">
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">菜单名称</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="title" name="title" value="" placeholder="">
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">父级菜单</label>
				<div class="col-sm-7">
				  <select name="pid" id="" style="height:25px;width:200px;padding-left:10px" >
							<option value="0">设为一级菜单</option>
							<?php if(is_array($pinfo) || $pinfo instanceof \think\Collection || $pinfo instanceof \think\Paginator): $i = 0; $__LIST__ = $pinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
							<option value="<?php echo $vv['id']; ?>"><?php echo $vv['title']; ?></option>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">菜单类型</label>
				<div class="col-sm-7">
				  <select name="type" id="type" onchange="setChange()"  style="height:25px;width:200px;padding-left:10px">
							<option value="">请选择菜单类型</option>
							<option value="click">关键词回复</option>
							<option value="view">URL跳转链接</option>
							<option value="scancode_push">扫一扫</option>
							<option value="scancode_waitmsg">扫一扫</option>
							<option value="pic_sysphoto">拍照发图</option>
							<option value="pic_photo_or_album">从相册或相机发图</option>
							<option value="pic_weixin">相册发图</option>
							<option value="location_select">发送地理位置</option>
						</select>
				</div>
			  </div>
			  <div class="form-group" id="tb1" style="display:none;color:red">
				<label for="inputEmail3" class="col-sm-3 control-label">关联关键词</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="keyword" name="keyword" value="" placeholder="">
				</div>
			  </div>
			  <div class="form-group" id="tb2" style="display:none;color:red">
				<label for="inputEmail3" class="col-sm-3 control-label">外链地址</label>
				<div class="col-sm-7">
				  <input type="text" class="form-control" id="url" name="url" value="" placeholder="">
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">显示</label>
				<div class="col-sm-7">
				  <input type="radio"  name="is_show" value="1" checked />显示<input type="radio" name="is_show" value="0" />隐藏
				</div>
			  </div>
			  <div class="form-group">
				<label for="inputEmail3" class="col-sm-3 control-label">排序</label>
				<div class="col-sm-2">
				  <input type="text" class="form-control" id="" name="code" value="" placeholder="">
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				  <button type="submit" class="btn btn-default">确认添加</button>
				</div>
			  </div>
			</form>
			</div>
			
		</div>
		<hr />
			<a href="<?php echo url('menusave'); ?>"><span class="updmenu">一键更新菜单</span></a>
			<p style="color:red">
				注意：①更新菜单时请确认菜单是否正确，一级菜单不能多于3个，一个一级菜单下二级菜单不能超过5个，更新后最迟24小时微信端生效，重新关注可以立即看到更改效果<br />
				　　　②一级菜单如果有子菜单，那么该一级菜单名称不能超过5个字
			</p>
			
		</div>
		<table class="table table-hover">
			<th width="25%">菜单名称</th>
			<th width="50%">数值</th>
			<th width="5%">显示</th>
			<th width="20%">操作</th>
			<?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
			<tr>
				<td style="text-align:left;border-bottom:1px solid #ccc"><?php echo $vv['title']; ?></td>				
				<td style="text-align:left;border-bottom:1px solid #ccc">
					<?php if($vv['type'] == 'click'): ?><?php echo $vv['keyword']; endif; if($vv['type'] == 'view'): ?><?php echo $vv['url']; endif; if($vv['type'] == 'scancode_push'): ?>正常二维码扫描<?php endif; if($vv['type'] == 'scancode_waitmsg'): ?>扫描结果在本账号内处理<?php endif; if($vv['type'] == 'pic_sysphoto'): ?>用户点击按钮后，微信客户端将调起系统相机<?php endif; if($vv['type'] == 'pic_photo_or_album'): ?>用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”<?php endif; if($vv['type'] == 'pic_weixin'): ?>用户点击按钮后，微信客户端将调起微信相册<?php endif; if($vv['type'] == 'location_select'): ?>用户点击按钮后，微信客户端将调起地理位置选择工具<?php endif; ?>
				</td>
				<td style="border-bottom:1px solid #ccc"><?php if($vv['is_show'] == '1'): ?>显示<?php else: ?>隐藏<?php endif; ?></td>
				<td style="border-bottom:1px solid #ccc">
					<span class="btn btn-default" onclick="abc(<?php echo $vv['id']; ?>)">修改</span>
					<span class="btn btn-default" onclick="deletemenu(<?php echo $vv['id']; ?>)">删除</span>
				</td>
			</tr>			
			<?php if(is_array($vv['list']) || $vv['list'] instanceof \think\Collection || $vv['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vv['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kk): $mod = ($i % 2 );++$i;?>
			<tr >
				<td style="text-align:left;border-bottom:1px solid #ccc">　<span style="color:#999">|————</span>　<?php echo $kk['title']; ?></td>
				<td style="text-align:left;border-bottom:1px solid #ccc">
					<?php if($kk['type'] == 'click'): ?><?php echo $kk['keyword']; endif; if($kk['type'] == 'view'): ?><?php echo $kk['url']; endif; if($kk['type'] == 'scancode_push'): ?>正常二维码扫描<?php endif; if($kk['type'] == 'scancode_waitmsg'): ?>扫描结果在本账号内处理<?php endif; if($kk['type'] == 'pic_sysphoto'): ?>用户点击按钮后，微信客户端将调起系统相机<?php endif; if($kk['type'] == 'pic_photo_or_album'): ?>用户点击按钮后，微信客户端将弹出选择器供用户选择“拍照”或者“从手机相册选择”<?php endif; if($kk['type'] == 'pic_weixin'): ?>用户点击按钮后，微信客户端将调起微信相册<?php endif; if($kk['type'] == 'location_select'): ?>用户点击按钮后，微信客户端将调起地理位置选择工具<?php endif; ?>
				</td>
				<td style="border-bottom:1px solid #ccc"><?php if($kk['is_show'] == '1'): ?>显示<?php else: ?>隐藏<?php endif; ?></td>
				<td style="border-bottom:1px solid #ccc">
					<span class="btn btn-default" onclick="abc(<?php echo $kk['id']; ?>)">修改</span>
					<span class="btn btn-default" onclick="deletemenu(<?php echo $kk['id']; ?>)">删除</span>
				</td>
			</tr>
			<?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
		</table>
	
	</div>
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
	<script src="/static/admin/js/bootstrap.min.js"></script>
	<script src="/static/admin/layer/layer.js"></script>
	<script>

		function abc(i){
			var index = layer.open({
				type: 2,
				title: '编辑菜单',
				content: "<?php echo url('menuedit'); ?>?menu_id="+i,
				area: ['700px', '600px'],
				maxmin: true
			});
			layer.full(index);			 
		}
		function check(){
			var title = $('#title').val();
			var type = $('#type').val();
			var keyword = $('#keyword').val();
			var url = $('#url').val();
			if(title == ''){
				layer.msg('菜单名称不能为空', function(){
				});
				return false;
			}
			if(type == ''){
				layer.msg('菜单类型不能为空', function(){
				});
				return false;
			}
			if($('#tb1').css('display') == "block"){
				if(keyword == ''){
					layer.msg('关键词不能为空', function(){
					});
					return false;
				}
			}
			if($('#tb2').css('display') == "block"){
				if(url == ''){
					layer.msg('外链地址不能为空', function(){
					});
					return false;
				}
			}
			
			
		}
		
		function deletemenu(i){
			layer.confirm('确定删除？', {
				btn: ['是，确认','否，再看看'] //按钮
			}, function(){
				layer.msg('正在删除，请稍候', {icon: 16});
				$.ajax({
						type: "POST",
						url: "<?php echo url('deletemenu'); ?>",
						dataType: "json",
						data: {"id":i},
						success: function(json){
							if(json.success==1){
								window.location.href = "<?php echo url('menu'); ?>";
								
							}else{
								layer.msg("处理失败，请重新尝试");				
							}
						},
						error:function(){
							alert(3);
						}
					});
				
			}, function(){
				
			});
		}
	</script>
</body>
</html>