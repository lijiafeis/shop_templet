<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/shop/type.html";i:1522286340;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<div class="container-fluid main">
	<div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>商城管理--分类管理</div>
	<div class="main-content">
<style>
.table tr td{font-size:12px;vertical-align:middle;}
.form-control{font-size:8px;}
</style>
<div>
  <ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">分类列表</a></li>		    		    	    
  </ul>
  
  <!-- Tab panes -->
  <div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="home">
		<div class="alert alert-success" style="padding:5px 10px;margin:15px 0;line-height:30px;">
			创建一个属性类，然后加入各个具体属性
		</div>
		<div class="well">
			<a href="<?php echo url('type_add'); ?>" class="btn btn-warning" >添加产品类型</button></a><br/><br/>
			<div role="tabpanel" class="tab-pane active" id="home1">
				<div id="list">

				</div>
				<div class="pagelist"><div id="demo3"></div></div>
			</div>
		</div>
		
		
	</div>
  </div>
</div>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script src="/static/admin/layui/layui.js" charset="utf-8"></script>
<script src="/static/admin/laydate/laydate.js"></script>
<script>
    getPage(1);
    function getPage(a) {
        var index = layer.load(2,{
            shade:[0.6,'#000']
        })
        $.ajax({
            url:"<?php echo url('type'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
            },
            success:function (data) {
                layer.close(index);
                setLayPage(data.number,a);
                setDivData(data.data);
            },
            error:function (data) {
                layer.close(index);
                layer.msg('网络错误,请稍后重试');
            }
        });

    }
</script>
<script>
    function setLayPage(number,a){
        layui.use(['laypage', 'layer'], function(){
            var laypage = layui.laypage
                ,layer = layui.layer;
            //自定义首页、尾页、上一页、下一页文本
            laypage.render({
                elem: 'demo3'
                ,count: number
                ,curr:a//显示第几页
                ,limit:10
                ,first: '首页'
                ,last: '尾页'
                ,prev: '<em>←</em>'
                ,next: '<em>→</em>'
                ,jump: function(obj,first){
                    if(!first){
                        getPage(obj.curr);
                    }
                }
            });
        });
    }

    function setDivData(data){
        var str = '';
//
//
//
//
        //0 表示可以操作  1 表示不可以操作
        str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>ID</th><th>产品类型名称</th><th>包含属性</th><th>操作</th>';
        for(i = 0; i < data.length; i++){
            str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['type_id']+'</td>';
            str += '<td>'+data[i]['type_name']+'</td>';
            str += '<td>'+data[i]['name']+'</td>';
            str += '<td><a><button onclick="editType('+data[i]['type_id']+')" class="btn btn-success btn-sm">编辑属性</button></a><a><button onclick="updateType('+data[i]['type_id']+')" class="btn btn-warning btn-sm">修改</button></a> <button class="btn btn-danger btn-sm" onclick="del(this,'+data[i]['type_id']+')">删除</button></td></tr>';


        }
        str += '</table>';
        $('#list').empty();
        $('#list').append(str);
    }
</script>
<script>
	function editType(type_id){
        location.href = "<?php echo url('type_spec'); ?>?id=" + type_id;
	}
	function updateType(type_id){
	    location.href = "<?php echo url('type_add'); ?>?id=" + type_id;
	}
function del(obj,id){
	layer.confirm('删除后无法恢复，确认删除吗', {
		btn: ['确认','取消'] //按钮
	}, function(){
		$.ajax({
			type: "POST",
			url: "<?php echo url('type_del'); ?>?time="+new Date(),
			dataType: "json",
			data: {
				"id":id,
			},
			success: function(json){
				if(json.success == 1){
					layer.msg("删除成功");
					var td = $(obj).parent();//alert(a);
					td.parent().css("display","none");	
				}else{
					layer.msg(json.info);
				}
				

			},
			error:function(){
				alert('发生错误');
			}
		});
	}, function(){
		
	});	
}

</script>