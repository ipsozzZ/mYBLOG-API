<?php
namespace App\Api;

use PhalApi\Api;
use App\Model\Friendlink as Model;
use App\Common\MyRules;

/**
 * 友情链接类接口
 */
class Friendlink extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'name'    => array('name' => 'name', 'require' => true, 'desc' => '友链id'),
				'address' => array('name' => 'address', 'require' => true, 'desc' => '链接地址'),
				'state'   => array('name' => 'state', 'desc' => '状态'),
				'isshow'  => array('name' => 'isshow', 'desc' => '是否显示'),
				'remarks'  => array('name' => 'remarks', 'desc' => '备注'),
			),
			'update' => array(
				'id'      => array('name' => 'id', 'require' => true, 'desc' => '配置id'),
				'name'    => array('name' => 'name', 'desc' => '友链id'),
				'address' => array('name' => 'address', 'desc' => '链接地址'),
				'state'   => array('name' => 'state', 'desc' => '状态'),
				'isshow'  => array('name' => 'isshow', 'desc' => '是否显示'),
				'remarks'  => array('name' => 'remarks', 'desc' => '备注'),
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '友链id')
			),
			'getList' => array(
			),
			'delete' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '友链id')
			),
			'getFriends' => array(),
		);
	}

	/**
	 * 添加一条友情链接
	 */
	public function add(){
		$model = new Model();
		$friend = $model -> getByName($this -> name);
		if($friend){
			return MyRules::myRuturn(0, '您已经是站长的链友了哦');
		}
		$data = array(
			'name'    => $this -> name,
			'address' => $this -> address,
			'state'   => $this -> state,
			'isshow'  => $this -> isshow,
			'remarks' => $this -> remarks
		);
		$res = $model -> insertOne($data);
		if(!$res){
			return MyRules::myRuturn(0, '申请失败，请稍后重试!');
		}
		return MyRules::myRuturn(1, '添加成功！', $res);
	}

	/**
	 * 根据id更新一条友情链接
	 */
	public function update(){
		$model = new Model();
		$Id = $this -> id;
		$friend = $model -> getById($Id);
		if(!$friend){
			return MyRules::myRuturn(0, '链友不存在');
		}
		$data = array(
			'name'    => $this -> name,
			'address' => $this -> address,
			'state'   => $this -> state,
			'isshow'  => $this -> isshow,
			'remarks' => $this -> remarks
		);
		$sql = $model -> updateOne($Id, $data);
		if(!$sql){
			return MyRules::myRuturn(0, '更新失败');
		}
		return MyRules::myRuturn(1, '更新成功!');
	}

	/**
	 * 通过id获取一条记录
	 */
	public function getById(){
		$model = new Model();
		$Id = $this -> id;
		$fr = $model -> getById($Id);
		if(!$fr){
			return MyRules::myRuturn(1, '失败！');
		}
		return MyRules::myRuturn(1, '成功！', $fr);
	}

	/**
	 * 获取所有友链
	 */
	public function getList(){
		$model = new Model();
		$frs = $model -> getAll();
		if(!$frs){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功', $frs);
	}

	/**
	 * 获取所有显示状态的友链
	 */
	public function getFriends(){
		$model = new Model();
		$frs = $model -> getFriends();
		if(!$frs){
			return MyRules::myRuturn(0, '获取失败');
		}
		return MyRules::myRuturn(1, '获取成功', $frs);
	}

	/**
	 * 根据id删除一条记录
	 */
	public function delete(){
		$model = new Model();
		$Id = $this -> id;
		$sql = $model -> deleteOne($Id);
		if(!$sql){
			return MyRules::myRuturn(0, '删除失败');
		}
		return MyRules::myRuturn(1, '删除成功！');
	}
}