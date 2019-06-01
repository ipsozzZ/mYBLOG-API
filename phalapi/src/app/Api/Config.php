<?php
namespace App\Api;
use PhalApi\Api;
use App\Model\Config as Model;
use App\Common\MyRules;

/**
 * 站点配置
 */
class Config extends Api{
	public function getRules(){
		return array(
			'add' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '配置id'),
				'title' => array('name' => 'title', 'require' => true, 'desc' => '站点标题'),
				'host' => array('name' => 'host',  'desc' => '站长'),
				'desc' => array('name' => 'desc', 'desc' => '站点描述'),
				'about' => array('name' => 'about', 'desc' => '站点站长介绍'),
				'certificates' => array('name' => 'certificates', 'desc' => '证件'),
				'email' => array('name' => 'email', 'desc' => '站点邮件'),
				'qq' => array('name' => 'qq', 'desc' => 'QQ'),
				'phone' => array('name' => 'phone', 'desc' => '联系电话'),
				'address' => array('name' => 'address', 'desc' => '站点地址'),
				'state' => array('name' => 'state', 'desc' => '站点状态'),
				'info' => array('name' => 'info', 'desc' => '关站信息'),
				'hobby' => array('name' => 'hobby', 'desc' => '业余爱好'),
				'work' => array('name' => 'work', 'desc' => '博主职业'),
			),
			'getId' => array(
			),
			'getCof' => array(
				'id' => array('name' => 'id', 'require' => true, 'desc' => '配置id'),
			),
		);
	}

	/**
	 * 添加/更新一条配置信息
	 */
	public function add(){
		$model = new Model();
		$oldConfig = $model -> getCount();
		$Id = $this -> id;
		$data = array(
			'title'   => $this -> title,
			'host'    => $this -> host,
			'desc'    => $this -> desc,
			'certificates' => $this -> certificates,
			'email'   => $this -> email,
			'qq'   		=> $this -> qq,
			'phone'   => $this -> phone,
			'address' => $this -> address,
			'state'   => $this -> state,
			'info'    => $this -> info,
			'about'   => $this -> about,
			'hobby'   => $this -> hobby,
			'work'    => $this -> work,
		);
		$config = json_encode($data,JSON_UNESCAPED_UNICODE);
		$newData = array(
			'config' => $config,
		);
		if($Id == 0){
			$sql = $model -> saveOne($newData);
			if(!$sql){
				return MyRules::myRuturn(0, '添加失败！');
			}
			return MyRules::myRuturn(1, '添加成功！', $sql);
		}
		$res = $model -> updateOne($Id, $newData);
		if(!$res){
			return MyRules::myRuturn(0, '添加失败！');
		}
		return MyRules::myRuturn(1, '添加成功！');
	}

	/**
	 * 获取配置信息id，数据库中只允许出现一条记录
	 * 所以通过max()就可获取到配置信息id
	 */
	public function getId(){
		$model = new Model();
		$Id = $model -> getMax();
		if(!$Id){
			return MyRules::myRuturn(1, '失败！');
		}
		return MyRules::myRuturn(1, '成功！', $Id);
	}

	/**
	 * 获取站点配置信息
	 */
	public function getCof(){
		$model = new Model();
		$Id = $this -> id;
		$config = $model -> getById($Id);
		if(!$config){
			return MyRules::myRuturn(0, '获取失败');
		}
		$data = json_decode($config['config'], true);
		if($data['state'] == 'true'){
			$data['state'] = true;
		}else{
			$data['state'] = false;
		}
		$data['id'] = $config['id'];
		return MyRules::myRuturn(1, '获取成功', $data);
	}
}