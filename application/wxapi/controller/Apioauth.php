<?php
/**
 * 前后端分离的时候  授权接口
 * Date: 2018/3/30
 * Time: 上午9:12
 */
namespace app\wxapi\controller;
use \think\Controller;
class ApiOauth extends Controller{

    public function _initialize()
    {
        parent::_initialize();
        //获取公众号的配置信息
        $config = cache('config');
        if(!$config){
            $config = db('config') -> find();
            cache('config',$config);
        }
        $this->appid = $config['appid'];
        $this->appsecret = $config['appsecret'];
    }

    /**
     *通过code获取access_token和openid信息
     */
    public function getToken(){
        $code = input('code',0);
        if(!$code){
            $return['code'] = 10001;
            $return['data'] = 'code错误';
            return json($return);
        }
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->appid}&secret={$this->appsecret}&code={$code}&grant_type=authorization_code";
        $res = http_request($url);
        $data = json_decode($res,true);
        if(!$data['access_token']){
            $return['code'] = 10002;
            $return['data'] = '授权失败';
            return json($return);
        }
        //获取到access_token和openid,然后通过openid等 获取用户的基本信息
        $user_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN';
        $user_info = http_request($user_url);

        $user_res = json_decode($user_info,true);
        if(!$user_res['openid']){
            $return['code'] = 10002;
            $return['data'] = '授权失败';
            return json($return);
        }
        $data1 = [
            'openid'=>$user_res['openid'],
            'nickname' => $user_res['nickname'],
            "headimgurl" =>$user_res['headimgurl']
        ];
        $userInfo = db('user') -> where(['openid' => $data1['openid']]) -> find();
        if($userInfo){

        }else{
            $user_info = session('user_info');
            if(!$user_info){
                $return['code'] = 10003;
                $return['data'] = '授权失败';
                return json($return);
            }
            db('user')->where(['user_id' => $user_info['user_id']])->update($data1);
            $userInfo = db('user') -> find($user_info['user_id']);
        }
        $session_id = session('user_info',$userInfo);
        $return['code'] = 10000;
        $return['data'] = '授权成功';
        $return['token'] = $session_id;
        return json($return);

    }

    public function test(){
        $weixin = controller('wxapi/Weixin');
        $weixin -> get_qr_limit(1);
    }



}