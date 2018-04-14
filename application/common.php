<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//加密方法
function xgmd5($pwd){
    $res = 'xiguakeji.com'.$pwd;
    return md5($res);
}

/*
       生成随机码
        */
function mkCode($_len){
    //通过类的参数获取需要的随机数的个数。这个值可以自由的指定
    $len = $_len;
    //我们生成的随机数的字母喝数字就是在这里面进行随机生成。
    $str = 'ABCDEFGHIGKLMNOPQRST1234567890';
    $code = '';
    //通过循环的生成随机数进行获取
    for($i = 0; $i < $len; $i++){
        //生成随机数
        $j = mt_rand(0,strlen($str)-1);
        //把随机生成的随机数拼接起来。
        $code .= $str[$j];
    }
    //把生成的随机数，保存在session中，便于当我们输入验证码是验证是否正确。
    session('imgCode',$code);
    return $code;
}

/**
 * @param int $_len 生成的字符的长度
 * @param int $_pixel 干扰点的个数
 * @param int $_width
 * @param int $_height
 * @return resource
 */
function makeImage($_len = 4,$_pixel = 100,$_width = 100,$_height = 20){
    //获取随机生成的随机码
    $code = mkCode($_len);
    //通过类的属性指定图形的大小,默认是100,20
    $canvas = imagecreatetruecolor($_width, $_height);
    //随机生成一个颜色的画笔
    $paint = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    //把背景的颜色进行改变，默认是黑色的。
    imagefill($canvas, 10, 10, $paint);
    //创建一个画随机码的笔，颜色也是随机生成的。
    $paint_str = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    //把随机码打印在画布上。
    imagestring($canvas, 4, 20, 2, $code, $paint_str);
    //绘制干扰点的颜色
    $paint_pixel = imagecolorallocate($canvas,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    //通过类的属性指定需要多少个干扰点。
    for($i = 0; $i < $_pixel; $i++){
        //绘制不同的干扰点，而绘制的位置也是随机生成的。
        imagesetpixel($canvas, mt_rand(0,imagesx($canvas)),  mt_rand(0,imagesy($canvas)), $paint_pixel);
    }
    ob_start ();
    imagepng ($canvas);
    $image_data = ob_get_contents ();
    ob_end_clean ();
    $image_data = "data:image/png;base64,". base64_encode ($image_data);
    return $image_data;
}

function verifyTel($tel){
    if(!$tel){return false;}
    preg_match_all("/^1[345789]\d{9}$/",$tel,$array);
    if($array[0]){
        return true;
    }else{
        return false;
    }
}


function verifyPass($password){
    if(!$password){return false;}
    preg_match_all("/^[a-zA-Z]\w{5,17}$/",$password,$array);
    if($array[0]){
        return true;
    }else{
        return false;
    }
}

/*发送短信接口*/
function msg_everify($code,$tel,$appname){
    $msg = cache('msg');
    if(!$msg){
        $msg = db('msg') -> find();
        cache('msg',$msg);
    }
    $key = $msg['key'];
    $tpl_id = $msg['tel_id'];
    $tpl_value = '#code#='.$code.'&#app#='.$appname;//您设置的模板变量，根据实际情况修改
    $tpl_value = urlencode($tpl_value);
    $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
    $smsConf = array(
        'key'   => $key,
        'mobile'    => $tel, //接受短信的用户手机号码
        'tpl_id'    => $tpl_id,
        'tpl_value' => $tpl_value,
    );

    $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
    if($content){
        $result = json_decode($content,true);
        $error_code = $result['error_code'];
        if($error_code == 0){
            //状态为0，说明短信发送成功
            return true;
        }else{
            file_put_contents('./test_dir/sendMsg.txt',$content);
            //状态非0，说明失败
            return false;
        }
    }else{
        //返回内容异常，以下可根据业务逻辑自行修改
        return false;
    }
}
/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}

//获取访客ip
function getIp()
{
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

/**
 * 删除目录和目录下的文件
 */
function rmAllDir($dir_path){
    $dir = opendir($dir_path);
    //通过readdir函数获取目录下的所有目录
    while(false !== ($fileName = readdir($dir))){
        //对当前的目录进行拼接
        if($fileName != '.' && $fileName != '..'){
            $dir_name = $dir_path . '/' . $fileName;
            if(is_dir($dir_name)){
                //是一个目录，通过递归在此判断。
                rmAllDir($dir_name);
                //递归完成，当前目录下为空，删除目录。
            }else if(is_file($dir_name)){
                //是文件删除文件。
                unlink($dir_name);
            }
        }

    }
    closedir($dir);
    rmdir($dir_path);
}

/**
 * @param $dir
 * @return array
 * 通过文件路径 获取文件下的所有文件
 */
function getFileByPath($dir){
    $file_arr = array();
    if(is_dir($dir)){
        //打开
        if($dh = opendir($dir)){
            //读取
            while(($file = readdir($dh)) !== false){

                if($file != '.' && $file != '..'){
                    $img_ext = substr($file, strrpos($file, '.'));
                    if($img_ext == '.zip' || $img_ext == '.jpg' || $img_ext == '.png' || $img_ext == '.gif' || $file == '__MACOSX'){

                    }else{
                        $file_arr[] = $file;
                    }

                }

            }
            //关闭
            closedir($dh);
        }
    }
    return $file_arr;
}

//判断打开方式 1 是 0不是在微信中
function is_weixin(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return 1;
    }
    return 0;
}

//https请求(支持GET和POST)
function http_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    //var_dump(curl_error($curl));
    curl_close($curl);
    return $output;
}

/**
 * 获取二十层的总业绩
 */
function getUserAllYj($user_id){
    if(!$user_id){
        return 0;
    }
    //定义变量存储业绩
    $yejiArr = array();

    $zt_yeji = db('user') -> where(['user_id' => $user_id]) -> value('yeji');
    $yejiArr[] = $zt_yeji;
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => $user_id]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();

    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();



    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();



    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();

    $yejiArr[] = $data['zongyeji'];
    return array_sum($yejiArr);

}

/**
 * 获取二十层的总业绩
 */
function getUserMonthYj($user_id){
    if(!$user_id){
        return 0;
    }
    //定义变量存储业绩
    $yejiArr = array();

    $zt_yeji = db('user') -> where(['user_id' => $user_id]) -> value('month_yeji');
    $yejiArr[] = $zt_yeji;
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => $user_id]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();

    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();



    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }

    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();
    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];
    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();


    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();



    if(!$data['user_id']){
        return array_sum($yejiArr);
    }else{
        $yejiArr[] = $data['zongyeji'];

    }
    $data = db('user') -> field('GROUP_CONCAT(user_id) as user_id,sum(month_yeji) as zongyeji') -> where(['p_id' => ['in',$data['user_id']]]) -> find();

    $yejiArr[] = $data['zongyeji'];
    return array_sum($yejiArr);

}

function setUserNumber($user_id){
    //把这个注册的人的上12级查找出来保存到user_number表中
    $pId = array();
    setUserPid($user_id,$pId,1);
    $is_true = db('user_number') -> where(['user_id' => $user_id]) -> find();
    if($is_true){
        $res = db('user_number') -> where(['user_id' => $user_id]) ->  update($pId);
    }else{
        $pId['user_id'] = $user_id;
        $res = db('user_number') -> insert($pId);
    }

}

function setUserPid($user_id,&$arr,$i){
    if($i > 20){
        return;
    }
    if(!$user_id){return;}
    $p_id = db('user') -> where(['user_id' => $user_id]) -> value('p_id');
    if(!$p_id){
        return;
    }else{
        $arr['sj_' . $i] = $p_id;
        $i++;
        setUserPid($p_id,$arr,$i);
    }
}

/**
 * @param $user_id 用户id
 * @param $number 数量，金额或积分
 * @param $state 1 金额 2 积分
 * @param $type 类型 11 积分兑换商品 12 积分参与大转盘 13购买商品获得积分 1 升级为小V 或大V的时候的晋级奖励 2 上三级的佣金奖 3 团队奖励薪酬 4 联合创始人的薪酬佣金 5 提现的花费,这时就是order_id为user_withdraw_log的id. 6 后台充值余额
 */
function finance_log($user_id,$number,$state,$type,$xj_userid = 0,$order_id = 0){
    if(!$user_id || !$number || !$state){
        return;
    }
    $financeLog['user_id'] = $user_id;
    $financeLog['number'] = $number;
    $financeLog['state'] = $state;
    $financeLog['type'] = $type;
    $financeLog['xj_userid'] = $xj_userid;
    $financeLog['create_time'] = time();
    $financeLog['order_id'] = $order_id;
    db('finance_log') -> insert($financeLog);
}

/**
 * 通过订单的名字编号获取快递的名字
 */
function getKdname($name){
    if(!$name){
        return '';
    }
    $return_name = '';
    switch ($name){
        case 'yuantong':
            $return_name = '圆通速递';
            break;
        case 'yunda':
            $return_name = '韵达快运';
            break;
        case 'shunfeng':
            $return_name = '顺丰';
            break;
        case 'shentong':
            $return_name = '申通';
            break;
        case 'tiantian':
            $return_name = '天天快递';
            break;
        case 'zhongtong':
            $return_name = '中通速递';
            break;
    }

    return $return_name;
}

function pay($sn,$money,$aliay_number,$name){
    if(!$sn || !$money || !$aliay_number || !$name){
        return;
    }
    //header('content-type:text/html;charset=utf8');
    //$ip = $_SERVER["REMOTE_ADDR"];
    //return json(1);
    \Think\loader::import('aop.AopClient');
    $aop = new \AopClient ();
    $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
    $aop->appId = '2018012602084894';
    $aop->rsaPrivateKey = 'MIIEpQIBAAKCAQEA4lZX7RjoMNyhVhRrrBZR+OqsSdget/OKDZVFqohrpi5Vz6YSsucpe0FJm2D3IwCHilDWCCULWS1JMy7XsOVxzErtgj0iRzytUJRNR7Bgmr/YGRv0WcT70+hxBJAJA0xqoJstHiKBd7augLcvKRskVrqll4y0g+OdmFLrsn2Do7uBb/WeqY4G19aQSW3RnSiaa5EWLPpUHuWaQgNcf2RLlEmWnvkV0hJIgvK78GeROwbURjD+F++Sw9Bo3TwuCBcObq4M7/HR7AmaXSYQuhi6mvDren/fkH0wUdJDhAUDWAXtxEMMYbaKX/7VLZrwJK36HqnQOVynee0RJ4lBzLpwbQIDAQABAoIBAEtnzL9XDvRIbQ/KmdypSwIM3P11HTbX0mSYGK+p54Nj6H7Xq18jGHTR2X4EnhFxObbhG413GgLJzZtZvc5XgsQ3Kk27pFHraypvXhfGMUkdJReoco39zJBa3lxQyE/rA5MiX7Osd0m0+Qo0/WdKfZ7PbB/DZtiR2o1HAvNiUZsYWncHGaJ4Jvggc3uzWjGT4seUJocVGt1YZWXG/FJo27INiAe0F76/V7Dcx3U3mH8jVTNaKS8gJprEyfQeJSHPwhElHXTwV8hYy/9jf6TMm7pE2B25aDbvzQOYm6ChCQQ7el/4TKK8K1Y1sOKJL++gaoVM94FY6Yl+yxCTa0qTlFkCgYEA/dk/l+qErYwgiWSeNKpc2H6+j1ohDS5PhLJ279klut5EaHONWsR8UHtR+hI7SNh2vGZ1egOxwWcyZbI0TMumpAxHBK3Yi0UNOyAZ3sQq+HOZ0oFytY4xr5kMCKgVvReJGiAAwAw3nwWMr3BrKy6bHXAOTsw4HZca8oCblOso9DcCgYEA5EFoAGV8GDg7Lb+EJz5fjnuYstL+ZgddU8tLNbDCa+hmMM4Q9qb7FCS/NvO7/FRCtJR9cDm+y00Nw9fmiw8fughFhABfJcSMj1rhqzcjqbt3Dg/loPEPKtst7vYkxGtxDEFvJnldRKsryRw0LCFVjRjjKIZko78IYC2GGYYCtnsCgYEApE/cRwRJT2C1qtlTQnnH0WbxCC9p13NTi2xNamEfd/7pPscVB1zJrvq0DG+CqltbOAYGIq2DgNHAoG0iR1dHDUbZLWEuGq/eqZfUxwopWlrRhZ2+12AsLyKc1HmgYJ58Y0m10pnV4vwfnWviIrhvNTXUPRMZe6XUjoXKrzEseC8CgYEAq/SKQSIzJpvWGVTaXiYjHtgF5VIGzR5nNKVGd6A+F8Twl3vmU6rgJAC6/M8Jo8JmrlvfVBhsoAPghtWznLc8E43/sL4G8BDuQ2EX+UCE4W2U90cKmwB/iK2uIQPWFxNKCw2Qis+LcBvz1IIm28gRB0bkerckQie8S5iAGeJXUNkCgYEAhgJTRXxc2zoyXKS/d4a6xDenozJtvfNoed+3dGxO0/MWasvURQSXfbqkNcQJ9PLUnsoggyoLbuaR+3yfrVtgBxSuPrPfhtzxNjWrKchSv8h03mxQuhE+Clo7xECtDfzgJt4PIAHMbh1Yw8Qamy5tMB0CuM0iQNCwwrUmsKeTqwE=';
    $aop->alipayrsaPublicKey='';
    $aop->apiVersion = '1.0';
    $aop->signType = 'RSA2';
    $aop->postCharset='utf-8';
    $aop->format='json';
    $out_sn =$sn;
    //$out_sn ='167115045237252653';
    //查询转账是否已完成
    \Think\loader::import('aop.request.AlipayFundTransOrderQueryRequest');
    $record_request = new \AlipayFundTransOrderQueryRequest ();
    $record_data = '{
			 "out_biz_no":"'.$out_sn.'"
		 }';
    $record_request->setBizContent($record_data);
    $record = $aop->execute ( $record_request);
    $record_responseNode = str_replace(".", "_", $record_request->getApiMethodName()) . "_response";
    $record_resultCode = $record->$record_responseNode->code;
    if($record_resultCode == 10000){return $record_resultCode;exit;}

    \Think\loader::import('aop.request.AlipayFundTransToaccountTransferRequest');
    $request = new \AlipayFundTransToaccountTransferRequest ();

    $amount = $money;

    $payee_account = $aliay_number;
    $payee_real_name = $name;
    // $amount = '1';
    // $payee_account = '529157244@qq.com';
    // $payee_real_name = '于春峰';
    $data = '{
            "out_biz_no":"'.$out_sn.'",
            "payee_type":"ALIPAY_LOGONID",
            "payee_account":"'.$payee_account.'",
            "amount":"'.$amount.'",
            "payer_show_name":"商城",
            "payee_real_name":"'.$payee_real_name.'",
            "remark":"商城"
            }';
    $request->setBizContent($data);
    $result = $aop->execute ( $request);
    $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
    $resultCode = $result->$responseNode->code;
//return $resultCode;
    if(!empty($resultCode)&&$resultCode == 10000){
        return $resultCode;
    } else {
        return $result->$responseNode->sub_code;

    }
}


