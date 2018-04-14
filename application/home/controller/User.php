<?php
/**
 * 个人中心的接口
 * User: lijiafei
 * Date: 2018/4/3
 * Time: 上午11:52
 */
namespace app\home\controller;
class User extends Action{

    public function getUserInfo(){
        $userInfo = db('user') -> field('nickname,headimgurl,libao_number,money,jifen,type,state') -> where(['user_id' => $this->user_id]) -> find();
        $return['code'] = 10000;
        $return['data'] = $userInfo;
        return json($return);
    }

    /**
     * 获取提现的账号信息
     */
    public function getWithdrawInfo(){
        $userInfo = db('user') -> field('money') -> where(['user_id' => $this->user_id]) -> find();
        $info = db('user_withdraw_info') -> where(['user_id' => $this->user_id]) -> find();
        $info['money'] = $userInfo['money'];
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        $info['min_money'] = $config['withdraw_min_money'];
        $info['max_money'] = 100000000;
        $return['code'] = 10000;
        $return['info'] = $info;
        return json($return);
    }

    /**
     * 前台发出提现申请，记录到user_withdraw_log表中，同时判断余额是否足够 type 1 支付宝 2 银行卡
     */
    public function setWithdrawInfo(){
        //根据传递过来的type值，接受alipay_number 或者bank_name bank_number
        $type = input('post.type',0);
        $name = input('post.name','');
        $tel = input('post.tel','');
        if(!$type){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        $money = intval(input('post.money',0));
        if(!$money || $money <= 0){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //判断user_withdraw_log的当前用户的state = 1 and state = 0是否有两条
        $log_number = db('user_withdraw_log')
            -> where(['user_id' => $this->user_id,'state' => ['in','0,1']])
            -> whereTime('create_time','today')
            -> count();
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        if($log_number >= $config['withdraw_number']){
            $return['code'] = 10003;
            $return['info'] = '当天最多提现' . $config['withdraw_number'] . '次';
            return json($return);
        }
        //判断当前最少提现和最多提现
        if($money < $config['withdraw_min_money']){
            $return['code'] = 10003;
            $return['info'] = '当天最少提现' . $config['withdraw_min_money'];
            return json($return);
        }

        //判断余额是否足够
        $user_money = db('user') -> field('money') -> where(['user_id' => $this->user_id]) -> find();
        if($user_money['money'] < $money){
            $return['code'] = 10002;
            $return['info'] = '用户余额不足';
            return json($return);
        }
        if($type == 1){
            //支付宝
            $alipay_number = input('post.alipay_number',0);
            if(!$alipay_number){
                $return['code'] = 10001;
                $return['info'] = '数据错误';
                return json($return);
            }
            $withdrawInfo['alipay_number'] = $alipay_number;
            $log['alipay_number'] = $alipay_number;

        }else if($type == 2){
            //银行卡
            $bank_name = input('post.bank_name','');
            $bank_number = input('post.bank_number','');
            if(!$bank_name || !$bank_number){
                $return['code'] = 10001;
                $return['info'] = '数据错误';
                return json($return);
            }
            $withdrawInfo['bank_name'] = $bank_name;
            $withdrawInfo['bank_number'] = $bank_number;
            $log['bank_name'] = $bank_name;
            $log['bank_number'] = $bank_number;
        }else{
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //保存账号信息，同时保存记录
        $withdrawInfo['user_id'] = $this->user_id;
        $withdrawInfo['name'] = $name;
        $withdrawInfo['tel'] = $tel;
        $model = db();
        $model -> startTrans();
        try{
            $is_true = db('user_withdraw_info') -> field('id') -> where(['user_id' => $this->user_id]) -> find();
            if($is_true){
                db('user_withdraw_info') -> where(['user_id' => $this->user_id]) -> update($withdrawInfo);
            }else{
                db('user_withdraw_info') -> insert($withdrawInfo);
            }
            //扣除用户的钱
            $res = db('user') -> where(['user_id' => $this->user_id]) -> setDec('money',$money);
            if($res){
                //保存记录
                $log['user_id'] = $this->user_id;
                $log['money'] = $money;
                $log['type'] = $type;
                $log['name'] = $name;
                $log['tel'] = $tel;
                $log['create_time'] = time();
                $re = db('user_withdraw_log') -> insertGetId($log);
                finance_log($this->user_id,$money,1,5,0,$re);
                if($re){
                    $model -> commit();
                    $return['code'] = 10000;
                    $return['info'] = '提现成功,后台审核中';
                    return json($return);
                }
            }
            $model -> rollback();
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }catch (\Exception $e){
            $model -> rollback();
            file_put_contents('./test_dir/withdraw.txt',$e,FILE_APPEND);
        }
    }

    /**
     * 余额明细
     */
    public function getMoneyLog(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $count = db('finance_log') -> where(['user_id' => $this->user_id,'state' => 1]) -> count();
        $info = db('finance_log')
            -> field('id,number,type,create_time,order_id')
            -> where(['user_id' => $this->user_id,'state' => 1])
            -> order('id desc')
            -> page($page,$number)
            -> select();
        foreach ($info as $k => $v){
            if($v['type'] == 5 && $v['order_id']){
                //查看提现状态
                $state = db('user_withdraw_log') -> where(['id' => $v['order_id']]) -> value('state');
                $info[$k]['state'] = $state;
            }
        }
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }


    /**
     * 生成二维码
     */
    public function qr(){
        //判断是否已经可以推广了。
        $userInfo = db('user') -> field('state,tel') -> where(['user_id' => $this->user_id]) -> find();
        if($userInfo['state'] < 1){
            $return['code'] = 10001;
            $return['data'] = '无法生成二维码';
            return json($return);
        }
        //判断是否有图片
        $img_url = 'uploads/qr/img/' . $this->user_id . '.jpg';
        if(file_exists($img_url)){
            $return['code'] = 10000;
            $return['data'] = $img_url;
            return json($return);
        }
        //生成头像
        $weixin = controller('wxapi/Weixin');
        $head_url = './uploads/qr/head_img/' . $this->user_id . '.jpg';
        if(!file_exists($head_url)){
            $weixin -> save_head_pic($this->user_id);
        }
        //判断是否有二维码图片
        $qr_url = "./uploads/qr/qr/" . $this->user_id . ".jpg";
        if(!file_exists($qr_url)){
//            $weixin -> get_qr_limit($this->user_id);
            import('phpqrcode.phpqrcode',EXTEND_PATH,'.php');
            $object = new \QRcode();
            #二维码内容
            $url = URL . "?id=" . $userInfo['tel'];
            #容错级别
            $errorCorrectionLevel = 'L';
            #生成图片大小
            $matrixPointSize = 4;
            #生成一个无logo二维码图片
            $object->png($url,$qr_url, $errorCorrectionLevel, $matrixPointSize, 2);
        }
        $nickname = $this->user['nickname'];
        $qrimg = controller('wxapi/Qrimg');
        $img_url = $qrimg -> create_img($this->user_id,$nickname);
        $return['code'] = 10000;
        $return['data'] = $img_url;
        return json($return);
    }

    /**
     * 领取礼包订单
     */
    public function lingqulb(){
        //获取收获地址
        $name = input('post.name','');
        $tel = input('post.tel','');
        $address = input('post.address','');
        if(!$name || !$tel || !$address){
            $return['code'] = 10001;
            $return['info'] = '数据错误';
            return json($return);
        }
        //查看是否可以领取
        $userInfo = db('user') -> field('libao_number') -> where(['user_id' => $this->user_id]) -> find();
        if($userInfo['libao_number'] != 1){
            $return['code'] = 10002;
            $return['info'] = '不可领取';
            return json($return);
        }
        $orderInfo['user_id'] = $this->user_id;
        $orderInfo['name'] = $name;
        $orderInfo['tel'] = $tel;
        $orderInfo['address'] = $address;
        $orderInfo['create_time'] = time();
        //可以领取，领取完zt_number为2
        $orderInfo['good_name'] = '产品一套';
        db('user') -> where(['user_id' => $this->user_id]) -> setField('libao_number',2);

        //保存订单信息
        $res = db('libao_order') -> insert($orderInfo);
        if($res){
            $return['code'] = 10000;
            $return['info'] = 'ok';
            return json($return);
        }else{
            $return['code'] = 10003;
            $return['info'] = '领取失败';
            return json($return);
        }

    }

    /**
     * 获取个人的领取礼包记录
     */
    public function lingquLog(){
        $info = db('libao_order') -> where(['user_id' => $this->user_id]) -> select();
        $return['code'] = 10000;
        $return['data'] = $info;
        return json($return);
    }

    /**
     * 积分的明细,也就是获得的和使用的
     */
    public function getJifenLog(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $count = db('finance_log') -> where(['user_id' => $this->user_id,'state' => 2]) -> count();
        $info = db('finance_log')
            -> field('id,number,type,create_time')
            -> where(['user_id' => $this->user_id,'state' => 2])
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }

    public function getTeamInfo(){
        //获取直推的人数
        $page = input('post.page',1);
        $number = input('post.number',10);
        $count = db('user') -> where(['p_id' => $this->user_id]) -> count();
        $info = db('user')
            -> field('nickname,headimgurl,type')
            -> where(['p_id' => $this->user_id])
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['info'] = ['data' => $info,'number' => $count];
        return json($return);
    }

    public function getUserYeji(){
        $info = db('user') -> field('user_id,type,yeji,month_yeji,zj_yeji,month_zj_yeji') -> where(['user_id' => $this->user_id]) -> find();
        //自己和下级总业绩
        $data['code'] = 10000;
        $data['type'] = $info['type'];
        $data['z_yeji'] = $info['zj_yeji'] + getUserAllYj($this->user_id);
        $data['month_yeji'] = $info['month_zj_yeji'] + getUserMonthYj($this->user_id);

        return json($data);
    }


}