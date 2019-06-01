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
				'msg'    => '昵称错了噢!',
				'data'   => ''
			);
		}
		if($pass != $sql['pass']){
			return array(
				'status' => 0,
				'msg'    => '密码不正确噢!',
				'data'   => ''
			);
		}
		return array(
			'status' => 1,
			'msg'    => '登录成功',
			'data'   => $sql,
		);
	}

	/**
	 * 用户注册
	 * @param data 用户注册信息  
	 */
	public function Register($data){
		$model = new Model();
		$sql = $model -> getByName($data['name']);
		if($sql){
			return array(
				'status' => 0,
				'msg'    => '昵称已存在，换个昵称试试吧!',
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
			'status' => $insert,
			'msg'    => '注册成功!',
		);
	}
}