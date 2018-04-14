<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15 0015
 * Time: 17:36
 */
namespace app\admin\controller;
use think\Request;

class Money extends Action{

    /**
     * 用户的提现申请
     */
    public function withdraw_sq(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            $map1 = array();
            $map['state'] = 0;
            $map1['a.state'] = 0;
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $alipay_number = trim(input('post.alipay_number'));
            $bank_number = trim(input('post.bank_number'));
            $type = input('post.type');
            if($alipay_number){
                $map['alipay_number'] = input('post.alipay_number');
                $map1['a.alipay_number'] = input('post.alipay_number');
            }
            if($bank_number){
                $map['bank_number'] = input('post.bank_number');
                $map1['a.bank_number'] = input('post.bank_number');
            }
            if($type){
                $map['type'] = input('post.type');
                $map1['a.type'] = input('post.type');
            }
            $count = db('user_withdraw_log')->where($map)->count();
            $info = db('user_withdraw_log')
                -> alias('a')
                -> field('a.id,a.user_id,a.money,a.type,a.name,a.tel,a.alipay_number,a.bank_name,a.bank_number,a.create_time,b.nickname')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    /**
     * 改变用户的状态
     */
    public function setWithdrawLogState(){
        $log_id = input('post.id',0);
        $type = input('post.type',0);
        if(!$log_id || !$type){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $info = db('user_withdraw_log') -> field('money,state,user_id') -> find($log_id);
        if(!$info || $info['state'] != 0){
            $return['code'] = -1;
            $return['info'] = '数据不可改变';
            return json($return);
        }
        if($type == 1){
            //成功
            $updateLog['state'] = 1;
            $updateLog['success_time'] = time();
            $res = db('user_withdraw_log') -> where(['id' => $log_id]) -> update($updateLog);
            if($res){
                $return['code'] = 1;
                $return['info'] = 'ok';
                return json($return);
            }else{
                $return['code'] = -1;
                $return['info'] = '状态改变失败';
                return json($return);
            }
        }else if($type == 2){
            //失败,用户余额增加，state = 2
            $updateLog['state'] = 2;
            $updateLog['success_time'] = time();
            $res = db('user_withdraw_log') -> where(['id' => $log_id]) -> update($updateLog);
            if($res){
                db('user') -> where(['user_id' => $info['user_id']]) -> setInc('money',$info['money']);
                $return['code'] = 1;
                $return['info'] = 'ok';
                return json($return);
            }else{
                $return['code'] = -1;
                $return['info'] = '状态改变失败';
                return json($return);
            }
        }
    }

    /**
     * 提现记录
     */
    public function withdraw_log(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array();
            $map1 = array();
            $map['state'] = ['in','1,2'];
            $map1['a.state'] = ['in','1,2'];
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $alipay_number = trim(input('post.alipay_number'));
            $bank_number = trim(input('post.bank_number'));
            $state = trim(input('post.state'));
            $type = input('post.type');
            if($alipay_number){
                $map['alipay_number'] = input('post.alipay_number');
                $map1['a.alipay_number'] = input('post.alipay_number');
            }
            if($bank_number){
                $map['bank_number'] = input('post.bank_number');
                $map1['a.bank_number'] = input('post.bank_number');
            }
            if($type){
                $map['type'] = input('post.type');
                $map1['a.type'] = input('post.type');
            }
            if($state){
                $map['state'] = input('post.state');
                $map1['a.state'] = input('post.state');
            }
            $count = db('user_withdraw_log')->where($map)->count();
            $info = db('user_withdraw_log')
                -> alias('a')
                -> field('a.id,a.state,a.user_id,a.money,a.type,a.name,a.tel,a.alipay_number,a.bank_name,a.bank_number,a.create_time,a.success_time,b.nickname')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> order('a.id desc')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }


    /**
     * 积分记录
     */
    public function jifen_log(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array('state' => 2);
            $map1 = array('a.state' => 2);
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $type = input('post.type');
            if($type){
                if($type == 1) {
                    //收入
                    $map['type'] = 13;
                    $map1['a.type'] = 13;
                }else if($type == 2){
                    //支出
                    $map['type'] = ['in','11,12'];
                    $map1['a.type'] = ['in','11,12'];
                }
            }
            $count = db('finance_log')->where($map)->count();
            $info = db('finance_log')
                -> alias('a')
                -> field('a.user_id,a.type,a.number,a.create_time,b.nickname,a.order_id')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> order('a.id desc')
                -> group('a.id')
                -> page($page,$number)
                -> select();
            foreach ($info as $k => $v){
                if($v['type'] == 11){
                    //兑换商品
                    $info[$k]['good_name'] = db('goods_jifen_order') -> where(['id' => $v['order_id']]) -> value('good_name');
                }else if($v['type'] == 12){

                }else if($v['type'] == 13){

                }
            }
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    /**
     * 余额记录
     */
    public function money_log(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $map = array('state' => 1);
            $map1 = array('a.state' => 1);
            if (input('post.user_id')) {
                $map['user_id'] = input('post.user_id');
                $map1['a.user_id'] = input('post.user_id');
            }
            $type = input('post.type');
            if($type){
                if($type == 2) {
                    //支出
                    $map['type'] = 5;
                    $map1['a.type'] = 5;
                }else if($type == 1){
                    //收入
                    $map['type'] = ['in','1,2,3,4,6'];
                    $map1['a.type'] = ['in','1,2,3,4,6'];
                }
            }
            $count = db('finance_log')->where($map)->count();
            $info = db('finance_log')
                -> alias('a')
                -> field('a.user_id,a.type,a.number,a.create_time,b.nickname,a.order_id')
                -> join('wx_user b','a.user_id = b.user_id','LEFT')
                -> where($map1)
                -> order('a.id desc')
                -> group('a.id')
                -> page($page,$number)
                -> select();
            foreach ($info as $k => $v){
                if($v['type'] == 5 && $v['order_id']){
                    //查看提现状态
                    $state = db('user_withdraw_log') -> where(['id' => $v['order_id']]) -> value('state');
                    $info[$k]['state1'] = $state;
                }else{
                    $info[$k]['state1'] = -1;
                }
            }
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    /**
     * 对整个页面进行打款
     */
    public function setUserWithdrawLogAll(){
        ini_set('max_execution_time','0');
        $ids = input('post.ids');
        $idLog = explode(',',$ids);
        if(!$idLog){
            $return['code'] = 0;
            $return['info'] = '网络异常';
            return json($return);
        }
        $ids = substr($ids,0,strlen($ids)-1);
        db('user_withdraw_log') -> where("id in ({$ids})") -> update(['state' => 3]);
        $success_number = 0;
        $error_number = 0;
        $return['code'] = 1;
        $return['info'] = '成功';
        $return['success'] = $success_number;
        $return['error'] = $error_number;
        return json($return);

    }

    /**
     * 导出提现记录
     */
    public function dchuExcel(){
        //得到当前是在导出的那种的类型。
        $time1 = input('get.time1');
        $time2 = input('get.time2');
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        $where = array();
        if($time1 - $time2 > 0){echo '时间错误';exit;}
        if($time1 && $time2){
            $where['a.create_time'] = ['between',[$time1,$time2]];
        }
        $info = db('user_withdraw_log')
            -> alias('a')
            -> field('a.id,a.state,a.user_id,a.money,a.type,a.name,a.tel,a.alipay_number,a.bank_name,a.bank_number,a.create_time,a.success_time,b.nickname')
            -> join('wx_user b','a.user_id = b.user_id','LEFT')
            -> where($where)
            -> select();
        //导入Excel使用的类库
        $flag = $this -> push($info,time());
        echo $flag;
    }

    /* 导出excel函数*/
    private function push($data,$name='Excel'){

        $name = iconv("UTF-8","GBK",$name);
        error_reporting(E_ALL);
        date_default_timezone_set('Europe/London');
        import('PHPExcel.PHPExcel',EXTEND_PATH);
        import('PHPExcel.PHPExcel.IOFactory',EXTEND_PATH);
        import('PHPExcel.PHPExcel.Reader.Excel5',EXTEND_PATH);
//        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel.php";
//        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel/IOFactory.php";
//        require_once "Xigua/Library/Vendor/PHPExcel/PHPExcel/Reader/Excel5.php";
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel -> setActiveSheetIndex(0);
        $objSheet = $objPHPExcel -> getActiveSheet();
        $objSheet -> setCellValue("A1","姓名")
            -> setCellValue("B1","金额")
            -> setCellValue("C1","类型")
            -> setCellValue("D1","状态")
            -> setCellValue("E1","支付宝")
            -> setCellValue("F1","银行卡")
            -> setCellValue("G1","银行卡账号")
            -> setCellValue("H1","创建时间")
            -> setCellValue("I1","处理时间");
        $j = 2;
        foreach ($data as $k => $v){
            if($v['state'] == 1){
                $state = '成功';
            }else if($v['state'] == 2){
                $state = '驳回';
            }
            if($v['type'] == 1){
                $type = '支付宝';
            }else if($v['type'] == 2){
                $type = '银行卡';
            }
            $objSheet -> setCellValue("A".$j,$v['name'])
                -> setCellValue("B".$j,$v['money'])
                -> setCellValue("C".$j,$type)
                -> setCellValue("D".$j,$state)
                -> setCellValue("E".$j,$v['alipay_number'])
                -> setCellValue("F".$j,$v['bank_name'])
                -> setCellValue("G".$j,$v['bank_number'])
                -> setCellValue("H".$j,date("Y-m-d H:i:s",$v['create_time']))
                -> setCellValue("I".$j,date("Y-m-d H:i:s",$v['success_time']));
            $j++;
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $path = "uploads/xls";

        if(!is_dir($path)){
            mkdir($path,777);
        }
//        $res = $objWriter->save("$path/$name.xls");
//        file_put_contents('3423.txt',$res);
        $file_xls = dirname(dirname(dirname(__DIR__))) . "/" . $path . "/" . $name.".xls";    //   文件的保存路径

        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$name.'.xls"');
        header("Content-Disposition:attachment;filename=$name.xls");//attachment新窗口打印inline本窗口打印
        $objWriter->save('php://output');
        exit;
    }


}