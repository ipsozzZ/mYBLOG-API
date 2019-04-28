<?php
namespace App\Api;
use PhalApi\Api;
use App\Domain\User as Domain;
use App\Model\Admin as Model;
use App\Common\MyRules;
use App\Common\GD;

/**
 * 管理员类接口
 */
class Admin extends Api
{
	public function getRules(){
		return array(
			'Login' => array(
				'Manager' => array('name' => 'Manager', 'require' => true, 'min' => 4, 'desc' => '用户名'),
				'Pass' => array('name' => 'Pass', 'require' => true, 'min' => 4, 'desc' => '用户密码'),
			),
			'add' => array(
				'account' => array('name' => 'account', 'require' => true, 'min' => 4, 'max' => 50, 'desc' => '用户名'),
				'pass' => array('name' => 'pass', 'require' => true, 'min' => 8, 'max' => 50, 'desc' => '用户密码'),
			),
			'getAdmins' => array(),
			'getCode' => array(),
		);
	}

	/**
	 * 管理员登录
	 */
	public function Login(){
		$user = $this -> Manager;
		$pass = $this -> Pass;
		$model = new Model();
		$manager = $model -> getByName($user);
		// 判断管理员是否存在
		if(!$manager){
			return MyRules::myRuturn(0, '登录失败，管理员不存在');
		}

		$logMsg = '';
		// 判断管理员密码是否正确
		if($pass != $manager['pass']){
			// 生成登录日志
			$logMsg = '登录失败，管理员密码不正确';
			$log = array(
				'msg'  => $logMsg,
			);
			return MyRules::myRuturn(0, '登录失败，管理员密码不正确');
		}

		// 生成登录日志
		$logMsg = '登录成功';
		$log = array(
			'msg'  => $logMsg,
		);

		// 登录成功 
		return MyRules::myRuturn(1, '登录成功', $manager);
	}

	/**
	 * 获取管理员信息
	 */
	public function GetMessage(){
		$user = $this -> username;
		$pass = $this -> password;
		$domain = new Domain();
	}

	/**
	 * 获取管理员列表
	 */
	public function getAdmins(){
		$model = new Model();
		$list = $model -> getAll();
		if(!$list){
			return array(
				'code' => 0,
				'msg'  => '获取失败',
				'data' => '',
			);
		}
		return array(
			'code' => 1,
			'msg'  => '获取成功',
			'data' => $list,
		);
	}

	/**
	 * 获取图片验证码信息
	 * @return res{
	 *  "code"  => 数字和字母组成的验证码,
	 * 	"pic"   => base64格式的图片
	 * }
	 */
	public function getCode(){
		$common = new GD();
		$res = $common -> getVerification(4);
		return MyRules::myRuturn(1, '获取成功', $res);
	}
}