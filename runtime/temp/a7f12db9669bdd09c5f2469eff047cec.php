<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/order/order.html";i:1523673261;}*/ ?>
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
							<button type="button" class="btn btn-default">订单号</button>
							<div class="btn-group">
								<input type="text" id="order_sn" class="form-control">
							</div>
							<button type="button" class="btn btn-default">支付状态</button>
							<div class="btn-group">
								<select name="is_pay" id="state" class="form-control">
									<option value="-1">全部</option>
									<option value="0">未支付</option>
									<option value="1">已支付</option>
								</select>
							</div>
							<button type="button" class="btn btn-default">发货状态</button>
							<div class="btn-group">
								<select name="is_pay" id="type" class="form-control">
									<option value="-1">全部</option>
									<option value="0">未发货</option>
									<option value="1">已发货</option>
									<option value="2">已收货</option>
								</select>
							</div>
							<button type="button" class="btn btn-default">支付类型</button>
							<div class="btn-group">
								<select name="is_pay" id="pay_type" class="form-control">
									<option value="0">全部</option>
									<option value="1">支付宝</option>
									<option value="2">微信</option>
								</select>
							</div>
							<button type="button" class="btn btn-default">订单类型</button>
							<div class="btn-group">
								<select name="is_pay" id="order_type" class="form-control">
									<option value="0">全部</option>
									<option value="1">普通订单</option>
									<option value="2">秒杀订单</option>
									<option value="2">199订单</option>
								</select>
							</div>
							<button type="button" class="btn btn-warning" onclick="getPage(1)">查询</button>
						</div>
					</div>

				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
				</ul>
					<a onclick="dcExcel()"><button type="button" class="btn btn-warning">导出</button></a>
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
        var state = $("#state").val();
        var type = $("#type").val();
        var pay_type = $("#pay_type").val();
        var order_type = $("#order_type").val();
        var index = layer.load(2,{
            shade:[0.6,'#000']
		})
        $.ajax({
            url:"<?php echo url('order'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
				'user_id':user_id,
				'order_sn':order_sn,
				'state':state,
				'type':type,
				'pay_type':pay_type,
				'order_type':order_type
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
		str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>编号</th><th>用户id</th><th>订单号</th><th>总价格</th><th>是否支付</th><th>发货状态</th><th>支付时间</th><th>订单类型</th><th>支付类型</th><th>操作</th>';
		for(i = 0; i < data.length; i++){
            if(data[i]['state'] == 0){
                data[i]['state'] = '未支付';
            }else if(data[i]['state'] == 1){
                data[i]['state'] = '已支付';
            }
            if(data[i]['order_type'] == 1){
                data[i]['order_type'] = '普通订单';
            }else if(data[i]['order_type'] == 2){
                data[i]['order_type'] = '秒杀订单';
            }else if(data[i]['order_type'] == 3){
                data[i]['order_type'] = '199订单';
            }
			if(data[i]['pay_type'] == 1){
				data[i]['pay_type'] = '支付宝';
			}else if(data[i]['pay_type'] == 2){
				data[i]['pay_type'] = '微信';
			}
            if(data[i]['type'] == 0){
                data[i]['type'] = '未发货';
            }else if(data[i]['type'] == 1){
                data[i]['type'] = '已发货';
            }else if(data[i]['type'] == 2){
                data[i]['type'] = '已收货';
            }
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['id']+'</td>';
			str += '<td>'+data[i]['user_id']+'</td>';
			str += '<td>'+data[i]['order_sn']+'</td>';
			str += '<td>'+data[i]['total_money']+'</td>';
			str += '<td>'+data[i]['state']+'</td>';
			str += '<td>'+data[i]['type']+'</td>';
			str += '<td>'+data[i]['pay_time']+'</td>';
			str += '<td>'+data[i]['order_type']+'</td>';
			str += '<td>'+data[i]['pay_type']+'</td>';
			str += '<td><button type="button" class="btn btn-warning" onclick="more('+data[i]['id']+','+a+')">详情</button></td></tr>';


		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>
<script>
	function more(id,page){
	    location.href = "<?php echo url('order_more'); ?>?id=" + id + "&page=" + page;
	}


</script>

<script>
	function dcExcel() {
		var opt = '<option value="0">全部</option>';
		openid = layer.open({
			type: 1,
			skin: '', //加上边框
			area: ['400px', '350px'], //宽高
			content: '<div style="text-align: center;margin-top:10px;"><div ><div ><div ><p>选择开始导出时间</p>' +
			'<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="time1" type="text"/>' +
			'<p style="margin-top:7px;">选择结束导出时间</p>' +
			'<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="time2" type="text"/>' +
			'<br/>' +
			'<select id="type" style="width:150px;height:25px;border-radius: 4px;border: 1px solid #D9DADC;">' +
			'<option value="-1">全部</option>' +
			'<option value="0">未发货</option>' +
			'<option value="1">已发货</option>' +
			'<option value="21">已完成</option>' +
			'</select><div class="">' +
			'<button style="margin-top: 17px;" type="submit" onclick="dcExcel1()" class="btn btn-default btn-default1" >导出</button>' +
			'</div></div></div></div></div>'
		});
	}
	function setDate() {
		laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'});
	}

	function dcExcel1() {
		var time1 = $('#time1').val();
		var time2 = $('#time2').val();
		var type = $('#type').val();
		layer.close(openid);
		location.href = "<?php echo url('dchuExcel'); ?>?time1=" + time1 + "&time2=" + time2 + "&type=" + type;

	}
</script>
