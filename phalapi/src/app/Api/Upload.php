<?php
namespace App\Api;

use PhalApi\Api;
/**
 * 文件上传服务类
 * @author ipso
 */

class Upload extends Api{
	public function getRules(){
		return array(
			'uploadImg' => array(
            'file' => array(
                'name' => 'file',        // 客户端上传的文件字段
                'type' => 'file', 
                'require' => true, 
                'max' => 2097152,        // 最大允许上传2M = 2 * 1024 * 1024, 
                'range' => array('image/jpeg', 'image/png'),  // 允许的文件格式
                'ext' => 'jpeg,jpg,png', // 允许的文件扩展名 
                'desc' => '待上传的图片文件',
            ),
        ),
        "base64UploadUPY" => array(
            "img" => array("name" => "img"),
            "name" => array("name" => "name"),
        )
		);
	}

	/**
	 * 图片上传
	 */
	public function uploadImg(){
		var_dump($this->file);
		$tmpName = $this -> file['tmp_name'];
		$name = md5($this -> file['name'] . $_SERVER['REQUEST_TIME']);
		$ext = strrchr($this->file['name'], '.'); // 查找'.'在name中最后一次出现的位置
		$uploadFolder = sprintf('%s/public/uploads/', API_ROOT); // 文件
		if (!is_dir($uploadFolder)) { // 查找uploads文件夹是否存在，不存在则建立新的文件夹
      mkdir($uploadFolder, 0777);
		}
		$imgPath = $uploadFolder .  $name . $ext;
    if (!move_uploaded_file($tmpName, $imgPath)) {
      $rs["msg"] = "移动文件失败！";
      return $rs;
		}
		return 1;
	}

	/**
	 * 图片转存upyun
	 */
	public function base64UploadUPY(){}
}