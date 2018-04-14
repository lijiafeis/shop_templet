<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/28 0028
 * Time: 15:50
 * 店面的展示和操作
 */
namespace app\admin\controller;
use think\Controller;
use think\Exception;
use think\Log;
use think\Request;

class Member extends Action {

    public function users(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
            }
            if (input('post.nickname')) {
                $map['nickname'] = input('post.nickname');
            }
            $count = db('user')->where($map)->count();
            $info = db('user')
                ->where($map)
                ->order('user_id desc')
                ->page($page,$number)
                ->select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    /**
     * 查看总业绩和当月业绩
     */
    public function getUserYeji(){
        $user_id = input('post.user_id',0);
        if(!$user_id){
            $arr['code'] = -1;
            $arr['info'] = '数据错误';
            return json($arr);
        }
        $info = db('user') -> field('user_id,yeji,month_yeji,zj_yeji,month_zj_yeji') -> where(['user_id' => $user_id]) -> find();
        if(!$info){
            $arr['code'] = -1;
            $arr['info'] = '数据错误';
            return json($arr);
        }
        //自己和下级总业绩
        $data['code'] = 1;
        $data['z_yeji'] = $info['zj_yeji'] + getUserAllYj($user_id);
        $data['month_yeji'] = $info['month_zj_yeji'] + getUserMonthYj($user_id);

        return json($data);
    }

    /**
     * 设置上级的id
     */
    public function setUserPinfo(){
        $user_id = input('post.user_id',0);
        $p_tel = trim(input('post.p_tel',0));
        if(!$user_id || !$p_tel){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $flag = verifyTel($p_tel);
        if(!$flag){
            $return['code'] = -1;
            $return['info'] = '请输入正确的手机号';
            return json($return);
        }
        //判断上级是否存在
        $is_true = db('user') -> field('user_id,state') -> where(['tel' => $p_tel]) -> find();
        if(!$is_true || !$is_true['state']){
            $return['code'] = -1;
            $return['info'] = '上级信息不存在或没有购买39产品';
            return json($return);
        }
        db('user') -> where(['user_id' => $user_id]) -> setField('p_id',$is_true['user_id']);
        //改变user_number表的值
        setUserNumber($user_id);
        $return['code'] = 1;
        $return['info'] = '设置成功';
        return $return;
    }

    /**
     * 设置用户的级别,生完级不能比上级高
     */
    public function setGrade(){
        $user_id = input('post.user_id',0);
        $grade = input('post.grade',0) * 1;
        if(!$user_id || !$grade || $grade > 5 || $grade < 1){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        //判断升级的级别是否比上级高
        $p_id = db('user') -> where(['user_id' => $user_id]) -> value('p_id');
        if($p_id){
            //获取上级级别
            $pInfo = db('user') -> field('grade') -> where(['user_id' => $p_id]) -> find();
            if(!$pInfo){
                exit;
            }
            if($grade > $pInfo['grade']){
                $return['code'] = -1;
                $return['info'] = '升级级别比上级高';
                return json($return);
            }
        }
        db('user') -> where(['user_id' => $user_id]) -> setField('grade',$grade);
        $return['code'] = 1;
        $return['info'] = '设置成功';
        return $return;
    }

    /**
     * 设置用户的余额
     */
    public function setUserMoney(){
        $money = input('post.money',0)*1;
        $user_id = input('post.user_id',0);
        if(!$money || !$user_id){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $res = db('user') -> where(['user_id' => $user_id]) -> setInc('money',$money);
        if($res){
            finance_log($user_id,$money,1,6,0,0);
            $return['code'] = 1;
            $return['info'] = '设置成功';
            return json($return);
        }else{
            $return['code'] = -1;
            $return['info'] = '设置失败';
            return json($return);
        }
    }

    /**
     * 设置用户的type值
     */
    public function setUserType(){
        $type = input('post.type',0)*1;
        $user_id = input('post.user_id',0);
        if(!$type || !$user_id){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $res = db('user') -> where(['user_id' => $user_id]) -> setField('type',$type);
        if($res){
            $return['code'] = 1;
            $return['info'] = '设置成功';
            return json($return);
        }else{
            $return['code'] = -1;
            $return['info'] = '设置失败';
            return json($return);
        }
    }

    /**
     * 禁用用户
     * type = 0 用户想要禁用，1 用户想要解禁
     */
    public function forbiddenUser(){
        $user_id = input('post.user_id',0);
        $type = input('post.type',-1);
        if(!$user_id || $type == -1){
            $return['code'] = 0;
            return json($return);
        }
        $userInfo = db('user') -> field('user_id,is_forbid') -> where(['user_id' => $user_id]) -> find();
        if($type == 0){
            //禁用
            $res = db('user') -> where(['user_id' => $userInfo['user_id']]) -> setField('is_forbid',1);
        }else if($type == 1){
            //解禁
            $res = db('user') -> where(['user_id' => $userInfo['user_id']]) -> setField('is_forbid',0);
        }
        if($res){
            $return['code'] = 1;
            return json($return);
        }
        $return['code'] = 0;
        return json($return);
    }
}