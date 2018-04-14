<?php
/**
 * Created by PhpStorm.
 * User: lijiafei
 * Date: 2018/4/3
 * Time: 下午4:55
 */
namespace app\home\controller;
use think\Controller;

class Error extends Controller{
    public function index(){
        $tel = input('id','');
        $url = URL . '/shop/#/login?id=' . $tel;
        header("location:{$url}");
    }
}