<?php
namespace App\Model;
use PhalApi\Model\NotORMModel as NotORM;


/**
 *  用户表模型类 
 * @author ipso
 */

class User extends NotORM
{
	
	public function getAll(){
		$model = $this -> getORM();
		return $model -> fectAll();
	}

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

	public function saveOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	public function saveAll($data){
		$model = $this -> getORM();
		return $model -> insert_multi($data);
	}

	public function updateOne($id,$data){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> update($data);
	}

	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> delete();
	}

	/**
	 * 通过名字获取一条数据
	 * @param user 用户名
	 */
	public function getByName($user){
		$model = $this -> getORM();
		return $model -> where('name', $user) -> fetchOne();
	}
}