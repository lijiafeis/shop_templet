<?php
namespace app\wxapi\controller;
use think\Controller;

class Qrimg extends Controller{

    public function create_img($user_id,$nickname){
//        ob_clean();
//        header('content-type:image/gif');

        $erweima_img='uploads/qr/qr/'.$user_id.'.jpg';
        $head_img="uploads/qr/head_img/".$user_id.".jpg";
        $qrset = db('qrset') -> find();
        //相关参数
        $head_height=$head_width=$qrset['head_size'];
        $erweima_height=$erweima_width=$qrset['qr_size'];
        $dst_path='.' . $qrset['pic_url'];
        $str=$nickname;
        $font_size=$qrset['font_size'];
        $fnt_x=$qrset['font_x'];
        $fnt_y=$qrset['font_y'];
        //载入字体zt.ttf
        $fnt = 'uploads/qr/msyh.ttf';
        //头像缩小
        $src1=$this->img_suo($head_img,$head_width,$head_height);
        //二维码缩小
        $src=$this->img_suo_png($erweima_img,$erweima_width,$erweima_height);
        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        //$src = imagecreatefromstring(file_get_contents($src_path));
        //获取水印图片的宽高
        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        imagecopymerge($dst, $src1, $qrset['head_x'], $qrset['head_y'], 0, 0, $head_width, $head_height, 100);
        imagecopymerge($dst, $src, $qrset['qr_x'],$qrset['qr_y'], 0, 0, $erweima_width, $erweima_height, 100);
        //如果水印图片本身带透明色，则使用imagecopy方法
        //imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);
        //创建颜色，用于文字字体的白和阴影的黑
        $color = explode(',',$qrset['font_color']);
        $black=imagecolorallocate($dst,$color[0],$color[1],$color[2]);
        $white=imagecolorallocate($dst,$color[0],$color[1],$color[2]);
        imagettftext($dst,$font_size, 0, $fnt_x+1, $fnt_y+1, $black, $fnt, $str);
        imagettftext($dst,$font_size, 0, $fnt_x, $fnt_y, $white, $fnt, $str);
        if(!is_dir('uploads/qr/img/')){
            mkdir('uploads/qr/img/');
        }
        ImageJPEG($dst,'uploads/qr/img/'.$user_id.'.jpg'); // 保存图片,但不显示
//        imagegif($dst);
//        imagedestroy($dst);die;
        return 'uploads/qr/img/'.$user_id.'.jpg';
    }
	function img_suo($img='head.jpg',$new_width=100,$new_height=100){
		list($width, $height) = getimagesize($img);
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($img);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		return $image_p;
	}
	function img_suo_png($img='head.jpg',$new_width=100,$new_height=100){
        list($width, $height) = getimagesize($img);
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefrompng($img);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        return $image_p;
	}
}
	