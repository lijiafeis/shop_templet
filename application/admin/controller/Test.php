<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 09:23
 */
namespace app\admin\controller;
use think\Controller;

class Test extends Controller{
    public function index(){
        $token = 215468;
        dump($token / 10);
    }
    public function test(){
//        $user_id = 6;
//        $pInfo = db('user_number') -> where(['user_id' => $user_id]) -> find();
//        dump($pInfo);
//        $pInfo = db('user_number') -> field('user_id',true) -> where(['user_id' => $user_id]) -> find();
//        dump($pInfo);
//        //等级奖励
//        unset($pInfo['user_id']);
//        $pInfo = array_diff($pInfo,[0]);
//        $pInfo = implode(',',$pInfo);
//        $userInfo = db('user')
//            -> field('user_id,type')
//            -> where(['user_id' => ['in',$pInfo],'type' => ['in','2,3,4,5']])
//            -> select();
//        dump($userInfo);
        $str = "0.1,0.5";
        $a = "/^.+,.+$/";
        $is_true = preg_match($a,$str);
        dump($is_true);

    }
}