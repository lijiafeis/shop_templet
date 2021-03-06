<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/shop/add_good.html";i:1523412563;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/weui.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<link href="/static/admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<style>
.btn-default{background:#44b549;color:#fff;}
.form-group1:hover{background:#fff;}
</style>
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>商城管理--添加商品</div>
	<div class="main-content">
	<!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">商品参数</a></li>
		<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">商品缩略图</a></li>
		<li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">商品详情</a></li>
		<li role="presentation"><a href="#type" aria-controls="type" role="tab" data-toggle="tab">商品属性</a></li>
		<li role="presentation"><a href="javascript:void(0);" onclick="history.go(-1);">返回上一页</a></li>
	  </ul>
<style>
.table tr td img{height:40px;cursor:hand;}
.code:hover{cursor:hand;}
.abc{display:inline-block;}
</style>
	  <!-- Tab panes -->
	  <form class="form-horizontal" action="add_good" method="post" onsubmit="return check();" enctype="multipart/form-data" >
	  <div class="tab-content well" style="margin-top:30px;border:1px solid #dddddd;padding:10px 2%;">
	  
		<div role="tabpanel" class="tab-pane active" id="home">
			
			 <div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">商品名称</label>
				<div class="col-sm-6 col-lg-4">
					<input type="text" class="form-control" id="good_name" name="good_name" value="" placeholder="">
				</div>
			</div>
			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">所属分类</label>
				<div class="col-sm-3  col-lg-2">
					<select class="form-control" name="cate_gid" id="pid">
						<?php if(is_array($categrey) || $categrey instanceof \think\Collection || $categrey instanceof \think\Paginator): $i = 0; $__LIST__ = $categrey;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
							<option value="<?php echo $vv['cate_id']; ?>"><?php echo $vv['cate_name']; ?></option>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
				<div class="col-sm-3 col-lg-2">
				</div>
			</div>
			<div class="form-group has-success">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">产品价格</label>
				<div class="col-sm-6 col-lg-4">
				<div class="col-sm-6 col-lg-6" style="margin-bottom:10px;">
				<div class="input-group">
				  <span class="input-group-addon">价格</span>
				  <input type="text" class="form-control" id="good_price" name="good_price" value="" placeholder="">
				</div>
				</div>

				</div>
					
			</div>

			<div class="form-group has-success">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">原价格</label>
				<div class="col-sm-6 col-lg-4">
					<div class="col-sm-6 col-lg-6" style="margin-bottom:10px;">
						<div class="input-group">
							<span class="input-group-addon">价格</span>
							<input type="text" class="form-control" id="market_price" name="market_price" value="" placeholder="">
						</div>
					</div>

				</div>

			</div>
			<div class="form-group has-success">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">返还积分比例</label>
				<div class="col-sm-6 col-lg-4">
					<div class="col-sm-6 col-lg-6" style="margin-bottom:10px;">
						<div class="input-group">
							<span class="input-group-addon">比例</span>
							<input type="text" class="form-control" name="jifen_bili" value="" placeholder="">
						</div>
					</div>

				</div>

			</div>

			<div class="form-group has-success">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">销量</label>
				<div class="col-sm-6 col-lg-4">
					<div class="col-sm-6 col-lg-6" style="margin-bottom:10px;">
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1">销量</span>
							<input type="text" class="form-control" id="xiaoliang" name="xiaoliang" value="" placeholder="">
						</div>
					</div>
				</div>

			</div>

			<div class="form-group">
				<label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">商品库存</label>
				<div class="input-group col-sm-6 col-lg-4">
				  <span class="input-group-addon" id="basic-addon1">正整数</span>
					<input type="text" class="form-control" id="number" name="number" value="" placeholder="">
				</div>
			</div>
			<div style="clear:both"></div>
			<div style="margin-top:30px"><button type="submit" class="btn btn-success">保存商品信息</button></div>
		</div>
		<div role="tabpanel" class="tab-pane" id="profile">
		<Br/><br/>
			<div type="button" class="btn btn-warning" onclick="upload(this)">添加商品图片</div>
			
			<div style="margin:20px 0;" id="pic_img"></div>
			<input type="hidden" name="pic1" value="">
			<input type="hidden" name="pic2" value="">
			<input type="hidden" name="pic3" value="">
			<input type="hidden" name="pic4" value="">
			<input type="hidden" name="pic5" value="">
			<input type="hidden" name="pic6" value="">
			<input type="hidden" name="pic7" value="">
			<input type="hidden" name="pic8" value="">
			 <div style="clear:both"></div>
			 <div style="margin-top:30px"><button type="submit" class="btn btn-success">保存商品信息</button></div>
			  <div style="clear:both"></div>
		</div>
		<div role="tabpanel" class="tab-pane" id="messages">
		<span style="margin-bottom:10px;color:red;">上传图片建议上传320 * 400</span>
		<div class="form-group">
			<div class="col-sm-12">
				<script id="editor" type="text/plain" name="good_desc" value="" style="width:100%;height:500px;"></script>
			</div>
		</div>
		<input type="hidden" class="form-control" id="hidden" name="id" value="" placeholder="">
			<div style="margin-top:30px"><button type="submit" class="btn btn-success">保存商品信息</button></div>
		 <div style="clear:both"></div>
		</div>
		  <!-- 商品属性 -->
		  <div role="tabpanel" class="tab-pane" id="type">
			  <div class="form-group">
				  <label for="inputEmail3" class="col-sm-2 col-lg-1 control-label">商品类型</label>
				  <div class="col-sm-3  col-lg-2">
					  <select class="form-control" name="type_id" id="">
						  <option value="0">请选择</option>
						  <?php if(is_array($type_info) || $type_info instanceof \think\Collection || $type_info instanceof \think\Paginator): $i = 0; $__LIST__ = $type_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?>
							  <option value="<?php echo $vv['type_id']; ?>"><?php echo $vv['type_name']; ?></option>
						  <?php endforeach; endif; else: echo "" ;endif; ?>
					  </select>
				  </div>
			  </div>
			  <div style="margin-top:30px"><button type="submit" class="btn btn-success">保存商品信息</button></div>
		  </div>


	</div>
	</form>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/admin/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
<script type="text/javascript" src="/static/admin/ueditor/ueditor.all.js"></script>
<script src="/static/admin/js/fileinput.js" type="text/javascript"></script>
<script src="/static/admin/js/fileinput_locale_zh.js" type="text/javascript"></script>
<script src="/static/admin/layer/layer.js"></script>
<script src="/static/admin/js/ajaxfileupload.js"></script>
<script>
    $(document).ready(function(){
        var val = $('#pid').val();
        change_pid(val);
        $('#pid').bind("change",function(){
            var v = $(this).val();
            change_pid(v);
        });
    });
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
function changeCode(obj){
	$(obj).css("display","none");
	$(obj).next().next().css("display","block");
}
 var ue = UE.getEditor('editor');
  imagePathFormat: "/images/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}";
  /*
  ["good_name"] => string(12) "的淡淡的"
  ["cate_pid"] => string(1) "1"
  ["code"] => string(1) "3"
  ["id"] => string(0) ""
  ["good_desc"]
  */
function check(){
	if($('#good_name').val()==""){layer.msg("商品名称不能为空");return false;}
	if($('#good_price').val()==""){layer.msg("商品价格不能为空");return false;}
	if($('#number').val()==""){layer.msg("库存不能为空");return false;}
}
function del(obj,id){
	layer.confirm('确定要删除这条数据吗？', {
	  btn: ['确定','取消'] //按钮
	}, function(){
	  $.ajax({
			type:'post',
			url:"<?php echo url('del_good_pic'); ?>",
			dataType:'json',
			data:{'id':id},
			success:function(){
				layer.msg('删除成功', {icon: 1});
				$(obj).parent().parent().remove();
			},
			error:function(){
				layer.msg('通信通道发生错误！刷新页面重试！');
			}
		});
	}, function(){
	  
	});
	
}
	
	$("#file-3").fileinput({
			showUpload: false,
			showCaption: false,
			browseClass: "btn btn-default",
			fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
		});
	$("#file-4").fileinput({
			showUpload: false,
			showCaption: false,
			browseClass: "btn btn-default",
			fileType: "any",
	        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
		});

</script>
<script>
    function change_pid(v){
        $('#pid').parent().next().children().remove();
        $.each(<?php echo $jscategrey; ?>,function(key,value){
            var html = '<select class="form-control" name="cate_pid" >';
            if(v == key && value){
                $.each(value,function(dd,vv){
                        html = html + "<option value='"+vv.cate_id+"'>"+vv.cate_name+"</option>";

                });
                $('#pid').parent().next().html(html+"</select>");
            }
        });
    }
</script>
<script>
var num = 1;
function upload(){
//alert(num);
if(num > 8){layer.msg('单次最多支持上传8张！');exit;}
	layer.open({
	  type: 2,
	  skin:'demo',
	  title:'上传图片--西瓜科技上传插件',
	  area: ['520px', '430px'],
	  fix: false, //不固定
	  //maxmin: true,
	  content: "<?php echo url('Img/index'); ?>?id=pic"+num,
	  end: function(){
			if($("input[name='pic"+num+"']").val() != ''){
			var url = $("input[name='pic"+num+"']").val();
			num++;
			$('#pic_img').append('<div class="col-sm-3 col-lg-2"><img src="'+url+'" alt="..." class="img-rounded" width="100%"></div>');
			}
			
		}
	});
}

</script>