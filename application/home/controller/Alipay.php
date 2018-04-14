<?php
/**
 * 支付宝的支付接口
 * User: lijiafei
 * Date: 2018/4/2
 * Time: 下午2:35
 */
namespace app\home\controller;
use think\Controller;

class Alipay extends Controller{
    public function pay(){
        $order_id = input('get.token',0) * 1;
        $order_type = input('get.type',0)*1;
        if(!$order_id || !$order_type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        if($order_type != 1 && $order_type != 2 && $order_type != 3){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        $orderInfo = db('shop_order') -> where(['id' => $order_id]) -> find();
        if(!$orderInfo || $orderInfo['state'] != 0 || $orderInfo['order_type'] != $order_type){
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        //判断支付时间
        if(time() - $orderInfo['create_time'] > 7200){
            $return['code'] = 10001;
            $return['data'] = '订单已过期';
            return json($return);
        }

        require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_config.php");
        require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_submit.class.php");
//        import('alilib.alipay_config',EXTEND_PATH,'.php');
//        import('alilib.alipay_submit',EXTEND_PATH,'.class.php');
        // $order_info = M('agent_orders') -> getByOrder_id($_POST['order_id']);
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $orderInfo['id'];
        //订单名称，必填
        $subject = '商品购买';
        //付款金额，必填
        $total_fee = $orderInfo['total_money'];
        // if($total_fee != $order_info['total_fee']){$this->error('订单金额不符！');exit;}
        // if($out_trade_no != $order_info['order_sn']){$this->error('订单号不符！');exit;}
        //收银台页面上，商品展示的超链接，必填
//        $show_url = "http://".$_SERVER['SERVER_NAME'].U('/user/center/user');
        $show_url = URL.url('/home/Error/index');
        //商品描述，可空
        $body = '';
        /************************************************************/
        //构造要请求的参数数组，无需改动
        //dump($alipay_config);exit;
        $parameter = array(
            "service"       => $alipay_config['service'],
            "partner"       => $alipay_config['partner'],
            "seller_id"  => $alipay_config['seller_id'],
            "payment_type"	=> $alipay_config['payment_type'],
            "notify_url"	=> $alipay_config['notify_url'],
            "return_url"	=> $alipay_config['return_url'],
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset'])),
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $total_fee,
            "show_url"	=> $show_url,
            "body"	=> $body,
        );
        //建立请求
        $alipaySubmit = new \AlipaySubmit($alipay_config);
//		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
//		echo $html_text;
        $html_text = $alipaySubmit->getHtml($parameter);
        $this->assign('html_url',$html_text);
        $this -> assign('order_id',$order_id);
        return $this -> fetch();

    }

    /**
     * 查看支付状态
     */
    public function redirectUrl(){
        $order_id = input('post.order_id',0);
        if(!$order_id){
            echo -1;exit;
        }
        $info = db('shop_order') -> field('state') -> where(['id' => $order_id]) -> find();
        if($info['state'] != 1){
            echo -1;exit;
        }
        echo 1;
    }

    public function return_url()
    {
        $this->redirect('home/Error/index');
//        require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_config.php");
//        require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_submit.class.php");
//        require_once(dirname(dirname(__FILE__)) . '/' . "alilib/alipay_notify.class.php");
//        $alipayNotify = new \AlipayNotify($alipay_config);
//         $verify_result = $alipayNotify->verifyReturn();
//         if($verify_result) {//验证成功
//              if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') { //判断该笔订单是否在商户网站中已经做过处理，进行商户处理 //
////                   $this -> pay_sure($_GET['out_trade_no']);
//                  dump($_GET);
//              } else {
//                  echo "trade_status=".$_GET['trade_status'];
//              }
//              echo "";
//             redirect(url('/home/Index/index'));
//         } else { //验证失败 //如要调试，请看alipay_notify.php页面的verifyReturn函数
//              echo "验证失败";
//         }
//    }

    }
}