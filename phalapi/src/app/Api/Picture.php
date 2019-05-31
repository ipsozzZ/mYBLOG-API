<?php
namespace App\Api;
use PhalApi\Api;
use App\Model\Picture as Model;
use App\Common\MyRules;

/**
 * 图片管理类接口
 */
class Picture extends Api{
	public function getRules(){
		return array(
			'getList' => array(
				'type' => array('name' => 'type', 'desc' => '图片分类'),
				'page' => array('name' => 'page', 'desc' => '当前页'),
				'num' => array('name' => 'num', 'desc' => '每页数量'),
			),
			'getCount' => array(
				'type' => array('name' => 'type', 'desc' => '图片分类'),
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '图片id')
			),
			'delete'  => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '图片id')
			),
			'getByType' => array(
				'type' => array('name' => 'type', 'desc' => '图片分类'),
			),
		);
	}

	/**
	 * 获取图片列表
	 * @param type 图片分类(可选参数，根据选用填写)
	 * @param page 当前页码(可选参数，没有该参数默认获取所有记录)
	 * @param num  每页数量(可选参数，没有该参数默认获取所有记录)
	 */
	public function getList(){
		$model = new Model();
		$type = $this -> type;
		if($type == 2 || $type == 3){
			$begin = ($this -> page - 1) * $this -> num;
			$list = $model -> getArtByType($type, $begin, $this -> num);
		}else if($type && $type != 2 && $type != 3){
			$list = $model -> getByType($type);
		}else{
			$begin = ($this -> page - 1) * $this -> num;
			$list = $model -> getList($begin, $this -> num);
		}
		if(!$list) return MyRules::myRuturn(0, '暂无数据');
		$newpics = $this -> base64EncodePic($list);
		return MyRules::myRuturn(1, '获取成功', $newpics);
	}

	/**
	 * 获取图片数目
	 * @param type 图片分类(可选参数，根据需要填写)
	 */
	public function getCount(){
		$model = new Model();
		$type = $this -> type;
		var_dump($type);
		if($type = null){
			$count = $model -> getCountByType($type);
			return MyRules::myRuturn(1, '成功', $count);
		}
		$count = $model -> getCount();
		return MyRules::myRuturn(1, '成功', $count);
	}

	/**
	 * 通过id获取一张图片信息
	 */
	public function getById(){
		$model = new Model();
		$Id = $this -> id;
		$pic = $model -> getById($Id);
		if(!$pic) return MyRules::myRuturn(0, '获取数据失败');
		$img = $pic['src'];
		$pic['pic'] = $img;
		$base64_image = MyRules::base64EncodeImage($img);
		$pic['src'] = $base64_image;
		return MyRules::myRuturn(1, '成功', $pic);
	}

	/**
	 * 通过id删除一张图片
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$pic = $model -> getById($Id);
		$sql = $model -> deleteOne($Id);
		if(!$sql) return MyRules::myRuturn(0, '删除失败');
		@unlink($pic['src']);
		return MyRules::myRuturn(1, '成功');
	}

	/**
	 * 通过type获取所有图片
	 */
	public function getByType(){
		$model = new Model();
		$sql = $model -> getByType($this -> type);
		if(!$sql){
			return MyRules::myRuturn(0, '暂无数据');
		}
		$list = $this -> base64EncodePic($sql);
		return MyRules::myRuturn(1, '成功', $list);
	}

	/**
	 * 将数组中的图片路径转化base64格式的图片
	 * @param data 含有图片路径的数组对象
	 */
	private function base64EncodePic($data){
		$count = count($data);
		for($i = 0; $i < $count; $i++){
			$img = $data[$i]['src'];
			$data[$i]['pic'] = $img;
			$base64_image = MyRules::base64EncodeImage($img);
			$data[$i]['src'] = $base64_image;
		}
		return $data;
	}
} 