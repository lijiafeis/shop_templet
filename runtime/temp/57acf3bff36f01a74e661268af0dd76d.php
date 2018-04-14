<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/set_qr.html";i:1523685223;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<link href="/static/Admin/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/static/admin/colpick/css/colpick.css" type="text/css"/>
<style>
.btn-default{background:#44b549;color:#fff;}
.form-group{padding:5px 0;}
.view-img{background: #fff;width: 375px;height:603px;background:#fff url(<?php echo $qrset[0]['pic_url']; ?>);background-size: 100% 100%;margin:50px;overflow:hidden;}
.view-img div{position: absolute;}
.qrleft{float:left;}
.right{float:right;}
.clear{clear: both;}
 .yulan img{width:100%}
.img-view{background: red;}
</style>
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>系统设置</div>
	<div class="main-content">
		<div>
		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    
		    <li role="presentation" class="active"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">二维码设置</a></li>	
		  </ul>
		  <!-- Tab panes -->
		  <div class="tab-content">
			  
		   
		    <div role="tabpanel" class="tab-pane active" id="messages">
		    	<h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">输入参数动态调整海报排版</h5>
				<div class="well" style="position: relative;width:100%;">
	<div class="view-img qrleft" style="position: relative;">
		<div class="nicheng" style="color: #fff;font-size: 30px;">西瓜科技有限公司</div>
		<div class="head_pic"><img src="/static/admin/image/head.jpg" width="40px" alt=""></div>
		<div class="erweima_pic"><img src="/static/admin/image/erweima.jpg" width="0" alt=""></div>
	</div>

<form class="form-horizontal qrleft" style="" action="<?php echo url('set_qr'); ?>" method="post" enctype="multipart/form-data"  >
		    		
		
 <div class="form-group">
					    <div class="col-sm-offset-2 col-sm-5">
					      <button type="button" class="btn btn-warning" onclick="upload(this)" style="margin:20px ;">上传640px*900px背景图</button>
					      <input type="hidden" name="pic_url" id="pic_url" value="<?php echo $qrset[0]['pic_url']; ?>" >
					    </div>
					    <div class="col-sm-5">
					      <button type="submit" class="btn btn-danger" style="margin:20px ">调试好，保存 </button>
					    </div>
					  </div>
					
					  <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">昵称大小</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="nicheng" name="font_size" value="<?php echo $qrset[0]['font_size']; ?>" placeholder="建议值35">
					    </div>
					  </div>
					<div class="form-group">
						<label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">昵称颜色</label>
						<div class="col-sm-10">
							<div id="picker1"></div>
							<input type="text" class="form-control" id="font_color" name="font_color" value="<?php echo $qrset[0]['font_color']; ?>" /><div class="btn btn-warning" onclick="setFontColor()">选择</div>
						</div>
					</div>
					  <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">昵称位置</label>
					    <div class="col-sm-5">
					      <input type="text" class="form-control" id="nicheng-x" name="font_x" value="<?php echo $qrset[0]['font_x']; ?>" placeholder="建议值150">
					    </div>
					    <div class="col-sm-5">					  
					      <input type="text" class="form-control" id="nicheng-y" name="font_y" value="<?php echo $qrset[0]['font_y']; ?>" placeholder="建议值120">
					    </div>
					  </div>			 	 
		    		 <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">头像宽高</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="head_pic" name="head_size" value="<?php echo $qrset[0]['head_size']; ?>" placeholder="建议值100">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">头像位置</label>
					    <div class="col-sm-5">
					      <input type="text" class="form-control" id="head_pic-x" name="head_x" value="<?php echo $qrset[0]['head_x']; ?>" placeholder="建议值50">
					    </div>
					    <div class="col-sm-5">					  
					      <input type="text" class="form-control" id="head_pic-y" name="head_y" value="<?php echo $qrset[0]['head_y']; ?>" placeholder="建议值30">
					    </div>
					  </div>					 
					  <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">二维码宽高</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="erweima_pic" name="qr_size" value="<?php echo $qrset[0]['qr_size']; ?>" placeholder="建议值250">
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputPassword3" class="col-sm-2 col-lg-2 control-label">二维码位置</label>
					    <div class="col-sm-5">
					      <input type="text" class="form-control" id="erweima_pic-x" name="qr_x" value="<?php echo $qrset[0]['qr_x']; ?>" placeholder="建议值200">
					    </div>
					    <div class="col-sm-5">					  
					      <input type="text" class="form-control" id="erweima_pic-y" name="qr_y" value="<?php echo $qrset[0]['qr_y']; ?>" placeholder="建议值500">
					    </div>
					  </div>
					  <input type="hidden" name="qr" value="<?php echo $qrset[0]['id']; ?>" >
					 
					</form>


		<div class="clear"></div>	
					
				</div>
		    </div>
			
		  </div>

		</div>
		
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>

<script src="/static/admin/colpick/js/colpick.js" type="text/javascript"></script>

<script src="/static/admin/layer/layer.js"></script>
<script>
	function setFontColor(){
		//关于取色
		$('#picker1').colpick({
			flat:true,
			layout: 'rgb',
			submit:0,
			onSubmit: function(hsb, hex, rgb, el) {
				console.log(rgb);
				str = rgb.r + ',' +  rgb.g + ',' + rgb.b;
				$('#font_color').val(str);
				$('.nicheng').css('color',"rgb("+rgb.r+","+rgb.g+","+rgb.b+")");
//				$(el).colpickHide();
			}
		});
	}
</script>
<script>
	$('.nicheng').css('color',"rgb(<?php echo $qrset[0]['font_color']; ?>)");

</script>
<script>
function upload(obj){
//alert(num);
	layer.open({
	  type: 2,
	  skin:'demo',
	  title:'上传图片--西瓜科技上传插件',
	  area: ['520px', '430px'],
	  fix: false, //不固定
	  //maxmin: true,
	  content: "<?php echo url('Img/index'); ?>?id=pic_url",
	  end: function(){
		var url = $('#pic_url').val();	
		$('.view-img').css('background-image','url('+url+')');	
		}
	});
}
$(document).ready(function(){
	$('#nicheng').keyup()
	$('#nicheng-x').keyup()
	$('#nicheng-y').keyup()
	$('#head_pic').keyup()
	$('#head_pic-x').keyup()
	$('#head_pic-y').keyup()
	$('#erweima_pic').keyup()
	$('#erweima_pic-x').keyup()
	$('#erweima_pic-y').keyup()

})
$('#nicheng').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.nicheng').css('font-size',value+'px');
})
$('#nicheng-x').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.nicheng').css('margin-left',value+'px');
})
$('#nicheng-y').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.nicheng').css('margin-top',value+'px');
})
$('#head_pic').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.head_pic').find('img').attr('width',value+'px');
	$('.head_pic').find('img').attr('height',value+'px');
})
$('#head_pic-x').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.head_pic').css('margin-left',value+'px');
})
$('#head_pic-y').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.head_pic').css('margin-top',value+'px');
})
$('#erweima_pic').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.erweima_pic').find('img').attr('width',value+'px');
	$('.erweima_pic').find('img').attr('height',value+'px');
})
$('#erweima_pic-x').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.erweima_pic').css('margin-left',value+'px');
})
$('#erweima_pic-y').keyup(function(){
	var value = $(this).val()
	value = value/2
	$('.erweima_pic').css('margin-top',value+'px');
})
</script>