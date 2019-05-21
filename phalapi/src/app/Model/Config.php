<?php
namespace App\Model;
use PhalApi\Model\NotORMModel as NotORM;

/**
 * 站点配置模型类
 * @author ipso
 */
class Config extends NotORM{

	public function getAll(){
		$model = $this -> getORM();
		return $model -> fectAll();
	}

	public function getById($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> fetchOne();
	}

	public function saveOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	public function updateOne($id,$data){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> update($data);
	}

	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> delete();
	}
}