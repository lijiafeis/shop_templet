<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"/Users/lijiafei/Documents/project/fanli1/public/../application/admin/view/admin/updatepassword.html";i:1522286340;}*/ ?>
<link rel="stylesheet" href="/static/admin/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/admin/css/base.css">
<style>
    .jifen{
        border: 0px;
        border-radius: 4px;
        padding:10px;
        width: 250px;
        height: 30px;
    }
</style>
<div class="container-fluid main">
    <div class="main-top"><span></span>设置密码</div>
    <div class="main-content well" style="height: 700px;">
        <h5 class="alert alert-success" style="padding:5px 10px;line-height:30px;">设置密码</h5>
        <div class="form-horizontal" style="height: 400px">
            <form action="<?php echo url('updatePassword'); ?>"  method="post" onsubmit="return checkPass()">

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">密码：</label>
                    <div class="col-sm-6">

                        <input class="jifen" type="password" id="password" name="password" class="inputstyle"  value=""  placeholder="" />
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <label for="inputEmail3" class="col-sm-2 control-label">重新输入密码：</label>
                    <div class="col-sm-6">
                        <input class="jifen" type="password" id="password1" name="password1" class="inputstyle"  value=""  placeholder="" />
                    </div>
                </div>
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">修改</button>
                </div>
                <input type="hidden" name="id" value=""/>
            </form>

        </div>
    </div>
</div>
<script src="/static/admin/js/jquery.js"></script>
<script src="/static/admin/js/bootstrap.min.js"></script>
<script src="/static/admin/layer/layer.js"></script>
<script>
 function checkPass(){
     password = $("#password").val();
     password1 = $("#password1").val();
//     alert(password);
//     alert(password1);
     if(password != password1){
         layer.msg('两次输入的密码不一样');
         return false;
     }
     return true;
 }
</script>