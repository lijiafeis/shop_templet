<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/29 0029
 * Time: 14:45
 */
namespace app\admin\controller;
use think\Request;

class Main extends Action{

    public function index(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else if(Request::instance() -> isPost()){
            $type = input('post.type',1);
            $data = array();
            switch ($type){
                case 1:
                    $order_number = db('shop_order') -> count();
                    $order_pay_number = db('shop_order') -> where(['state' => 1]) ->  count();
                    $order_type_number = db('shop_order') -> where(['state' => 1,'type' => 0]) -> count();
                    $order_type_number1 = db('shop_order') -> where(['state' => 1,'type' => 1]) -> count();
                    $order_type_number2 = db('shop_order') -> where(['state' => 1,'type' => 2]) -> count();
                    $order_money = db('shop_order') -> where(['state' => 1]) -> sum('total_money');
                    $today_money = db('shop_order')
                        -> whereTime('pay_time','>','today')
                        -> where(['state' => 1]) -> sum('total_money');
                    $data['order_number'] = $order_number;
                    $data['order_pay_number'] = $order_pay_number;
                    $data['order_type_number'] = $order_type_number;
                    $data['order_type_number1'] = $order_type_number1;
                    $data['order_type_number2'] = $order_type_number2;
                    $data['order_money'] = $order_money;
                    $data['today_money'] = $today_money;
                    break;
                case 2:
                    $user_money = db('user') -> sum('money');
                    $user_jifen = db('user') -> sum('jifen');
                    $tixian_money = db('user_withdraw_log') -> where(['state' => 0]) ->  sum('money');
                    $tixian_success_money = db('user_withdraw_log') -> where(['state' => 1]) ->  sum('money');
                    $tixian_fail_money = db('user_withdraw_log') -> where(['state' => 2]) ->  sum('money');
                    $data['user_money'] = $user_money;
                    $data['user_jifen'] = $user_jifen;
                    $data['tixian_money'] = $tixian_money;
                    $data['tixian_success_money'] = $tixian_success_money;
                    $data['tixian_fail_money'] = $tixian_fail_money;
                    break;
                case 3:
                    $alipay_money = db('shop_order') -> where(['state' => 1,'pay_type' => 1]) -> sum('total_money');
                    $weixin_money = db('shop_order') -> where(['state' => 1,'pay_type' => 2]) -> sum('total_money');
                    $setwithdrow = db('user_withdraw_log') -> where(['state' => 3]) -> sum('money');
                    $withdrowId = db('user_withdraw_log') -> field('id') -> where(['state' => 3]) -> order('id asc') -> find();
                    $data['alipay_money'] = $alipay_money;
                    $data['weixin_money'] = $weixin_money;
                    $data['setwithdrow'] = $setwithdrow;
                    $data['withdrowId'] = $withdrowId['id'];
                    break;
            }
            return json($data);
        }
    }

}