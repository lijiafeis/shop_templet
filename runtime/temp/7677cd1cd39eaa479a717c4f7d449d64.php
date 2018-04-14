<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/index/left.html";i:1523677660;}*/ ?>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/iconfont/iconfont.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<script src="/static/admin/js/jquery.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
	$('.menu_title').click(function(){
		$(this).attr('data','1');
		$('.menu_title').each(function(){
			if($(this).attr('data') == 1){
				$(this).css('color','#fff');$(this).css('background','#44b549');$(this).find('i').css('color','#fff');
			}else{
				$(this).css('color','#555');$(this).css('background','#fff');$(this).find('i').css('color','#555');
			}
		});
		var obj = $(this).next('dd');
		if(obj.css("display") == 'block'){
			obj.css("display","none");$(this).attr('data','0');
		}else{
			$('dd').each(function(){
				$(this).css("display","none");
			});
			obj.css("display","block");
			$(this).attr('data','0');
		}
	});
	$('.menu dl dd ul li').click(function(){
		$('.menu dl dd ul li').each(function(){
			$(this).css('background','');
			$(this).css('color','');
		});
		$(this).css('background','#44b549');
		$(this).css('color','#fff');
	});
	
});
</script>
<style>
.menu_title:hover{background:#44b549;color:#fff;font-size:15px;}
.menu_title:hover i{color:#fff;}
.iconfont{font-weight:normal;}
</style>
<div class="left" oncontextmenu=self.event.returnValue=false onselectstart="return false">
  <div class="menu">
    <dl>
      <dt class="menu_title" data='0'><i class="icon iconfont add">&#xe668;</i>　账号设置</dt>
      <dd style="display:none;">
        <ul>
            <a href="<?php echo url('Admin/updatePassword'); ?>" target="main-frame"><li>修改密码</li></a>
            <a href="<?php echo url('Admin/msg'); ?>" target="main-frame"><li>信息设置</li></a>
            <a href="<?php echo url('Admin/config'); ?>" target="main-frame"><li>微信参数</li></a>
            <!--<a href="<?php echo url('Admin/menu'); ?>" target="main-frame"><li>菜单设置</li></a>-->
            <!--<a href="<?php echo url('Admin/subscribe'); ?>" target="main-frame"><li>关注回复</li></a>-->
            <!--<a href="<?php echo url('Admin/text'); ?>" target="main-frame"><li>文本回复</li></a>-->
            <!--<a href="<?php echo url('Admin/news'); ?>" target="main-frame"><li>图文回复</li></a>-->
            <a href="<?php echo url('Admin/set_qr'); ?>" target="main-frame"><li>二维码</li></a>
        </ul>
      </dd>
    </dl>
      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont add">&#xe668;</i>　系统配置</dt>
          <dd style="display:none;">
              <ul>
                  <a href="<?php echo url('Config/img_list'); ?>" target="main-frame"><li>首页图片</li></a>
                  <a href="<?php echo url('Config/config'); ?>" target="main-frame"><li>参数配置</li></a>

              </ul>
          </dd>
      </dl>
      <dl>
          <dt class="menu_title" data='0'><i class="icon iconfont add">&#xe668;</i>　商品管理</dt>
          <dd style="display:none;">
              <ul>
                  <a href="<?php echo url('Shop/category'); ?>" target="main-frame"><li>商品分类</li></a>
                  <a href="<?php echo url('Shop/type'); ?>" target="main-frame"><li>属性管理</li></a>
                  <a href="<?php echo url('Shop/shop'); ?>" target="main-frame"><li>商品管理</li></a>
                  <a href="<?php echo url('Order/order'); ?>" target="main-frame"><li>订单管理</li></a>
                  <a href="<?php echo url('Jifen/jifen'); ?>" target="main-frame"><li>积分商品</li></a>
                  <a href="<?php echo url('Jifen/order'); ?>" target="main-frame"><li>积分订单</li></a>
                  <a href="<?php echo url('Order/libao_order'); ?>" target="main-frame"><li>礼包订单</li></a>
              </ul>
          </dd>
      </dl>
	<dl>
      <dt class="menu_title"><i class="icon iconfont">&#xe617;</i>　会员管理</dt>
      <dd  style="DISPLAY: none">
        <ul>
          <a href="<?php echo url('Member/users'); ?>" target="main-frame"><li>会员列表</li></a>
        </ul>
      </dd>
    </dl>
      <dl>
          <dt class="menu_title"><i class="icon iconfont">&#xe617;</i>　大转盘</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo url('Lottery/lottery_list'); ?>" target="main-frame"><li>奖项</li></a>
                  <a href="<?php echo url('Lottery/lottery_log'); ?>" target="main-frame"><li>中奖纪录</li></a>
              </ul>
          </dd>
      </dl>
      <dl>
          <dt class="menu_title"><i class="icon iconfont">&#xe617;</i>　资金列表</dt>
          <dd  style="DISPLAY: none">
              <ul>
                  <a href="<?php echo url('Money/money_log'); ?>" target="main-frame"><li>金额记录</li></a>
                  <a href="<?php echo url('Money/jifen_log'); ?>" target="main-frame"><li>积分记录</li></a>
                  <a href="<?php echo url('Upgrade/upgrade_sq'); ?>" target="main-frame"><li>申请审核</li></a>
                  <a href="<?php echo url('Upgrade/upgrade_log'); ?>" target="main-frame"><li>申请记录</li></a>
                  <a href="<?php echo url('Money/withdraw_sq'); ?>" target="main-frame"><li>提现申请</li></a>
                  <a href="<?php echo url('Money/withdraw_log'); ?>" target="main-frame"><li>提现记录</li></a>
              </ul>
          </dd>
      </dl>

  <dl>
      <dt class="menu_title" data='0'><i class="icon iconfont">&#xe65a;</i>　统计</dt>
      <dd  style="DISPLAY: none">
          <ul>
              <a href="<?php echo url('Main/Index'); ?>" target="main-frame"><li>统计</li></a>
          </ul>
      </dd>
  </dl>
  </div>
  <div style="font-size:12px;color:#999;text-align:center;">Created By 郑州西瓜科技</div>
</div>
