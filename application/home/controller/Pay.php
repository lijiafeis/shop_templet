<?php
/**
 * 微信支付的订单
 * User: lijiafei
 * Date: 2018/4/2
 * Time: 上午11:34
 */
namespace app\home\controller;
class Pay extends Action{

    /**
     * 微信支付订单,前台传递过来订单号和order_type
     * 1. 普通订单 2 秒杀订单 3 199订单
     */
    public function weixin_pay(){
        $order_id = input('post.order_id',0) * 1;
        $order_type = input('post.order_type',0)*1;
        if(!$order_id || !$order_type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        if($order_type != 1 && $order_type != 2 && $order_type != 3){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('shop_order') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['user_id'] != $this->user_id || $orderInfo['state'] != 0 || $orderInfo['order_type'] != $order_type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        //判断支付时间
        if(time() - $orderInfo['create_time'] > 7200){
            $return['code'] = 10001;
            $return['data'] = '订单已过期';
            return json($return);
        }

        //获取openid
        $userInfo = db('user') -> field('openid') -> where(['user_id' => $this->user_id]) -> find();
        if(!$userInfo['openid']){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }

        $notify_url = URL . url('/home/Notify/shop');
        $weixin = controller('wxapi/Weixin');
        $prepay_id = $weixin -> get_prepay_idd($userInfo['openid'],$orderInfo['total_money'] * 100,$orderInfo['order_sn'],$notify_url,$order_id,'shop');
        $arr = $weixin -> paysign($prepay_id);
        $return['code'] = 10000;
        $return['data'] = $arr;
        return json($return);
    }

    /**
     * 支付宝支付订单
     */
    public function alipay_pay(){
        $order_id = input('get.order_id',0) * 1;
        $order_type = input('get.order_type',0)*1;
        if(!$order_id || !$order_type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $html_url = URL . url('home/Alipay/pay') . '?token=' . $order_id . "&type=" . $order_type;
        $this-> redirect($html_url);
    }



}