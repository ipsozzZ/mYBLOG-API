<?php
namespace App\Model;
use PhalApi\Model\NotORMModel as NotORM;
class Picture extends NotORM{

	public function getById($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> fetchOne();
	}

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('id');
	}

	public function getList($begin = 1, $num = 10){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	public function insertOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> delete();
	}

	/**
	 * 通过分类获取数据
	 * @param type 分类
	 */
	public function getByType($type){
		$model = $this -> getORM();
		return $model -> where('type', $type) -> fetchAll();
	}

	/**
	 * 通过分类分页获取数据文章图片
	 * @param type 分类
	 */
	public function getArtByType($type = 3, $begin = 0, $num = 10){
		$model = $this -> getORM();
		return $model -> where('type', $type) -> limit($begin, $num) -> fetchAll();
	}

	public function getCountByType($type = 0){
		$model = $this -> getORM();
		return $model -> where('type', $type) -> count('id');
	}
}