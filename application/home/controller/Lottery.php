<?php
/**
    关于积分的转盘抽奖
 * User: lijiafei
 * Date: 2018/4/8
 * Time: 下午3:15
 */
namespace app\home\controller;
class Lottery extends Action{

    public function getLottImg(){
        $info = db('lottery_list') -> field('img,code,id') -> order('code asc') ->limit(0,6) -> select();
        //获取用户的积分
        $jifen = db('user') -> where(['user_id' => $this->user_id]) -> value('jifen');
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        $return['code'] = 10000;
        $return['data'] = $info;
        $return['jifen'] = $jifen;
        $return['use_jifen'] = $config['lottery_jifen'];
        return json($return);
    }

    /**
     * 点击抽奖,请求接口
     */
    public function setLottery(){
        //比例
        $config = cache('web_config');
        if(!$config){
            $config = db('web_config') -> find();
            cache('web_config',$config);
        }
        $win_bili = $config['lottery_win_bili'] * 10000;
        $lottery_jifen = $config['lottery_jifen'];
        if(!$win_bili || !$lottery_jifen){
            $return['code'] = '10001';
            $return['data'] = '缺少参数';
            return json($return);
        }
        //判断用户是否有几分
        $userInfo = db('user') -> field('jifen') -> where(['user_id' => $this->user_id]) -> find();
        if($userInfo['jifen'] < $lottery_jifen){
            $return['code'] = 10002;
            $return['data'] = '积分不足';
            return json($return);
        }
        $model = db();
        $model -> startTrans();
        try{
            //扣积分
            db('user') -> where(['user_id' => $this->user_id]) -> setDec('jifen',$lottery_jifen);
            finance_log($this->user_id,$lottery_jifen,2,12);
            $number = mt_rand(1,10000);
            if($number < $win_bili){
                //中奖了,从code中选择一个
                $win = db('lottery_list') -> field('id,code') -> where(['is_win' => 1]) -> select();
                $key = array_rand($win,1);
                $code = $win[$key]['code'];
                $return['lottery_state'] = 1;
                //保存记录,lottery_log里面
                $lotteryDesc = db('lottery_list') -> where(['code' => $code]) -> value('desc');
                $return['desc'] = $lotteryDesc;
                $lotteryLog['user_id'] = $this->user_id;
                $lotteryLog['jifen'] = $lottery_jifen;
                $lotteryLog['lottery_code'] = $code;
                $lotteryLog['create_time'] = time();
                $lotteryLog['desc'] = $lotteryDesc;
                db('lottery_log') -> insertGetId($lotteryLog);
            }else{
                //没中
                $fail = db('lottery_list') -> field('id,code') -> where(['is_win' => 0]) -> select();
                $key = array_rand($fail,1);
                $code = $fail[$key]['code'];
                $return['lottery_state'] = 0;
                $return['desc'] = '';
            }
            if(!$code){
                $model -> rollback();
                $return['code'] = '10001';
                $return['data'] = '缺少参数';
                return json($return);
            }
            $model -> commit();
            $return['code'] = 10000;
            $return['lottery_code'] = $code;
            return json($return);
        }catch (\Exception $e){
            $model -> rollback();
            file_put_contents('./test_dir/lottery.txt',$e,FILE_APPEND);
        }
    }

    public function getLotteryLog(){
        $page = input('post.page',1);
        $number = input('post.number',10);
        $count = db('lottery_log') -> where(['user_id' => $this->user_id]) -> count();
        $data = db('lottery_log') -> where(['user_id' => $this->user_id]) -> page($page,$number) -> select();
        $return['code'] = 10000;
        $return['data'] = ['count' => $count,'data' => $data];
        return json($return);
    }


}