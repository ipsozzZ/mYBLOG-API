<?php
namespace App\Api;
use PhalApi\Api;
use App\Model\Cate as Model;
use App\Common\MyRules;
use App\Model\Article;

/**
 * 管理员类接口
 */
class Category extends Api
{
	public function getRules(){
		return array(
			'add' => array(
				'name' => array('name' => 'name', 'require' => true, 'desc' => '分类名称'),
				'isshow' => array('name' => 'isshow', 'desc' => '栏目是否显示'),
				'desc' => array('name' => 'desc', 'desc' => '栏目描述'),
			),
			'getList' => array(),
			'delete' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '需要删除的栏目Id'),
			),
			'update' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '需要更新的分类Id'),
				'isshow' => array('name' => 'isshow', 'desc' => '栏目是否显示'),
				'name' => array('name' => 'name', 'desc' => '分类名称'),
				'desc' => array('name' => 'desc', 'desc' => '栏目描述'),
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '栏目Id'),
			),
			'getArtCount' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '栏目Id'),
			),
		);
	}

	/**
	 * 添加分类
	 */
	public function add(){
		$model = new Model();

		$isCate = $model -> getByName($this -> name);
		if($isCate){
			return MyRules::myRuturn(0, '栏目已经存在!', '');
		}
		$data = array(
			'name'   => $this -> name,
			'desc'   => $this -> desc,
			'isshow' => $this -> isshow
		);
		$sql = $model -> insertOne($data);
		if(!$sql){
			return MyRules::myRuturn(0, '添加失败', '');
		}
		return MyRules::myRuturn(1, '添加成功!', $sql);
	}

	/**
	 * 获取管理员列表
	 */
	public function getList(){
		$model = new Model();
		$list = $model -> getAll();
		if(!$list){
			return array(
				'code' => 0,
				'msg'  => '获取失败',
				'data' => '',
			);
		}
		$len = count($list);
		for( $i = 0; $i < $len; $i++ ){
			$count = $this -> changeCate($list[$i]['id']);
			$list[$i]['count'] = $count;
		}
		return array(
			'code' => 1,
			'msg'  => '获取成功',
			'data' => $list,
		);
	}

	/**
	 * 超级管理员删除普通管理员
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$cate = $model -> getById($Id);
		if(!$cate){
			return MyRules::myRuturn(0, '栏目不存在','');
		}
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '操作异常，请重试!', '');
		}
		return MyRules::myRuturn(1, '删除成功!', '');
	}

	/**
	 * 修改管理员信息
	 */
	public function update(){
		$model = new Model();
		$Id = $this -> id;
		$cate = $model -> getById($Id);
		if(!$cate){
			return MyRules::myRuturn(0, '栏目不存在！');
		}
		$data = array(
			"name"   => $this -> name,
			"desc"   => $this -> desc,
			"isshow" => $this -> isshow,
		);
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyRules::myRuturn(0, '修改失败，请稍后重试!', '');
		}
		return MyRules::myRuturn(1, '修改成功!', '');
	}

	/**
	 * 通过Id获取栏目
	 */
	public function getById(){
		$Id = $this -> id;
		$model = new Model();
		$cate = $model -> getById($Id);
		if(!$cate){
			return MyRules::myRuturn(0, '获取数据失败!');
		}
		return MyRules::myRuturn(1, '获取成功！', $cate);
	}

	/**
	 * 通过分类id获取文章数量
	 */
	public function getArtCount(){
		$model = new Model();
		$artModel = new Article();
		$Id = $this -> id;
		$cate = $model -> getById($Id);
		if(!$cate){
			return MyRules::myRuturn(1,'无栏目', 0);
		}
		$count = $model -> getCountByCate($Id);
		if(!$count){
			return MyRules::myRuturn(1,'无栏目', 0);
		}
		return MyRules::myRuturn(1,'无栏目', $count);
	}

	/**
	 * 通过分类id获取文章数量
	 */
	private function changeCate($Id = 1){
		$artModel = new Article();
		$count = $artModel -> getCountByCate($Id);
		if(!$count){
			return 0;
		}
		return $count;
	}
}