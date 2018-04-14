<?php
/**
 * 商城的首页
 * Date: 2018/3/30
 * Time: 上午10:50
 */
namespace app\home\controller;
class Index extends Action{

    /**
     * 获取首页的轮播图
     */
    public function getShowImg(){
        $info = db('img') -> field('path,url') -> where(['type' => 1]) ->  select();
        $return['code'] = 10000;
        $return['info'] = $info;
        return json($return);
    }

    /**
     * 获取推荐商品的接口
     */
    public function getShopList(){
        //获取后台的添加的现金的商品
        $page = input('post.page',1);
        $number1 = input('post.number',10);
        $number = db('shop_goods') -> where(['is_new' => 1]) -> count();
        $info = db('shop_goods')
            -> alias('a')
            -> field('a.good_id,a.good_name,a.good_price,b.pic_url')
            -> join('wx_shop_goods_pic b','a.good_id = b.good_id','LEFT')
            -> where(['a.is_new' => 1])
            -> group('a.good_id')
            -> page($page,$number1)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $number];
        return json($return);
    }

}