<?php
/**
 * 用户登录
 * User: lijiafei
 * Date: 2018/4/11
 * Time: 上午10:15
 */
namespace app\home\controller;
use think\Controller;

class Login extends Controller{

    /**
     * 发送手机验证码
     * type 发送短信的类型，1是要注册 2 是要修改  类型不一样，查询的逻辑不一样
     * tel 手机号
     */
    public function sendMsg(){
        $tel = trim(input('post.tel',''));
        $session_name = "code" . $tel;
        $flag = verifyTel($tel);
        if(!$flag){
            $return['code'] = '10001';
            $return['msg'] = '手机号码格式错误';
            return json($return);
        }
        $code = rand(100000,999999);
        $appname = "登录验证码";
        $xigua_verify = cache($session_name);
        $xigua_verify = json_decode($xigua_verify,true);
        if($xigua_verify){
            //判断是否过期
            if(time() - $xigua_verify['time'] < 1800){
                $return['code'] = '10003';
                $return['msg'] = '验证码已发送过,请查看手机短信';
                return json($return);
            }
        }
        $flag = msg_everify($code,$tel,$appname);
        if($flag){
            $data['code'] = $code;
            $data['time'] = time();
            cache($session_name,json_encode($data),1800);
            $return['code'] = '10000';
            $return['msg'] = '验证码发送成功';
            return json($return);
        }else{
            $return['code'] = '10004';
            $return['msg'] = '验证码发送失败';
            return json($return);
        }

    }

    public function login(){
        $tel = trim(input('post.tel',0));
        $msgCode = trim(input('post.msgCode'));
        $p_id = trim(input('post.p_id',0));
        if(!$tel || !$msgCode){
            $return['code'] = 10001;
            $return['data'] = '请填写手机号或验证码';
            return json($return);
        }
        $flag = verifyTel($tel);
        if(!$flag){
            $return['code'] = 10004;
            $return['msg'] = '手机号码格式错误';
            return json($return);
        }
        //判断验证码是否正确
        $session_name = 'code' . $tel;
        $code = cache($session_name);
        if(!$code){
            $return['code'] = 10002;
            $return['data'] = '验证码已过期';
            return json($return);
        }else{
            $code = json_decode($code,true);
            if(time() - $code['time'] > 1800){
                $return['code'] = 10002;
                $return['data'] = '验证码已过期';
                return json($return);
            }
        }
        $code = $code['code'];
        if($msgCode != $code){
            $return['code'] = 10003;
            $return['data'] = '验证码不正确';
            return json($return);
        }
        //判断数据库是否保存数据
        $userInfo = db('user') -> where(['tel' => $tel]) -> find();
        if($userInfo['is_forbid'] == 1){
            $return['code'] = 10003;
            $return['data'] = '账号异常,请联系管理员';
            return json($return);
        }
        if($userInfo){
            $token = session('user_info',$userInfo);
            $return['code'] = 10000;
            $return['data'] = '登录成功';
            $return['token'] = $token;
            return json($return);
        }else{
            //判断是否是第一个人,并判断是否有效,保存在user_number中
            $user = db('user') -> find();
            if(!$user){
                $p_id = 0;
            }else{
                //判断是否有效
                if(!$p_id){
                    $return['code'] = 10005;
                    $return['data'] = '上级手机号不正确';
                    return json($return);
                }
                $pInfo = db('user') -> field('user_id,grade') -> where(['tel' => $p_id,'state' => 1]) -> find();
                if(!$pInfo || $pInfo['grade'] < 1){
                    $return['code'] = 10005;
                    $return['data'] = '上级手机号不正确';
                    return json($return);
                }else{
                    $p_id = $pInfo['user_id'];
                }
            }
            $userInfo['tel'] = $tel;
            $userInfo['p_id'] = $p_id;
            $userInfo['create_time'] = time();
            $userInfo['openid'] = '';
            $userInfo['user_id'] = db('user') -> insertGetId($userInfo);
            setUserNumber($userInfo['user_id']);
            $token = session('user_info',$userInfo);
            $return['code'] = 10000;
            $return['data'] = '登录成功';
            $return['token'] = $token;
            return json($return);
        }
    }

    public function getState(){
        $return['code'] = 10000;
        $return['data'] = is_weixin();
        return json($return);
    }
}