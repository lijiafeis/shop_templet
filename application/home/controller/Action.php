<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/5 0005
 * Time: 13:40
 */
namespace app\home\controller;
use think\Controller;

class Action extends Controller{
    // /*用户来，判断session存不存在，*/
    function __construct()
    {
        header('Access-Control-Allow-Origin:*');

        parent::__construct();
        if (!session('user_info')) {
            $token = input('token');
            if (!$token) {
                $arr['code'] = 99999;
                $arr['data'] = '请先登录';
                echo json_encode($arr);
                exit;
            }
            session_id(input('token'));
            if (!session('user_info')) {
                $arr['code'] = 99999;
                $arr['data'] = '请先登录';
                echo json_encode($arr);
                exit;
            }
        }
        $this->user = session('user_info');
        $this->user_id = $this->user['user_id'];
        //判断是否在微信中
        $is_weixin = is_weixin();
        if($is_weixin){
            //判断是否有openid nickname headimgurl等信息
            if(!$this->user['openid']){
                $openid = db('user') -> where(['user_id' => $this->user_id]) -> value('openid');
                if(!$openid){
                    //授权
                    $config = cache('config');
                    if(!$config){
                        $config = db('config') -> find();
                        cache('config',$config);
                    }
                    $appid = $config['appid'];
                    $arr['code'] = 11111;
                    $arr['data'] = '请先授权';
                    $arr['id'] = $appid;
                    echo json_encode($arr);
                    exit;
                }
            }

        }

    }


}