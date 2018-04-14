<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2 0002
 * Time: 15:22
 */
namespace app\automate\controller;
use think\Controller;

class Index extends Controller{


    public function index(){
        try{
            $argv = isset($_SERVER['argv'])?$_SERVER['argv']:array();
            $number = $argv[2];
            $userWithInfo  = db('user_withdraw_log') -> field('id,state,money,user_id,type,name,alipay_number,create_time') -> where(['state' => 3]) -> order('id asc') -> limit($number,$number+30) ->  select();
            if(!$userWithInfo){return;}
//        file_put_contents('/home/c.txt',json_encode($userWithInfo),FILE_APPEND);
            foreach ($userWithInfo as $k => $v){
                $order_sn = $v['create_time'] . $v['user_id'];
                $flag = pay($order_sn,$v['money'],$v['alipay_number'],$v['name']);

                if($flag == 10000){
                    //打款成功
                    $data['state'] = 1;
                    $data['order_sn'] = $order_sn;
                    $data['success_time'] = time();
                    db('user_withdraw_log') -> where(['id' => $v['id']]) -> update($data);
                }else if($flag == 'PAYEE_NOT_EXIST' || $flag == 'PAYEE_USER_INFO_ERROR'){
                    //驳回，返回到用户账户
                    $data['state'] = 2;
                    $data['success_time'] = time();
                    $res = db('user_withdraw_log') -> where(['id' => $v['id']]) -> update($data);
                    if($res){
                        //给用户价钱
                        $res = db('user') -> where(['user_id' => $v['user_id']]) -> setInc('money',$v['money']);
                    }
                }else{
                    file_put_contents('/home/withdraw.txt',$flag . '...',FILE_APPEND);
                    exit;
                }

            }
        }catch (\Exception $e){
            file_put_contents('/home/withdraw_err.txt',$e . '...',FILE_APPEND);

        }

    }

}