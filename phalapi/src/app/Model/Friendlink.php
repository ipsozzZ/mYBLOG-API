<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Friendlink extends NotORM{

	/**
	 * 获取所有记录
	 */
	public function getAll(){
		$model = new Model();
		return $model -> fetchAll();
	}

	/**
	 * 通过id获取一条记录
	 * @param id 管理员id
	 */
	public function getById($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> fetchOne();
	}

	/**
	 * 通过账号获取一条记录
	 * @param name 管理员账号
	 */
	public function getByName($name){
		$model = $this -> getORM();
		return $model -> where('account', $name) -> fetchOne();
	}

	/**
	 * 插入一条数据
	 * @param data 一条数据（一维数组）
	 */
	public function insertOne($data){
		$model = $this -> getORM();
		$model -> insert($data);
		return $model -> insert_id();
	}

	/**
	 * 根据id更新一条数据
	 * @param id   需要跟新的用户id
	 * @param data 更新的一条数据
	 */
	public function updateOne($id,$data){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> update($data);
	}

	/**
	 * 根据id删除一条数据
	 * @param id 用户id
	 */
	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> delete();
	}
}