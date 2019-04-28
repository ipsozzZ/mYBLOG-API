<?php
namespace App\Common;

use App\Model\User;
use App\Model\Admin;

class GD {
	// 定义6种默认颜色
	private $colors = array(
		array(
			"R" => "45",
			"G" => "140",
			"B" => "240"
		),
		array(
			"R" => "25",
			"G" => "190",
			"B" => "107"
		),
		array(
			"R" => "128",
			"G" => "42",
			"B" => "42"
		),
		array(
			"R" => "237",
			"G" => "63",
			"B" => "20"
		),
		array(
			"R" => "237",
			"G" => "145",
			"B" => "33"
		),
		array(
			"R" => "128",
			"G" => "138",
			"B" => "135"
		)
	);

	// 定义背景颜色
	private $bgcolor = array(
		"R" => "202",
		"G" => "235",
		"B" => "216"
	);

	/**
	 * 通过用户名首字母生成用户头像
	 * @param  str 用户名
	 * @return img base64格式u的图片文件
	 */
	public function getUserDefaultHeaderByName($str){
		$char = $str[0];

		if($char > "@" && $char < "{"){
			$char = strtoupper($char); // 将字符变为大写
		}else{
			$words = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$char  = $words[rand(0,25)];
		}

		$width  = 300;
		$height = 300;

		// 创建画布
		$canvas = imagecreate($width, $height);

		$color = rand(0,5); // 随机获取默认颜色下标

		$backgroud_color = ImageColorAllocate($canvas, $this->bgcolor["R"], $this->bgcolor["G"], $this->bgcolor["B"]);

		$fontSize = 190;

		$paint = imagecolorallocate($canvas, $this->colors[$color]["R"], $this->colors[$color]["G"], $this->colors[$color]["B"]);
		//字体高度
    $textWidth = imagefontwidth($fontSize);
    //字体宽度
		$textHeight = imagefontheight($fontSize);
		$font = 'font/Exo-ExtraBold.ttf';
		//绘制文字
		imagettftext($canvas, $fontSize, 0, 65, 230, $paint, $font, $char);
		
		ob_start();
        imagepng($canvas);
        $img_base64 = ob_get_contents();
        //销毁图片
        imagedestroy($canvas);
    ob_end_clean();
    $img = 'data:image/png;base64,';
    $img .= chunk_split(base64_encode($img_base64));

		return $img;
	}

	/**
	 * 生成num长度的随机验证码
	 * @param  num 生成的验证码长度
	 * @return codeArr 含有base64格式图片信息的数组
	 */
	public function getVerification($num){
		$width     = 150;
    $height    = 40;
    $canvas    =  imagecreate($width, $height);

    // 将默认的背景有黑色改为白色
    $bgColor   = ImageColorAllocate($canvas,255,255,255);
    imagefill($canvas, 0, 0, $bgColor);

    /* 验证码设计 */
    $codeArr   = []; // 保存验证码，用来与用户验证码对比
    $code      = "";
    $fontStyle = 'font/Exo-ExtraBold.ttf';
    for($i = 0; $i < $num; $i++){
        $fontSize  = 20;
        $fontColor = ImageColorAllocate($canvas,10,10,10);
        $codeDataSource      = 'abcdefghijklmnopqrsguvwxyz0123456789'; // 用来生成随机的数字与字母的混合验证码
        $letter      = substr($codeDataSource, mt_rand(0,strlen($codeDataSource) - 1),1);
        $code .= $letter;
        // 每个验证码之间的间隔
        $x     = ($i*$width/4) + rand(5,10);
        $y     = rand(18,28);
        imagettftext($canvas, $fontSize, 0, $x, $y, $fontColor, $fontStyle, $letter);
    }
    $codeArr['code'] = $code;

    /* 生成3条线干扰 */
    $randSpot = rand(10,22); // 用于生成非直线干扰线
    $randSpot1 = rand(10,22);
    $color = $this->colors; // 获取默认的四种颜色作为干扰线颜色
    $randcolor = $color[rand(0,3)];
    $lineWidth = 2;
    for($i = 0;$i < 5; $i++){
        $lineColor = imageColorAllocate($canvas, $randcolor['R'], $randcolor['G'], $randcolor['B']);
        if($i == 2){
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 1, rand(10,22), 50, $randSpot,$lineColor);
        }elseif($i == 3){ // 折线的第一个转折点
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 50, $randSpot, 55, $randSpot1, $lineColor);
        }elseif($i == 4){ // 折线的第二个转折点
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 55, $randSpot1, 149, rand(10,22), $lineColor);
        }
        else{
            imageline($canvas, rand(5,140), rand(18,25), rand(5,140), rand(18,25),$lineColor);
        }
    }

    /* 截取base64 */
    ob_start();
        imagepng($canvas);
        $img_base64 = ob_get_contents();
        //销毁图片
        imagedestroy($canvas);
    ob_end_clean(); 
    $res = 'data:image/png;base64,';
    $res .= chunk_split(base64_encode($img_base64));
		$codeArr['pic'] = $res;
		
		return $codeArr;
	}
}