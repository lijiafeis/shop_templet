<?php
/**
 * 关于升级的处理逻辑
 * User: lijiafei
 * Date: 2018/4/12
 * Time: 上午9:20
 */
namespace app\admin\controller;
use think\Request;

class Upgrade extends Action{

    /**
     * 用户的提现申请
     */
    public function upgrade_sq(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            $map1 = array();
            $map['state'] = 0;
            $map1['a.state'] = 0;
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $count = db('upgrade_log')->where($map)->count();
            $info = db('upgrade_log')
                -> alias('a')
                -> field('a.id,a.user_id,a.old_grade,a.new_grade,a.pic_url,a.create_time,b.nickname')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    /**
     * 改变用户的状态
     */
    public function setUpgradeLogState(){
        $log_id = input('post.id',0);
        $type = input('post.type',0);
        if(!$log_id || !$type){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $info = db('upgrade_log') -> field('state,user_id,new_grade') -> find($log_id);
        if(!$info || $info['state'] != 0){
            $return['code'] = -1;
            $return['info'] = '数据不可改变';
            return json($return);
        }
        if($type == 1){
            //成功
            //判断是否比上级高
            $userInfo = db('user') -> field('grade,p_id') -> where(['user_id' => $info['user_id']]) -> find();
            if($userInfo['p_id']){
                $pGrade = db('user') -> where(['user_id' => $userInfo['p_id']]) -> value('grade');
                if($pGrade < $info['new_grade']){
                    $return['code'] = -1;
                    $return['info'] = '升级级别比上级高';
                    return json($return);
                }
            }
            $updateLog['state'] = 1;
            $updateLog['success_time'] = time();
            $res = db('upgrade_log') -> where(['id' => $log_id]) -> update($updateLog);
            db('user') -> where(['user_id' => $info['user_id']]) -> setField('grade',$info['new_grade']);
            if($res){
                $return['code'] = 1;
                $return['info'] = 'ok';
                return json($return);
            }else{
                $return['code'] = -1;
                $return['info'] = '状态改变失败';
                return json($return);
            }
        }else if($type == 2){
            $updateLog['state'] = 2;
            $updateLog['success_time'] = time();
            $res = db('upgrade_log') -> where(['id' => $log_id]) -> update($updateLog);
            if($res){
                $return['code'] = 1;
                $return['info'] = 'ok';
                return json($return);
            }else{
                $return['code'] = -1;
                $return['info'] = '状态改变失败';
                return json($return);
            }
        }
    }

    public function lookPic(){
        $id = input('post.id',0);
        if(!$id){
            exit;
        }
        $pic_url = db('upgrade_log') -> where(['id' => $id]) -> value('pic_url');
        $pic_url = URL . '/' . $pic_url;
        $return['code'] = 1;
        $return['info'] = $pic_url;
        return $return;
    }

    /**
     * 提现记录
     */
    public function upgrade_log(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            $map1 = array();
            $map['state'] = ['in','1,2'];
            $map1['a.state'] = ['in','1,2'];
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $state = trim(input('post.state'));
            if($state){
                $map['state'] = input('post.state');
                $map1['a.state'] = input('post.state');
            }
            $count = db('upgrade_log')->where($map)->count();
            $info = db('upgrade_log')
                -> alias('a')
                -> field('a.id,a.user_id,a.old_grade,a.new_grade,a.pic_url,a.create_time,a.success_time,a.state,b.nickname')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

}