<?php
namespace App\Domain;
use App\Model\User as Model;

/**
 * 用户逻辑处理类
 * @author ipso
 */

class User
{
	/**
	 * 用户登录
	 * @param  user 用户名
	 * @param  pass 用户密码
	 * @return array
	 */
	public function Login($user, $pass){
		$model = new Model();
		$sql = $model -> getByName($user);
		if(!$sql){
			return array(
				'status' => 0,
				'msg'    => '用户名不存在!',
			);
		}
		if($pass != $sql['pass']){
			return array(
				'status' => 0,
				'msg'    => '用户密码不正确!'
			);
		}
		return array(
			'status' => 1,
			'msg'    => '登录验证成功',
		);
	}

	/**
	 * 用户注册
	 * @param data 用户注册信息  
	 */
	public function Register($data){
		$model = new Model();
		if($data['sex'] == 'male'){
			$data['sex'] = 1;
		}else{
			$data['sex'] = 0;
		}
		$sql = $model -> getByName($data['name']);
		if($sql){
			return array(
				'status' => 0,
				'msg'    => '用户名已存在!',
			);
		}
		$insert = $model -> saveOne($data);
		if(!$insert){
			return array(
				'status' => 0,
				'msg'    => '注册失败!',
			);
		}
		return array(
			'status' => 1,
			'msg'    => '注册成功!',
		);
	}
}