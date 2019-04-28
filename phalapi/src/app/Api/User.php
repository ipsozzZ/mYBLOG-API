<?php
namespace App\Api;
use PhalApi\Api;
use App\Domain\User as Domain;
use App\Common\GD as GD;

/**
 * 用户模块接口服务
 * @author ipso
 */
class User extends Api {
    public function getRules() {	
      return array(
				'Login' => array(
					'username' => array('name' => 'username', 'require' => true, 'min' => 4, 'desc' => '用户名'),
					'password' => array('name' => 'password', 'require' => true, 'min' => 8, 'desc' => '用户密码'),
				),
				'Register' => array(
					'username' => array('name' => 'username', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
					'password' => array('name' => 'password', 'require' => true, 'min' => 8, 'max' => 50, 'desc' => '用户密码'),
					'age'      => array('name' => 'age', 'type' => 'int', 'desc' => '年龄'),
					'sex'      => array('name' => 'sex', 'enum', 'range' => array('female', 'male')),
				),
			);
		}
		
    /**
     * 登录接
     */
    public function Login() {
      $user = $this->username;
			$pass = $this->password;
			$GD = new GD();
			$headerimg = $GD -> getUserDefaultHeaderByName($user);
			$codeimg = $GD -> getVerification(4);
			$domain = new Domain();
			$login = $domain -> Login($user, $pass);
			if($login['status'] == 0){
				return $this -> getReturn(0, $login['msg']);
			}
			return $this -> getReturn(1,$login['msg'], $codeimg['pic']);
		}

		/**
     * 注册接口
     */
    public function Register() {
			$data = array(
				'name' => $this->username,
				'pass' => $this->password,
				'age'  => $this->age,
				'sex'  => $this->sex,
			);
			$domain = new Domain();
			$register = $domain -> Register($data);
			if($register['status'] == 0){
				return $this -> getReturn(0, $register['msg']);
			}
			return $this -> getReturn(1, $register['msg']);
		}
		
		/**
		 * 组织返回信息的格式，将返回信息放入数组
		 * @param state 状态码
		 * @param msg   返回信息,
		 * @param data  返回的数据
		 */
		protected function getReturn($state = 0, $msg = '', $data = null){
			return array(
				'code' => $state,
				'msg'   => $msg,
				'data'  => $data,
			);
		}
} 
