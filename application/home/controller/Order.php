<?php
/**
 * 关于订单的接口
 * User: lijiafei
 * Date: 2018/3/30
 * Time: 下午3:07
 */
namespace app\home\controller;
class Order extends Action{

    /**
     * 不是积分商品的点击提交订单,跳转到这里 good_id number type_info
     * 1,普通订单 2 秒杀订单 3 199订单
     */
    public function createOrder(){
        $infoList = input('post.list',0);
        $infoList = json_decode($infoList,true);
        $address_id = input('post.address_id',0);
        $type = input('post.type',0);
        if(!$infoList || !$address_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        if($type != 1 && $type != 2 && $type != 3){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        if($type != 3){
            //是普通商品或者是秒杀商品,判断state是否购买过会员产品
            $state = db('user') -> where(['user_id' => $this->user_id]) -> value('state');
            if(!$state){
                $return['code'] = 10004;
                $return['info'] = '请先购买39.8产品';
                return json($return);
            }
        }
        $address_info = db('user_address') -> where(['address_id' => $address_id]) -> find();
        if(!$address_info || $address_info['user_id'] != $this->user_id || $address_info['is_true'] != 1){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }else{
            $address_str = $address_info['username'] . ',' . $address_info['telphone'] . ',' . $address_info['address'] . ',';
            if($address_info['city']){
                $address = $address_info['city'];
                $address = json_decode($address,true);
                $str = '';
                foreach ($address as $k => $v){
                    $str .= $v['name'];
                }
                $address_str .= $str;
            }else{
            }

        }
        $orderInfo['address_info'] = $address_str;
        $detail_id = array();
        $total_money = 0;//商品的总价格
        //判断商品是否有库存,保存订单到goods_order_detail和goods_order表中和表中,减少商品的库存
        foreach ($infoList as $k => $v){
            //判断是否有这个商品，同时判断有没有默认的收获地址
            $goodInfo = db('shop_goods')
                -> alias('a')
                -> field('a.*,b.pic_url')
                -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
                -> where(['a.good_id' => $v['good_id']])
                -> find();
            if(!$goodInfo){
                exit;
            }
            if($goodInfo['number'] < $v['number']){
                $return['code'] = 10002;
                $return['info'] = '商品[' . $goodInfo['good_name'] . ']库存不足';
                return json($return);
            }
            //库存足够
            $detail = array();
            $detail['user_id'] = $this->user_id;
            $detail['good_id'] = $goodInfo['good_id'];
            $detail['good_name'] = $goodInfo['good_name'];
            $detail['good_num'] = $v['number'];
            $detail['pic_url'] = $goodInfo['pic_url'];
            $detail['create_time'] = time();
            $detail['type'] = $v['type_info'];
            $detail['order_type'] = $type;
            //根据type计算价格
            if($type == 1){
                if($goodInfo['is_buy'] == 1 || $goodInfo['is_miaosha'] == 1){
                    $return['code'] = 10001;
                    $return['info'] = '数据错误';
                    return json($return);
                }
                $detail['good_danjia'] = $goodInfo['good_price'];
                $detail['good_price'] = $goodInfo['good_price'] * $v['number'];
            }else if($type == 2){
                if($goodInfo['is_miaosha'] != 1){
                    $return['code'] = 10001;
                    $return['info'] = '数据错误';
                    return json($return);
                }
                $info['good_price'] = 0;
                //判断当前时间是否是抢购时间
                $config = cache('web_config');
                if(!$config){
                    $config = db('web_config') -> find();
                    cache('web_config',$config);
                }
                $detail['good_danjia'] = $config['yunfei'];
                $detail['good_price'] = $config['yunfei'] * $v['number'];
            }else if($type == 3){
                if($goodInfo['is_buy'] != 1){
                    $return['code'] = 10001;
                    $return['info'] = '数据错误';
                    return json($return);
                }
                $detail['good_danjia'] = $goodInfo['good_price'];
                $detail['good_price'] = $goodInfo['good_price'] * $v['number'];
            }
            $total_money += $detail['good_price'];
            $id = db('shop_order_detail') -> insertGetId($detail);
            $detail_id[] = $id;
        }
        $detail_id = implode(',',$detail_id);
        //保存信息到order表中
        $orderInfo['user_id'] = $this->user_id;

        $orderInfo['order_sn'] = $this->user_id . time();
        $orderInfo['total_money'] = $total_money;
        $orderInfo['content'] = input('post.content','');
        $orderInfo['create_time'] = time();
        $orderInfo['order_type'] = $type;
        $order_id = db('shop_order') -> insertGetId($orderInfo);
        db('shop_order_detail') -> where(['id' => ['in',$detail_id]]) -> setField('order_id',$order_id);
        foreach ($infoList as $k => $v){
            db('shop_goods') -> where(['good_id' => $v['good_id']]) -> setDec('number',$v['number']);
        }
        $return['code'] = 10000;
        $return['data'] = $order_id;
        return json($return);
    }



    /**
     * 创建积分订单good_id number
     */
    public function createJiFenOrder(){
        $good_id = input('post.good_id');
        $good_num = input('post.good_num',1);
        $address_id = input('post.address_id');
        $content = input('post.content','');
        if(!$good_id || !$good_num || !$address_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $goodInfo = db('goods_jifen')
            -> alias('a')
            -> field('a.*,b.pic_url')
            -> join('wx_good_jifen_pic b','a.id = b.good_id','LEFT')
            -> where(['a.id' => $good_id])
            -> find();
        if(!$goodInfo){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        if($goodInfo['number'] < $good_num){
            $return['code'] = 10002;
            $return['info'] = '库存不足';
            return json($return);
        }

        $orderInfo['user_id'] = $this->user_id;
        $address_info = db('user_address') -> where(['address_id' => $address_id]) -> find();
        if(!$address_info || $address_info['user_id'] != $this->user_id || $address_info['is_true'] != 1){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }else{
            $address_str = $address_info['username'] . ',' . $address_info['telphone'] . ',' . $address_info['address'] . ',';
            if($address_info['city']){
                $address = $address_info['city'];
                $address = json_decode($address,true);
                $str = '';
                foreach ($address as $k => $v){
                    $str .= $v['name'];
                }
                $address_str .= $str;
            }else{
            }

        }
        $orderInfo['address_info'] = $address_str;
        $orderInfo['order_sn'] = $this->user_id . time();
        $orderInfo['jifen'] = $goodInfo['good_price'] * $good_num;
        $orderInfo['content'] = $content;
        $orderInfo['create_time'] = time();
        $orderInfo['good_id'] = $good_id;
        $orderInfo['good_name'] = $goodInfo['good_name'];
        $orderInfo['good_num'] = $good_num;
        $orderInfo['good_pic'] = $goodInfo['pic_url'];

        //判断当前用户积分是否足够
        $userInfo = db('user') -> field('jifen') -> where(['user_id' => $this->user_id]) -> find();
        $jifen = $userInfo['jifen'];
        if($orderInfo['jifen'] > $jifen){
            $return['code'] = 10003;
            $return['data'] = '积分不足';
            return json($return);
        }
        //扣除用户的积分并添加记录
        $model = db();
        $model -> startTrans();
        try{
            db('user') -> where(['user_id' => $this->user_id]) -> setDec('jifen',$orderInfo['jifen']);
            //记录积分
            $updateJifen['number'] = ['exp','number-' . $good_num];
            $updateJifen['xiaoliang'] = ['exp','xiaoliang + ' . $good_num];
            db('goods_jifen') -> where(['id' => $good_id]) -> update($updateJifen);
            $orderInfo['state'] = 1;
            $orderInfo['pay_time'] = time();
            $order_id = db('goods_jifen_order') -> insertGetId($orderInfo);
            finance_log($this->user_id,$orderInfo['jifen'],2,11,0,$order_id);
            //调起支付支付
            $model -> commit();
            $return['code'] = 10000;
            $return['data'] = '兑换成功';
            return json($return);
        }catch (\Exception $e){
            $model -> rollback();
            file_put_contents('./test_dir/jifenOrder.txt',$e,FILE_APPEND);
            $return['code'] = 10004;
            $return['data'] = '购买失败,请稍后重试';
            return json($return);
        }
    }

    /**
     * 获取全部的订单列表
     */
    public function getAllOrder(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $where = array();
        $where1 = array();
        $where['user_id'] = $this->user_id;
        $where1['a.user_id'] = $this->user_id;
        $where['is_true'] = 0;
        $where1['a.is_true'] = 0;
        $count = db('shop_order') -> where($where) -> count();
        $orderInfo = db('shop_order')
            -> alias('a')
            -> field('a.id,a.order_sn,a.create_time,a.total_money,a.create_time + 7200 as end_time,a.order_type,a.state,a.type,b.good_name,b.pic_url,b.good_num,count(b.id) as number,b.good_price')
            -> join('wx_shop_order_detail b','a.id = b.order_id','left')
            -> where($where1)
            -> page($page,$number)
            -> order('a.id desc')
            -> group('a.id')
            -> select();

        $return['code'] = 10000;
        $return['info'] = ['info' => $orderInfo,'number' => $count];
        return json($return);
    }

    /**
     * 获取用户的订单列表,前台传递type 1 未付款 2 未发货 3 未收货 4 已完成订单
     * state 0 未支付 1 支付
     * type 0 未发货 1 发货 2 收获
     */
    public function getOrderList(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $type = input('post.type',0);
        $where = array();
        $where1 = array();
        $where['user_id'] = $this->user_id;
        $where1['a.user_id'] = $this->user_id;
        $where['is_true'] = 0;
        $where1['a.is_true'] = 0;
        if($type == 1){
            $where['state'] = 0;
            $where1['a.state'] = 0;
        }else if($type == 2){
            $where['state'] = 1;
            $where['type'] = 0;
            $where1['a.state'] = 1;
            $where1['a.type'] = 0;
        }else if($type == 3){
            $where['state'] = 1;
            $where['type'] = 1;
            $where1['a.state'] = 1;
            $where1['a.type'] = 1;
        }else if($type == 4){
            $where['state'] = 1;
            $where['type'] = 2;
            $where1['a.state'] = 1;
            $where1['a.type'] = 2;
        }
        $count = db('shop_order') -> where($where) -> count();
        $orderInfo = db('shop_order')
            -> alias('a')
            -> field('a.id,a.order_sn,a.create_time,a.total_money,a.create_time + 7200 as end_time,a.order_type,b.good_name,b.pic_url,b.good_num,count(b.id) as number,b.good_price')
            -> join('wx_shop_order_detail b','a.id = b.order_id','left')
            -> where($where1)
            -> page($page,$number)
            -> order('a.id desc')
            -> group('a.id')
            -> select();

        $return['code'] = 10000;
        $return['info'] = ['info' => $orderInfo,'number' => $count];
        return json($return);
    }

    /**
     * 通过订单id获取订单的所有内容
     */
    public function getOrderAllInfo(){
        $order_id = input('post.order_id',0);
        if(!$order_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $order_info = db('shop_order') -> field('id,address_info,order_sn,total_money,content,state,type,kd_name,kd_number,create_time,pay_time,create_time + 7200 as end_time,order_type') -> find($order_id);
        if($order_info){
            $order_info['detail'] = db('shop_order_detail') -> field('good_name,good_danjia,good_price,good_num,pic_url,create_time,order_type') -> where(['order_id' => $order_id]) -> select();
        }
        $return['code'] = 10000;
        $return['info'] = $order_info;
        return json($return);
    }

    /**
     * 用户取消订单
     */
    public function closeOrder(){
        $order_id = input('post.order_id',0);
        if(!$order_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $order_info = db('shop_order') -> find($order_id);
        if(!$order_info || $order_info['state'] != 0){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //增加库存
        $detailInfo = db('shop_order_detail') -> field('id,good_id,good_num') -> where(['order_id' => $order_id]) -> select();
        foreach ($detailInfo as $k => $v){
            $res = db('shop_goods') -> field('good_id') -> find($v['good_id']);
            if($res){
                db('shop_goods') -> where(['good_id' => $v['good_id']]) -> setInc('number',$v['good_num']);
            }
            db('shop_order_detail') -> delete($v['id']);
        }
        db('shop_order') -> delete($order_id);
        $return['code'] = 10000;
        $return['info'] = '取消成功';
        return json($return);
    }

    /**
     * 用户收货
     */
    public function setOrderType(){
        $order_id = input('post.order_id',0);
        if(!$order_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('shop_order') -> field('id,state,type,order_type') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['state'] != 1 || $orderInfo['type'] != 1){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //改变订单的状态
        $res = db('shop_order') -> where(['id' => $order_id]) -> setField('type',2);
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
     * 用户查看三种状态几个
     */
    public function getOrderNumber(){
        $a['a'] = db('shop_order')
            -> where(['user_id' => $this->user_id,'state' => 0,'is_true' => 0])
            -> count();
        $a['b']  = db('shop_order')
            -> where(['user_id' => $this->user_id,'state' => 1,'type' => 0])
            -> count();
        $a['c'] = db('shop_order')
            -> where(['user_id' => $this->user_id,'state' => 1,'type' => 1])
            -> count();
        $return['code'] = 10000;
        $return['info'] = $a;
        return json($return);
    }

    /**
     * 查看物流
     */
    public function getWuliu(){
        $order_id = input('get.order_id',0)*1;
        if(!$order_id){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('shop_order') -> field('state,type,kd_name,kd_number') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['type'] != 1 || $orderInfo['state'] != 1){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $url = "https://www.kuaidi100.com/chaxun?com={$orderInfo['kd_name']}&nu={$orderInfo['kd_number']}";
        header("location:{$url}");

    }
























}