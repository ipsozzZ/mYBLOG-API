<?php
namespace App\Model;

use PhalApi\Api;
use App\Model\Friendlink as Model;
use App\Common\MyRules;

class Friendlink extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'name'    => array('name' => 'name', 'require' => true, 'desc' => '友链id'),
				'address' => array('name' => 'address', 'require' => true, 'desc' => '链接地址'),
				'state'   => array('name' => 'state', 'desc' => '链接地址'),
				'isshow'  => array('name' => 'isshow', 'desc' => '链接地址'),
			),
			'update' => array(
				'id'      => array('name' => 'id', 'require' => true, 'desc' => '配置id'),
				'name'    => array('name' => 'name', 'desc' => '友链id'),
				'address' => array('name' => 'address', 'desc' => '链接地址'),
				'state'   => array('name' => 'state', 'desc' => '链接地址'),
				'isshow'  => array('name' => 'isshow', 'desc' => '链接地址'),
			),
			'getById' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '友链id')
			),
			'getList' => array(
			),
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
		);
		$res = $model -> insertOne($data);
		if(!$res){
			return MyRules::myRuturn(0, '添加失败！');
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
		$Id = $this -> Id;
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
}