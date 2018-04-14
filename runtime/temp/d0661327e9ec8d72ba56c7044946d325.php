<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/news.html";i:1522830634;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

<link rel="stylesheet" href="/static/admin/css/font-awesome.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<div class="container-fluid main">
    <div class="main-top"><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>微信管理</div>
    <div class="main-content well">




        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">您可以在这里创建一些必要的关键词进行自动的快捷回复</h5>

        <div style="margin-bottom:20px;"><a href="<?php echo url('newsadd'); ?>"><button type="button" class="btn btn-warning">新增图文回复</button></a></div>
        <table class="table table-striped table-hover" style="font-size:14px;">
            <th>编号</th>
            <th>关键词</th>
            <th>图文标题</th>
            <th>创建时间</th>
            <th>调用</th>
            <th>转发</th>
            <th>阅读</th>
            <th>点赞</th>
            <!--<th>评论</th>-->
            <th>操作</th>
            <?php if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $kk = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($kk % 2 );++$kk;?>
                <tr>
                    <td><?php echo $kk; ?></td>
                    <td><?php echo $vv['keyword']; ?></td>
                    <td><button class="btn btn-link"><?php echo $vv['title']; ?></button></td>
                    <td><?php echo $vv['create_time']; ?></td>
                    <td><?php echo $vv['click']; ?></td>
                    <td><?php echo $vv['share']; ?></td>
                    <td><?php echo $vv['read_num']; ?></td>
                    <td><?php echo $vv['zan']; ?></td>
                    <!--<td><a href="<?php echo url('news_comment'); ?>?new_id=<?php echo $vv['id']; ?>"><?php echo $vv['comment']; ?></a></td>-->
                    <td><a href="<?php echo url('newsedit'); ?>?news_id=<?php echo $vv['id']; ?>">
                        <button type="button" class="btn btn-warning btn-sm">修改</button></a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="del(this,<?php echo $vv['id']; ?>)">删除</button></td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </div>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
    function del(obj,id){


        layer.confirm('删除后无法恢复，确认删除吗', {
            btn: ['确认','取消'] //按钮
        }, function(){
            $.ajax({
                type: "POST",
                url: "<?php echo url('admin/Admin/delnews'); ?>",
                dataType: "json",
                data: {
                    "id":id,
                },
                success: function(json){
                    if(json.code == 10000){
                        layer.msg("删除成功");
                        var td = $(obj).parent();
                        td.parent().css("display","none");
                    }else{
                        layer.msg(json.msg);
                    }
                },
                error:function(){
                }
            });
        }, function(){

        });
    }

</script>
