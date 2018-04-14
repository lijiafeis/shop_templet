<?php
/**
 * 图文回复
 * User: lijiafei
 * Date: 2018/4/4
 * Time: 下午4:51
 */
namespace app\home\controller;
use think\Controller;

class News extends Controller{
    public function index(){
        $news_id = input('get.id');
        if(!$news_id){
            exit;
        }
        db('news') -> where(['id' => $news_id]) -> setInc('read_num',1);
        $newsInfo = db('news') -> where(['id' => $news_id]) -> find();
        if(!$newsInfo){exit;}
        $newsInfo['create_time'] = date('Y-m-d H:i:s',$newsInfo['create_time']);
        if($newsInfo['out_link'] != null){
            header("location:{$newsInfo['out_link']}");
            exit;
        }
        $this->assign('info',$newsInfo);
        return $this->fetch();
    }

    /* 用户点赞 */
    function zan(){
        $id = input('news');
        if(!$id){exit;}
        $news_zan = db('news_zan');$news = db('news');
        /* 查询是否点赞 */
        $time1 = strtotime(date("Y-m-d",time()));
        $time2 = strtotime(date("Y-m-d",strtotime('+1 day')));
        $zan_info = $news_zan -> where("user_id = '$this->user_id' and new_id = '$id' and time >= '$time1' ")->find();
        $zan_info = $news_zan -> where(['user_id' => $this->user_id])->find();
        if($zan_info == null){
            $data = array(
                'new_id'=>$id,
                'user_id'=>$this->user_id,
                'time'=>time()
            );
            $news_zan -> add($data);
            $news -> where("id = '$id' ") -> setInc('zan');
            $arr['type'] = 1;
        }else{
            $news_zan -> where(" id = '$zan_info[id]' ") -> delete();
            $news -> where("id = '$id' ") -> setDec('zan');
            $arr['type'] = 0;
        }
        echo json_encode($arr);

    }
}