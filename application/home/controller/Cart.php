<?php
/**
 * 购物车的信息
 * User: lijiafei
 * Date: 2018/3/30
 * Time: 上午11:39
 */
namespace app\home\controller;
class Cart extends Action{

    /**
     * 点击详情页的立即购买
     * 当type = 1 的时候
     */
    public function setShopInfo(){
        $good_id = input('post.good_id',0);
        $number = input('post.number',1);
        $type_info = input('post.type_info','');
        $type = input('post.type',0);
        if(!$good_id || !$number || !$type){
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
        //判断是否有这个商品，同时判断有没有默认的收获地址
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,a.is_buy,a.is_miaosha,a.type_id,a.number,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> where(['a.good_id' => $good_id])
            -> find();
        if(!$info){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        if($info['type_id']){
            if(!$type_info){
                $return['code'] = 10002;
                $return['info'] = '请选择属性';
                return json($return);
            }
        }
        //判断库存
        if($info['number'] < $number){
            $return['code'] = 10003;
            $return['info'] = '库存不足';
            return json($return);
        }
        //根据type判断订单是什么类型的
        switch ($type){
            case 1:
                //普通订单
                if($info['is_miaosha'] == 1 || $info['is_buy'] == 1){
                    $return['code'] = 10001;
                    $return['info'] = '数据错误';
                    return json($return);
                }

                $info['yunfei'] = 0;
                break;
            case 2:
                //秒杀订单,运费为后台设置,价格为0
                if($info['is_miaosha'] != 1){
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
                $info['yunfei'] = $config['yunfei'];
                break;
            case 3:
                //199订单
                if($info['is_buy'] != 1){
                    $return['code'] = 10001;
                    $return['info'] = '数据错误';
                    return json($return);
                }
                $info['yunfei'] = 0;
                break;
        }

        $info['good_num'] = $number;
        $info['type_info'] = $type_info;
        //查看收获地址
        $address = db('user_address') -> where(['user_id' => $this->user_id,'is_true' => 1]) -> find();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'address' => $address,'type' => $type];
        return json($return);
    }


    /**
     * 添加商品到购物车
     */
    public function addShopToCart(){
        $good_id = input('post.good_id',0);
        $number = intval(input('post.number',1));
        $type_info = input('post.type_info','');
        if(!$good_id || !$number || $number <= 0){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $goodInfo = db('shop_goods') -> field('good_id,number,type_id,is_buy,is_miaosha') -> find($good_id);
        if(!$goodInfo || $goodInfo['is_buy'] == 1 || $goodInfo['is_miaosha'] == 1){
            $return['code'] = 10001;
            $return['info'] = '数据错误,不能加入购物车';
            return json($return);
        }
        //判断库存
        if($goodInfo['number'] < $number){
            $return['code'] = 10002;
            $return['info'] = '当前商品库存不足';
            return json($return);
        }
        if($goodInfo['type_id']){
            if(!$type_info){
                $return['code'] = 10003;
                $return['info'] = '请选择属性';
                return json($return);
            }
        }
        //可以加入购物车
        //判断当前购物车是否有这种商品，有数量增加，没有新增
        $res = db('shop_order_temp') -> field('id') -> where(['user_id' => $this->user_id,'good_id' => $good_id,'type' => $type_info]) -> find();
        if($res){
            $re = db('shop_order_temp') -> where(['id' => $res['id']]) -> setInc('good_num',$number);
        }else{
            $insertInfo['user_id'] = $this->user_id;
            $insertInfo['good_id'] = $good_id;
            $insertInfo['good_num'] = $number;
            $insertInfo['type'] = $type_info;
            $re = db('shop_order_temp') -> insertGetId($insertInfo);
        }
        if($re){
            $return['code'] = 10000;
            $return['info'] = '加入购物车成功';
            return json($return);
        }else{
            $return['code'] = 10004;
            $return['info'] = '加入失败';
            return json($return);
        }
    }

    /**
     * 查找购物车的商品信息,把有库存的和总价格计算后返回去
     * 总价格  =  good_price * good_num;
     */
    public function getAllTemp(){
        $info = db('shop_order_temp')
            -> alias('a')
            -> field('id,good_id,good_num,type as type_info')
            -> where(['user_id' => $this->user_id])
            -> select();
        //根据查找的good_id查找商品的信息，并查看库存是否足够
        if($info){
            foreach ($info as $k => $v){
                $goodInfo = db('shop_goods')
                    -> alias('a')
                    -> field('a.good_id,a.good_name,a.good_price,a.is_buy,a.is_miaosha,a.number,b.pic_url')
                    -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
                    -> where(['a.good_id' => $v['good_id']])
                    -> find();
                if(!$goodInfo || $goodInfo['is_buy'] == 1 || $goodInfo['is_miaosha'] == 1){
                    unset($info[$k]);
                }else{
                    if($goodInfo['number'] < $v['good_num']){
                        $info[$k]['info'] = '库存不足';
                        $info[$k]['good_name'] = $goodInfo['good_name'];
                        $info[$k]['good_price'] = $goodInfo['good_price'];
                        $info[$k]['pic_url'] = $goodInfo['pic_url'];
                    }else{
                        $info[$k]['info'] = '';
                        $info[$k]['good_name'] = $goodInfo['good_name'];
                        $info[$k]['good_price'] = $goodInfo['good_price'];
                        $info[$k]['pic_url'] = $goodInfo['pic_url'];
                    }
                }

            }
        }
        $info = array_values($info);
        $return['code'] = 10000;
        $return['info'] = $info;
        return json($return);
    }

    /**
     * 删除购物车的内容
     */
    public function delTempShop(){
        $id = input('post.id',0);
        if(!$id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $info = db('shop_order_temp') -> where(['id' => $id]) -> find();
        if(!$info || $info['user_id'] != $this->user_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $res = db('shop_order_temp') -> delete($id);
        $return['code'] = 10000;
        $return['info'] = '删除成功';
        return json($return);
    }

    /**
     * 添加购物车的数量
     */
    public function setCartNumber(){
        $id = input('post.id',0);
        $good_id = input('post.good_id',0);
        $number = input('post.number',0);
        if(!$id || !$number || $number <= 0 || !$good_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //判断库存是否足够
        $shopInfo = db('shop_goods') -> field('number') -> where(['good_id' => $good_id]) -> find();
        if(!$shopInfo){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        if($shopInfo['number'] < $number){
            $return['code'] = 10002;
            $return['info'] = '库存不足';
            return json($return);
        }
        //修改购物车的内容
        $res = db('shop_order_temp') -> where(['id' => $id]) -> setField('good_num',$number);
        if($res){
            $return['code'] = 10000;
            $return['info'] = 'ok';
            return json($return);
        }else{
            $return['code'] = 10003;
            $return['info'] = '失败';
            return json($return);
        }

    }

    /**
     *从购物车调转到这里，展示商品页让用户确认，然后提交订单
     */
    public function showAllShop(){
        //传递过来购物车的id
        $idList = input('post.idList','');
        $tempInfo = db('shop_order_temp') -> where(['id' => ['in',$idList]]) -> select();
        if(!$tempInfo){
            $return['code'] = 10001;
            $return['info'] = '请选择商品';
            return json($return);
        }
        $info = array();
        foreach ($tempInfo as $k => $v){
            $goodInfo = db('shop_goods')
                -> alias('a')
                -> field('a.good_id,a.good_name,a.good_price,a.number,b.pic_url')
                -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
                -> where(['a.good_id' => $v['good_id']])
                -> find();
            if($goodInfo){
                if($goodInfo['number'] > $v['good_num']){
                    $goodInfo['good_num'] = $v['good_num'];
                    $goodInfo['type_info'] = $v['type'];
                    //判断是否是复购商品
                    unset($goodInfo['number']);
                    $info[] = $goodInfo;
                }else{
                    $return['code'] = 10002;
                    $return['info'] = '商品[' . $goodInfo['good_name'] . ']库存不足';
                    return json($return);
                }

            }
        }
        //查看收获地址
        $address = db('user_address') -> where(['user_id' => $this->user_id,'is_true' => 1]) -> find();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'address' => $address,'type' => 1,'yunfei' => 0];
        return json($return);
    }



}