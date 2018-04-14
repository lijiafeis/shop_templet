<?php
/**
 * 前台申请升级
 * User: lijiafei
 * Date: 2018/4/11
 * Time: 下午2:48
 */
namespace app\home\controller;
class Upgrade extends Action{

    public function getGrade(){
        $userInfo = db('user') -> field('grade,state') -> where(['user_id' => $this->user_id]) -> find();
        $money = array(100,200,300,500);
        $return['code'] = 10000;
        $return['data'] = $userInfo;
        $return['money'] = $money;
        return json($return);
    }

    /**
     * 获取升级记录
     */
    public function getUpgradeLog(){
        $page = input('post.page',1);
        $number = input('post.number',20);
        $count = db('upgrade_log') -> where(['user_id' => $this->user_id]) -> count();
        $info = db('upgrade_log')
            -> field('old_grade,new_grade,pic_url,create_time,state')
            -> where(['user_id' => $this->user_id])
            -> order('id desc')
            -> page($page,$number)
            -> select();
        $return['code'] = 10000;
        $return['data'] = ['count' => $count,'info' => $info];
        return json($return);
    }

    public function savePicUrl(){
        $base64_image_content = input('post.base64img','');
        if(!$base64_image_content){
            $return['code'] = 10001;
            $return['data'] = '无信息';
            return json($return);
        }
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $new_file = "uploads/upgrade/";
            if(!is_dir($new_file)){
                mkdir($new_file,777);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $return['code'] = 10000;
                $return['data'] = $new_file;
                return json($return);
            } else {
                $return['code'] = 10002;
                $return['data'] = '保存失败';
                return json($return);
            }
        }else{
            $return['code'] = 10003;
            $return['data'] = '上传失败';
            return json($return);
        }

    }

    /**
     * 保存信息
     */
    public function setUpgradeInfo(){
        $data['new_grade'] = input('post.new_grade',0);
        $data['pic_url'] = input('post.pic_url','');
        if(!$data['new_grade'] || !$data['pic_url']) {
            $return['code'] = 10001;
            $return['data'] = '数据错误';
            return json($return);
        }
        //判断之前有没有申请过没有审核的记录
        $is_true = db('upgrade_log') -> where(['user_id' => $this->user_id,'state' => 0]) -> find();
        if($is_true){
            $return['code'] = 10004;
            $return['data'] = '上次申请没有审核';
            return json($return);
        }
        //判断升级的级别是否比上级高
        $userInfo = db('user') -> field('grade,p_id,state') -> where(['user_id' => $this -> user_id]) -> find();
        if($userInfo['state'] == 0 || $userInfo['grade'] >= $data['new_grade']){
            $return['code'] = 10001;
            $return['data'] = '升级级别比当前级别低';
            return json($return);
        }
        if($userInfo['p_id']){
            $grade = db('user') -> where(['user_id' => $userInfo['p_id']]) -> value('grade');
            if($grade < $data['new_grade']){
                $return['code'] = 10003;
                $return['data'] = '申请级别太高,请上级先升级';
                return json($return);
            }
        }
        $data['user_id'] = $this->user_id;
        $data['old_grade'] = $userInfo['grade'];
        $data['create_time'] = time();
        $res = db('upgrade_log') -> insertGetId($data);
        if($res){
            $return['code'] = 10000;
            $return['data'] = '提交成功';
            return json($return);
        }
        $return['code'] = 10002;
        $return['data'] = '提交失败';
        return json($return);
    }

















}