<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:88:"/Users/lijiafei/Documents/fanli/fanli/public/../application/admin/view/member/users.html";i:1522317514;}*/ ?>
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
		color: blue;;
	}
	.main-top button{width:50px;height:34px;margin-left:1555px;}

	/*点击显示业绩 */
	.yeji{
		margin-left: 20px;
		margin-top: 10px;
	}
	.yeji span{
		margin-left: 40px;
	}
</style>
<div class="container-fluid main">
	<div class="main-top">
		<span  aria-hidden="true">会员列表</span>

	</div>
		<div class="main-content">
			<div>
				<div>
					
					<div class="well">
					
						<div class="btn-group" style="">
						</div><br/>
						<div class="btn-group" style="margin-top:20px;">
							<button type="button" class="btn btn-default">用户id</button>
							<div class="btn-group">
								<input type="text" id="id" class="form-control">
							</div>
							<button type="button" class="btn btn-default">昵称</button>
							<div class="btn-group">
								<input type="text" id="nickname" class="form-control">
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
			</div>
		</div>
	</div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script src="/static/admin/layui/layui.js" charset="utf-8"></script>
<script>
	getPage(1);
	function getPage(a) {
        var user_id = $("#id").val();
        var nickname = $("#nickname").val();
        var index = layer.load(2,{
            shade:[0.6,'#000']
		})
        $.ajax({
            url:"<?php echo url('users'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
                'user_id':user_id,
                'nickname':nickname,
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
	    str = '';
	    str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>user_id</th><th>昵称</th><th>余额</th><th>积分</th><th>父级id</th><th>等级</th><th>业绩</th><th>创建时间</th>';
		for(i = 0; i < data.length; i++){
		    if(data[i]['type'] == 1){
		        data[i]['type'] = '代言人';
			}else if(data[i]['type'] == 2){
                data[i]['type'] = '小V';
			}else if(data[i]['type'] == 3){
                data[i]['type'] = '大V';
            }else if(data[i]['type'] == 0){
                data[i]['type'] = '无';
            }else if(data[i]['type'] == 4){
                data[i]['type'] = '官方创始人';
            }else if(data[i]['type'] == 5){
                data[i]['type'] = '联合创始人';
            }
            data[i]['create_time'] = new Date(parseInt(data[i]['create_time']) * 1000).toLocaleString().substr(0,20)
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['user_id']+'</td>';
			str += '<td>'+data[i]['nickname']+'</td>';
			str += '<td>'+data[i]['money']+'</td>';
			str += '<td>'+data[i]['jifen']+'</td>';
			str += '<td>'+data[i]['p_id']+'</td>';
            str += '<td>'+data[i]['type']+'</td>';
            str += '<td><a onclick="setLook('+data[i]['user_id']+')">查看</a></td>';
            str += '<td>'+data[i]['create_time']+'</td></tr>';
		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>
<script>
	var open = '';
	function setLook(user_id){
	    if(user_id == ''){
	        layer.msg('请刷新页面');return;
		}
        var index = layer.load(2,{
            shade:[0.6,"#000"]
        });
        $.ajax({
            url:"<?php echo url('getUserYeji'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'user_id':user_id,
            },
            success:function (data){
                layer.close(index);
                if(data.code == 1){
                    layer.open({
                        type: 1,
						title:'业绩',
                        skin: 'layui-layer-molv', //样式类名
                        area: ['250px', '250px'],
                        closeBtn: 0, //不显示关闭按钮
                        anim: 4,
                        shadeClose: true, //开启遮罩关闭
                        content: '<div class="yeji">'
							+ '<div>总业绩:</div><span>'+data.z_yeji+'</span>'
							+ '<div>当月业绩:</div><span>'+data.month_yeji+'</span></div>'
                    });
                }else{
                    layer.msg(data.info);
                }
            },
            error:function (data) {
                layer.close(index);
                layer.msg('网络错误,请稍后重试');
            }
        });

	}
</script>