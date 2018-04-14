<?php
/**
*   系统的一些配置
 */
namespace app\admin\controller;

use think\Request;

class Config extends Action{

    /**
     * 系统的参数配置
     */
    public function config(){
        if(Request::instance() -> isGet()){
            $data = db('web_config') -> find();
            $this -> assign('info',$data);
            return $this -> fetch();
        }else{
            $id = intval(input('post.id',0));
            $data = input('post.');
            if(!$id){
                $res = model('web_config') -> insertGetId($data);
            }else{
                $res = model('web_config') -> allowField(true) -> save($data,['id' => $id]);
            }
            if($res){
                cache('web_config',null);
                $this -> success('成功','config');
            }else{
                $this -> error('失败','config');
            }
        }
    }


    public function img_list(){
        $img = db('img');
        if(Request::instance() -> isGet()){
            $info  = $img -> order("id desc") -> where(['type' => 1]) -> select();
            $this ->assign("info",$info);
            return $this -> fetch();
        }else{
            //新添加的记录，有文件才可以
            if($_FILES['path']['error'] == 0){
                $data['path'] = $this -> uploadFile();
                $data['url'] = input('post.url','');
                $data['type'] = 1;
                $res = db('img') -> insert($data);
                if($res){
                    $this -> success('上传成功','img_list');
                }else{
                    $this -> error('上传错误','img_list');
                }
                exit;
            }else{
                $this -> error('请选择图片','img_list');

            }
        }
    }
        //删除商城的首页的图片
    function del_shop_bannar(){
        $id = input('id',0);
        if(!$id){
            exit;
        }
        $path = db('img') -> field('path') -> where(['id' => $id]) -> find();
        $a = ROOT_PATH . 'public'  . $path['path'];
        @unlink($a);
        db('img') -> where(['id' => $id]) -> delete();
        return json(1);
    }



    /**
     * 文件上传
     */
    private function uploadFile(){
        $name = 'imglist';
        $file = request()->file('path');
        $path1 = "/uploads/" . $name;
        $info = $file-> validate(['size'=>1000000,'ext'=>'jpg,png,gif']) -> move(ROOT_PATH . 'public' . DS . 'uploads' . DS . $name,'');
        if($info){
            $path = $path1 . '/' . str_replace("\\","/",$info->getSaveName());
            return $path;
        }else{
            $a = $file-> getError();
            $this -> error('上传文件出错','img_list');
        }
    }




}