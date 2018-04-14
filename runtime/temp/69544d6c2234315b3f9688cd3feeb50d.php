<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/upgrade/upgrade_sq.html";i:1523667597;}*/ ?>
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
            url:"<?php echo url('upgrade_sq'); ?>",
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
	    str += '<table class="table table-striped"  style="font-size:14px;margin-top: 20px;"><th>user_id</th><th>昵称</th><th>旧级别</th><th>新级别</th><th>图片</th><th>创建时间</th><th>操作</th>';
		for(i = 0; i < data.length; i++){
		    if(data[i]['old_grade'] == 1){
                data[i]['old_grade'] = 'VIP';
			}else if(data[i]['old_grade'] == 2){
                data[i]['old_grade'] = '省代';
			}else if(data[i]['old_grade'] == 3){
				data[i]['old_grade'] = '总代';
			}else if(data[i]['old_grade'] == 4){
				data[i]['old_grade'] = '官方';
			}else if(data[i]['old_grade'] == 5){
				data[i]['old_grade'] = '联创';
			}else if(data[i]['old_grade'] == 0){
				data[i]['old_grade'] = '普通会员';
			}
			if(data[i]['new_grade'] == 1){
				data[i]['new_grade'] = 'VIP';
			}else if(data[i]['new_grade'] == 2){
				data[i]['new_grade'] = '省代';
			}else if(data[i]['new_grade'] == 3){
				data[i]['new_grade'] = '总代';
			}else if(data[i]['new_grade'] == 4){
				data[i]['new_grade'] = '官方';
			}else if(data[i]['new_grade'] == 5){
				data[i]['new_grade'] = '联创';
			}else if(data[i]['new_grade'] == 0){
				data[i]['new_grade'] = '普通会员';
			}
            data[i]['create_time'] = new Date(parseInt(data[i]['create_time']) * 1000).toLocaleString().substr(0,20)
        	str += '<tr style="font-size:13px;" class="data"><td>'+data[i]['user_id']+'</td>';
			str += '<td>'+data[i]['nickname']+'</td>';
			str += '<td>'+data[i]['old_grade']+'</td>';
			str += '<td>'+data[i]['new_grade']+'</td>';
			str += "<td><a onclick='lookPic("+data[i]['id']+")'>查看图片</a></td>";
            str += '<td>'+data[i]['create_time']+'</td>';
            str += '<td><button type="button" class="btn btn-warning" onclick="setLogState(1,'+data[i]['id']+',this)">成功</button><button type="button" class="btn btn-warning" onclick="setLogState(2,'+data[i]['id']+',this)">驳回</button></td></tr>';
		}
		str += '</table>';
		$('#list').empty();
		$('#list').append(str);
	}
</script>
<script>
	function setLogState(type,log_id,is){
	    if(log_id == '' || type == ''){
	        layer.msg('请刷新页面');return;
		}
        layer.confirm('确定改变状态？', {
            btn: ['是，确认','否，再看看'] //按钮
        }, function(){
            var index = layer.load(2,{
                shade:[0.6,"#000"]
            });
            $.ajax({
                type: "POST",
                url: "<?php echo url('setUpgradeLogState'); ?>",
                dataType: "json",
                data: {"id":log_id,'type':type},
                success: function(json){
                    layer.close(index)
                    if(json.code==1){
                        layer.msg('成功', {icon: 1});
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

	/**
	 * 点击查看图片
	 * @param token
	 */
	function lookPic(id){
		if(id == ''){
			layer.msg('数据错误');return;
		}
		var index = layer.load(2,{
			shade:[0.6,"#000"]
		});
		$.ajax({
			type: "POST",
			url: "<?php echo url('lookPic'); ?>",
			dataType: "json",
			data: {"id":id},
			success: function(json) {
				layer.close(index);
				if (json.code == 1) {
					layer.open({
						type: 1,
						title: false,
						closeBtn: 0,
						area: ['460px', '560px'],
						skin: 'layui-layer-nobg', //没有背景色
						shadeClose: true,
						content: '<img style="width: 460px;height: 560px;" src="'+json.info+'"/>'
					});
				} else {
					layer.msg(json.info);
				}
			},
			error:function() {
				layer.close(index);
				layer.msg('发生异常!');
			}
		});
	}
</script>