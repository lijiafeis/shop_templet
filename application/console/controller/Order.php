<?php
/**
 * Created by PhpStorm.
 * User: lijiafei
 * Date: 2018/3/22
 * Time: 上午10:39
 */
namespace app\console\controller;
use think\Controller;

class Order extends Controller{
    /**
     * 查找两小时create_time和state = 0 未支付的is_true为0 的订单，查询详情，商品，order_type = 3 积分返回，删除finance_log记录
     */
    public function index(){
        if(PHP_SAPI != 'cli'){
            echo '1111';exit;
        }
        $orderInfo = db('shop_order')
            -> field('id,total_money,order_type,create_time,user_id')
            -> where(['state' => 0,'is_true' => 0])
            -> whereTime('create_time','<',time() - 7200)
            -> select();
        if(!$orderInfo){
            return;
        }
        foreach ($orderInfo as $k => $v){
            //根据$v['id']获取goods_order_detail表里的数据，
            $detailInfo = db('shop_order_detail')
                -> field('id,good_id,good_name,good_num')
                -> where(['order_id' => $v['id']])
                -> select();
            if(!$detailInfo){
                break;
            }
            foreach ($detailInfo as $key => $val){
                $res = db('shop_goods') -> field('good_id') -> find($val['good_id']);
                if($res){
                    db('shop_goods') -> where(['good_id' => $val['good_id']]) -> setInc('number',$val['good_num']);
                }
                //db('goods_order_detail') -> delete($v['id']);
            }
            db('shop_order') -> where(['id' => $v['id']]) -> setField('is_true',1);
        }

    }
}