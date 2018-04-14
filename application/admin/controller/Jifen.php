<?php
/**
 * 积分商城积分和积分订单
 * Date: 2018/3/28 0028
 * Time: 10:51
 */
namespace app\admin\controller;
use think\Request;

class Jifen extends Action{

    public function jifen(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $name = input('post.name','','htmlspecialchars');
            $where = array();
            $where1 = array();
            if($name){
                $where['good_name'] = $name;
                $where1['a.good_name'] = $name;
            }
            $count=db('goods_jifen') -> where($where) -> count();
            $info = db('goods_jifen')
                -> alias('a')
                -> field('a.*')
                -> where($where1)
                -> order('a.id desc')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }


    public function delJifen(){
        $good_id = input('id',0);
        if(!$good_id){
            $info['code'] = -1;
            $info['info'] = '数据错误';
            return json($info);
        }
        $arr = array();
        //删除所有的图片信息
        db('good_jifen_pic') -> where(['good_id' => $good_id]) -> delete();
        $res = db('goods_jifen') -> where(['id' => $good_id]) -> delete();
        if($res){$arr['code'] = 1;}else{$arr['info'] = 0;}
        return json($arr);
    }

    public function add_jifen(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $data['good_name'] = input('post.good_name','');
            $data['good_price'] = input('post.good_price',0);
            $data['xiaoliang'] = input('post.xiaoliang',0);
            $data['number'] = input('post.number',0);
            $data['good_desc'] = input('post.good_desc','');
            if(!$data['good_name'] || !$data['good_price'] || !$data['number']){
                $this -> error('格式不正确','add_good');
            }
            $shop_goods = db('goods_jifen');$good_pic = db('good_jifen_pic');
            $good_id = $shop_goods -> insertGetId($data);
            if($data && $good_id){
                $arr = array_keys(input('post.'));
                foreach($arr as $val){
                    if(strstr($val,'pic')){
                        if(input($val)){
                            $good_pic->insert(array('good_id'=>$good_id,'pic_url'=>input($val)));
                        }
                    }
                }
            }
            $this->success("添加积分成功",'jifen');exit;
        }

    }

    /**
     * 修改积分信息
     */
    public function edit_jifen(){
        if(Request::instance() -> isGet()){
            $good_id = input('get.id',0);
            if(!$good_id){
                $this -> error('数据错误','shop');
            }else{
                //查询这个积分的信息
                $data = db('goods_jifen') -> find($good_id);
                //查询这个积分的图片信息
                $imgList = db('good_jifen_pic') -> where(['good_id' => $good_id]) -> select();
                $this -> assign('good_info',$data);
                $this -> assign('imgList',$imgList);
                return $this -> fetch();
            }
        }else{
            $shop_goods = db('goods_jifen');$good_pic = db('good_jifen_pic');$good_id =input('id',0);
            if(!$good_id){
                $this -> error('数据错误','jifen');
            }else{
                $data['good_name'] = input('post.good_name','');
                $data['good_price'] = input('post.good_price',0);
                $data['xiaoliang'] = input('post.xiaoliang',0);
                $data['number'] = input('post.number',0);
                $data['good_desc'] = input('post.good_desc','');
                if(!$data['good_name'] || !$data['good_price'] || !$data['number']){
                    $this -> error('格式不正确','add_jifen');
                }
                $arr = array_keys(input('post.'));
                foreach($arr as $val){
                    if(strstr($val,'pic')){
                        if(input($val)){
                            $good_pic->insert(array('good_id'=>$good_id,'pic_url'=>input($val)));
                        }
                    }
                }
                $shop_goods -> where(['id' => $good_id]) -> update($data);
                $this->success("已更新积分信息",'jifen');
            }
        }

    }

    public function del_jifen_pic(){
        $good_pic = db('good_jifen_pic');
        $id = input('post.id',0);
        if(!$id){
            echo json_encode(-1);
        }
        $good_pic -> where(['id' => $id]) -> delete();
        $arr = array();echo json_encode($arr);
    }

    /**
     * 关于订单的接口
     */
    public function order(){
        if(Request::instance() -> isGet()){
            $page = input('get.page',1);
            $this -> assign('page',$page);
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $user_id = input('post.user_id','','htmlspecialchars');
            $order_sn = input('post.order_sn','','htmlspecialchars');
            $type = input('post.type','','htmlspecialchars');
            $where = array();
            $where1 = array();
            if($user_id){
                $where['user_id'] = $user_id;
//                $where1['a.user_id'] = $user_id;
            }
            if($order_sn){
                $where['order_sn'] = trim($order_sn);
//                $where1['a.order_sn'] = $order_sn;
            }
            if($type != -1){
                $where['type'] = $type;
                $where['state'] = 1;
//                $where1['a.type'] = $type;
            }
            $count=db('goods_jifen_order') -> where($where) -> count();
            $info = db('goods_jifen_order')
                -> where($where)
                -> order('id desc')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    public function order_more(){
        $order_id = intval(input('get.id',0));
        $page = input('get.page',1);
        if(!$order_id){
            $this -> error('查看详情失败','order');
        }
        $order_info = db('goods_jifen_order')
            -> alias('a')
            -> field('a.*')
            -> where(['a.id' => $order_id])
            -> order('a.id desc')
            -> find();
        if($order_info['address_info']){
            $addressInfo = explode(',',$order_info['address_info']);
            if(is_array($addressInfo)){
                $order_info['username'] = $addressInfo[0];
                $order_info['telphone'] = $addressInfo[1];
                $order_info['address'] = $addressInfo[2];
                $order_info['city'] = $addressInfo[3];
            }

        }else{
            $order_info['username'] = '';
            $order_info['telphone'] = '';
            $order_info['address'] = '';
            $order_info['city'] = '';
        }
        $this -> assign('order',$order_info);
        $this -> assign('page',$page);
        return $this -> fetch();
    }

    /**
     * 修改订单的状态
     */
    public function setOrderState(){
        $kd_name = input('post.kd_name','');
        $kd_number = input('post.kd_number','');
        $order_id = input('post.order_id',0);
        if(!$order_id){
            return json(-1);
        }
        $orderInfo = db('goods_jifen_order') -> field('state,type') -> find($order_id);
        if(!$orderInfo){
            return json(-1);
        }
        if($orderInfo['state'] != 1 || $orderInfo['type'] == 2){
            return json(-1);
        }
        $data['type'] = 1;
        $data['kd_name'] = $kd_name;
        $data['kd_number'] = $kd_number;
        $res = db('goods_jifen_order') -> where(['id' => $order_id]) -> update($data);
        if($res){
            return json(1);
        }else{
            return json(2);
        }
    }
}