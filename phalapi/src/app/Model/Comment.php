<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Comment extends NotORM{

	/**
	 * 获取所有记录
	 */
	public function getList($begin = 1, $num = 10){
		$model = $this -> getORM();
		return $model -> limit($begin, $num) -> fetchAll();
	}

	public function getCount(){
		$model = $this -> getORM();
		return $model -> count('id');
	}

	/**
	 * 获取所有记录数量通过文章id
	 */
	public function getCountByAid($aid){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> count('id');
	}

	/**
	 * 获取所有记录通过文章id
	 */
	public function getListByAid($aid, $begin = 1, $num = 10){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> limit($begin, $num) -> fetchAll();
	}

	/**
	 * 通过id获取一条记录
	 * @param id 评论id
	 */
	public function getById($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> fetchOne();
	}

	/**
	 * 通过用户id获取所有记录
	 * @param uid 用户id
	 */
	public function getByUid($uid){
		$model = $this -> getORM();
		return $model -> where('uid', $uid) -> fetchAll();
	}

	/**
	 * 通过文章id获取所有记录
	 * @param name 管理员账号
	 */
	public function getByAid($aid){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> fetchAll();
	}

	/**
	 * 通过评论id获取所有记录
	 * @param pid 父评论id
	 */
	public function getByPid($pid){
		$model = $this -> getORM();
		return $model -> where('parentid', $pid) -> fetchAll();
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
	 * @param id 评论id
	 */
	public function deleteOne($id){
		$model = $this -> getORM();
		return $model -> where('id', $id) -> delete();
	}

	/**
	 * 根据文章id删除所有数据
	 * @param aid 文章id
	 */
	public function deleteAllByArt($aid){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> delete();
	}

	/**
	 * 根据评论id删除所有数据
	 * @param pid 评论id
	 */
	public function deleteAllByParent($pid){
		$model = $this -> getORM();
		return $model -> where('parentid', $pid) -> delete();
	}

	/* ---------------- 前台系统 ---------------- */

	/**
	 * 获取默认显示的十条数据
	 */
	public function getDefault($aid){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> limit(0, 10) -> fetchAll();
	}

	/**
	 * 获取所有评论
	 */
	public function getByArt($aid){
		$model = $this -> getORM();
		return $model -> where('aid', $aid) -> fetchAll();
	}
}