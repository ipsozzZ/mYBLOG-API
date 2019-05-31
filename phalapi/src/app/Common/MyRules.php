<?php
namespace App\Common;

/* ------------  ipso  -------------- */

class MyRules {

	/**
	 * 定义全局接口相应规则
	 * 
	 * @param code  状态码
	 * @param msg   响应信息
	 * @param data  响应数据
	 * 
	 * @return array
	 */
	public static function myRuturn($code = 0, $msg = '', $data = ''){
		$rule = array(
			'code' => $code,
			'msg'  => $msg,
			'data' => $data
		);

		return $rule;
	}

	/**
	 * 图片转存upyun
	 */
	public function base64UploadUPY(){}


	/**
	 * 将一张图片进行base64编码
	 * @param image_file 图片路径
	 */
	public static function base64EncodeImage($image_file){
		$base64_image = '';
		$image_info = @getimagesize($image_file);
		$image_data = @fread(fopen($image_file, 'r'), @filesize($image_file));
		$base64_image = 'data:' . $image_info['mime'] . ';base64,' . @chunk_split(@base64_encode($image_data));
		return $base64_image;
  }
}