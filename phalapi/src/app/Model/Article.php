<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Article extends NotORM{

	/**
	 * 获取所有行
	 */
	public function getAll(){
		$model = $this -> getORM();
		return $model -> select('id, title') -> fetchAll();
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
	 * 通过文章作者获取一条记录
	 * @param name 管理员账号
	 */
	public function getByAuthor($name){
		$model = $this -> getORM();
		return $model -> where('author', $name) -> fetchOne();
	}

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('id');
	}

	public function getList($begin = 1, $num = 10){
		$model = $this -> getORM();
		return $model -> order('id DESC') -> limit($begin, $num) -> fetchAll();
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
	 * 插入多条数据
	 * @param data 包含多条数据的数组(多维数组)
	 */
	public function saveAll($data){
		$model = $this -> getORM();
		return $model -> insert_multi($data);
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

	/**
	 * 根据栏目id删除所有数据
	 * @param cid 栏目id
	 */
	public function deleteAll($cid){
		$model = $this -> getORM();
		return $model -> where('cate', $cid) -> delete();
	}

	/**
	 * 通过分类获取文章数量
	 */
	public function getCountByCate($cid){
		$model = $this -> getORM();
		return $model -> where('cate', $cid) -> where('state', 1) -> count('id');
	}

	public function getListByCate($begin = 1, $num = 10, $cate = 1){
		$model = $this -> getORM();
		return $model -> where('cate', $cate) -> where('state', 1) -> limit($begin, $num) -> fetchAll();
	}

	/**
	 * 通过栏目id获取所有文章的id
	 */
	public function getArtsByCate($cate = 0){
		$model = $this -> getORM();
		return $model -> where('cate', $cate) -> select('id, cate') -> fetchAll();
	}


	/* 前台系统 */

	/**
	 * 获取已发布的所有文章
	 */
	public function getArts($begin = 1, $num = 10){
		$model = $this -> getORM();
		return $model -> where('state', 1) -> order('id DESC') -> limit($begin, $num) -> fetchAll();
	}

	/**
	 * 获取已发布的所有文章
	 */
	public function getArtsCount(){
		$model = $this -> getORM();
		return $model -> where('state', 1) -> count('id');
	}

	/**
	 * 添加文章like
	 */
	public function addLike($Id = 1){
		$model = $this -> getORM();
		return $model -> where('id', $Id) -> updateCounter('like', 1);
	}
}