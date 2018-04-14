<?php
/**
    抽奖的后台处理
 * User: lijiafei
 * Date: 2018/4/8
 * Time: 下午2:03
 */
namespace app\admin\controller;
use think\Request;

class Lottery extends Action{

    /**
     * 奖项的设置
     */
    public function lottery_list(){
        if(Request::instance() -> isGet()){
            $info = db('lottery_list') -> order('code asc') -> select();
            $this -> assign('info',$info);
            return $this -> fetch();
        }else{
            $data['is_win'] = input('post.is_win',1);
            $data['desc'] = input('post.desc','');
            //判断是否有六张图片
            $number = db('lottery_list') -> count();
            if($number >= 6){
                $this -> error('最多有六张图片','lottery_list');
            }
            $data['code'] = input('post.code',0)*1;
            if(!$data['code']){
                $this -> error('code不能为空','lottery_list');
            }
            if($data['code'] > 6 || $data['code'] < 1){
                $this -> error('code格式不正确','lottery_list');
            }
            //判断是否存在
            $is_true = db('lottery_list') -> where(['code' => $data['code']]) -> find();
            if($is_true){
                $this -> error('code已经存在','lottery_list');
            }
            //新添加的记录，有文件才可以
            if($_FILES['path']['error'] == 0){
                $data['img'] = $this -> uploadFile();
            }else{
                $this -> error('请选择图片','lottery_list');
            }
            $res = db('lottery_list') -> insert($data);
            if($res){
                $this -> success('成功','lottery_list');
            }else{
                $this -> error('失败','lottery_list');
            }
            exit;
        }
    }

    /**
     * 删除图片
     */
    public function del_lottery_img(){
        $id = input('post.id',0);
        if(!$id){
            exit;
        }
        $res = db('lottery_list') -> delete($id);
        $return['code'] = 1;
        return json($return);

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

    /**
     * 记录的显示
     */
    public function lottery_log(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            $map1 = array();
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $count = db('lottery_log')->where($map)->count();
            $info = db('lottery_log')
                -> alias('a')
                -> field('a.user_id,a.jifen,a.desc,a.create_time,b.nickname')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> order('a.id desc')
                -> group('a.id')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }
}