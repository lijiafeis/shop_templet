<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28 0028
 * Time: 14:59
 * 账号的管理  修改密码
 */
namespace app\admin\controller;
use think\Request;

class Admin extends Action{
    /**
     * 修改账户的密码
     */
    public function updatePassword(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else if(Request::instance() -> isPost()){
            $password = input('post.password');
            $password1 = input('post.password1');
            $password = xgmd5($password);
            $password1 = xgmd5($password1);
            if($password != $password1){
                $this -> error('两次输入的密码不一样','updatePassword');exit;
            }else{
                $res = db('admin') -> where(['id' => $this -> admin_id]) -> setField('password',$password);
                if($res){
                    //修改成功，然后重新登录
                    session('admin_info',null);
                    $this -> success('修改成功',url('User/index'));
                }
            }
        }

    }

    /**
     * 设置短信的信息
     */
    public function msg(){
        if(Request::instance() -> isGet()){
            $info = db('msg') -> find();
            $this -> assign('info',$info);
            return $this -> fetch();
        }else{
            $data['key'] = input('post.key','');
            $data['tel_id'] = input('post.tel_id');
            $id = input('post.id',0);
            if(!$data['key'] || !$data['tel_id']){
                $this -> error('数据有误','msg');exit;
            }else{
                if($id){
                    $res = db('msg') -> where(['id' => $id]) -> update($data);
                }else{
                    $res = db('msg') -> insert($data);
                }
                if($res){
                    cache('msg',null);
                    $this -> success('设置成功','msg');
                }else{
                    $this -> error('设置失败','msg');exit;
                }
            }
        }
    }


    /**
     * 微信参数
     */
    public function config(){
        if($_POST){
            $data['wxname'] = trim(input('post.wxname','','htmlspecialchars'));
            $data['wxid'] = trim(input('post.wxid','','htmlspecialchars'));
            $data['appid'] = trim(input('post.appid','','htmlspecialchars'));
            $data['appsecret'] = trim(input('post.appsecret','','htmlspecialchars'));
            $data['machid'] = trim(input('post.machid','','htmlspecialchars'));
            $data['mkey'] = trim(input('post.mkey','','htmlspecialchars'));
            if(!$data['wxname'] || !$data['wxid'] || !$data['appid'] || !$data['appsecret'] || !$data['machid'] || !$data['mkey']){
                $this -> error('请填写详细信息','config');
            }else{
                $app_id = input('post.app_id',0);
                if($app_id){
                    $res = db('config') -> where(['id' => $app_id]) -> update($data);
                }else{
                    $res = db('config') -> insert($data);
                }
                if($res){
                    $data1[] = $data;
                    cache('config_info',$data1);
                    cache('config',null);
                    $this -> success('设置成功','config');
                }else{
                    $this -> error('信息未更改','config');
                }
            }
        }else{

            $info=db('config')->find();
            $this->assign("info",$info);
            return $this -> fetch();
        }

    }

    /**
     * 菜单设置
     */
    public function menu(){
        $pinfo=db('menu')->where("pid = 0 ")->order('code desc')->select();
        foreach($pinfo as $key => $value){
            $info=db('menu')->where("pid = '$value[id]' ")->order('code desc')->select();
            $res[]=array("title"=>$value['title'],
                "type"=>$value['type'],
                "keyword"=>$value['keyword'],
                "is_show"=>$value['is_show'],
                "url"=>$value['url'],
                "code"=>$value['code'],
                "title"=>$value['title'],
                "list"=>$info,
                "id"=>$value['id']
            );
        }
        $result=db('menu')->field("title,id")->where("pid = 0 and is_show = 1 ")->order('code desc')->select();
        //var_dump($res);exit;
        foreach($result as $key => $ccc){
            $result1=db('menu')->field("title,id")->where("pid = '$ccc[id]' and is_show = 1 ")->order('code desc')->select();
            $ress[]=array("title"=>$ccc['title'],"id"=>$ccc['id'],"list"=>$result1);
        }
        $num=count($ress);
        $this->assign('pinfo',$pinfo);
        $this->assign('num',$num);
        $this->assign('ress',$ress);
        $this->assign('info',$res);
        return $this -> fetch();
    }

    /**
     * 菜单添加
     */
    public function menuadd(){
        if($_POST){
            if($_POST['pid'] == 0){
                $info=db('menu')->where(['pid' => 0,'is_show' => 1])->count();
                if($info >= 3){
                    $this->error('一级菜单数量已经达到上限');
                    exit;
                }
            }
            $data=$this->check($_POST);
            $res=db('menu')->insert($data);
            if($res){
                $this->success("添加成功","menu");
            }else{
                $this->error('添加失败','menu');
            }

        }
    }

    function menusave(){
        $pinfo=db('menu')->where("pid = 0 and is_show = 1 ")->order('code desc')->select();
        foreach($pinfo as $key => $value){
            $arr="";
            $res=db('menu')->where("pid = $value[id] and is_show = 1 ")->order('code desc')->select();
            if($res){
                foreach($res as $kk=>$vv){
                    $arr[]=array("type"=>$vv['type'],"name"=>urlencode($vv['title']),"url"=>urlencode($vv['url']),"key"=>urlencode($vv['keyword']));
                }
                $arr1[]=array("name"=>urlencode($value['title']),
                    "sub_button"=>$arr
                );
            }else{
                $arr1[]=array("type"=>$value['type'],"name"=>urlencode($value['title']),"url"=>urlencode($value['url']),"key"=>urlencode($value['keyword']));
            }
        }
        $botton=array("button"=>$arr1);
        $aaa=urldecode(json_encode($botton));
        $weixin = controller('wxapi/Weixin');
        $info=$weixin->send_menu($aaa);
        //var_dump($info);exit;
        if($info['errmsg'] == "invalid button name size"){
            $this->error("菜单按钮字数过多");
        }elseif($info['errmsg'] == "ok"){
            $this->success("成功更新自定义菜单",url('menu'));
        }else{
            $this->error("出现错误，错误原因:".$info['errmsg'].$info['errcode']);
        }
    }

    /**
     * 删除按钮
     */
    public function deletemenu(){
        $menu_id = input('post.id',0)*1;
        if(!$menu_id) {
            $arr['success']=0;
            return json($arr);
        }else{
            $res = db('menu')->where(['id' => $menu_id])->delete();
            if($res){
                $arr['success']=1;
            }else{
                $arr['success']=0;
            }

            return json($arr);
        }
    }

    function menuedit(){
        if($_POST){
            $data=$this->check($_POST);
            $res=db('menu')->where(['id' => input('post.id',null)])->update($data);
            // var_dump($res);exit;
            $this -> success('设置成功','menu');
        }else{
            //var_dump($_GET);
            $menu_id=input('get.menu_id',0);;
            if(!$menu_id){
                $this -> error('数据错误','menu');exit;
            }
            $info=db('menu')->where(['id' => $menu_id])->select();
            if(!$info){
                $this -> error('数据错误','menu');exit;
            }
            $pid=$info[0]['pid'];
            $pidtitle=db('menu')->field("title,id")->where(['id' => $pid])->select();
            $pinfo=db('menu')->field("title,id")->where("pid = 0 ")->select();
            $pinfonum=db('menu')->where("pid = 0 ")->count();
            $ppinfo="";
            foreach($pinfo as $key=>$value){
                if($value['id'] != $pid){
                    $ppinfo[]=$value;
                }
            }
            if(!$pidtitle){
                $pidtitle[0]['title'] = '';
            }
            $this->assign("pidtitle",$pidtitle);
            $this->assign("info",$info);
            $this->assign("pinfo",$ppinfo);
            $this->assign("pinfonum",$pinfonum);
            return $this -> fetch();
        }
    }

    private function check($arr){
        if($arr['type'] == "click"){
            $arr['url']="";
        }elseif($arr['type'] == "view"){
            $arr['keyword']="";
        }elseif($arr['type'] == "scancode_push"){
            $arr['keyword']="rselfmenu_0_1";
            $arr['url']="";
        }elseif($arr['type'] == "scancode_waitmsg"){
            $arr['keyword']="rselfmenu_0_0";
            $arr['url']="";
        }elseif($arr['type'] == "pic_sysphoto"){
            $arr['keyword']="rselfmenu_1_0";
            $arr['url']="";
        }elseif($arr['type'] == "pic_photo_or_album"){
            $arr['keyword']="rselfmenu_1_1";
            $arr['url']="";
        }elseif($arr['type'] == "pic_weixin"){
            $arr['keyword']="rselfmenu_1_2";
            $arr['url']="";
        }elseif($arr['type'] == "location_select"){
            $arr['keyword']="rselfmenu_2_0";
            $arr['url']="";
        }
        return $arr;

    }

    /**
     * 关注回复
     */
    public function subscribe(){

        if($_POST){
            $data = array(
                'content'=>$_POST['content'],
                //'keyword'=>$_POST['keyword'],
            );
            $subscribe = db('subscribe');
            $info = $subscribe ->select();
            if($info != null){
                $id = $info[0]['id'];
                $res = $subscribe->where(" id = '$id' ")->update($data);
            }else{
                $res = $subscribe ->insert($data);
            }
            if($res){
                $this->success("保存成功",'subscribe');
            }else{
                $this->error("未发现数据更改，保存失败",'subscribe');
            }
        }else{
            $info = db('subscribe')->select();
            if(!$info){
                $info = '';
            }
            $this->assign("info",$info);
            return $this->fetch();
        }

    }

    public function text(){
        if(Request::instance() -> isGet()){
            return $this->fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $count=db('text')-> count();
            $info = db('text')
                -> order('id desc')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }

    }

    /**
     * 回复修改添加
     */
    public function textadd(){
        if($_POST){
            $_POST['keyword'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$_POST['keyword']);
            if($_POST['id']){
                $id = $_POST['id']*1;
                $data = array(
                    'keyword'=>$_POST['keyword'],
                    'content'=>$_POST['content'],
                    'keyword_type'=>$_POST['keyword_type']
                );
                $res = db('text')->where(['id' => $id])->update($data);
                if($res){
                    $this->success("修改成功",'text');
                }else{
                    $this->error("未发现数据更改，保存失败",'text');
                }
            }else{
                $data = $_POST;
                $data['createtime'] = time();
                $res = db('text')->insert($data);
                if($res){
                    $this->success("添加成功",'text');
                }else{
                    $this->error("添加失败",'text');
                }
            }

        }else{
            if(isset($_GET['text_id'])){
                $id = $_GET['text_id']*1;
                $info = db('text')->where(['id' => $id])->select();
                $this->assign("info",$info);
            }else{
                $info[0]['keyword'] = '';
                $info[0]['content'] = '';
                $info[0]['createtime'] = '';
                $info[0]['click'] = '';
                $info[0]['keyword_type'] = '';
                $info[0]['id'] = '';
                $this->assign("info",$info);
            }
            return $this->fetch();
        }
    }

    /**
     * 删除回复
     */
    function deltext(){
        $text_id = $_POST['id']*1;
        $res = db('text')->where(['id' => $text_id])->delete();
        if($res){
            $arr['success']=1;
        }else{
            $arr['success']=0;
        }

        return json($arr);
    }


    /**
     * 图文回复
     */

    public function news(){
        $info = db('news')->order("id desc") ->select();
        // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $news_comment = db('news_comment');
        foreach ($info as $key => $value) {
            $info[$key]['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
            $new_id = $value['id'];
            $info[$key]['comment'] = $news_comment->where(['new_id' => $new_id])->count();
        }

        $this->assign('info',$info);
        return $this->fetch();
    }


    function newsadd(){

        if($_POST){
            $data = $_POST;
            if(!isset($data['pic_show'])){$data['pic_show'] = 0;}
            $data['keyword'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$data['keyword']);

            $data['create_time'] = time();
            $res = db('news')->insert($data);
            if($res){
                $this -> success('添加成功',url('Admin/news'));
            }else{
                $this -> error('添加失败',url('Admin/newsadd'));
            }

        }else{

            return $this->fetch();
        }

    }


    function newsedit(){

        if($_POST){
            $data = $_POST;
            if(!isset($data['pic_show'])){$data['pic_show'] = 0;}
            $data['keyword'] = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$data['keyword']);
            $res = db('news')->where(['id'=>$data['id']])->update($data);
            if($res){
                $this -> success('修改成功',url('Admin/news'));
            }else{
                $this -> error('修改失败',url('Admin/newsadd'));
            }

        }else{
            $news_id = input('get.news_id',0)*1;
            if(!$news_id){exit;}
            $info = db('news')->where(['id'=>$news_id])->select();
            $this->assign("info",$info);
            return $this->fetch();
        }

    }

    function delnews(){

        $news_id = $_POST['id'];
        $res = db('news')->where(['id' => $news_id])->delete();
        if($res){
            $data['code'] = 10000;
            $data['msg'] = '删除成功';
            return json($data);

        }else{
            $data['code'] = 10001;
            $data['msg'] = '失败';
            return json($data);

        }
    }

    public function set_qr(){
        if(Request::instance() -> isPost()){
            if($_POST['qr'] == null){
                $res = model('qrset') -> allowField(true) -> save($_POST);
            }else{
                $res = model('qrset') -> allowField(true) -> save($_POST,['id' => $_POST['qr']]);
            } //
            rmAllDir(ROOT_PATH . 'public'  . '/uploads/qr/img/');
            $this->success("保存成功",'set_qr');
        }else{
            $qrset = db('qrset') -> select();
            $this->assign("qrset",$qrset);
            return $this->fetch();
        }
    }
}