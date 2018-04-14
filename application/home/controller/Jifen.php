<?php
/**
 * 关于积分商城的内容
 * User: lijiafei
 * Date: 2018/3/30
 * Time: 下午2:54
 */
namespace app\home\controller;
class Jifen extends Action{

    /**
     * 获取积分的商品列表
     */
    public function getJifenShop(){
        $page = input('post.page',1);
        $number1 = input('post.number',10);
        $number = db('goods_jifen') -> count();
        $info = db('goods_jifen')
            -> alias('a')
            -> field('a.id,a.good_name,a.good_price,b.pic_url')
            -> join('wx_good_jifen_pic b','a.id = b.good_id','LEFT')
            -> group('a.id')
            -> page($page,$number1)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $number];
        return json($return);
    }

    /**
     * 通过积分商品的id获取商品的详细信息
     */
    public function getJifenShopById(){
        $good_id = input('post.id',0);
        if(!$good_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $where = array();
        $where['id'] = $good_id;
        //判断是否有这个商品
        $shopInfo = db('goods_jifen')
            -> where($where)
            -> find();
        if(!$shopInfo){
            $return['code'] = 10001;
            $return['msg'] = '数据错误';
            return json($return);
        }
        //查找图片
        $imgUrl = db('good_jifen_pic') -> field('pic_url') -> where(['good_id' => $good_id]) -> column('pic_url');
        $shopInfo['pic_url'] = $imgUrl;
        $return['code'] = 10000;
        $return['info'] = $shopInfo;
        return json($return);
    }

    public function setJifenShopInfo(){
        $good_id = input('post.id',0);
        $number = input('post.number',1);
        if(!$good_id || !$number){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //判断是否有这个商品，同时判断有没有默认的收获地址
        $info = db('goods_jifen')
            -> alias('a')
            -> field('a.id,a.good_name,a.good_price,a.number,b.pic_url')
            -> join('wx_good_jifen_pic b','a.id = b.good_id','LEFT')
            -> where(['a.id' => $good_id])
            -> find();
        if(!$info){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //判断库存
        if($info['number'] < $number){
            $return['code'] = 10002;
            $return['info'] = '库存不足';
            return json($return);
        }
        unset($info['number']);
        $info['yunfei'] = 0;
        $info['good_num'] = $number;
        //查看收获地址
        $address = db('user_address') -> where(['user_id' => $this->user_id,'is_true' => 1]) -> find();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'address' => $address];
        return json($return);
    }

    //获取积分的兑换记录
    /**
     * 获取全部的订单列表
     */
    public function getJiFenOrder(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $where = array();
        $where1 = array();
        $where['user_id'] = $this->user_id;
        $count = db('goods_jifen_order') -> where($where) -> count();
        $orderInfo = db('goods_jifen_order')
            -> alias('a')
            -> field('a.id,a.order_sn,a.jifen,a.state,a.type,a.create_time,a.pay_time,a.good_name,a.good_pic,a.good_num')
            -> where($where1)
            -> page($page,$number)
            -> order('a.id desc')
            -> group('a.id')
            -> select();

        $return['code'] = 10000;
        $return['info'] = ['info' => $orderInfo,'number' => $count];
        return json($return);
    }

    public function getJiFenOrderById(){
        $id = input('post.id',0);
        if(!$id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('goods_jifen_order') -> where(['id' => $id]) -> find();
        unset($orderInfo['user_id']);
        $return['code'] = 10000;
        $return['info'] = $orderInfo;
        return json($return);
    }

    /**
     * 用户收货
     */
    public function setOrderType(){
        $order_id = input('post.id',0);
        if(!$order_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('goods_jifen_order') -> field('id,state,type') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['state'] != 1 || $orderInfo['type'] != 1){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //改变订单的状态
        $res = db('goods_jifen_order') -> where(['id' => $order_id]) -> setField('type',2);
        if($res){
            $return['code'] = 10000;
            $return['info'] = '收获成功';
            return json($return);
        }else{
            $return['code'] = 10002;
            $return['info'] = '收获失败';
            return json($return);
        }
    }

    /**
     * 查看物流
     */
    public function getWuliu(){
        $order_id = input('get.id',0)*1;
        if(!$order_id){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('goods_jifen_order') -> field('state,type,kd_name,kd_number') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['type'] != 1 || $orderInfo['state'] != 1){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $url = "https://www.kuaidi100.com/chaxun?com={$orderInfo['kd_name']}&nu={$orderInfo['kd_number']}";
        header("location:{$url}");

    }

}