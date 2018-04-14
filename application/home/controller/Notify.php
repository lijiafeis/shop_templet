<?php
/**
 * 支付宝和微信的一部回调
 * User: lijiafei
 * Date: 2018/4/2
 * Time: 下午2:48
 */
namespace app\home\controller;
use think\Controller;

class Notify extends Controller{

    /**
     * 微信的异步回调
     */
    public function shop(){
        $postStr = file_get_contents("php://input");
        $postStr = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        $order_id = trim($postStr -> attach);
        $order_sn = trim($postStr -> out_trade_no);
        $total_fee = trim($postStr -> total_fee)/100;
        $orderInfo = db('shop_order') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['state'] != 0 || $orderInfo['total_money'] != $total_fee || $orderInfo['order_sn'] != $order_sn){
            file_put_contents('./test_dir/notify_weixin.txt',json_encode($orderInfo),FILE_APPEND);
            die('SUCCESS');
        }
        //改变订单的状态和order_detai的状态,同事商品的订单改变
        $this->pay_type($orderInfo,2);
        die('SUCCESS');


    }

    /**
     * 支付宝的异步回调
     */
    public function notify_alipay(){
        try{
            require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_config.php");
            require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_submit.class.php");
            require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_notify.class.php");
            //计算得出通知验证结果
            $alipayNotify = new \AlipayNotify($alipay_config);
            $verify_result = $alipayNotify->verifyNotify();
            if($verify_result) {//验证成功
                //请在这里加上商户的业务逻辑程序代
                //商户订单号
                //S('alipay',$_POST);
                $out_trade_no = $_POST['out_trade_no'];
                //支付宝交易号
                $trade_no = $_POST['trade_no'];
                //交易状态
                $trade_status = $_POST['trade_status'];
                if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                    //判断该笔订单是否在商户网站中已经做过处理，进行商户处理
                    $this->pay_sure($out_trade_no);
                } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                    //判断该笔订单是否在商户网站中已经做过处理，进行商户处理
                    $this->pay_sure($out_trade_no);
                }
                echo "success";
            }
        }catch (\Exception $e){
            file_put_contents('./test_dir/notify_alipay.txt',$e,FILE_APPEND);
        }

    }

    private function pay_sure($pay_id){
        $orderInfo = db('shop_order') -> where(['id' => $pay_id]) -> find();
        if(!$orderInfo || $orderInfo['state'] != 0){
            file_put_contents('./test_dir/notify_alipay.txt',json_encode($orderInfo),FILE_APPEND);
            die('SUCCESS');
        }

        $this->pay_type($orderInfo,1);
        die('SUCCESS');

    }

    private function pay_type($orderInfo,$pay_type){
        try{
            if(!$orderInfo || !$pay_type){
                return;
            }
            $updateOrder['state'] = 1;
            $updateOrder['pay_type'] = $pay_type;
            $updateOrder['pay_time'] = time();
            db('shop_order') -> where(['id' => $orderInfo['id']]) -> update($updateOrder);
            //修改shop_order的值
            $shop_detail = db('shop_order_detail') -> where(['order_id' => $orderInfo['id']]) -> select();
            if(!$shop_detail){return;}
            db('shop_order_detail') -> where(['order_id' => $orderInfo['id']]) -> setField('state',1);
            //积分和佣金,用于返
            $jifen = 0;
            foreach ($shop_detail as $k => $v){
                //state为1 并增加销量
                $goodInfo = db('shop_goods') -> field('jifen_bili') -> where(['good_id' => $v['good_id']]) -> find();
                if(!$goodInfo)
                {
                    break;
                }else{
                    $jifen += $v['good_price'] * $goodInfo['jifen_bili'];
                }
                db('shop_goods') -> where(['good_id' => $v['good_id']]) -> setInc('xiaoliang',$v['good_num']);
                //判断当前用户当前商品是否有记录,没有的话增加
            }
            //自己的业绩增加,上级的业绩增加,yeji 和mother_yeji zj_yeji
            $updateUser['zj_yeji'] = ['exp','zj_yeji+' . $orderInfo['total_money']];
            $updateUser['month_zj_yeji'] = ['exp','month_zj_yeji + ' . $orderInfo['total_money']];
            db('user') -> where(['user_id' => $orderInfo['user_id']]) -> update($updateUser);
            $userInfo = db('user') -> field('user_id,p_id,type,grade,state') -> where(['user_id' => $orderInfo['user_id']]) -> find();
            $p_id = $userInfo['p_id'];
            if($p_id){
                $updatePUser['yeji'] = ['exp','yeji+' . $orderInfo['total_money']];
                $updatePUser['month_yeji'] = ['exp','month_yeji + '.$orderInfo['total_money']];
                db('user') -> where(['user_id' => $p_id]) -> update($updatePUser);
            }
            //判断订单的类型
            if($orderInfo['order_type'] == 3){
                //这个订单是199的订单,user表的state职位1  同时判断是否够人数 升级
                $this->setUserType($userInfo);
            }else if($orderInfo['order_type'] == 2){
                die('SUCCESS');
            }
            //根据订单金额,返佣金和积分。
            //团队奖励  V4奖励  上三级奖励 自己的income增加。
            if($jifen){
                db('user') -> where(['user_id' => $orderInfo['user_id']]) -> setInc('jifen',$jifen);
                if($jifen){
                    finance_log($orderInfo['user_id'],$jifen,2,13,0,$orderInfo['id']);

                }
            }
            if(!$userInfo['p_id']){
                die('SUCCESS');
            }
            $config = cache('web_config');
            if(!$config){
                $config = db('web_config') -> find();
                cache('web_config',$config);
            }
            $pInfo = db('user_number') -> field('user_id',true) ->  where(['user_id' => $orderInfo['user_id']]) -> find();
            if($userInfo['grade'] >= 0){
                $this -> setThreeMoney($pInfo,$orderInfo['user_id'],$orderInfo['total_money'],$orderInfo['id']);
            }
            //等级奖励
            $pInfo = array_diff($pInfo,[0]);
            $pInfo = implode(',',$pInfo);
            $userAllInfo = db('user')
                -> field('user_id,type')
                -> where(['user_id' => ['in',$pInfo],'type' => ['in','2,3,4,5']])
                -> select();
            foreach ($userAllInfo as $k => $v){
                switch ($v['type']){
                    case 2:
                        $money = $orderInfo['total_money'] * $config['xv_bili'];
                        break;
                    case 3:
                        $money = $orderInfo['total_money'] * $config['dv_bili'];
                        break;
                    case 4:
                        $money = $orderInfo['total_money'] * $config['gf_bili'];
                        break;
                    case 5:
                        $money = $orderInfo['total_money'] * $config['lh_bili'];
                        if($money){
                            $this->setPMoney($v['user_id'],$money,$config);
                        }
                        break;
                    default:
                        break;
                }
                if($money){
                    $up['income'] = ['exp','income+' . $money];
                    $up['money'] = ['exp','money+' . $money];
                    db('user') -> where(['user_id' => $v['user_id']]) -> update($up);
                    finance_log($v['user_id'],$money,1,3,$orderInfo['user_id'],$orderInfo['id']);
                }

            }
        }catch (\Exception $e){
            file_put_contents('./test_dir/notify.php',$e,FILE_APPEND);
        }


    }

    /**
     * 购买39.8的商品逻辑处理
     */
    private function setUserType($userInfo){
        //判断order_type的订单是否超过199
        if($userInfo['state'] >= 1){
            return;
        }
        $money = db('shop_order') -> field('sum(total_money) as money') -> where(['user_id' => $userInfo['user_id'],'state' => 1,'order_type' => 3]) -> find();
        $money = $money['money'];
        if($money < 0.01){return;}
        $updateUserInfo['state'] = 1;
        $updateUserInfo['grade'] = 1;
        db('user') -> where(['user_id' => $userInfo['user_id']]) -> update($updateUserInfo);
        //判断上级
        if(!$userInfo['p_id']){return;}
        $countInfo = db('user') -> field('count(*) as count,group_concat(user_id) as user_id') -> where(['p_id' => $userInfo['p_id'],'state' => 1]) -> find();
        $count = $countInfo['count'];
        if($count < 5){
            return;
        }

        $pInfo = db('user') -> field('type,income') -> where(['user_id' => $userInfo['p_id']]) -> find();
        if($count >= 5 && $pInfo['type'] < 1){
            $setUserInfo['type'] = 1;
            $setUserInfo['libao_number'] = 1;
            db('user') -> where(['user_id' => $userInfo['p_id']]) -> update($setUserInfo);
            return;
        }
        if($count >= 15 && $count < 30){
            if($pInfo['type'] < 2){
                //要升级为小V  晋级奖励300元
                $updatePInfo['type'] = 2;
                db('user') -> where(['user_id' => $userInfo['p_id']]) -> update($updatePInfo);
            }
        }else if($count >= 30 && $count < 100){
            if($pInfo['type'] < 3){
                //判断是否有五个小V
                $is_have = db('user') -> field('count(*) as number') ->  where(['user_id' => ['in',$countInfo['user_id']],'type' => 2]) -> find();
                if($is_have['number'] > 5){
                    //要升级为小V
                    $updatePInfo['type'] = 3;
                    db('user') -> where(['user_id' => $userInfo['p_id']]) -> update($updatePInfo);

                }
            }
        }else if($count >= 100 && $count < 200){
            if($pInfo['type'] < 4){
                //判断上级的incorm是否够3万元
                if($pInfo['income'] >= 30000){
                    //升级为官方合伙人
                    //要升级为小V
                    $updatePInfo['type'] = 4;
                    db('user') -> where(['user_id' => $userInfo['p_id']]) -> update($updatePInfo);

                }
            }
        }else if($count >= 200){
            if($pInfo['type'] < 5){
                if($pInfo['income'] >= 200000){
                    //要升级为小V
                    $updatePInfo['type'] = 5;
                    db('user') -> where(['user_id' => $userInfo['p_id']]) -> update($updatePInfo);
                }
            }
        }
        return;
    }

    /**
     * 三级分佣
     *
     */
    private function setThreeMoney($pInfo,$user_id,$money,$order_id){
        if(!$pInfo['sj_1']){
            return;
        }
        $grade = db('user') -> where(['user_id' => $user_id]) -> value('grade');
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        $bili = array(1 => $config['vip_bili'],2 => $config['sd_bili'],3 => $config['zd_bili'],4 => $config['gf1_bili'],5 => $config['lc_bili'],6 => $config['tj_bili'],7 => $config['xj_bili']);
        if($grade == 0){
            return;
        }else{
            //查看上级是否和自己等级一样
            $sj1_grade = db('user') -> where(['user_id' => $pInfo['sj_1']]) -> value('grade');
            if($sj1_grade == $grade){
                $fee = $money * $bili[6];
            }else if($sj1_grade > $grade){
                $fee = $money * $bili[$sj1_grade];
            }else{
                return;
            }
            if($fee){
                $upPinfo['income'] = ['exp','income+' . $fee];
                $upPinfo['money'] = ['exp','money+' . $fee];
                db('user') -> where(['user_id' => $pInfo['sj_1']]) -> update($upPinfo);
                finance_log($pInfo['sj_1'],$fee,1,2,$user_id,$order_id);
            }
            if(!$pInfo['sj_2']){
                return;
            }
            $sj2_grade = db('user') -> where(['user_id' => $pInfo['sj_2']]) -> value('grade');
            if($sj2_grade == $sj1_grade){
                $fee = $money * $bili[6];
            }else if($sj2_grade > $sj1_grade){
                $fee = $money * $bili[7];
            }else{
                return;
            }
            if($fee){
                $upPinfo['income'] = ['exp','income+' . $fee];
                $upPinfo['money'] = ['exp','money+' . $fee];
                db('user') -> where(['user_id' => $pInfo['sj_2']]) -> update($upPinfo);
                finance_log($pInfo['sj_2'],$fee,1,2,$user_id,$order_id);
            }
            if(!$pInfo['sj_3']){
                return;
            }
            $sj3_grade = db('user') -> where(['user_id' => $pInfo['sj_3']]) -> value('grade');
            if($sj3_grade == $sj2_grade){
                $fee = $money * $bili[6];
            }else if($sj3_grade > $sj2_grade){
                $fee = $money * $bili[$sj3_grade];
            }else{
                return;
            }
            if($fee){
                $upPinfo['income'] = ['exp','income+' . $fee];
                $upPinfo['money'] = ['exp','money+' . $fee];
                db('user') -> where(['user_id' => $pInfo['sj_2']]) -> update($upPinfo);
                finance_log($pInfo['sj_3'],$fee,1,2,$user_id,$order_id);
            }
        }

    }

    /**
     * 如果上级是联合创始人  , 上级是联合创始人,上级有薪酬的比例分红
     */
    private function setPMoney($user_id,$money,$config){
        if(!$money || !$user_id){
            return;
        }
        //判断user_id的级别,如果不是type = 5 联合创始人
        $p_info = db('user') -> field('type') -> where(['user_id' => $user_id]) -> find();
        if($p_info['type'] != 5){
            return;
        }
        $user_number = db('user_number') -> where(['user_id' => $user_id]) -> find();
        if(!$user_number){return;}
        //查看上级是否是5级
        if($user_number['sj_1']){
            $sjInfo = db('user') -> field('type') -> where(['user_id' => $user_number['sj_1']]) -> find();
            if($sjInfo['type'] != 5){
                return;
            }
            $yongjin = $money * $config['one_bili'];
            if($yongjin){
                $upPinfo['income'] = ['exp','income+' . $yongjin];
                $upPinfo['money'] = ['exp','money+' . $yongjin];
                db('user') -> where(['user_id' => $user_number['sj_1']]) -> update($upPinfo);
                finance_log($user_number['sj_1'],$yongjin,1,4,$user_id,0);
            }
        }else{return;}
        if($user_number['sj_2']){
            $sjInfo = db('user') -> field('type') -> where(['user_id' => $user_number['sj_2']]) -> find();
            if($sjInfo['type'] != 5){
                return;
            }
            $yongjin = $money * $config['two_bili'];
            if($yongjin){
                $upPinfo['income'] = ['exp','income+' . $yongjin];
                $upPinfo['money'] = ['exp','money+' . $yongjin];
                db('user') -> where(['user_id' => $user_number['sj_2']]) -> update($upPinfo);
                finance_log($user_number['sj_2'],$yongjin,1,4,$user_id,0);
            }
        }else{return;}
        if($user_number['sj_3']){
            $sjInfo = db('user') -> field('type') -> where(['user_id' => $user_number['sj_3']]) -> find();
            if($sjInfo['type'] != 5){
                return;
            }
            $yongjin = $money * $config['three_bili'];
            if($yongjin){
                $upPinfo['income'] = ['exp','income+' . $yongjin];
                $upPinfo['money'] = ['exp','money+' . $yongjin];
                db('user') -> where(['user_id' => $user_number['sj_3']]) -> update($upPinfo);
                finance_log($user_number['sj_3'],$yongjin,1,4,$user_id,0);
            }
        }else{return;}

    }
}