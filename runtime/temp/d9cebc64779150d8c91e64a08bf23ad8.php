<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/shop/shop.html";i:1523523723;}*/ ?>
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
	/*弹窗列表*/
	ul{list-style: none;}
	#box{display: none;}
	#lists{position: fixed;z-index: 5000;top: 50%;left: 50%;width: 30%; transform: translate(-50%,-50%);
		background:#fff;margin: auto;padding: 4vw;box-sizing: border-box;border-radius: 2vw;height:56%;overflow-y: scroll;}
	#lists::-webkit-scrollbar{display: none;}
	#lists a{line-height: 2.5;display: block;color: #333;}
	.mask{position: fixed;z-index: 1000;top: 0;left: 0;right: 0;bottom:0;background: rgba(0,0,0,0.6);margin: auto;}
	/**/
	.close-img{position: fixed;z-index: 5000;bottom: 10%;left: 50%; transform: translate(-50%,-50%);}
	.close-img img{width: 30px;height: 30px;}
	a{
		color: blue;
	}
</style>
<div class="container-fluid main">
	<div class="main-top"><span  aria-hidden="true"></span>文件列表</div>
		<div class="main-content">
			<div>
				<div>
					<div class="well">
						<div class="btn-group" style="">

						</div><br/>
						<div class="btn-group" style="margin-top:20px;">
							<button type="button" class="btn btn-default">商品名称</button>
							<div class="btn-group">
								<input type="text" id="name" class="form-control">
							</div>
							<button type="button" class="btn btn-warning" onclick="getPage(1)">查询</button>
						</div>
					</div>

				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">列表</a></li>
				</ul>
					<div style="margin-bottom:20px;"><a href="<?php echo url('add_good'); ?>"><button type="button" class="btn btn-default" style="background:#44b549;color:#fff;margin-top: 10px;">新增商品</button></a>&nbsp;&nbsp;抢购开始时间:<?php echo $start; ?>,结束时间:<?php echo $end; ?>&nbsp;&nbsp;<button onclick="setQgTime()">设置时间</button></div>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="home">
						<div id="list">

						</div>
						<div class="pagelist"><div id="demo3"></div></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--弹窗列表-->
	<div id="box">
		<div class="mask"></div>
		<div id="lists">

		</div>
	</div>
	<div class="close-img" onclick="hide()">
		<img src="/static/admin/image/close.png"/>
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
        var name = $("#name").val();
        var index = layer.load(2,{
            shade:[0.6,'#000']
		})
        $.ajax({
            url:"<?php echo url('shop'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
                'name':name,
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
	    //0 表示可以操作  1 表示不可以操作
		str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>序号</th><th>商品名称</th><th>价格</th><th>分类名字</th><th>库存</th><th>销量</th><th>是否39.8元商品</th><th>是否秒杀</th><th>是否推荐</th><th>操作</th>';
		for(i = 0; i < data.length; i++){
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['good_id']+'</td>';
			str += '<td>'+data[i]['good_name']+'</td>';
			str += '<td>'+data[i]['good_price']+'</td>';
			str += '<td>'+data[i]['g_name']+','+data[i]['p_name']+'</td>';
			str += '<td>'+data[i]['number']+'</td>';
			str += '<td>'+data[i]['xiaoliang']+'</td>';
            str += '<td><a onclick="setIsBuy('+data[i]['good_id']+',1)">'+data[i]['is_buy']+'</a></td>';
            str += '<td><a onclick="setIsBuy('+data[i]['good_id']+',2)">'+data[i]['is_miaosha']+'</a></td>';
            str += '<td><a onclick="setIsBuy('+data[i]['good_id']+',3)">'+data[i]['is_new']+'</a></td>';
			str += '<td><button type="button" class="btn btn-warning" onclick="editShop('+data[i]['good_id']+')">修改</button><button type="button" class="btn btn-warning" onclick="delshop('+data[i]['good_id']+',this)">删除</button></td></tr>';


		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>
<script>
	function editShop(id) {
		if(id == ''){
		    layer.msg('数据错误');return;
		}else{
		    location.href = "<?php echo url('edit_good'); ?>?id=" + id;
		}
	}

	function delshop(id,is){
	    if(id == ''){
	        layer.msg('数据有误');
	        return;
		}
        layer.confirm('确定删除当前的记录？', {
            btn: ['是，确认','否，再看看'] //按钮
        }, function(){
            var index = layer.load(2,{
                shade:[0.6,"#000"]
            });
            $.ajax({
                type: "POST",
                url: "<?php echo url('delShop'); ?>",
                dataType: "json",
                data: {"id":id},
                success: function(json){
                    layer.close(index)
                    if(json.code==1){
                        layer.msg('删除成功', {icon: 1});
                        $(is).parent().parent().remove();
                    }else{
                        layer.msg(json.info);
                    }
                },
                error:function(){
                    layer.close(index);
                    layer.msg("发生异常！");
                }
            });
        }, function(){

        });
	}
	function setQgTime(){
        openid = layer.open({
            type: 1,
            skin: '', //加上边框
            area: ['400px', '250px'], //宽高
            content: '<div style="text-align: center;margin-top:10px;"><div ><div ><div>' +
            '<div style="text-align: center;margin-top:10px;"><div ><div ><div ><p>选择开始时间</p>' +
            '<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="start1" type="text"/>' +
            '<p style="margin-top:7px;">选择结束时间</p>' +
            '<input onclick="setDate()" style="border-radius: 4px;border: 1px solid #D9DADC;" id="end1" type="text"/>' +
            '<br/>' +
            '<button style="margin-top: 7px;" type="submit" onclick="setQgTime1()" class="btn btn-default btn-default1" >设置</button>' +
            '</div></div></div></div></div>'
        });
	}
	function setQgTime1(){
        var startTime = $('#start1').val();
        var endTime = $('#end1').val();
        var index = layer.load(2,{
            shade:[0.6,"#000"]
        });
        layer.close(openid);
        $.ajax({
            type: 'post',
            url: "<?php echo url('setQgTime'); ?>",
            dataType: 'json',
            data: {
                'start':startTime,
                'end':endTime
            },
            success: function (json) {
                layer.close(index);
                if(json.code == 1){
                    layer.msg('设置成功');
                    setTimeout(function () {
                        history.go(0);
                    },500);
                }else{
                    layer.msg('')
                }

            },
            error: function () {
                layer.close(index);
                layer.msg('通信通道发生错误！刷新页面重试！');
            }
        });
	}


    function setDate() {
        laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'});
    }

</script>
<script>
    //点击是否抢购
    function setIsBuy(id,type){
        if(id == ''){
            layer.msg('参数错误');
        }else{
            openid = layer.open({
                type: 1,
                skin: '', //加上边框
                closeBtn:1,
                area: ['350px', '200px'], //宽高
                content: '<div style="text-align: center;margin-top:20px;"><div ><div ><input type="radio" value="1" name="is_buy">是<input  style="margin-left: 20px;" type="radio" value="0" name="is_buy">否' +
                '<br/>' +
                '<button style="margin-top: 7px;" type="submit" onclick="setIsBuy1('+id+','+type+')" class="btn btn-default btn-default1" >设置</button>' +
                '</div></div></div>'
            });
        }
    }

    function setIsBuy1(id,type){
        if(id == ''){
            return;
        }else{
            is_buy =  $('input[name="is_buy"]:checked ').val();
            if(is_buy != 1 && is_buy != 0){
                layer.msg('请选择是或否');return;
            }
            var index = layer.load(2,{
                shade:[0.6,"#000"]
            });
            layer.close(openid);
            $.ajax({
                type: 'post',
                url: "<?php echo url('setShopBuy'); ?>",
                dataType: 'json',
                data: {
                    'id': id,
                    'is_buy':is_buy,
					'type':type
                },
                success: function (json) {
                    layer.close(index);
                    if(json.code == 1){
                        layer.msg('设置成功');
                        setTimeout(function () {
                            history.go(0);
                        },500);
                    }else{
                        layer.msg(json.info)
                    }

                },
                error: function () {
                    layer.close(index);
                    layer.msg('通信通道发生错误！刷新页面重试！');
                }
            });
        }
    }
</script>
