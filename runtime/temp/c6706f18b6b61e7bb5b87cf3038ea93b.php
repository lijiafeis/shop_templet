<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"/Users/lijiafei/Documents/fanli/fanli/public/../application/admin/view/order/libao_order.html";i:1522813902;}*/ ?>
<head>
	<meta charset="UTF-8">
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<title>Title</title>
</head>
<link rel="stylesheet" href="/static/admin/css/base.css">
<link rel="stylesheet" href="/static/admin/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<link rel="stylesheet" href="/static/admin/layui/css/layui.css"  media="all">
<style>
	table button{
		width: 50px;
		height: 34px;
		margin-left: 5px;
	}
	a{
		color: blue;
	}
</style>
<div class="container-fluid main">
	<div class="main-top"><span  aria-hidden="true"></span>订单列表</div>
		<div class="main-content">
			<div>
				<div>
					<div class="well">
						<div class="btn-group" style="">

						</div><br/>
						<div class="btn-group" style="margin-top:20px;">
							<button type="button" class="btn btn-default">用户id</button>
							<div class="btn-group">
								<input type="text" id="user_id" class="form-control">
							</div>
							<!--<button type="button" class="btn btn-default">订单号</button>-->
							<!--<div class="btn-group">-->
								<!--<input type="text" id="order_sn" class="form-control">-->
							<!--</div>-->
							<button type="button" class="btn btn-default">发货状态</button>
							<div class="btn-group">
								<select name="is_pay" id="type" class="form-control">
									<option value="-1">全部</option>
									<option value="0">未发货</option>
									<option value="1">已发货</option>
									<option value="2">已收货</option>
								</select>
							</div>
							<button type="button" class="btn btn-warning" onclick="getPage(1)">查询</button>
						</div>
					</div>

				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						<div id="list">

						</div>
						<div class="pagelist"><div id="demo3"></div></div>
					</div>
				</div>
					<input type="hidden" id="page" value="<?php echo $page; ?>"/>
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
	page1 = $("#page").val();
	if(page1 == ''){
	    page1 = 1;
	}
	var a = page1;
	getPage(a);
	function getPage(a) {
        var user_id = $("#user_id").val();
        var order_sn = $("#order_sn").val();
        var type = $("#type").val();
        var index = layer.load(2,{
            shade:[0.6,'#000']
		})
        $.ajax({
            url:"<?php echo url('libao_order'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
				'user_id':user_id,
				'order_sn':order_sn,
				'type':type,
            },
            success:function (data) {
                layer.close(index);
                setLayPage(data.number,a);
                setDivData(data.data,a);
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

	function setDivData(data,a){
	    var str = '';
	    //0 表示可以操作  1 表示不可以操作
		str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>编号</th><th>用户id</th><th>姓名</th><th>电话</th><th>地址</th><th>礼包名称</th><th>发货状态</th><th>时间</th><th>快递名</th><th>单号</th><th>操作</th>';
		for(i = 0; i < data.length; i++){
			data[i]['create_time'] = new Date(parseInt(data[i]['create_time']) * 1000).toLocaleString().substr(0,20)
			var type = data[i]['type'];
            if(data[i]['type'] == 0){
                data[i]['type'] = '未发货';
            }else if(data[i]['type'] == 1){
                data[i]['type'] = '已发货';
            }else if(data[i]['type'] == 2){
                data[i]['type'] = '已收货';
            }
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['id']+'</td>';
			str += '<td>'+data[i]['user_id']+'</td>';
			str += '<td>'+data[i]['name']+'</td>';
			str += '<td>'+data[i]['tel']+'</td>';
			str += '<td>'+data[i]['address']+'</td>';
			str += '<td>'+data[i]['good_name']+'</td>';
			str += '<td>'+data[i]['type']+'</td>';
			str += '<td>'+data[i]['create_time']+'</td>';
			str += '<td>'+data[i]['kd_name']+'</td>';
			str += '<td>'+data[i]['kd_number']+'</td>';
			str += '<td><button type="button" class="btn btn-warning" onclick="fahuo('+data[i]['id']+','+type+')">操作</button></td></tr>';


		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>
<script>
	function fahuo(id,type){
	    if(type != 0 || id == ''){
	        layer.msg('当前订单不是代发货状态');return;
		}
		//进行发货
        openid = layer.open({
            type: 1,
            skin: '', //加上边框
            area: ['300px', '250px'], //宽高
            content: '<div  style="margin-left: 0px;"><div >' +
            '<div style="text-align: center;margin-top: 5px;font-size: 15px;"><div><p>输入快递名</p>' +
            '<select style="margin:5px auto;width: 200px;marig-top:5px;border-radius: 4px;border: 1px solid #D9DADC;" class="form-control" name="kd_name" id="kd_name">'+
			'<option value="yuantong">圆通速递</option>'+
			'<option value="yunda">韵达快运</option>'+
			'<option value="shunfeng">顺丰</option>'+
			'<option value="shentong">申通</option>'+
			'<option value="tiantian">天天快递</option>'+
			'<option value="zhongtong">中通速递</option>'+
			'</select>' +
            '<div class="" style="margin-top: 5px;">' +
            '<div style="text-align: center;margin-top: 5px;font-size: 15px;"><div><p>输入快递单号</p>' +
            '<input style="border-radius: 4px;border: 1px solid #D9DADC;"  placeholder="" id="kd_number" type="text" />' +
            '<div class="" style="margin-top: 5px;">' +
            '<button style="background:#44B549;"  class="btn btn-default btn-default1" onclick="fahuo1('+id+')">确定</button>' +
            '</div></div></div></div></div>'
        });
	}

	function fahuo1(id){
        if(id == ''){
            layer.msg('当前订单不是代发货状态');return;
        }
        kd_name = $("#kd_name").val();
        kd_number = $("#kd_number").val();
        if(kd_name == '' || kd_number == ''){
            layer.msg('请填写信息');return;
		}
        var index = layer.load(2,{
            shade:[0.6,"#000"]
        });
        $.ajax({
            url:"<?php echo url('setLiBaoOrderKd'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'id':id,
                'kd_name':kd_name,
                'kd_number':kd_number,
            },
            success:function (data) {
                if(data.code == 1){
                    layer.msg('设置成功');
                    setTimeout(function () {
                        history.go(0);
                    },500);
                }else{
                    layer.msg(data.info);
                }
                layer.close(index);
            },
            error:function (data) {
                layer.close(index);
                layer.msg('网络错误,请稍后重试');
            }
        });
	}


</script>
