<?php
/**
 * Created by PhpStorm.
 * 后台订单的逻辑处理
 */
namespace app\admin\controller;
use think\Request;

class Order extends Action{



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
            $state = input('post.state','','htmlspecialchars');
            $type = input('post.type','','htmlspecialchars');
            $pay_type = input('post.pay_type','','htmlspecialchars');
            $order_type = input('post.order_type','','htmlspecialchars');
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
            if($state != -1){
                $where['state'] = $state;
//                $where1['a.state'] = $state;
            }
            if($type != -1){
                $where['type'] = $type;
                $where['state'] = 1;
//                $where1['a.type'] = $type;
            }
            if($pay_type){
                $where['pay_type'] = $pay_type;
//                $where1['a.order_type'] = $order_type;
            }
            if($order_type){
                $where['order_type'] = $order_type;
//                $where1['a.order_type'] = $order_type;
            }
            $count=db('shop_order') -> where($where) -> count();
            $info = db('shop_order')
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
        $order_info = db('shop_order')
            -> alias('a')
            -> field('a.*')
            -> where(['a.id' => $order_id])
            -> find();
        $order_info['goods_info'] = db('shop_order_detail') -> where(['order_id' => $order_id]) -> select();
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
        $orderInfo = db('shop_order') -> field('state,type') -> find($order_id);
        if(!$orderInfo){
            return json(-1);
        }
        if($orderInfo['state'] != 1 || $orderInfo['type'] == 2){
            return json(-1);
        }
        $data['type'] = 1;
        $data['kd_name'] = $kd_name;
        $data['kd_number'] = $kd_number;
        $res = db('shop_order') -> where(['id' => $order_id]) -> update($data);
        if($res){
            return json(1);
        }else{
            return json(2);
        }
    }


    public function libao_order(){
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
            if($user_id){
                $where['user_id'] = $user_id;
            }
            if($order_sn){
                $where['order_sn'] = trim($order_sn);
            }
            if($type != -1){
                $where['type'] = $type;
            }
            $count=db('libao_order') -> where($where) -> count();
            $info = db('libao_order')
                -> where($where)
                -> order('id desc')
                -> page($page,$number)
                -> select();
            foreach ($info as $k => $v){
                $info[$k]['kd_name'] = getKdname($v['kd_name']);
            }
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }



    /**
     * 对礼包订单进行发货
     */
    public function setLiBaoOrderKd(){
        $id = input('post.id',0);
        $kd_name = input('post.kd_name','');
        $kd_number = input('post.kd_number','');
        if(!$id || !$kd_number || !$kd_name){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $info = db('libao_order') -> find($id);
        if(!$info || $info['type'] != 0){
            $return['code'] = -1;
            $return['info'] = '数据错误';
            return json($return);
        }
        $updateOrder['kd_name'] = $kd_name;
        $updateOrder['kd_number'] = $kd_number;
        $updateOrder['type'] = 1;
        db('libao_order') -> where(['id' => $id]) -> update($updateOrder);
        $return['code'] = 1;
        $return['info'] = 'ok';
        return json($return);
    }

    /**
     * 导出提现记录
     */
    public function dchuExcel(){
        //得到当前是在导出的那种的类型。
        $time1 = input('get.time1');
        $time2 = input('get.time2');
        $type = input('get.type',0);
        $time1 = strtotime($time1);
        $time2 = strtotime($time2);
        $where = array();
        $where['a.state'] = 1;
        if($time1 - $time2 > 0){echo '时间错误';exit;}
        if($time1 && $time2){
            $where['a.pay_time'] = ['between',[$time1,$time2]];
        }
        if($type == 0){
            $where['a.type'] = 0;
        }else if($type == 1){
            $where['a.type'] = 1;
        }else if($type == 2){
            $where['a.type'] = 2;
        }

        $info = db('shop_order')
            -> alias('a')
            -> field('a.*,b.nickname')
            -> join('wx_user b','a.user_id = b.user_id','left')
            -> where($where)
            -> select();
        foreach ($info as $k => $v)
        {
            $detail = db('shop_order_detail') -> field('good_name,good_num,type') ->  where(['order_id' => $v['id']]) -> select();
            $shop = '';
            foreach ($detail as $key => $val){
                $type = json_decode($val['type'],true);
                $type_str = '';
                if(is_array($type)){
                    foreach ($type as $k1 => $v1){
                        $type_str .= $v1['name'] . ':' . $v1['value'] . ';';
                    }
                }else{
                    $type_str = $type;
                }
                $shop .= '【商品名字:' . $val['good_name'] . ';数量:' . $val['good_num'] . ';规格:' . $type_str . '】';
            }
            $info[$k]['detail'] = $shop;
        }
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
            -> setCellValue("B1","订单号")
            -> setCellValue("C1","金额")
            -> setCellValue("D1","发货状态")
            -> setCellValue("E1","支付时间")
            -> setCellValue("F1","订单类型")
            -> setCellValue("G1","支付类型")
            -> setCellValue("H1","收货地址")
            -> setCellValue("I1","商品信息");
        $j = 2;
        foreach ($data as $k => $v){
            if($v['type'] == 1){
                $type = '已发货';
            }else if($v['type'] == 2){
                $type = '已收货';
            }else if($v['type'] == 0){
                $type = '未发货';
            }
            if($v['order_type'] == 1){
                $order_type = '普通订单';
            }else if($v['order_type'] == 2){
                $order_type = '秒杀订单';
            }else if($v['order_type'] == 3){
                $order_type = '会员订单';
            }else{
                $order_type = '';
            }
            if($v['pay_type'] == 1){
                $pay_type = '支付宝';
            }else if($v['pay_type'] == 2){
                $pay_type = '微信';
            }else{
                $pay_type = '';
            }
            $objSheet -> setCellValue("A".$j,$v['nickname'])
                -> setCellValue("B".$j,$v['order_sn'])
                -> setCellValue("C".$j,$v['total_money'])
                -> setCellValue("D".$j,$type)
                -> setCellValue("E".$j,date("Y-m-d H:i:s",$v['pay_time']))
                -> setCellValue("F".$j,$order_type)
                -> setCellValue("G".$j,$pay_type)
                -> setCellValue("H".$j,$v['address_info'])
                -> setCellValue("I".$j,$v['detail']);
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