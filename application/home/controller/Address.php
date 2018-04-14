<?php
/**
 * 用户的收获地址的管理接口
 * Date: 2018/3/19 0019
 * Time: 15:08
 */
namespace app\home\controller;
class Address extends Action{

    /**
     * 得到用户的收获地址
     */
    public function getUserAddress(){
        $info = db('user_address') -> where(['user_id' => $this->user_id]) -> select();
        $return['code'] = 10000;
        $return['info'] = $info;
        return json($return);
    }

    /**
     * 得到id
     */
    public function getUserAddressInfo(){
        $address_id = input('post.id',0);
        $info = db('user_address') -> where(['address_id' => $address_id]) -> find();
        $return['code'] = 10000;
        $return['info'] = $info;
        return json($return);
    }

    /**
     * 删除收获地址
     */
    public function delUserAddress(){
        $address_id = input('post.address_id',0);
        if(!$address_id){
            $return['code'] = 10001;
            $return['info'] = '参数错误';
            return json($return);
        }
        //判断当前的地址是否有订单使用，如果使用，无法删除
        $is_true = db('goods_order') -> field('id') -> where(['address_id' => $address_id,'state' => 1,'type' => 0]) -> find();
        if($is_true){
            $return['code'] = 10002;
            $return['info'] = '当前地址有订单使用,暂时无法删除';
            return json($return);
        }
        $res = db('user_address') -> delete($address_id);
        $return['code'] = 10000;
        $return['info'] = '删除成功';
        return json($return);
    }

    /**
     * 设置某一个收获地址为默认的收获地址
     */
    public function setDefault(){
        $address_id = input('post.address_id',0);
        if(!$address_id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        db('user_address') -> where(['user_id' => $this->user_id]) -> setField('is_true',0);
        $res = db('user_address') -> where(['address_id' => $address_id]) -> setField('is_true',1);
        if($res){
            $return['code'] = 10000;
            $return['info'] = '设置成功';
            return json($return);
        }else{
            $return['code'] = 10000;
            $return['info'] = '设置失败';
            return json($return);
        }

    }

    /**
     * 添加收获地址
     */
    public function addUserAddress(){
        $data['username'] = input('post.username','');
        $data['telphone'] = input('post.tel','');
        $data['address'] = input('post.address','');
        $data['city'] = input('post.city','');
        $data['code'] = input('post.code',1000);
        $is_true = input('post.is_true',0);
        if(!$data['username'] || !$data['telphone'] || !$data['address'] || !$data['city']){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $data['user_id'] = $this->user_id;
        if($is_true){
            //这个地址是默认的地址
            db('user_address') -> where(['user_id' => $this->user_id]) -> setField('is_true',0);
            $data['is_true'] = 1;
        }
        db('user_address') -> insert($data);
        $return['code'] = 10000;
        $return['info'] = 'ok';
        return json($return);
    }

    /**
     * 修改收获地址
     */
    public function updateUserAddress(){
        $data['username'] = input('post.username','');
        $data['telphone'] = input('post.tel','');
        $data['address'] = input('post.address','');
        $data['city'] = input('post.city','');
        $data['code'] = input('post.code',1000);
        $id = input('post.id',1000);
        $is_true = input('post.is_true',0);
        if(!$data['username'] || !$data['telphone'] || !$data['address'] || !$data['city'] || !$id){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $data['user_id'] = $this->user_id;
        if($is_true){
            //这个地址是默认的地址
            db('user_address') -> where(['user_id' => $this->user_id]) -> setField('is_true',0);
            $data['is_true'] = 1;
        }
        db('user_address') -> where(['address_id' => $id]) -> update($data);
        $return['code'] = 10000;
        $return['info'] = 'ok';
        return json($return);
    }


}