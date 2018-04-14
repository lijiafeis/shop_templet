<?php
namespace app\admin\controller;


class Img extends Action {

	function index(){
		$id = $_GET['id'];
		$this->assign('id',$id);
		return $this->fetch();
	}
	
	function pic_upload(){
        $file = request()->file('file');
        $path1 = "/uploads/shop";
        $info = $file-> validate(['size'=>1000000,'ext'=>'jpg,png,gif'])  ->rule('uniqid') -> move('uploads' . DS . 'shop');
        if($info){
            $path = $path1 . '/' . str_replace("\\","/",$info->getSaveName());
            echo json_encode($path);
        }else{
            $a = $file-> getError();
            echo json_encode(0);
        }
	}
	
	function pic_data(){
		$arr =array();
		//获取到指定文件夹内的照片数量
		$img = array('gif','png','jpg');//所有图片的后缀名
		$dir = $_POST['address'];//文件夹名称
		$num = $_POST['num'];
		$pic = array();
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh))!= false){
					$filePath = $dir.$file;
					$arr1 = explode('.',$file);
					if(in_array('png',$arr1) || in_array('jpg',$arr1) || in_array('gif',$arr1)){
						$all = array($filePath);
						$pic = array_merge($pic,$all);
						
					}
					
				}
				closedir($dh);
			}
		}
		//echo "<pre>";
        $new = array();
		foreach($pic as $k=>$p)
		{
			$new[$k]['time'] =  filemtime($p);
			$new[$k]['pic'] =  $p;
		//分行分页显示代码
		}
        rsort($new);
		//var_dump($new);
		foreach($new as $k=>$v)
		{
			$pic[$k] =  $v['pic'];
		//分行分页显示代码
		}
		$arr['num'] = ceil(count($pic)/$num);
		$arr['pic'] = $pic;
		echo json_encode($arr);
	}
}