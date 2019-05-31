<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\MyRules;
use App\Model\Picture as Model;
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
          'max' => 20 * 1024 * 1024,        // 最大允许上传2M = 2 * 1024 * 1024, 
          'range' => array('image/jpeg', 'image/png'),  // 允许的文件格式
          'ext' => 'jpeg,jpg,png', // 允许的文件扩展名 
          'desc' => '待上传的图片文件',
				),
				'type' => array('name' => 'type', 'require' => true, 'desc' => '图片分类'),
      ),
		);
	}

	/**
	 * 图片上传
	 */
	public function uploadImg(){
		$model = new Model();
		$tmpName = $this -> file['tmp_name'];
		$type = $this -> type;
		$name = md5($this -> file['name'] . $_SERVER['REQUEST_TIME'] . rand(0, 10));
		$ext = strrchr($this->file['name'], '.'); // 查找'.'在name中最后一次出现的位置并将该位置到末尾的子字符串返回
		$uploadFolder = sprintf('%s/public/uploads/', API_ROOT);
		if (!is_dir($uploadFolder)) {
      mkdir($uploadFolder, 0777);
		}
		$imgPath = $uploadFolder .  $name . $ext;
    if (!move_uploaded_file($tmpName, $imgPath)) {
      return MyRules::myRuturn(0, '上传文件失败！');
		}
		$data = array(
			'src'  => $imgPath,
			'type' => $type,
		);
		$sql = $model -> insertOne($data);
		if(!$sql){
			@unlink($imgPath);
			return MyRules::myRuturn(0, '上传文件失败！');
		}
		return MyRules::myRuturn(1, '成功', $imgPath);
	}
}