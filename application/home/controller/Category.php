<?php
/**
 * 分类的信息接口
 * Date: 2018/3/30
 * Time: 上午10:55
 */
namespace app\home\controller;
class Category extends Action{

    /**
     * 获取所有分类的信息
     */
    public function getCateInfo(){
        $category = db('shop_category') -> field('cate_id,cate_name,pic_url') -> where(['pid' => 0]) -> order('code desc') -> select();
        foreach ($category as $k => $v){
            $info = db('shop_category')
                -> field('cate_id,cate_name,pic_url')
                -> where(['pid' => $v['cate_id']])
                -> select();
            $category[$k]['cateInfo'] = $info;
        }
        $return['code'] = 10000;
        $return['data'] = $category;
        return json($return);
    }

    /**
     * 通过分类id获取商品列表
     */
    public function getShopByCateId(){
        $cate_id = input('post.cate_id',0);
        if(!$cate_id){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $page = input('post.page',1);
        $number = input('post.number',10);
        $where = array();
        $where['is_buy'] = 0;
        $where['is_miaosha'] = 0;
        $where['cate_pid'] = $cate_id;
        $count = db('shop_goods') -> where($where) -> count();
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,a.xiaoliang,a.number,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> where($where)
            -> group('a.good_id')
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }

    /**
     * 获取抢购时间
     */
    public function getQgTime(){
        //获取抢购商品列表,判断是否到时间
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        $data = json_decode($config['buy_time'],true);
        $return['code'] = 10000;
        $return['info'] = $data;
        return json($return);
    }

    /**
     * 获取秒杀或推荐的商品信息
     */
    public function getOtherShop(){
        $type = input('post.type',0);
        if(!$type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $page = input('post.page',1);
        $number = input('post.number',10);
        $where = array();
        if($type == 1){
            //获取秒杀商品
            $where['is_miaosha'] = 1;
        }else if($type == 2){
            //获取推荐商品
            $where['is_new'] = 1;
        }else{
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $count = db('shop_goods') -> where($where) -> count();
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,a.xiaoliang,a.number,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> where($where)
            -> group('a.good_id')
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }

    /**
     * 通过商品id获取商品信息
     *
     */
    public function getShopById(){
        $good_id = intval(input('post.good_id',0));
        if(!$good_id){
            $return['code'] = 10001;
            $return['msg'] = '数据错误';
            return json($return);
        }
        $where = array();
        $where['good_id'] = $good_id;
        //判断是否有这个商品
        $shopInfo = db('shop_goods')
            -> where($where)
            -> find();
        if(!$shopInfo || $shopInfo['is_buy'] == 1){
            $return['code'] = 10001;
            $return['msg'] = '数据错误';
            return json($return);
        }
        //查找图片
        $imgUrl = db('shop_goods_pic') -> field('pic_url') -> where(['good_id' => $good_id]) -> column('pic_url');
        $shopInfo['pic_url'] = $imgUrl;
        //获取属性的信息
        $type_info = db('shop_spec') -> field('spec_name,value') -> where(['type_id' => $shopInfo['type_id']]) -> select();
        $shopInfo['type_info'] = $type_info;
        if($shopInfo['is_miaosha']){
            //判断当前时间是否是抢购时间
            $config = cache('web_config');
            if(!$config){
                $config = db('web_config') -> find();
                cache('web_config',$config);
            }
            $time = json_decode($config['buy_time'],true);
            if(time() > $time['end'] || time() < $time['start']){
                $return['code'] = 10003;
                $return['msg'] = '还未到秒杀时间';
                return json($return);
            }
            $shopInfo['good_price'] = 0;
        }
        $return['code'] = 10000;
        $return['info'] = $shopInfo;
        return json($return);
    }

    /**
     * 获取199产品的列表
     */
    public function getOtherShopList(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $where = array();
        $where['is_buy'] = 1;
        $count = db('shop_goods') -> where($where) -> count();
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,a.xiaoliang,a.number,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> where($where)
            -> group('a.good_id')
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }


    /**
     * 获取199的特殊产品
     */
    public function getOtherShopInfo(){
        $where = array();
        $good_id = input('post.id',0);
        if(!$good_id){
            $return['code'] = 10001;
            $return['msg'] = '数据错误';
            return json($return);
        }
        $where['good_id'] = $good_id;
        //判断是否有这个商品
        $shopInfo = db('shop_goods')
            -> where($where)
            -> find();
        if(!$shopInfo || $shopInfo['is_buy'] != 1){
            $return['code'] = 10001;
            $return['msg'] = '无商品';
            return json($return);
        }
        //查找图片
        $imgUrl = db('shop_goods_pic') -> field('pic_url') -> where(['good_id' => $shopInfo['good_id']]) -> column('pic_url');
        $shopInfo['pic_url'] = $imgUrl;
        //获取属性的信息
        $type_info = db('shop_spec') -> field('spec_name,value') -> where(['type_id' => $shopInfo['type_id']]) -> select();
        $shopInfo['type_info'] = $type_info;
        $return['code'] = 10000;
        $return['info'] = $shopInfo;
        return json($return);
    }

    public function getShopByName(){
        $good_name = input('post.good_name','');
        if(!$good_name){
            $return['code'] = 10001;
            $return['msg'] = '数据错误';
            return json($return);
        }
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,a.xiaoliang,a.number,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> whereOr('a.good_name','LIKE','%' . $good_name)
            -> whereOr('a.good_name','LIKE','%' . $good_name . '%')
            -> whereOr('a.good_name','LIKE',$good_name . '%')
            -> whereOr(['a.good_name' => $good_name])
            -> group('a.good_id')
            -> select();
        $return['code'] = 10000;
        $return['data'] = $info;
        return json($return);
    }

}