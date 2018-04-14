<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13 0013
 * Time: 17:31
 */
namespace app\admin\controller;
use think\Request;

class Shop extends Action{



    function category(){
        //查询分类
        $categrey = db('shop_category');
        $pid_info = $categrey -> where(" pid = 0 ")->order("code desc") -> select();
        foreach($pid_info as $k=>$val){
            $pid = $val['cate_id'];
            $pid_info[$k]['children'] = $categrey -> where(" pid = '$pid' ")->order("code desc") -> select();
        }
        //dump($pid_info);
        $this -> assign("pid_info",$pid_info);
        return $this->fetch();
    }

    function del_shop_categrey(){
        $cate_id = input('post.id',0);
        db('shop_category') -> where(['cate_id' => $cate_id]) -> delete();
        $arr = array();
        echo json_encode($arr);
    }

    /**
     * 添加和修改分类
     */
    function add_category(){
        $shop_categrey = db('shop_category');
        if(Request::instance() -> isPost()){
            $data['cate_name'] = input('post.cate_name','');
            $data['pid'] = input('post.pid',0);
            $data['code'] = input('post.code',0);
            $data['is_show'] = input('post.is_show',0);
            $cate_id = input('post.cate_id',0);
            if(!$cate_id){
                $data['pic_url'] = $this-> pic_upload('category');
                $shop_categrey -> insert($data);$this->success("新分类创建成功",'category');exit;
            }else{
                //判断是否上传了新图片
                if($_FILES['file']['error'] == 0){
                    //说明有新上传的文件
                    $data['pic_url'] = $this -> pic_upload('category');
                }
                $shop_categrey -> where(['cate_id' => $cate_id]) -> update($data);
                $this->success("分类信息保存成功",'category');exit;
            }
        }
        $cate_id = input('get.cate_id',0);
        if($cate_id){
            $cate_info =  $shop_categrey -> getByCate_id($cate_id);
        }else{
            $cate_info['cate_id'] = 0;
            $cate_info['pid'] = 0;
            $cate_info['code'] = 0;
            $cate_info['cate_name'] = '';
            $cate_info['pic_url'] = 2;
            $cate_info['is_show'] = 0;
        }
        $this -> assign("cate_info",$cate_info);
        //顶级分类信息
        $cate_pid = $shop_categrey -> where(['pid' => 0]) -> select();
        $this -> assign("cate_pid",$cate_pid);
        return $this->fetch();
    }

    function pic_upload($file_name){
        $file = request()->file('file');
        $path1 = "/uploads/{$file_name}";
        if (!is_dir('./'.$path1)) {
            mkdir($path1,777);
        }
        $info = $file-> validate(['size'=>1000000,'ext'=>'jpg,png,gif'])  ->rule('uniqid') -> move('uploads' . DS . $file_name);
        if($info){
            $path = $path1 . '/' . str_replace("\\","/",$info->getSaveName());
            return $path;
        }else{
            $a = $file-> getError();
            return 0;
        }
    }

    /**
     * 商品属性
     */
    function type(){
        if(Request::instance() -> isGet()){
            return $this -> fetch();
        }else{
            $type = db('shop_good_type');
            $spec = db('shop_spec');
            $page = input('post.page',1);
            $number = 10;
            $count=$type -> order('type_id desc') -> count();
            $info = $type
                -> order('type_id desc')
                -> page($page,$number)
                -> select();
            foreach($info as $k=>$v){
                $content = $spec -> where(['type_id' => $v['type_id']]) ->order("spec_id desc") -> select();
                $name = '';
                foreach($content as $jj){
                    if($name != ''){
                        $str = '、';
                    }else{$str = '';}
                    $name = $name.$str.$jj['spec_name'];
                }
                $info[$k]['name'] = $name;
            }
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }
    function type_add(){
        $spec = db('shop_good_type');
        if(Request::instance() -> isPost()){
            $data['type_name'] = input('post.type_name','');
            if(!$data['type_name']){exit;}
            $type_id = input('post.id',0);
            if($type_id){
                /* 保存修改数据 */
                $spec->where(['type_id' => $type_id]) -> setField('type_name',$data['type_name']);
            }else{
                /* 保存新建数据 */
                $spec_id = $spec -> insert($data);
            }
            $this->success('属性组信息保存成功','type');exit;
        }else{
            $type_id = input('get.id',0);
            if($type_id){
                /* 加载修改视图数据 */
                $info = $spec -> where(['type_id' => $type_id]) -> find();
                if($info == null){$this -> redirect('type');exit;}
            }else{
                $info['type_name'] = '';
                $info['type_id'] = '';
            }
            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    function type_del(){
        if(!input('post.id')){exit;}
        $id = input('post.id');
        db('shop_good_type') -> where(['type_id' => $id]) -> delete();
        db('shop_spec') -> where(['type_id' => $id]) -> delete();
        $arr =array('success'=>1);
        echo json_encode($arr);
    }

    function type_spec(){
        $type_id = input('get.id',0);
        if(!$type_id){exit;}
        $type = db('shop_good_type');
        $type_info = $type->where(['type_id' => $type_id])->find();
        $this->assign('type_info',$type_info);
        $spec = db('shop_spec');
        $info=$spec->where(['type_id' => $type_id])->order('spec_id desc')->select();
        $this->assign('info',$info);
        return $this->fetch();
    }

    function type_spec_add(){
        $type = db('shop_good_type');$spec = db('shop_spec');
        if(Request::instance()->isPost()){
            $data['spec_name'] = input('post.spec_name');
            $data['type_id'] = input('post.type_id',0);
            $data['value'] = input('post.value','');
            $data['type'] = input('post.type',0);
            $id = input('post.id',0);
            if(!$data['spec_name']|| !$data['value']){exit;}
            $arr = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',',$data['value']);
//			$shop_spec_info = M('shop_spec_info');
            if($id){
                /* 保存修改数据 */
                $spec_id = $id;
                $spec->where(['spec_id' => $spec_id]) -> update($data);
            }else{
                /* 保存新建数据 */
                $spec_id = $spec -> insert($data);
            }
            $this->success('属性组信息保存成功',url('type_spec') . "?id=" . $data['type_id']);
        }else{
            $type_id = input('get.type_id',0);
            if(!$type_id){
                $this->redirect('type');exit;
            }
            $spec_id = input('get.spec_id',0);
            if($spec_id){
                /* 加载修改视图数据 */
                $info = $spec -> where(['spec_id' => $spec_id]) -> find();
                if($info == null){$this->redirect('type');exit;}

            }else{
                $info['spec_id'] = '';
                $info['spec_name'] = '';
                $info['type_id'] = '';
                $info['value'] = '';
                $info['type'] = '';
            }
            $this->assign('info',$info);
            $type_info = $type -> where(['type_id' => $type_id]) -> find();
            $this->assign('type_info',$type_info);
            return $this->fetch();
        }
    }
    function type_spec_del(){
        $id = input('post.id');
        if(!$id){exit;}
        db('shop_spec') -> where(['spec_id' => $id]) -> delete();
        $arr =array('success'=>1);
        echo json_encode($arr);
    }



    public function shop(){
        if(Request::instance() -> isGet()){
//            $config = cache('config');
//            if(!$config){
                $config = db('web_config') -> find();
                cache('web_config',$config);
//            }
            $time = json_decode($config['buy_time'],true);
            if($time){
                $startTime = date('Y-m-d H:i:s',$time['start']);
                $endTime = date('Y-m-d H:i:s',$time['end']);
            }else{
                $startTime = 0;
                $endTime = 0;
            }
            $this -> assign('start',$startTime);
            $this -> assign('end',$endTime);
            return $this -> fetch();
        }else{
            $page = input('post.page',1);
            $number = 10;
            $name = input('post.name','','htmlspecialchars');
            $where = array();
            $where1 = array();
            if($name){
                $where['good_name'] = $name;
                $where1['a.good_name'] = $name;
            }
            $count=db('shop_goods') -> where($where) -> count();
            $info = db('shop_goods')
                -> alias('a')
                -> field('a.*,b.cate_name as p_name,c.cate_name as g_name')
                -> join('wx_shop_category b','a.cate_pid = b.cate_id','left')
                -> join('wx_shop_category c','a.cate_gid = c.cate_id','left')
                -> where($where1)
                -> order('a.good_id desc')
                -> page($page,$number)
                -> select();
            $return['number'] = $count;
            $return['data'] = $info;
            return json($return);
        }
    }

    public function setQgTime(){
        $startTime = input('post.start');
        $endTime = input('post.end');
        $startTime = strtotime($startTime);
        $endTime = strtotime($endTime);
        if($endTime - $startTime < 0){
            $arr['code'] = -1;
            $arr['info'] = '结束时间必须大于开始时间';
            return json($arr);
        }
        $data['start'] = $startTime;
        $data['end'] = $endTime;
        $updateInfo['buy_time'] = json_encode($data);
        $id = db('web_config') -> field('id') -> find();
        $res = db('web_config') -> where(['id' => $id['id']]) -> update($updateInfo);
        if($res){
            cache('config',null);
            $arr['code'] = 1;
            $arr['info'] = '设置成功';
            return json($arr);
        }else{
            $arr['code'] = -1;
            $arr['info'] = '设置失败';
            return json($arr);
        }
    }

    public function delShop(){
        $good_id = input('id',0);
        if(!$good_id){
            $info['code'] = -1;
            $info['info'] = '数据错误';
            return json($info);
        }
        $arr = array();
        db('shop_goods_pic') -> where(['good_id' => $good_id]) -> delete();
        $res = db('shop_goods') -> where(['good_id' => $good_id]) -> delete();
        if($res){$arr['code'] = 1;}else{$arr['info'] = 0;}
        return json($arr);
    }

    public function add_good(){
        if(Request::instance() -> isGet()){
            $shop_categrey = db('shop_category');
            $category = $shop_categrey -> where(['pid' => 0]) -> select();
            $this->assign("categrey",$category);
            foreach($category as $k=>$v){
                $id = $v['cate_id'];
                $jscategrey[$id] = $shop_categrey -> where(['pid' => $v['cate_id']]) -> select();
            }
            foreach($category as $k=>$v){
                $categrey[$k]['arr'] = $shop_categrey -> where(['pid' => $v['cate_id']]) ->order("code desc") -> select();
            }
            $shop_good_type = db('shop_good_type');
            $type_info = $shop_good_type ->order("type_id desc")-> select();
            $this->assign('type_info',$type_info);
            $this->assign("jscategrey",json_encode($jscategrey));
            return $this->fetch();
        }else{
            $data['good_name'] = input('post.good_name','');
            $data['cate_gid'] = input('post.cate_gid','');
            $data['cate_pid'] = input('post.cate_pid','');
            $data['type_id'] = input('post.type_id','');
//            $data['fy_one'] = input('post.fy_one','');
//            $data['fy_two'] = input('post.fy_two','');
//            $data['fy_three'] = input('post.fy_three','');
            $data['jifen_bili'] = input('post.jifen_bili','');
            $data['good_price'] = input('post.good_price',0);
            $data['market_price'] = input('post.market_price',0);
            $data['xiaoliang'] = input('post.xiaoliang',0);
            $data['number'] = input('post.number',0);
            $data['good_desc'] = input('post.good_desc','');
            if(!$data['good_name'] || !$data['good_price'] || !$data['number']){
                $this -> error('格式不正确','add_good');
            }
//            //判断三级分佣是否合理
//            $is_true1 = preg_match("/^.+,.+$/",$data['fy_one']);
//            $is_true2 = preg_match("/^.+,.+$/",$data['fy_two']);
//            $is_true3 = preg_match("/^.+,.+$/",$data['fy_three']);
//            if(!$is_true1 || !$is_true2 || !$is_true3){
//                $this -> error('三级佣金不正确','add_good');
//            }
            //判断三级的佣金的个数是否正确
            $shop_goods = db('shop_goods');$good_pic = db('shop_goods_pic');
            $good_id = $shop_goods -> insertGetId($data);
            if($data && $good_id){
                $arr = array_keys(input('post.'));
                foreach($arr as $val){
                    if(strstr($val,'pic')){
                        if(input($val)){
                            $good_pic->insert(array('good_id'=>$good_id,'pic_url'=>input($val)));
                        }
                    }
                }
            }
            $this->success("添加商品成功",'shop');exit;
        }

    }

    /**
     * 修改商品信息
     */
    public function edit_good(){
        $shop_goods = db('shop_goods');
        $good_pic = db('shop_goods_pic');
        if(Request::instance() -> isGet()){
            $good_id = input('get.id',0);
            if(!$good_id){
                $this -> error('数据错误','shop');
            }else{
                $good_id = input('get.id',0)*1;
                $shop_categrey = db('shop_category');
                $categrey = $shop_categrey -> where(['pid' => 0]) -> select();
                $good_info = $shop_goods -> getByGood_id($good_id);
                $bannar = $good_pic -> where(['good_id' => $good_id]) -> order("code desc") -> select();
                $this->assign("categrey",$categrey);
                $this->assign("good_info",$good_info);
                $this->assign("imgList",$bannar);
                foreach($categrey as $k=>$v){
                    $id = $v['cate_id'];
                    $jscategrey[$id] = $shop_categrey -> where(['pid' => $v['cate_id']]) -> select();
                }
                foreach($categrey as $k=>$v){
                    $categrey[$k]['arr'] = $shop_categrey -> where(['pid' => $v['cate_id']]) ->order("code desc") -> select();
                }
                /* 查询商品类型属性 */
                $shop_good_type = db('shop_good_type');
                $type_info = $shop_good_type ->order("type_id desc")-> select();
                $this->assign('type_info',$type_info);
                $this->assign("jscategrey",json_encode($jscategrey));
                return $this->fetch();
            }
        }else{
            $shop_goods = db('shop_goods');$good_pic = db('shop_goods_pic');$good_id =input('id',0);
            if(!$good_id){
                $this -> error('数据错误','shop');
            }else{
                $data['good_name'] = input('post.good_name','');
                $data['cate_gid'] = input('post.cate_gid','');
                $data['cate_pid'] = input('post.cate_pid','');
                $data['type_id'] = input('post.type_id','');
//                $data['fy_one'] = input('post.fy_one','');
//                $data['fy_two'] = input('post.fy_two','');
//                $data['fy_three'] = input('post.fy_three','');
                $data['jifen_bili'] = input('post.jifen_bili','');
                $data['good_price'] = input('post.good_price',0);
                $data['market_price'] = input('post.market_price',0);
                $data['xiaoliang'] = input('post.xiaoliang',0);
                $data['number'] = input('post.number',0);
                $data['good_desc'] = input('post.good_desc','');
                if(!$data['good_name'] || !$data['good_price'] || !$data['number']){
                    $this -> error('格式不正确','add_good');
                }
//                //判断三级分佣是否合理
//                $is_true1 = preg_match("/^.+,.+$/",$data['fy_one']);
//                $is_true2 = preg_match("/^.+,.+$/",$data['fy_two']);
//                $is_true3 = preg_match("/^.+,.+$/",$data['fy_three']);
//                if(!$is_true1 || !$is_true2 || !$is_true3){
//                    $this -> error('三级佣金不正确','add_good');
//                }
                $arr = array_keys(input('post.'));
                foreach($arr as $val){
                    if(strstr($val,'pic')){
                        if(input($val)){
                            $good_pic->insert(array('good_id'=>$good_id,'pic_url'=>input($val)));
                        }
                    }
                }
                $shop_goods -> where(['good_id' => $good_id]) -> update($data);
                $this->success("已更新商品信息",'shop');
            }
        }

    }

    public function del_good_pic(){
        $good_pic = db('shop_goods_pic');
        $id = input('post.id',0);
        if(!$id){
            echo json_encode(-1);
        }
        $good_pic -> where(['id' => $id]) -> delete();
        $arr = array();echo json_encode($arr);
    }


    public function setShopBuy(){
        $id = input('post.id',0);
        $is_buy = input('post.is_buy',0);
        $type = input('post.type',0);
        if(!$id || !$type){
            $arr['code'] = -1;
            $arr['info'] = '参数缺失';
            return json($arr);
        }else{
            //根据type的值 确定要修改的是哪个字段
            $field = '';
            switch ($type){
                case 1:
                    $field = 'is_buy';
                    break;
                case 2:
                    $field = 'is_miaosha';
                    break;
                case 3:
                    $field = 'is_new';
                    break;
            }
            if(!$field){
                $arr['code'] = -1;
                $arr['info'] = '参数缺失';
                return json($arr);
            }


            if($is_buy == 0){
                //关闭抢购
                db('shop_goods') -> where(['good_id' => $id]) -> setField($field,0);
                $arr['code'] = 1;
                $arr['info'] = 'ok';
                return json($arr);
            }else if($is_buy == 1){
//                if($field == 'is_buy'){
//                    db('shop_goods') -> where(['is_buy' => 1]) -> setField('is_buy',0);
//                }
                if($field == 'is_buy'){
                    //199 商品 ,is_秒杀 商品 is_new 推荐商品
                    $data['is_miaosha'] = 0;
                    $data['is_new'] = 0;
                }else if($field == 'is_miaosha'){
                    $data['is_buy'] = 0;
                    $data['is_new'] = 0;
                }else if($field == 'is_new'){
                    $data['is_buy'] = 0;
                    $data['is_miaosha'] = 0;
                }
                $data[$field] = $is_buy;
                db('shop_goods') -> where(['good_id' => $id]) -> update($data);
                $arr['code'] = 1;
                $arr['info'] = 'ok';
                return json($arr);
            }
        }

    }



}