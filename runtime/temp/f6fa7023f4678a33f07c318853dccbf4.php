<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"/Users/lijiafei/Documents/fanli/fanli/public/../application/admin/view/lottery/lottery_log.html";i:1523235445;}*/ ?>
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
		<span  aria-hidden="true">积分列表</span>

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
								<input type="text" id="user_id" class="form-control">
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
        var user_id = $("#user_id").val();
        var index = layer.load(2,{
            shade:[0.6,'#000']
		})
        $.ajax({
            url:"<?php echo url('lottery_log'); ?>",
            type:"post",
            dataType:"json",
            data:{
                'page':a,
                'user_id':user_id,
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
	    str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>user_id</th><th>昵称</th><th>积分</th><th>奖品描述</th><th>时间</th>';
		for(i = 0; i < data.length; i++){

            data[i]['create_time'] = new Date(parseInt(data[i]['create_time']) * 1000).toLocaleString().substr(0,20)
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['user_id']+'</td>';
			str += '<td>'+data[i]['nickname']+'</td>';
			str += '<td>'+data[i]['jifen']+'</td>';
			str += '<td>'+data[i]['desc']+'</td>';
            str += '<td>'+data[i]['create_time']+'</td></tr>';
		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>